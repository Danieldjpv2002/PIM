<?php

namespace App\Http\Controllers;

use App\Http\Classes\dxResponse;
use App\Models\Adjuntos;
use App\Models\dxDataGrid;
use App\Models\Tipos;
use App\Models\Views\ViewAdjuntos;
use App\Models\Views\ViewTipos;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use SoDe\Extend\JSON;
use SoDe\Extend\Response;

class AdjuntosController extends Controller
{

    public function obtener(Request $request, string $id): HttpResponse|ResponseFactory
    {
        $response = new Response();
        try {
            $adjuntoJpa = Adjuntos::find($id);

            if (!$adjuntoJpa) throw new Exception('El adjunto solicitado no existe');

            return response($adjuntoJpa->binario, 200, [
                'Content-Type' => $adjuntoJpa->mimetipo
            ]);
        } catch (\Throwable $th) {
            $response->status = 400;
            $response->message = $th->getMessage();
            return response(
                $response->toArray(),
                $response->status
            );
        }
    }

    public function obtenerPorTicket(Request $request, string $ticket): HttpResponse|ResponseFactory
    {
        $response = new Response();
        try {
            $adjuntosJpa = ViewAdjuntos::select([
                'id', 'nombre', 'mimetipo', 'ticket__id'
            ])
                ->where('ticket__id', $ticket)
                ->get();

            $adjuntos = [];
            foreach ($adjuntosJpa as $adjuntoJpa) {
                $adjunto = JSON::unflatten($adjuntoJpa->toArray(), '__');
                $adjuntos[] = $adjunto;
            }

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $adjuntos;
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
            $blob = $request->file('blob');
            $ticket = $request->ticket;

            $adjunto = new Adjuntos();
            $adjunto->nombre = $blob->getClientOriginalName();
            $adjunto->mimetipo = $blob->getMimeType();
            $adjunto->binario = file_get_contents($blob->getRealPath());
            $adjunto->_ticket = $ticket;

            $adjunto->save();

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
