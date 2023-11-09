<?php

namespace App\Http\Controllers;

use App\Http\Classes\dxResponse;
use App\Models\Categories;
use App\Models\dxDataGrid;
use App\Models\Response;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use SoDe\Extend\JSON;

class CategoriesController extends Controller
{
    public function all(Request $request): HttpResponse|ResponseFactory
    {
        $response = new Response();
        $session = $request->user;
        try {
            $catgoriesJpa = Categories::select(['id', 'category', '_business'])
                ->where('_business', $session['session']['business'])
                ->whereNotNull('status')
                ->get();

            $categories = [];
            foreach ($catgoriesJpa as $categoryJpa) {
                $category = JSON::unflatten($categoryJpa->toArray(), '__');
                unset($category['_business']);
                $categories[] = $category;
            }

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $categories;
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
    public function paginate(Request $request): HttpResponse|ResponseFactory
    {
        $response =  new dxResponse();
        $session = $request->user;
        try {
            $businessInstance = Categories::select([
                'id',
                'category',
                'description',
                'status',
                '_business'
            ]);

            if ($request->group != null) {
                [$grouping] = $request->group;
                $selector = \str_replace('.', '__', $grouping['selector']);
                $businessInstance = Categories::select([
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
            $category = null;
            if ($request->id) {
                $category = Categories::find($request->id);
            }
            if (!$category) {
                $category = new Categories();
            }
            $category->category = $request->category;
            $category->description = $request->description;
            $category->_business = $session['session']['business'];

            $category->save();

            $response->status = 200;
            $response->message = 'Operacion correcta';
            $response->data = $category->toArray();
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
            Categories::where('_business', $session['session']['business'])
                ->where('id', $id)
                ->update([
                    'status' => $request->status ? 0 : 1
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
            $deleted = Categories::where('_business', $session['session']['business'])
                ->where('id', $id)
                ->update(['status' => null]);

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
