<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessMessage;
use App\Models\Response;
use Exception;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
  /**
   * Recibe una solicitud de la API de WhatsApp Business, verifica
   * si la solicitud es válida y, si lo es, devuelve una respuesta
   * con el desafío.
   * 
   * @param Request La solicitud entrante.
   * 
   * @return El challenge enviado por Meta.
   */
  public function verify(Request $request)
  {
    $status = 403;
    $message = 'Error inesperado';
    try {

      $mode = $request->hub_mode;
      $token = $request->hub_verify_token;
      $challenge = $request->hub_challenge;

      if (!$mode || !$token) {
        throw new Exception('Request inválido', 1);
      }

      if ($mode != 'subscribe' || $token != $_ENV['WHATSAPP_TOKEN']) {
        throw new Exception('Token inválido', 1);
      }

      $status = 200;
      $message = $challenge;
    } catch (\Throwable $th) {
      $status = 403;
      $message = $th->getMessage();
    } finally {
      return response($message, $status);
    }
  }

  public function webhook(Request $request)
  {
    $response = new Response();
    $response->status = 200;
    $response->message = 'Operación correcta';
    ProcessMessage::dispatchAfterResponse($request);
    return response($response->toArray(), $response->status);
  }
}
