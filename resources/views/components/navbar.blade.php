@php
  use App\ENV;
@endphp

<div class="navbar-custom">
  <ul class="list-unstyled topnav-menu float-end mb-0">

    <li class="notification-list">
      <a id="btn-newTicket" class="nav-link waves-effect waves-light" title="Nuevo ticket" data-bs-toggle="modal" data-bs-target="#modal-newTicket">
        <i class="fa fas fa-plus noti-icon"></i>
      </a>
    </li>

    <li class="dropdown notification-list topbar-dropdown">
      <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#"
        role="button" aria-haspopup="false" aria-expanded="false">
        <img
          session="alt: Perfil de {nombres}; title: Perfil de {nombres}"
          src="//auth.sode.me/api/users/avatar/undefined/mini" alt="Perfil de usuario"
          title="Perfil de usuario" class="rounded-circle">
        <span class="pro-user-name ms-1">
          <span session="nombres"></span>
          <i class="mdi mdi-chevron-down"></i>
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
        <div class="dropdown-header noti-title">
          <h6 class="text-overflow m-0">Bienvenido!</h6>
        </div>

        <div class="dropdown-divider"></div>

        <a id="btn-signout" href="javascript:void(0);" class="dropdown-item notify-item">
          <i class="fe-log-out"></i>
          <span>Cerrar sesión</span>
        </a>

      </div>
    </li>

    <li class="dropdown notification-list">
      <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
        <i class="fe-box noti-icon"></i>
      </a>
    </li>

  </ul>

  <!-- LOGO -->
  <div class="logo-box">
    <a href="./home" class="logo logo-light text-center">
      <span class="logo-sm">
        <img src="./assets/images/icons/ticket.icon.svg"
          alt="PIM by Pacheco" height="22">
      </span>
      <span class="logo-lg">
        <img src="./assets/images/banners/ticket.banner.svg"
          alt="PIM by Pacheco" height="16">
      </span>
    </a>
    <a href="./home" class="logo logo-dark text-center">
      <span class="logo-sm">
        <img src="./assets/images/icons/ticket.icon.svg"
          alt="PIM by Pacheco" height="22">
      </span>
      <span class="logo-lg">
        <img src="./assets/images/banners/ticket.banner.svg"
          alt="PIM by Pacheco" height="16">
      </span>
    </a>
  </div>

  <ul class="list-unstyled topnav-menu topnav-menu-left mb-0">
    <li>
      <button class="button-menu-mobile disable-btn waves-effect">
        <i class="fe-menu"></i>
      </button>
    </li>

    <li>
      <h4 class="page-title-main">{{ $title ?? 'Inicio' }}</h4>
    </li>

  </ul>

  <div class="clearfix"></div>

</div>
