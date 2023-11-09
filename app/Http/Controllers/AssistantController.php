<?php

namespace App\Http\Controllers;

use App\Http\Classes\Logger;
use App\Http\Classes\Messages;
use App\Http\RestClient\GPTRestClient;
use App\Http\RestClient\ToMP3RestClient;
use App\Http\RestClient\WhatsAppRestClient;
use App\Models\AssistantConfig;
use App\Models\AssistantMessages;
use App\Models\Logs;
use App\Templates\InteractiveTemplate;
use App\Templates\MediaTemplate;
use App\Templates\RolTemplate;
use Exception;
use Illuminate\Http\Request;
use SoDe\Extend\File;
use SoDe\Extend\JSON;
use SoDe\Extend\Text;
use SoDe\Extend\Tokenizer;
use SoDe\Extend\Trace;

class AssistantController extends Controller
{

    public function newUser(Request $request)
    {
        $request->header();
    }

    public static function main(AssistantConfig $config, array $content): void
    {
        try {

            if ($config->status == null) die();

            if ($config->status != 1) {
                throw new Exception(
                    "Tu subscripción con *{$config->name}* ha vencido, consulta con *Carlos Manuel Gamboa Palomino* sobre el estado de tu suscripción." . Text::lineBreak() .
                        "*wa.me/51999413711*"
                );
            }

            $text = '';
            if ($content['type'] == 'audio' || ($content['type'] == 'document' && Text::startsWith($content['document']['mime_type'], 'audio'))) {
                $content_flatten = JSON::flatten($content);
                $audio_id = $content_flatten['audio.id'] ?? $content_flatten['document.id'];
                $mime_type = $content_flatten['audio.mime_type'] ?? $content_flatten['document.mime_type'];
                $url = "{$_ENV['APP_URL']}/api/audio/{$audio_id}";
                $audio_data = file_get_contents($url);
                $ext = File::getExtention($mime_type);
                $path = "../storage/temp/{$audio_id}.{$ext}";
                file_put_contents($path, $audio_data);
                $res_transcription = GPTRestClient::transcriptions("{$audio_id}.{$ext}", $config->token);
                $text = "[audio] {$res_transcription}";
            } else if ($content['type'] == 'interactive') {
                if (
                    $content['interactive']['type'] == 'button_reply' ||
                    $content['interactive']['type'] == 'list_reply'
                ) {
                    $type = $content['interactive']['type'];
                    $id = $content['interactive'][$type]['id'];
                    if (Text::startsWith($id, 'downloadvideo')) {
                        $media_id = str_replace('downloadvideo-', '', $id);
                        AssistantController::executeCommand($config, ":video https://youtu.be/{$media_id}");
                    } else if (Text::startsWith($id, 'downloadaudio')) {
                        $media_id = str_replace('downloadaudio-', '', $id);
                        AssistantController::executeCommand($config, ":music https://youtu.be/{$media_id}");
                    } else if (Text::startsWith($id, 'relatedmusic')) {
                        $media_id = str_replace('relatedmusic-', '', $id);
                        AssistantController::executeCommand($config, ":relatedmusic https://youtu.be/{$media_id}");
                    } else {
                        AssistantController::denyNotString($config);
                    }
                } else {
                    AssistantController::denyNotString($config);
                }
                die();
            } else if ($content['type'] != 'text') {
                AssistantController::denyNotString($config);
                die();
            } else {
                $text = $content['text']['body'];
            }

            if (Text::startsWith($text, ':')) {
                Messages::add($config->id, $content['id'], $text, 'command');
                AssistantController::executeCommand($config, $text);
                die();
            }

            if (
                Text::startsWith($text, 'https://') &&
                Text::hasOne($text, ['youtube.com', 'youtu.be'])
            ) {
                $text = str_replace('&feature=share', '', $text);
                AssistantController::executeCommand($config, ":music {$text}");
                die();
            }

            Messages::add($config->id, $content['id'], $text, 'client');

            $a = 0;
            $gpt_last_response = "Lo siento, *{$config->name}* no puede responderte en estos momentos";

            $rol = new RolTemplate();
            $rol->owner = $config->owner_name;
            $rol->owner_birthdate = $config->owner_birthdate;
            $rol->name = $config->name;
            $rol->rol = $config->rol;
            $rol->rules = $config->rules;
            $rol->location = $config->owner_location;

            $role_template = $rol->get();

            $max_length = 3072 - Tokenizer::tokens($role_template);

            $messagesJpa = AssistantMessages::select('*')
                ->whereIn('type', ['client', 'assistant'])
                ->where('_user', $config->id)
                ->where('status', true)
                ->take(25)
                ->orderBy('date', 'desc')
                ->get();

            Logger::info('Records AssistantMessages: ' . JSON::stringify($messagesJpa));

            $completion = '';
            $length = 0;

            $last_messages_reform = [];
            $last_date = '0000-00-00';

            foreach ($messagesJpa as $messageJpa) {
                $length += $messageJpa->length + Tokenizer::tokens($messageJpa->type . $messageJpa->date);
                if ($length > $max_length) break;
                $last_messages_reform[] = $messageJpa;
            }

            $last_messages = [];

            foreach (array_reverse($last_messages_reform) as $msgJpa) {
                [$date, $time] = explode(' ', $msgJpa->date);
                if ($last_date != $date) {
                    $last_date = $date;
                    $last_messages[] = $date;
                }
                $msgJpa->time = $time;
                $last_messages[] = $msgJpa;
            }

            $last_messages = array_map(function ($msgJpa) {
                if (gettype($msgJpa) == 'string') return $msgJpa;
                $clean_message = Text::cleanLineBreak($msgJpa->message);
                return "{$msgJpa->type} [{$msgJpa->time}]: {$clean_message}";
            }, $last_messages);

            if ($last_date != Trace::getDate('date')) {
                $last_messages[] = Trace::getDate('date');
            }

            $completion .=  implode(Text::lineBreak(), $last_messages) . Text::lineBreak() . 'assistant [' . Trace::getDate('time') . ']: ';

            $rol->message = $completion;

            while ($a < 3) {

                $res_gpt = GPTRestClient::completions($rol->get(), $config->token);
                $a = $a + 1;

                $data_gpt = JSON::parseable($res_gpt->text()) ? $res_gpt->json() : [];

                if (!$res_gpt->ok) {
                    $gpt_last_response = $data_gpt['error']['message'] ?? $gpt_last_response;
                    continue;
                }

                $data = $res_gpt->json();

                if ($data['choices'][0]['finish_reason'] != 'stop') {
                    continue;
                }

                [$found, $match, $clean_text] = Text::match($data['choices'][0]['text']);

                $message = '*' . $config->name . '*' . Text::lineBreak() . Text::cleanLineBreak($clean_text);
                $res_wa = WhatsAppRestClient::sendMessage($message, $config->owner, 'WHATSAPP2_ID');

                $data_wa = JSON::parseable($res_wa->text()) ? $res_wa->json() : [];

                if (!$res_wa->ok) {
                    throw new Exception($data_wa['error']['message'] ?? "Lo siento, *{$config->name}* no puede responderte en estos momentos");
                }


                Messages::add($config->id, $data_wa['messages'][0]['id'], Text::cleanLineBreak($data['choices'][0]['text']), 'assistant');

                if ($found) {
                    AssistantController::executeCommand($config, $match);
                }

                die();
            }

            throw new Exception($gpt_last_response, 1);
        } catch (\Throwable $th) {
            $message = '*Automático*' . Text::lineBreak() . $th->getMessage();
            WhatsAppRestClient::sendMessage($message, $config->owner, 'WHATSAPP2_ID');
        }
    }

    public static function denyNotString(AssistantConfig $config): void
    {
        try {
            $message = "Lo siento, *{$config->name}* solo puede responder mensajes textuales o ejecutar comandos";
            $res_wa = WhatsAppRestClient::sendMessage('*Automático*' . Text::lineBreak() . $message, $config->owner, 'WHATSAPP2_ID');

            $data_wa = JSON::parseable($res_wa->text()) ? $res_wa->json() : [];

            if (!$res_wa->ok) {
                throw new Exception($data_wa['error']['message']);
            }

            Messages::add($config->id, $data_wa['messages'][0]['id'], $message, 'automatic');

            $log = new Logs();
            $log->status = 200;
            $log->message = 'Operación correcta';
            $log->request = json_encode($config->toArray());
            $log->response = json_encode($res_wa->json());
            $log->save();
        } catch (\Throwable $th) {
            $log = new Logs();
            $log->status = 400;
            $log->message = $th->getMessage();
            $log->request = json_encode($config->toArray());
            $log->response = '{}';
            $log->save();
        }
    }

    public static function executeCommand(AssistantConfig $config, string $message): ?bool
    {
        try {
            $splitted = Text::split($message);
            $command = $splitted[0];
            $content = str_replace($command . ' ', '', $message);

            if ($command == ':dall-e') {
                $res_gpt = GPTRestClient::generations($content, $config->token);

                $data_gpt = JSON::parseable($res_gpt->text()) ? $res_gpt->json() : [];

                if (!$res_gpt->ok) {
                    throw new Exception($data_gpt['error']['message'] ?? 'Error al generar imagen de: _' . $content . '_');
                }

                $log = new Logs();
                $log->status = $res_gpt->status;
                $log->message = 'GTP: Generación de imagen';
                $log->request = json_encode($config->toArray());
                $log->response = $res_gpt->text();
                $log->save();

                $media = new MediaTemplate();
                $media->phone($config->owner);
                $media->type('image');
                $media->media($data_gpt['data'][0]['url'], '*' . $config->name . '*' . Text::lineBreak() . $content);

                $res_wa = WhatsAppRestClient::sendMedia($media, 'WHATSAPP2_ID');

                $data_wa = JSON::parseable($res_wa->text()) ? $res_wa->json() : [];
                if (!$res_wa->ok) {
                    throw new Exception($data_wa['error']['message'] ?? 'Lo siento. No pude enviarte la imagen de vuelta');
                }

                $log = new Logs();
                $log->status = 200;
                $log->message = 'WhatsApp: Envío de imagen';
                $log->request = json_encode($config->toArray());
                $log->response = $res_wa->text();
                $log->save();
            } else if ($command == ':music') {
                $res_music = ToMP3RestClient::smart($content, 'mp3');
                if (!$res_music['status']) {
                    throw new Exception($res_music['message']);
                }
                $interactive = [
                    "type" => "button",
                    "header" => [
                        "type" => "document",
                        "document" => [
                            "link" => "{$_ENV['APP_URL']}/api/youtube/audio/{$res_music['data']['id']}",
                            "filename" => $res_music['data']['title'] . '.' . $res_music['data']['mime_type']
                        ]
                    ],
                    "body" => [
                        "text" => "*Automático*" . Text::lineBreak() .
                            "Música: " . $res_music['data']['title'] . Text::lineBreak() .
                            "Calidad: " . $res_music['data']['quality'] . Text::lineBreak() .
                            "Link: youtu.be/" . $res_music['data']['id']
                    ],
                    "footer" => [
                        "text" => ":music {$content}"
                    ],
                    "action" => [
                        "buttons" => [
                            [
                                "type" => "reply",
                                "reply" => [
                                    "id" => "downloadvideo-" . $res_music['data']['id'],
                                    "title" => "Descargar en video"
                                ]
                            ],
                            [
                                "type" => "reply",
                                "reply" => [
                                    "id" => "relatedmusic-" . $res_music['data']['id'],
                                    "title" => "Músicas relacionadas"
                                ]
                            ]
                        ]
                    ]
                ];
                $template = new InteractiveTemplate();
                $template->phone($config->owner);
                $template->interactive($interactive);
                WhatsAppRestClient::sendTemplate($template, 'WHATSAPP2_ID');
            } else if ($command == ':video') {
                $res_video = ToMP3RestClient::smart($content, 'mp4');
                if (!$res_video['status']) {
                    throw new Exception($res_video['message']);
                }
                $interactive = [
                    "type" => "button",
                    "header" => [
                        "type" => "document",
                        "document" => [
                            "link" => "{$_ENV['APP_URL']}/api/youtube/video/{$res_video['data']['id']}",
                            "filename" => $res_video['data']['title'] . '.' . $res_video['data']['mime_type']
                        ]
                    ],
                    "body" => [
                        "text" => "*Automático*" . Text::lineBreak() .
                            "Video: " . $res_video['data']['title'] . Text::lineBreak() .
                            "Calidad: " . $res_video['data']['quality'] . Text::lineBreak() .
                            "Link: youtu.be/" . $res_video['data']['id']
                    ],
                    "footer" => [
                        "text" => ":video {$content}"
                    ],
                    "action" => [
                        "buttons" => [
                            [
                                "type" => "reply",
                                "reply" => [
                                    "id" => "downloadaudio-" . $res_video['data']['id'],
                                    "title" => "Descargar en audio"
                                ]
                            ],
                            [
                                "type" => "reply",
                                "reply" => [
                                    "id" => "relatedvideo-" . $res_video['data']['id'],
                                    "title" => "Videos relacionadas"
                                ]
                            ]
                        ]
                    ]
                ];
                $template = new InteractiveTemplate();
                $template->phone($config->owner);
                $template->interactive($interactive);
                WhatsAppRestClient::sendTemplate($template, 'WHATSAPP2_ID');
            } else if ($command == ':relatedmusic' || $command == ':relatedvideo') {
                $res_related = ToMP3RestClient::related($content);
                if (!$res_related['status']) {
                    throw new Exception($res_related['message']);
                }
                $interactive = [
                    "type" => "list",
                    "header" => [
                        "type" => "text",
                        "text" => "Automático"
                    ],
                    "body" => [
                        "text" => "Aquí tienes una lista de 10 músicas relacionadas a *{$content}*"
                    ],
                    "footer" => [
                        "text" => "{$command} {$content}"
                    ],
                    "action" => [
                        "button" => "Ver lista",
                        "sections" => [
                            [
                                "rows" => array_map(function ($data) use ($command) {
                                    $action = $command == ':relatedmusic' ? 'downloadaudio' : 'downloadvideo';
                                    return [
                                        "id" => "{$action}-{$data['id']}",
                                        "title" => $data['uri'],
                                        "description" => Text::reduce($data['title'], 72)
                                    ];
                                }, JSON::take($res_related['data'], 10))
                            ]
                        ]
                    ]
                ];
                $template = new InteractiveTemplate();
                $template->phone($config->owner);
                $template->interactive($interactive);
                WhatsAppRestClient::sendTemplate($template, 'WHATSAPP2_ID');
            } else if ($command == ':reset') {
                if (!password_verify($content, $config->password)) {
                    throw new Exception('No puedes eliminar la interacción, la contraseña es incorrecta');
                }

                $deleteRows = AssistantMessages::where('_user', $config->id)->update(['status' => false]);

                $message = '*Automático*' . Text::lineBreak() . "Se ha eliminado *{$deleteRows}* interacciones entre *{$config->owner_name}* y *{$config->name}*";
                WhatsAppRestClient::sendMessage($message, $config->owner, 'WHATSAPP2_ID');
            } else  if ($command == ':info') {
                $message = "🤖📱🎮 Estimado/a *" . $config->owner_name . "*," . Text::lineBreak(2) .
                    "Me complace presentarle a mi asistente de chat GPT, diseñado bajo la arquitectura de *OpenAI* y vinculado a una cuenta de *WhatsApp*. Este asistente es capaz de mantener conversaciones inteligentes y proporcionar información relevante y útil para el usuario." . Text::lineBreak(2) .
                    "El asistente está configurado para jugar un juego de roles, el cual el usuario puede personalizar según su preferencia. Además, el asistente puede ser asignado con un *nombre*, *rol*, *personalidad* y *reglas* específicas para crear una experiencia de usuario única y personalizada." . Text::lineBreak(2) .
                    "El desarrollo del asistente se llevó a cabo utilizando el framework *Laravel*, que emplea una arquitectura híbrida entre *MVC* y *microservicios* para lograr un alto grado de escalabilidad y adaptabilidad." . Text::lineBreak(2) .
                    "Además, el asistente puede ejecutar diversos comandos, como *:dall-e* (para generar imágenes utilizando la API de DALL-E) y *:reset* (para eliminar la interacción con el asistente, pero se requiere una contraseña). De esta manera, se ofrece una mayor flexibilidad y control para el usuario." . Text::lineBreak(2) .
                    "Si desea obtener más información o tiene alguna pregunta, no dude en ponerse en contacto con *Carlos Manuel Gamboa Palomino* a través del enlace *wa.me/51999413711*." . Text::lineBreak(2) .
                    "Agradecemos su confianza en nuestro asistente de chat GPT y esperamos poder seguir brindando una experiencia de usuario excepcional." . Text::lineBreak(2) .
                    "Saludos cordiales," . Text::lineBreak() . "Carlos Manuel Gamboa Palomino";
                $res_wa = WhatsAppRestClient::sendMessage($message, $config->owner, 'WHATSAPP2_ID');
                $data_wa = JSON::parseable($res_wa->text()) ? $res_wa->json() : [];

                if (!$res_wa->ok) {
                    throw new Exception($data_wa['error']['message'] ?? "Lo siento, en estos momentos {$config->name} teniendo inconvenientes para enviar mensajes", 1);
                }

                Messages::add($config->id, $data_wa['messages'][0]['id'], 'Información del asistente', 'info');
            } else if (in_array($command, [':name', ':rol', ':rules'])) {
                $commands = [
                    'name' => 'el nombre',
                    'rol' => 'el rol',
                    'rules' => 'las reglas del asistente',
                ];
                $command = str_replace(':', '', $command);
                $password = Text::split($content, ' ')[0];
                $content = trim(str_replace($password, '', $content));

                if (!password_verify($password, $config->password)) {
                    throw new Exception("No puedes modificar {$commands[$command]}, la contraseña es incorrecta");
                }

                $last_data = $config->$command;
                $new_data = $content;

                $config->$command = $new_data;
                $config->save();

                $message = '*Automático*' . Text::lineBreak() . "Se ha actualizado {$commands[$command]} de *{$last_data}* a *{$new_data}*";
                WhatsAppRestClient::sendMessage($message, $config->owner, 'WHATSAPP2_ID');
            } else if ($command == ':get') {
                $fields = ['name', 'rol', 'rules'];
                if (in_array($content, $fields)) {
                    $message = '*Automático*' . Text::lineBreak() . $config->$content;
                    WhatsAppRestClient::sendMessage($message, $config->owner, 'WHATSAPP2_ID');
                } else {
                    throw new Exception(
                        "El dato que intentas obtener de tu asistente no existe. Los datos disponibles son:" . Text::lineBreak(2) .
                            '```name```: Nombre del asistente' . Text::lineBreak() .
                            '```rol```: Rol del asistente' . Text::lineBreak() .
                            '```rules```: Reglas del asistente',
                        1
                    );
                }
            } else {
                throw new Exception('El comando ```' . $command . '``` es inválido');
            }
            return true;
        } catch (\Throwable $th) {

            $log = new Logs();
            $log->status = 400;
            $log->message = $th->getMessage() . ' ln' . $th->getLine();
            $log->request = json_encode($config->toArray());
            $log->response = '{}';
            $log->save();

            $message = '*Automático*' . Text::lineBreak() . $th->getMessage();
            $res_wa = WhatsAppRestClient::sendMessage($message, $config->owner, 'WHATSAPP2_ID');
            $data_wa = JSON::parseable($res_wa->text()) ? $res_wa->json() : [];

            if ($res_wa->ok) {
                Messages::add($config->id, $data_wa['messages'][0]['id'], $th->getMessage(), 'automatic');
            }
            return false;
        }
    }
}
