<?php

namespace App\Http\Controllers;

use App\Http\RestClient\GitHubRestClient;
use App\Http\RestClient\WhatsAppRestClient;
use App\Models\Response;
use App\Models\SoDe\Views\ViewUsers;
use App\Templates\GitHubMessage;
use App\Templates\MediaTemplate;
use Exception;
use Illuminate\Http\Request;

# SoDe Packages
use SoDe\Extend\HTML;
use SoDe\Extend\JSON;
use SoDe\Extend\Status;
use SoDe\Extend\Text;
use SoDe\Extend\Trace;

class GitHubController extends Controller
{
    public function webhook(Request $request)
    {
        $response = new Response();
        try {
            if (isset($request->hook)) {
                throw new Exception('Hook listo', 200);
            }
            $flatten = JSON::flatten($request->toArray());

            $added = implode(Text::lineBreak(), $request->head_commit['added']);
            $added = $added == '' ? 'Ninguno' : $added;
            $added = Text::reduce($added, 256);

            $modified = implode(Text::lineBreak(), $request->head_commit['modified']);
            $modified = $modified == '' ? 'Ninguno' : $modified;
            $modified = Text::reduce($modified, 256);

            $removed = implode(Text::lineBreak(), $request->head_commit['removed']);
            $removed = $removed == '' ? 'Ninguno' : $removed;
            $removed = Text::reduce($removed, 256);

            $commits = array();

            foreach ($request->commits as $commit) {
                $commits[] = trim($commit['message']);
            }

            $github_message = new GitHubMessage();

            $github_message->commiter = $flatten['head_commit.committer.name'];
            $github_message->repository = $flatten['repository.name'];
            $github_message->branch = explode('/', $flatten['ref'])[2];
            $github_message->owner = $flatten['repository.owner.name'];
            $github_message->commit = implode('. ', $commits);
            $github_message->added = $added == '' ? 'Ninguno' : $added;
            $github_message->modified = $modified == '' ? 'Ninguno' : $modified;
            $github_message->removed = $removed == '' ? 'Ninguno' : $removed;
            $github_message->username = $flatten['repository.owner.login'];
            $github_message->commit_id = $flatten['head_commit.id'];

            // INICIO: Generar banner GitHub
            $html = file_get_contents('../storage/templates/banner.template.html');
            $res_github = GitHubRestClient::users($flatten['repository.owner.login']);
            $data_github = $res_github->json();

            $html = str_replace([
                '{{avatar_url}}',
                '{{id}}',
                '{{login}}',
                '{{company}}',
                '{{name}}'
            ], [
                "https://avatars.githubusercontent.com/u/{$data_github['id']}?v=" . Trace::getId(),
                $data_github['id'],
                $data_github['login'],
                $data_github['company'],
                $data_github['name']
            ], $html);

            $image_url = ''; //HTML::toImage($html);
            // FIN: Generar banner GitHub

            $data = [
                'success' => [],
                'error' => []
            ];

            $uxssJpa = ViewUsers::select([
                'person__phone__full',
                'person__name'
            ])
                ->where('person__phone__full', '<>', '')
                ->where('developer', 1)
                ->get();

            $media = new MediaTemplate();

            foreach ($uxssJpa as $uxsJpa) {
                $uxs = JSON::unflatten($uxsJpa->toArray(), '__');
                $phone = $uxs['person']['phone']['full'];
                if ($phone == null || $phone == '') {
                    $data['error'][] = [
                        'phone' => $phone,
                        'error' => 'Número inválido'
                    ];
                    continue;
                }
                
                $github_message->contact = $uxs['person']['name'];

                $media->media($image_url, $github_message->get());
                $media->to = $phone;
                $res = WhatsAppRestClient::sendMessage($github_message->get(), $phone);
                // $res = WhatsAppRestClient::sendMedia($media);

                if ($res->ok) {
                    $data['success'][] = [
                        'phone' => $phone
                    ];
                } else {
                    $json = $res->json();
                    $f_json = JSON::flatten($json);
                    $details = array();
                    if (isset($f_json['error.message'])) $details[] = $f_json['error.message'];
                    if (isset($f_json['error.error_data.details'])) $details[] = $f_json['error.error_data.details'];
                    $data['error'][] = [
                        'phone' => $phone,
                        'error' => implode('. ', $details)
                    ];
                }
            }

            $success = count($data['success']);
            $error = count($data['error']);

            $response->setStatus(200);
            $response->setMessage("Se han enviado {$success} correctos y {$error} fallidos");
            $response->setData($data);
        } catch (\Throwable $th) {
            $response->setStatus(Status::get($th->getCode()));
            $response->setMessage($th->getMessage() . ' ' . $th->getFile() . 'Ln' . $th->getLine());
            $response->setData($request->toArray());
        } finally {
            return response(
                $response->toArray($request),
                $response->getStatus()
            );
        }
    }

    public function banner(Request $request, string $username)
    {
        // INICIO: Generar banner GitHub
        $html = file_get_contents('../storage/templates/banner.template.html');
        $res_github = GitHubRestClient::users($username);
        $data_github = $res_github->json();

        $html = str_replace([
            '{{id}}',
            '{{login}}',
            '{{company}}',
            '{{name}}'
        ], [
            $data_github['id'],
            $data_github['login'],
            $data_github['company'],
            $data_github['name']
        ], $html);

        $image = HTML::toImage($html, 'blob');
        // FIN: Generar banner GitHub

        return response($image, 200, [
            'Content-Type' => 'image/png'
        ]);
    }
}
