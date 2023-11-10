<?php

namespace App\Http\Controllers;

use App\Http\Classes\dxResponse;
use App\Models\Categorias;
use App\Models\dxDataGrid;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use SoDe\Extend\JSON;
use SoDe\Extend\Response;

class CategoriasController extends Controller
{
    public function lista(Request $request): HttpResponse|ResponseFactory
    {
        $response = new Response();
        try {
            $categoriasJpa = Categorias::select(['id', 'categoria', 'descripcion', 'estado'])
                ->whereNotNull('estado')
                ->get();

            $categorias = [];
            foreach ($categoriasJpa as $categoriaJpa) {
                $categoria = JSON::unflatten($categoriaJpa->toArray(), '__');
                unset($categoria['_business']);
                $categorias[] = $categoria;
            }

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $categorias;
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
            $categoriaInstance = Categorias::select([
                'id',
                'categoria',
                'descripcion',
                'estado',
            ]);

            if ($request->group != null) {
                [$grouping] = $request->group;
                $selector = \str_replace('.', '__', $grouping['selector']);
                $categoriaInstance = Categorias::select([
                    "{$selector} AS key"
                ])
                    ->groupBy($selector);
            }

            $categoriaInstance->whereNotNull('estado');
            if ($request->filter) {
                $categoriaInstance->where(function ($query) use ($request) {
                    dxDataGrid::filter($query, $request->filter ?? []);
                });
            }

            if ($request->sort != null) {
                foreach ($request->sort as $sorting) {
                    $selector = \str_replace('.', '__', $sorting['selector']);
                    $categoriaInstance->orderBy(
                        $selector,
                        $sorting['desc'] ? 'DESC' : 'ASC'
                    );
                }
            } else {
                $categoriaInstance->orderBy('id', 'DESC');
            }

            $totalCount = $categoriaInstance->count('*');
            $categoriasJpa = $request->isLoadingAll
                ? $categoriaInstance->get()
                : $categoriaInstance
                ->skip($request->skip ?? 0)
                ->take($request->take ?? 10)
                ->get();

            $categorias = [];

            foreach ($categoriasJpa as $categoriaJpa) {
                $categoria = JSON::unflatten($categoriaJpa->toArray(), '__');
                $categorias[] = $categoria;
            }

            $response->status = 200;
            $response->message = 'Operación correcta';
            $response->data = $categorias;
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
            $categoria = null;
            if ($request->id) {
                $categoria = Categorias::find($request->id);
            }
            if (!$categoria) {
                $categoria = new Categorias();
            }
            $categoria->categoria = $request->categoria;
            $categoria->descripcion = $request->descripcion;

            $categoria->save();

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $categoria->toArray();
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
            Categorias::where('id', $id)
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
            $deleted = Categorias::where('id', $id)
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
