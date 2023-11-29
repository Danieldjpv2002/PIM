@php
  use App\ENV;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

  <!-- NO CACHE -->
  <meta http-equiv="Last-Modified" content="0" http-equiv="Cache-Control" content="no-cache, mustrevalidate"
    http-equiv="cache-control" content="max-age=0" http-equiv="cache-control" content="no-store"
    http-equiv="expires"content="-1" http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" http-equiv="pragma"
    content="no-cache">
  <!-- NO CACHE -->

  <meta charset="utf-8" />
  <title>Iniciar sesion | {{ ENV::APP_LONGNAME }}</title>
  <link rel="manifest" href="/manifest.webmanifest">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Proyecto de Innovacion y Mejora" name="description" />
  <meta content="Pacheco" name="author" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- App favicon -->
  <link rel="shortcut icon" href="/assets/images/icons/ticket.icon.svg">

  <!-- App css -->
  <link href="/lte/assets/css/config/default/bootstrap-dark.min.css" rel="stylesheet" type="text/css"
    id="bs-default-stylesheet" />
  <link href="/lte/assets/css/config/default/app-dark.min.css" rel="stylesheet" type="text/css"
    id="app-default-stylesheet" />

  <!-- icons -->
  <link href="/lte/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

</head>

<body class="loading authentication-bg-pattern">

  <div class="account-pages my-5">
    <div class="container">

      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-4">
          <div class="text-center">
            <a href="index.html">
              <img src="/assets/images/banners/ticket.banner.svg" alt="" height="22" class="mx-auto">
            </a>
            <p class="text-muted mt-2 mb-4">{{ ENV::APP_LONGNAME }}</p>

          </div>
          <div class="card">
            <div class="card-body p-4">

              <div class="text-center mb-4">
                <h4 class="text-uppercase mt-0">INICIAR SESION</h4>
              </div>

              <form autocomplete="off">
                <div class="mb-3">
                  <label for="txt-usuario" class="form-label">Nombre de usuario</label>
                  <input class="form-control" type="text" id="txt-usuario" required placeholder="Ingrese su usuario">
                </div>

                <div class="mb-3">
                  <label for="txt-clave" class="form-label">Contraseña</label>
                  <input class="form-control" type="password" required id="txt-clave"
                    placeholder="Ingrese su contraseña">
                </div>

                <div class="mb-3 d-grid text-center">
                  <button class="btn btn-primary" type="submit"> Iniciar sesion </button>
                </div>
              </form>

            </div>
          </div>


        </div>
      </div>
    </div>
  </div>

  <script src="/assets/extends/json.extend.js"></script>
  <script src="/assets/extends/fetch.extend.js"></script>
  <script src="/assets/extends/cookies.extend.js"></script>
  <script src="/assets/extends/notify.extend.js"></script>


  <script src="/lte/assets/js/vendor.min.js"></script>
  <script src="/lte/assets/js/app.min.js"></script>

  <script src="/assets/js/login/events.js?v={{ uniqid() }}"></script>
  <script src="/assets/js/login/handlers.js?v={{ uniqid() }}"></script>

</body>

</html>
