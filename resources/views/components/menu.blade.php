@php
  use App\ENV;
@endphp

<div class="left-side-menu">

  <div class="h-100" data-simplebar>

    <div class="user-box text-center">

      <img session="alt: Perfil de {nombres}; title: Perfil de {nombres}"
        src="//auth.sode.me/api/users/avatar/undefined/mini" alt="Perfil de usuario" title="Perfil de usuario"
        class="rounded-circle img-thumbnail avatar-md" style="background-color: transparent">
      <div class="dropdown">
        <a session="nombres" href="#" class="user-name dropdown-toggle h5 mt-2 mb-1 d-block"
          data-bs-toggle="dropdown" aria-expanded="false"></a>
        <div class="dropdown-menu user-pro-dropdown">

          <a id="btn-signout" href="javascript:void(0);" class="dropdown-item notify-item">
            <i class="fe-log-out me-1"></i>
            <span>Cerrar sesión</span>
          </a>

        </div>
      </div>

      <p session="usuario" class="text-muted left-user-info"></p>

      <ul class="list-inline">
        <li class="list-inline-item">
          <a href="#" class="text-muted left-user-info">
            <i class="mdi mdi-cog"></i>
          </a>
        </li>

        <li class="list-inline-item">
          <a id="btn-signout" href="javascript:void(0);">
            <i class="mdi mdi-power"></i>
          </a>
        </li>
      </ul>
    </div>

    <div id="sidebar-menu">

      <ul id="side-menu">

        <li class="menu-title">PANEL DE NAVEGACIÓN</li>

        <li>
          <a href="./inicio">
            <i class="mdi mdi-home"></i>
            <span> Inicio </span>
          </a>
        </li>

        <li class="menu-title mt-2">VENTANAS DEL SISTEMA</li>

        <li>
          <a href="#tickets" data-bs-toggle="collapse">
            <i class="mdi mdi-ticket-confirmation-outline"></i>
            <span> Tickets </span>
            <span class="menu-arrow"></span>
          </a>
          <div class="collapse" id="tickets">
            <ul class="nav-second-level">

              <li>
                <a href="./tickets">
                  <i class="mdi mdi-format-list-text"></i>
                  Mis Tickets
                </a>
              </li>
              <li rol-Agente rol-Administrador style="display: none">
                <a href="./lista">
                  <i class="mdi mdi-book-clock-outline"></i>
                  Lista de tickets
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li rol-Administrador style="display: none">
          <a href="#configuraciones" data-bs-toggle="collapse">
            <i class="mdi mdi-application-cog"></i>
            <span> Configuraciones </span>
            <span class="menu-arrow"></span>
          </a>
          <div class="collapse" id="configuraciones">
            <ul class="nav-second-level">

              <li>
                <a href="./categorias">
                  <i class="mdi mdi-format-list-text"></i>
                  Categorias
                </a>
              </li>
              <li>
                <a href="./tipos">
                  <i class="mdi mdi-format-list-bulleted-square"></i>
                  Tipos
                </a>
              </li>
              <li>
                <a href="./estados">
                  <i class="mdi mdi-list-status"></i>
                  Estados
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li rol-Administrador style="display: none">
          <a href="./usuarios">
            <i class="mdi mdi-account-group-outline"></i>
            <span> Usuarios </span>
          </a>
        </li>

      </ul>

    </div>

    <div class="clearfix"></div>

  </div>

</div>
