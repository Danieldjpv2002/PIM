<x-main title="Tipos" env="{{ $ENV }}">
  <x-table title="tipos"></x-table>
  <x-modal id="modal-tipos" title="Nuevo tipo" size="modal-sm">
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

<script src="./assets/js/tipos/events.js"></script>
<script src="./assets/js/tipos/handlers.js"></script>
<script src="./assets/js/tipos/tabla.js"></script>
