<?php

namespace App\Http\Middleware;

use App\ENV;
use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\SoDe\Users;
use Closure;
use Exception;
use Illuminate\Http\Request;
use SoDe\Extend\JSON;

class AuthSode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = new Response();
        try {
            $username = Controller::decode($request->header('SoDe-Auth-User'));
            $auth_token = Controller::decode($request->header('SoDe-Auth-Token'));

            $userJpa = Users::select([
                'users.id AS id',
                'users.username AS username',
                'users.auth_token AS auth_token',
                'sessions._business AS session__business',
                'services.correlative AS session__service'
            ])
                ->leftJoin('sessions', 'users.id', 'sessions._user')
                ->leftJoin('services', 'sessions._service', 'services.id')
                ->where('users.username', '=', $username)
                ->where('users.auth_token', '=', $auth_token)
                ->where('services.correlative', '=', ENV::APP_CORRELATIVE)
                ->orderBy('sessions.id', 'DESC')
                ->first();

            if (!$userJpa) {
                throw new Exception("No tienes una sesión activa con {$username} {$auth_token}");
            }

            $request->user = JSON::unflatten($userJpa->toArray(), '__');

            return $next($request);
        } catch (\Throwable $th) {
            $response->status = 401;
            $response->message = $th->getMessage();
            return response(
                $response->toArray(),
                $response->status
            );
        }
    }
}
