<x-main title="Categorias" env="{{$ENV}}">
  <x-table title="categorias"></x-table>
  <x-modal id="modal-categorias" title="Nuevo categoria" size="modal-sm">
    <input type="hidden" name="txt_id" id="txt_id">
    <div class="from-group mb-2">
      <label for="txt_categoria">Categoria</label>
      <input id="txt_categoria" type="text" class="form-control">
    </div>
    <div class="form-group">
      <label for="txt_descripcion">Descripcion</label>
      <textarea name="txt_descripcion" id="txt_descripcion" class="form-control" rows="3"></textarea>
    </div>
  </x-modal>
</x-main>

<script src="./assets/js/categorias/events.js"></script>
<script src="./assets/js/categorias/handlers.js"></script>
<script src="./assets/js/categorias/tabla.js"></script>