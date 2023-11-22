<x-main title="Tickets" env="{{ $ENV }}">
  <x-table title="tickets"></x-table>
  <x-modal id="modal-tickets" title="Detalles del ticket" size="modal-md">
    <input type="hidden" name="txt_id" id="txt_id">
    <div class="from-group mb-2">
      <label for="cbo_categoria">Categoria</label>
      <select id="cbo_categoria" class="form-control" style="width: 100%"></select>
    </div>
    <div class="from-group mb-2">
      <label for="txt_tipo">Tipo</label>
      <input id="txt_tipo" type="text" class="form-control">
    </div>
    <div class="form-group">
      <label for="txt_descripcion">Descripcion</label>
      <textarea name="txt_descripcion" id="txt_descripcion" class="form-control" rows="3"></textarea>
    </div>
  </x-modal>
</x-main>

<script src="./assets/js/lista/events.js"></script>
<script src="./assets/js/lista/handlers.js"></script>
<script src="./assets/js/lista/tabla.js"></script>
