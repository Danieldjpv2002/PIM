<x-main title="Usuarios">
  <x-table title="usuarios"></x-table>
  <x-modal id="modal-usuarios" title="Nuevo usuario">
    <input type="hidden" name="txt_id" id="txt_id">
    <div class="row">
      <div class="col-md-6">
        <div class="from-group mb-2">
          <label for="txt_usuario">Usuario</label>
          <input id="txt_usuario" type="text" class="form-control" placeholder="Ingrese el usuario" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="from-group mb-2">
          <label for="txt_clave">Clave</label>
          <input id="txt_clave" type="password" class="form-control" placeholder="Ingrese la clave" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="from-group mb-2">
          <label for="txt_nombres">Nombres</label>
          <input id="txt_nombres" type="text" class="form-control" placeholder="Ingrese el/los nombre(s)" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="from-group mb-2">
          <label for="txt_apellidos">Apellidos</label>
          <input id="txt_apellidos" type="text" class="form-control" placeholder="Ingrese el/los nombre(s)" required>
        </div>
      </div>
      <div class="col-md-8">
        <div class="from-group mb-2">
          <label for="txt_correo">Correo</label>
          <input id="txt_correo" type="email" class="form-control" placeholder="Ingrese el correo" required>
        </div>
      </div>
      <div class="col-md-4">
        <div class="from-group mb-2">
          <label for="txt_telefono">Telefono</label>
          <input id="txt_telefono" type="phone" class="form-control" placeholder="Ingrese el telefono" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="from-group mb-2">
          <label for="txt_ip">IP</label>
          <input id="txt_ip" type="phone" class="form-control" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$"
            placeholder="192.168.0.1">
        </div>
      </div>
      <div class="col-md-6">
        <div class="from-group mb-2">
          <label for="txt_anydesk">Anydesk</label>
          <input id="txt_anydesk" type="text" class="form-control" placeholder="7646576712">
        </div>
      </div>
      <div class="col-md-6">
        <div class="from-group mb-2">
          <label for="cbo_rol">Resuelve tickets</label>
          <select name="cbo_rol" id="cbo_rol" class="form-control" style="width: 100%" required>
            <option>Administrador</option>
            <option>Agente</option>
            <option selected>Usuario</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="from-group mb-2">
          <label for="txt_importancia">Importancia</label>
          <input id="txt_importancia" type="number" class="form-control" placeholder="Ingrese la importancia">
        </div>
      </div>
    </div>
  </x-modal>
</x-main>

<script src="./assets/js/usuarios/events.js?v={{ uniqid() }}"></script>
<script src="./assets/js/usuarios/handlers.js?v={{ uniqid() }}"></script>
<script src="./assets/js/usuarios/tabla.js?v={{ uniqid() }}"></script>
