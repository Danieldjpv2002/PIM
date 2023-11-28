<x-main title="Inicio" env="{{ $ENV }}">
  <div class="d-flex justify-content-center align-items-center" style="height: calc(100vh - 180px)">
    <div class="text-center">
      <h1>Bienvenido a mi PIM</h1>
      <p>Diseñar y desarrollar un sistema web de soporte técnico y de sistemas para el área de Sistemas de CARVIMSA”</p>

      <div>
        <button class="btn btn-sm btn-primary rounded-pill waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modal-newTicket">Nuevo ticket</button>
        <a href="/tickets" class="btn btn-sm btn-dark rounded-pill waves-effect waves-light">Mis tickets</a>
      </div>
    </div>
  </div>
</x-main>
