<x-main title="Tickets" env="{{ $ENV }}">
  <style>
    #contenedor_adjuntos {
      display: flex;
      flex-direction: row;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
    }

    #contenedor_adjuntos img {
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
  <x-modal id="modal-tickets" title="Detalles del ticket" size="modal-md">
    <p class="mb-1"><b>Informador</b>: <span id="txt_informador"></span></p>
    <p class="mb-1"><b>Correo</b>: <span id="txt_correo"></span></p>
    <p class="mb-1"><b>Telefono</b>: <span id="txt_telefono"></span></p>
    <p class="mb-1"><b>Anydesk</b>: <span id="txt_anydesk"></span></p>
    <p class="mb-1"><b>IP</b>: <span id="txt_ip"></span></p>
    <hr>
    <p class="mb-1"><b>Categoria</b>: <span id="txt_categoria"></span></p>
    <p class="mb-1"><b>Tipo</b>: <span id="txt_tipo"></span></p>
    <p class="mb-1"><b>Resumen</b>: <span id="txt_resumen"></span></p>
    <p class="mb-1"><b>Descripcion</b>:</p>
    <div id="contenedor_descripcion" style="border: 1px solid #ddd; border-radius: 5px; padding: 10px" class="mb-1">
    </div>
    <p class="mb-1"><b>Adjuntos</b>:</p>
    <div id="contenedor_adjuntos"></div>
  </x-modal>
</x-main>

<script src="./assets/js/lista/data.js?v={{ uniqid() }}"></script>
<script src="./assets/js/lista/events.js?v={{ uniqid() }}"></script>
<script src="./assets/js/lista/handlers.js?v={{ uniqid() }}"></script>
<script src="./assets/js/lista/tabla.js?v={{ uniqid() }}"></script>
