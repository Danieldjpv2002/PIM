<?php

namespace App\Http\Controllers;

use App\Http\Classes\dxResponse;
use App\Models\dxDataGrid;
use App\Models\Tickets;
use App\Models\Tipos;
use App\Models\Views\ViewTipos;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use SoDe\Extend\JSON;
use SoDe\Extend\Response;

class TicketsController extends Controller
{
    public function lista(Request $request): HttpResponse|ResponseFactory
    {
        $response = new Response();
        try {
            $tiposJpa = ViewTipos::select()
                ->whereNotNull('estado')
                ->get();

            $tipos = [];
            foreach ($tiposJpa as $tipoJpa) {
                $tipo = JSON::unflatten($tipoJpa->toArray(), '__');
                $tipos[] = $tipo;
            }

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $tipos;
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
    public function paginado(Request $request): HttpResponse|ResponseFactory
    {
        $response =  new dxResponse();
        try {
            $tiposInstance = ViewTipos::select();

            if ($request->group != null) {
                [$grouping] = $request->group;
                $selector = \str_replace('.', '__', $grouping['selector']);
                $tiposInstance = ViewTipos::select([
                    "{$selector} AS key"
                ])
                    ->groupBy($selector);
            }

            $tiposInstance->whereNotNull('estado');
            if ($request->filter) {
                $tiposInstance->where(function ($query) use ($request) {
                    dxDataGrid::filter($query, $request->filter ?? []);
                });
            }

            if ($request->sort != null) {
                foreach ($request->sort as $sorting) {
                    $selector = \str_replace('.', '__', $sorting['selector']);
                    $tiposInstance->orderBy(
                        $selector,
                        $sorting['desc'] ? 'DESC' : 'ASC'
                    );
                }
            } else {
                $tiposInstance->orderBy('id', 'DESC');
            }

            $totalCount = $tiposInstance->count('*');
            $tiposJpa = $request->isLoadingAll
                ? $tiposInstance->get()
                : $tiposInstance
                ->skip($request->skip ?? 0)
                ->take($request->take ?? 10)
                ->get();

            $tipos = [];

            foreach ($tiposJpa as $tipoJpa) {
                $tipo = JSON::unflatten($tipoJpa->toArray(), '__');
                $tipos[] = $tipo;
            }

            $response->status = 200;
            $response->message = 'Operación correcta';
            $response->data = $tipos;
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

    public function crear(Request $request): HttpResponse|ResponseFactory
    {
        $response = new Response();
        try {
            $ticket = new Tickets();
            $ticket->_tipo = $request->_tipo;
            $ticket->asunto = $request->asunto;
            $ticket->descripcion = $request->descripcion;

            $ticket->save();

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $ticket->toArray();
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

    static function estado(Request $request, string $id)
    {
        $response = new Response();
        try {
            Tipos::where('id', $id)
                ->update([
                    'estado' => $request->status ? 0 : 1
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

    static function eliminar(Request $request, string $id)
    {
        $response = new Response();
        try {
            $deleted = Tipos::where('id', $id)
                ->update(['estado' => null]);

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
