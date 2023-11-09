<?php

namespace App\Http\Controllers;

use App\Http\Classes\dxResponse;
use App\Models\dxDataGrid;
use App\Models\Response;
use App\Models\Templates;
use App\Models\Views\ViewTemplates;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use SoDe\Extend\JSON;

class TemplatesController extends Controller
{
    public function paginate(Request $request): HttpResponse|ResponseFactory
    {
        $response =  new dxResponse();
        $session = $request->user;
        try {
            $businessInstance = ViewTemplates::select();

            if ($request->group != null) {
                [$grouping] = $request->group;
                $selector = \str_replace('.', '__', $grouping['selector']);
                $businessInstance = ViewTemplates::select([
                    "{$selector} AS key"
                ])
                    ->groupBy($selector);
            }

            $businessInstance->where('_business', '=', $session['session']['business']);
            $businessInstance->whereNotNull('status');
            if ($request->filter) {
                $businessInstance->where(function ($query) use ($request) {
                    dxDataGrid::filter($query, $request->filter ?? []);
                });
            }

            if ($request->sort != null) {
                foreach ($request->sort as $sorting) {
                    $selector = \str_replace('.', '__', $sorting['selector']);
                    $businessInstance->orderBy(
                        $selector,
                        $sorting['desc'] ? 'DESC' : 'ASC'
                    );
                }
            } else {
                $businessInstance->orderBy('id', 'DESC');
            }

            $totalCount = $businessInstance->count('*');
            $businessesJpa = $request->isLoadingAll
                ? $businessInstance
                ->skip($request->skip ?? 0)
                ->take($request->take ?? 10)
                ->get()
                : $businessInstance->get();

            $businesses = [];

            foreach ($businessesJpa as $businessJpa) {
                $business = JSON::unflatten($businessJpa->toArray(), '__');
                $businesses[] = $business;
            }

            $response->status = 200;
            $response->message = 'Operación correcta';
            $response->data = $businesses;
            $response->totalCount = $totalCount;
        } catch (\Throwable $th) {
            $response->status = 400;
            $response->message = $th->getMessage();
        } finally {
            return response(
                $response->toArray(),
                $response->status
            );
        }
    }

    public function create(Request $request): HttpResponse|ResponseFactory
    {
        $response = new Response();
        $session = $request->user;
        try {
            $template = null;
            if ($request->id) {
                $template = Templates::find($request->id);
            }
            if (!$template) {
                $template = new Templates();
            }
            $template->_category = $request->category;
            $template->template = $request->template;
            $template->description = $request->description;

            $template->save();

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $template->toArray();
        } catch (\Throwable $th) {
            $response->status = 400;
            $response->message = $th->getMessage();
        } finally {
            return response(
                $response->toArray(),
                $response->status
            );
        }
    }

    static function status(Request $request, string $id)
    {
        $response = new Response();
        $session = $request->user;
        try {
            Templates::join('categories', 'templates._category', 'categories.id')
                ->where('categories._business', $session['session']['business'])
                ->where('templates.id', $id)
                ->update([
                    'templates.status' => $request->status ? 0 : 1
                ]);

            $response->status = 200;
            $response->message = 'Operacion correcta';
        } catch (\Throwable $th) {
            $response->status = 400;
            $response->message = $th->getMessage();
        } finally {
            return response(
                $response->toArray(),
                $response->status
            );
        }
    }

    static function delete(Request $request, string $id)
    {
        $response = new Response();
        $session = $request->user;
        try {
            $deleted = Templates::join('categories', 'templates._category', 'categories.id')
                ->where('categories._business', $session['session']['business'])
                ->where('templates.id', $id)
                ->update([
                    'templates.status' => null
                ]);

            if (!$deleted) throw new Exception('No se ha eliminado ningun registro');

            $response->status = 200;
            $response->message = 'Operacion correcta';
        } catch (\Throwable $th) {
            $response->status = 400;
            $response->message = $th->getMessage();
        } finally {
            return response(
                $response->toArray(),
                $response->status
            );
        }
    }
}
