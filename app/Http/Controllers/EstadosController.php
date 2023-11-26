<?php

namespace App\Http\Controllers;

use App\Http\Classes\dxResponse;
use App\Models\dxDataGrid;
use App\Models\Estados;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use SoDe\Extend\JSON;
use SoDe\Extend\Response;

class EstadosController extends Controller
{
    public function lista(Request $request): HttpResponse|ResponseFactory
    {
        $response = new Response();
        try {
            $estadosJpa = Estados::select(['id', 'estado', 'descripcion'])
                ->get();

            $estados = [];
            foreach ($estadosJpa as $estadoJpa) {
                $estado = JSON::unflatten($estadoJpa->toArray(), '__');
                $estados[] = $estado;
            }

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $estados;
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
            $estadosInstance = Estados::select([
                'id',
                'estado',
                'descripcion'
            ]);

            if ($request->group != null) {
                [$grouping] = $request->group;
                $selector = \str_replace('.', '__', $grouping['selector']);
                $estadosInstance = Estados::select([
                    "{$selector} AS key"
                ])
                    ->groupBy($selector);
            }

            if ($request->filter) {
                $estadosInstance->where(function ($query) use ($request) {
                    dxDataGrid::filter($query, $request->filter ?? []);
                });
            }

            if ($request->sort != null) {
                foreach ($request->sort as $sorting) {
                    $selector = \str_replace('.', '__', $sorting['selector']);
                    $estadosInstance->orderBy(
                        $selector,
                        $sorting['desc'] ? 'DESC' : 'ASC'
                    );
                }
            } else {
                $estadosInstance->orderBy('id', 'DESC');
            }

            $totalCount = $estadosInstance->count('*');
            $estadosJpa = $request->isLoadingAll
                ? $estadosInstance->get()
                : $estadosInstance
                ->skip($request->skip ?? 0)
                ->take($request->take ?? 10)
                ->get();

            $estados = [];

            foreach ($estadosJpa as $estadoJpa) {
                $estado = JSON::unflatten($estadoJpa->toArray(), '__');
                $estados[] = $estado;
            }

            $response->status = 200;
            $response->message = 'Operación correcta';
            $response->data = $estados;
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
            $estado = null;
            if ($request->id) {
                $estado = Estados::find($request->id);
            }
            if (!$estado) {
                $estado = new Estados();
            }
            $estado->estado = $request->estado;
            $estado->descripcion = $request->descripcion;

            $estado->save();

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $estado->toArray();
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
            $deleted = Estados::where('id', $id)
                ->delete();

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
