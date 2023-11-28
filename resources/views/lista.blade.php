<x-main title="Tickets" env="{{ $ENV }}">
  <style>
    #contenedor_adjuntos,
    #contenedor_adjuntos_solucion {
      display: flex;
      flex-direction: row;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
    }

    #contenedor_adjuntos img,
    #contenedor_adjuntos_solucion img {
      width: 100px;
      height: 80px;
      border-radius: 5px;
      object-fit: cover;
      object-position: center;
      -o-object-fit: cover;
      -o-object-position: center;
      box-shadow: 0 0 5px rgba(0, 0, 0, .25);
    }
  </style>
  <x-table title="tickets"></x-table>
  <x-modal id="modal-tickets" title="Detalles del ticket" size="modal-md" hide-footer>
    <p class="mb-1"><b>Informador</b>: <span id="txt_informador"></span></p>
    <p class="mb-1"><b>Correo</b>: <span id="txt_correo"></span></p>
    <p class="mb-1"><b>Telefono</b>: <span id="txt_telefono"></span></p>
    <p class="mb-1"><b>Anydesk</b>: <span id="txt_anydesk"></span></p>
    <p class="mb-1"><b>IP</b>: <span id="txt_ip"></span></p>
    <hr>
    <p class="mb-1"><b>Categoria</b>: <span id="txt_categoria"></span></p>
    <p class="mb-1"><b>Tipo</b>: <span id="txt_tipo"></span></p>
    <p class="mb-1"><b>Resumen:</b> <span id="txt_resumen"></span></p>
    <p class="mb-1"><b>Descripcion:</b></p>
    <div id="contenedor_descripcion" style="border: 1px solid #ddd; border-radius: 5px; padding: 10px" class="mb-1">
    </div>
    <p class="mb-1"><b>Adjuntos:</b></p>
    <div id="contenedor_adjuntos" class="mb-2"></div>
    <hr>
    <p class="mb-1"><b>Estado:</b> <span id="txt_estado"></span></p>
    <p class="mb-1"><b>Solucion:</b></p>
    <div id="contenedor_descripcion_solucion" style="border: 1px solid #ddd; border-radius: 5px; padding: 10px"
      class="mb-1">
    </div>
    <p class="mb-1"><b>Adjuntos de la solucion:</b></p>
    <div id="contenedor_adjuntos_solucion" class="mb-2"></div>

  </x-modal>
  <x-modal id="modal-solucion" title="Agregar solucion al ticket" size="modal-sm">
    <input type="hidden" name="txt-ticket-id" id="txt-ticket-id">
    <div class="form-group mb-2">
      <label for="txt-solucion"><b>Solucion:</b></label>
      <textarea name="txt-solucion" id="txt-solucion" rows="3" class="form-control"></textarea>
    </div>
    <div class="form-group">
      <label for="container-ticket-adjunto-solucion" class="mb-1"><b>Adjuntos de la solucion:</b></label>
      <div id="container-ticket-adjunto-solucion"
        style="position: relative; height: 120px; border: 1px dashed #ced4da; border-radius: 0.2rem">
        <div class="message-pasting">
          <div class="text-center">
            <h2 style="margin: 0; ">
              <i class="mdi mdi-cloud-upload-outline"></i>
            </h2>
            <h4 style="margin: 0;">- Arrastra tus archivos adjuntos aquí -
            </h4>
          </div>
        </div>
        <input id="file-ticket-adjunto-solucion"type="file" style="display: none" multiple>
        <label for="file-ticket-adjunto-solucion" class="btn btn-xs btn-soft-dark"
          style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%)">Carga o arrastra tus
          archivos aqui</label>
      </div>
      <div id="container-ticket-lista-adjuntos-solucion"></div>
    </div>
  </x-modal>
</x-main>

<script src="./assets/js/lista/data.js?v={{ uniqid() }}"></script>
<script src="./assets/js/lista/events.js?v={{ uniqid() }}"></script>
<script src="./assets/js/lista/handlers.js?v={{ uniqid() }}"></script>
<script src="./assets/js/lista/tabla.js?v={{ uniqid() }}"></script>
