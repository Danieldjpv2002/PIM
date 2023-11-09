<?php

namespace App\Jobs;

use App\Http\Classes\Logger;
use App\Http\Controllers\AssistantController;
use App\Http\RestClient\WhatsAppRestClient;
use App\Models\AssistantConfig;
use App\Models\Contact;
use App\Models\Message;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SoDe\Extend\JSON;

class ProcessMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $request = $this->request;
        try {
            if ($request->header('user-agent') != 'facebookexternalua') {
                throw new Exception('Token inválido', 1);
            }

            $entry = $request->entry[0];
            $change = $entry['changes'][0];
            $value = $change['value'];
            if (!isset($value['messages'])) {
                throw new Exception('Not message');
            }
            $metadata = $value['metadata'];
            $profile = $value['contacts'][0];
            $content = $value['messages'][0];

            if ($metadata['phone_number_id'] == '100647609481983') {
                $assistant = AssistantConfig::select()->where('owner', '=', $profile['wa_id'])->first();
                if ($assistant) {
                    AssistantController::main($assistant, $content);
                }
            }

            $contact = Contact::where('whatsapp_id', '=', $profile['wa_id'])->first();

            if (!$contact) {
                $contact = new Contact();
                $contact->whatsapp_name = $profile['profile']['name'];
            }

            $contact->conversation_id = $entry['id'];
            $contact->whatsapp_id = $profile['wa_id'];
            $contact->whatsapp_phone = $content['from'];
            $contact->save();

            $message = new Message();
            $message->_contact = $contact->id;
            $message->whatsapp_id = $content['id'];
            $message->author_id = $metadata['phone_number_id'];
            $message->author_phone = $metadata['display_phone_number'];
            $message->type_message = $content['type'];

            switch ($content['type']) {
                case 'text':
                    $message->body = $content['text']['body'];
                    $message->save();
                    if ($content['text']['body'] == '!notify') {
                        WhatsAppRestClient::sendMessage('Perfecto, te has suscrito al servicio de notificaciones de *SoDe*. No olvides enviarme un mensaje al menos cada 24 horas', $profile['wa_id']);
                    }
                    break;
                case 'video':
                    $message->file_id = $content['video']['id'];
                    $message->body = $content['video']['caption'] ?? '';
                    $message->file_type = $content['video']['mime_type'];
                    $message->file_sha256 = $content['video']['sha256'];
                    $message->save();
                    break;
                case 'image':
                    $message->file_id = $content['image']['id'];
                    $message->body = $content['image']['caption'] ?? '';
                    $message->file_type = $content['image']['mime_type'];
                    $message->file_sha256 = $content['image']['sha256'];
                    $message->save();
                    break;
                case 'audio':
                    $message->file_id = $content['audio']['id'];
                    $message->body = $content['audio']['caption'] ?? '';
                    $message->file_type = $content['audio']['mime_type'];
                    $message->file_sha256 = $content['audio']['sha256'];
                    $message->file_voice = $content['audio']['voice'];
                    $message->save();
                    break;
                case 'sticker':
                    $message->file_id = $content['sticker']['id'];
                    $message->body = $content['sticker']['caption'] ?? '';
                    $message->file_type = $content['sticker']['mime_type'];
                    $message->file_sha256 = $content['sticker']['sha256'];
                    $message->file_animated = $content['sticker']['animated'];
                    $message->save();
                    break;
                case 'contacts':
                    foreach ($content['contacts'] as $c) {
                        if (!isset($c['phones'])) continue;

                        foreach ($c['phones'] as $phone) {
                            if (!isset($phone['wa_id'])) continue;
                            $message->contact_phone = $phone['phone'];
                            $message->contact_id = $phone['wa_id'];
                        }

                        $message->contact_name = $c['name']['formatted_name'];

                        $new_contact = Contact::where('whatsapp_id', '=', $message->contact_id)->first();
                        if (!$new_contact) {
                            $new_contact = new Contact();
                        }
                        $new_contact->whatsapp_id = $message->contact_id;
                        $new_contact->whatsapp_name = $message->contact_name;
                        $new_contact->whatsapp_phone = $message->contact_phone;

                        $new_contact->save();
                        $message->save();
                    }
                    break;
                case 'location':
                    $message->location_latitude = $message['location']['latitude'];
                    $message->location_longitude = $message['location']['longitude'];
                    $message->save();
                    break;
                case 'document':
                    $message->file_id = $content['document']['id'];
                    $message->body = $content['document']['caption'] ?? '';
                    $message->file_type = $content['document']['mime_type'];
                    $message->file_sha256 = $content['document']['sha256'];
                    $message->save();
                    break;
                default:
                    $message->body = 'Indefinido';
                    $message->save();
                    break;
            }

            Logger::info('WebHook Whatsapp: ' . JSON::stringify($request->toArray()));
        } catch (\Throwable $th) {
            Logger::info('WebHook Whatsapp: ' . $th->getMessage() . JSON::stringify($request->toArray()));
        }
    }
}
