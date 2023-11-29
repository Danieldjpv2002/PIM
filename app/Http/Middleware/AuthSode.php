<?php

namespace App\Http\Middleware;

use App\ENV;
use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use Closure;
use Exception;
use Illuminate\Http\Request;
use SoDe\Extend\JSON;
use SoDe\Extend\Response;

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
            $username = $request->header('Auth-User');
            $auth_token = $request->header('Auth-Token');

            $userJpa = Usuarios::select()
                ->where('usuario', '=', $username)
                ->where('token', '=', $auth_token)
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
