<x-modal id="modal-newTicket" title="Nuevo ticket" btn-submit-text="Guardar">
  <div class="row">
    <div class="form-group col-12">
      <label for="cbo-ticket-tipo" class="mb-1">Tipo de incidencia</label>
      <select name="cbo-ticket-tipo" id="cbo-ticket-tipo" class="form-control" style="width: 100%" required></select>
    </div>
    <div id="modal-description" style="display: none">
      <hr>
      <div class="form-group col-12 mb-2">
        <label for="txt-ticket-asunto" class="mb-1">Resumen</label>
        <input name="txt-ticket-asunto" id="txt-ticket-asunto" type="text" class="form-control" required>
      </div>
      <div class="form-group col-12 mb-2">
        <label for="txt-ticket-descripcion" class="mb-1">Descripcion</label>
        <div name="txt-ticket-descripcion" id="txt-ticket-descripcion" style="height: 120px"></div>
      </div>
      <div class="form-group col-12">
        <label for="container-ticket-adjunto" class="mb-1">Adjuntos</label>
        <div id="container-ticket-adjunto"
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
          <input id="file-ticket-adjunto"type="file" style="display: none" multiple>
          <label for="file-ticket-adjunto" class="btn btn-xs btn-soft-dark"
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%)">Carga o arrastra tus
            archivos aqui</label>
        </div>
        <div id="container-ticket-lista-adjuntos"></div>
      </div>
    </div>

  </div>
</x-modal>
