<?php


namespace App\Http\Controllers;

use App\Models\Usuarios;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use SoDe\Extend\Crypto;
use SoDe\Extend\JSON;
use SoDe\Extend\Response;

class AuthController
{
    public function login(Request $request): HttpResponse|ResponseFactory
    {
        $response = new Response();
        try {
            $userJpa = Usuarios::select([
                'id',
                'usuario',
                'clave',
                'estado',
            ])
                ->where('usuario', $request->usuario)
                ->first();

            if (!$userJpa) {
                throw new Exception('El usuario no está registrado en el sistema');
            }

            if (!$userJpa->estado) {
                throw new Exception('El usuario se encuentra inactivo');
            }

            if (!password_verify($request->clave, $userJpa->clave)) {
                throw new Exception('La contraseña ingresada es incorrecta');
            }

            $token = Crypto::randomUUID();

            $userJpa->token = $token;
            $userJpa->save();

            $response->status = 200;
            $response->message = 'Operación correcta';
            $response->data = [
                'usuario' => $userJpa->usuario,
                'token' => $userJpa->token
            ];
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

    public function verify(Request $request)
    {
        $response = new Response();
        try {
            $username = $request->header('Auth-User');
            $auth_token = $request->header('Auth-Token');

            if (!$username || !$auth_token) {
                throw new Exception('Faltan alguno parámetros para la autenticación');
            }

            $userJpa = Usuarios::select()
                ->where('usuario', $username)
                ->first();

            if (!$userJpa) {
                throw new Exception('El usuario no está registrado en el sistema');
            }

            if (!$userJpa->estado) {
                throw new Exception('El usuario se encuentra inactivo');
            }

            if ($userJpa->token != $auth_token) {
                throw new Exception('El token de autenticación es inválido');
            }

            $user = $userJpa->toArray();
            unset($user['clave']);

            $response->status = 200;
            $response->message = 'Operación correcta';
            $response->data = JSON::unflatten($user, '__');
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
