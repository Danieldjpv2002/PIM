<x-main title="Estados" env="{{$ENV}}">
  <x-table title="estados"></x-table>
  <x-modal id="modal-estados" title="Nuevo estado" size="modal-sm">
    <input type="hidden" name="txt_id" id="txt_id">
    <div class="from-group mb-2">
      <label for="txt_estado">Estado</label>
      <input id="txt_estado" type="text" class="form-control">
    </div>
    <div class="form-group">
      <label for="txt_descripcion">Descripcion</label>
      <textarea name="txt_descripcion" id="txt_descripcion" class="form-control" rows="3"></textarea>
    </div>
  </x-modal>
</x-main>

<script src="./assets/js/estados/events.js"></script>
<script src="./assets/js/estados/handlers.js"></script>
<script src="./assets/js/estados/tabla.js"></script>