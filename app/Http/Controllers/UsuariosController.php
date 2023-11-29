<?php

namespace App\Http\Controllers;

use App\Http\Classes\dxResponse;
use App\Models\dxDataGrid;
use App\Models\Tipos;
use App\Models\Usuarios;
use App\Models\Views\ViewTipos;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use SoDe\Extend\JSON;
use SoDe\Extend\Response;

class UsuariosController extends Controller
{
    public function lista(Request $request): HttpResponse|ResponseFactory
    {
        $response = new Response();
        try {
            $jpas = Usuarios::select([
                'id', 'nombres', 'apellidos'
            ])
                ->whereNotNull('estado')
                ->get();

            $results = [];
            foreach ($jpas as $jpa) {
                $result = JSON::unflatten($jpa->toArray(), '__');
                $results[] = $result;
            }

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $results;
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
            $instance = Usuarios::select();

            if ($request->group != null) {
                [$grouping] = $request->group;
                $selector = \str_replace('.', '__', $grouping['selector']);
                $instance = Usuarios::select([
                    "{$selector} AS key"
                ])
                    ->groupBy($selector);
            }

            $instance->whereNotNull('estado');
            if ($request->filter) {
                $instance->where(function ($query) use ($request) {
                    dxDataGrid::filter($query, $request->filter ?? []);
                });
            }

            if ($request->sort != null) {
                foreach ($request->sort as $sorting) {
                    $selector = \str_replace('.', '__', $sorting['selector']);
                    $instance->orderBy(
                        $selector,
                        $sorting['desc'] ? 'DESC' : 'ASC'
                    );
                }
            } else {
                $instance->orderBy('id', 'DESC');
            }

            $totalCount = $instance->count('*');
            $jpas = $request->isLoadingAll
                ? $instance->get()
                : $instance
                ->skip($request->skip ?? 0)
                ->take($request->take ?? 10)
                ->get();

            $results = [];

            foreach ($jpas as $jpa) {
                $result = JSON::unflatten($jpa->toArray(), '__');
                $results[] = $result;
            }

            $response->status = 200;
            $response->message = 'Operación correcta';
            $response->data = $results;
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
            $usuario = null;
            if ($request->id) {
                $usuario = Usuarios::find($request->id);
            }
            if (!$usuario) {
                $usuario = new Usuarios();
            }
            $usuario->usuario = $request->usuario;
            if ($request->clave) {
                if (strlen($request->clave) >= 8) {
                    $usuario->clave = password_hash($request->clave, PASSWORD_DEFAULT);
                    $usuario->token = null;
                } else {
                    throw new Exception('La contrasena debe tener al menos 8 caracteres');
                }
            }
            $usuario->nombres = $request->nombres;
            $usuario->apellidos = $request->apellidos;
            $usuario->correo = $request->correo;
            $usuario->telefono = $request->telefono;
            $usuario->ip = $request->ip;
            $usuario->anydesk = $request->anydesk;
            $usuario->rol = $request->rol;
            $usuario->importancia = $request->importancia;

            $usuario->save();

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $usuario->toArray();
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
            Usuarios::where('id', $id)
                ->update([
                    'estado' => $request->status ? 0 : 1,
                    'token' => null
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
            $deleted = Usuarios::where('id', $id)
                ->update([
                    'estado' => null,
                    'token' => null
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
