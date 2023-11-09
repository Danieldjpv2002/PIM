<?php

namespace App\Http\Controllers;

use App\Http\RestClient\WhatsAppRestClient;
use App\Models\Message;
use App\Models\Response;
use App\Templates\ActivityIssue;
use App\Templates\Contact;
use App\Models\SoDe\ViewUsersByDedicated;
use Exception;
use Illuminate\Http\Request;
use SoDe\Extend\Fetch;
use SoDe\Extend\JSON;

class WhatsAppController extends Controller
{

    private array $services = [
        'activity' => 'd4b5c7c3-1b7f-436b-a9e3-58b49dac8938',
        'store' => null,
        'roosevelt' => '751d7c54-0e4f-4663-fbc5-8e1199d23adf'
    ];

    private function verify(Request $request)
    {
        $token = $request->header('SoDe-Auth-Token');
        $service = $request->header('SoDe-Auth-Service');
        if (
            $token == null ||
            $service == null
        ) {
            throw new Exception('Envíe los encabezados de autorización');
        }

        if ($this->services[$service] != $token) {
            throw new Exception('Los encabezados de autenticación son incorrectos');
        }
    }

    public function get($contact)
    {
        $response = new Response();
        try {
            $messagesJpa = Message::where('_contact', '=', $contact)->limit(20)->get();

            $response->setStatus(200);
            $response->setMessage('Operación correcta');
            $response->setData($messagesJpa->toArray());
        } catch (\Throwable $th) {
            $response->setStatus(400);
            $response->setMessage($th->getMessage());
        } finally {
            return response(
                $response->toArray($contact),
                $response->getStatus()
            );
        }
    }

    public function getAudio(Request $request, string $media_id)
    {
        try {
            $res = WhatsAppRestClient::getMedia($media_id);
            if (!$res->ok) {
                throw new Exception('Error al obtener el archivo');
            }
            $data = $res->json();

            $file = WhatsAppRestClient::downloadMedia($data['url'], $data['mime_type']);

            if (!$file) {
                throw new Exception('No se pudo descargar el archivo');
            }
            return response($file, 200, ['Content-Type' => $data['mime_type']]);
        } catch (\Throwable $th) {
            return response($th->getMessage(), 400, ['Content-Type' => 'text/html']);
        }
    }

    public function sendActivityIssue(Request $request)
    {
        $response = new Response();
        $data = [
            'success' => [],
            'error' => []
        ];
        $activity_issue = new ActivityIssue();
        try {
            $this->verify($request);

            if (
                !isset($request->phones) ||
                !isset($request->components)
            ) {
                throw new Exception('Envíe todos los campos necesarios para esta plantilla');
            }

            $components = $request->components;

            $activity_issue->setUrlPDF($components['urlpdf']);
            $activity_issue->setNamePDF($components['namepdf']);
            $activity_issue->setStartDate($components['startdate']);
            $activity_issue->setEndDate($components['enddate']);
            $activity_issue->setIssueDate($components['issuedate']);

            foreach ($request->phones as $destinatary) {
                $activity_issue->setPhone($destinatary['phone']);
                $activity_issue->setDestinatary($destinatary['name']);

                $body = $activity_issue->get();

                $res = new Fetch("https://graph.facebook.com/v15.0/{$_ENV['WHATSAPP_ID']}/messages", [
                    'method' => 'POST',
                    'body' => $body,
                    'headers' => [
                        "Authorization: Bearer {$_ENV['WHATSAPP_TOKEN_API']}",
                        "Content-Type: application/json"
                    ]
                ]);

                if ($res->ok) {
                    $data['success'][] = [
                        'phone' => $destinatary['phone'],
                        'destinatary' => $destinatary['name']
                    ];
                } else {
                    $json = $res->json();
                    $data['error'][] = [
                        'phone' => $destinatary['phone'],
                        'destinatary' => $destinatary['name'],
                        'error' => $json['error']['message']
                    ];
                }
            }

            if (count($data['success']) == 0) {
                throw new Exception('Ocurrió un error al enviar los mensajes');
            }

            $success = count($data['success']);
            $error = count($data['error']);

            $response->setStatus(200);
            $response->setMessage("Se han enviado {$success} correctos y {$error} fallidos");
            $response->setData($data);
        } catch (\Throwable $th) {
            $response->setStatus(400);
            $response->setMessage($th->getMessage() . ' Ln' . $th->getLine());
            $response->setData($data);
        } finally {
            return response(
                $response->toArray($request->toArray()),
                $response->getStatus()
            );
        }
    }

    public function sendContact(Request $request)
    {
        $response = new Response();
        $data = [
            'success' => [],
            'error' => []
        ];
        $contact = new Contact();
        try {
            //    $this->verify($request);

            if (
                !isset($request->name) ||
                !isset($request->email) ||
                !isset($request->phone) ||
                !isset($request->reason)
            ) {
                throw new Exception('Envíe todos los campos necesarios para esta plantilla');
            }

            $uxdJpa =  ViewUsersByDedicated::select([
                'user__person__name',
                'user__person__lastname',
                'user__person__phone__prefix',
                'user__person__phone__number',

                'dedicated__page',
                'dedicated__full_page',
                'dedicated__token',
                'dedicated__status'
            ])->where('dedicated__token', '=', $request->header('SoDe-Auth-Token'))->first();

            if (!$uxdJpa) {
                throw new Exception("Error: Usuario dedicado no existente");
            }

            $uxd = JSON::unflatten($uxdJpa->toArray(), '__');

            $contact->setDestinataryPhone($uxd['user']['person']['phone']['number']);
            $contact->setDestinataryName($uxd['user']['person']['name'] . ' ' . $uxd['user']['person']['lastname']);
            $contact->setPage($uxd['dedicated']['page']);
            $contact->setFullPage($uxd['dedicated']['full_page']);
            $contact->setNamesUser($request->name);
            $contact->setPhone($request->phone);
            $contact->setEmail($request->email);
            $contact->setMessage($request->reason);

            $body = $contact->get();

            $res = new Fetch("https://graph.facebook.com/v15.0/{$_ENV['WHATSAPP2_ID']}/messages", [
                'method' => 'POST',
                'body' => $body,
                'headers' => [
                    "Authorization: Bearer {$_ENV['WHATSAPP_TOKEN_API']}",
                    "Content-Type: application/json"
                ]
            ]);

            if ($res->ok) {
                $data['success'][] = [
                    'phone' => $request->phone,
                    'destinatary' => $request->name
                ];
            } else {
                $json = $res->json();
                $data['error'][] = [
                    'phone' => $request->phone,
                    'destinatary' => $request->name,
                    'error' => $json['error']['message']
                ];
            }


            if (count($data['success']) == 0) {
                throw new Exception('Ocurrió un error al enviar los mensajes');
            }

            $success = count($data['success']);
            $error = count($data['error']);

            $response->setStatus(200);
            $response->setMessage("Se han enviado {$success} correctos y {$error} fallidos");
            // $response->setData($body);
        } catch (\Throwable $th) {
            $response->setStatus(400);
            $response->setMessage($th->getMessage() . ' Ln' . $th->getLine());
            $response->setData($data);
        } finally {
            return response(
                $response->toArray($request->toArray()),
                $response->getStatus()
            );
        }
    }
}
