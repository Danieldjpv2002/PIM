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
  <title>{{ $title ?? 'Inicio' }} | {{ ENV::APP_LONGNAME }}</title>
  <link rel="manifest" href="/manifest.webmanifest">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Proyecto de Innovacion y Mejora" name="description" />
  <meta content="Pacheco" name="author" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="shortcut icon" href="./assets/images/icons/ticket.icon.svg">

  <link href="./lte/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
  <link href="./lte/assets/libs/quill/quill.bubble.css" rel="stylesheet" type="text/css" />
  <link href="./lte/assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />

  <link href="./lte/assets/css/config/default/bootstrap.min.css" rel="stylesheet" type="text/css"
    id="bs-default-stylesheet" />
  <link href="./lte/assets/css/config/default/app.min.css" rel="stylesheet" type="text/css"
    id="app-default-stylesheet" />
  <link href="./lte/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
  <link href="./lte/assets/libs/dxdatagrid/css/dx.light.css" rel="stylesheet" type="text/css">

  <style>
    * {
      scrollbar-width: thin;
      scrollbar-color: rgba(0, 0, 0, 0.25) transparent;
    }

    *::-webkit-scrollbar {
      width: 5px;
      height: 5px;
    }

    *::-webkit-scrollbar-thumb {
      border-radius: 5px;
      background-color: rgba(0, 0, 0, 0.25);
    }

    hr {
      width: 75% !important;
      display: block;
      margin: 20px auto 15px !important;
    }

    .message-pasting {
      background-color: rgba(255, 255, 255, .5);
      opacity: 0;
      visibility: hidden;
      transition: .125s;
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      backdrop-filter: blur(40px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999999;
    }

    .message-pasting span {
      color: #000;
      font-weight: bold;
    }

    [pasting="true"] .message-pasting {
      opacity: 1;
      visibility: visible;
    }
  </style>
</head>

<body class="loading"
  data-layout='{"mode": "light","width": "fluid","menuPosition": "fixed","sidebar": {"color": "dark","size": "default","showuser": true},"topbar": {"color": "dark"},"showRightSidebarOnPageLoad": true}'>
  <div id="wrapper">
    @include('components.navbar')
    @include('components.menu')
    <div class="content-page">
      <div class="content">

        <div class="container-fluid">

          {{ $slot }}



          @include('components.footer')

        </div>



      </div>

      @include('components.rightbar')

      <div class="rightbar-overlay" style="background-color: rgba(0, 0, 0, 0.5)"></div>
    </div>
  </div>

  @include('components.modals')

  <script src="./lte/assets/js/vendor.min.js"></script>
  <script src="./lte/assets/libs/quill/quill.min.js"></script>
  <script src="./lte/assets/libs/dropzone/min/dropzone.min.js"></script>
  <script src="./lte/assets/libs/select2/js/select2.min.js"></script>
  <script src="./lte/assets/libs/tippy.js/tippy.all.min.js"></script>
  <script src="./lte/assets/js/app.min.js"></script>

  {{-- dxDataGrid files --}}
  <script src="./lte/assets/libs/dxdatagrid/js/exceljs.min.js"></script>
  <script src="./lte/assets/libs/dxdatagrid/js/FileSaver.min.js"></script>
  <script src="./lte/assets/libs/dxdatagrid/js/jszip.min.js"></script>
  <script src="./lte/assets/libs/dxdatagrid/js/dx.all.js"></script>
  <script src="./lte/assets/libs/dxdatagrid/js/localization/dx.messages.es.js"></script>

  {{-- JavaScript Extend Files --}}
  <script src="./assets/extends/file.extend.js"></script>
  <script src="./assets/extends/string.extend.js"></script>
  <script src="./assets/extends/json.extend.js"></script>
  <script src="./assets/extends/fetch.extend.js"></script>
  <script src="./assets/extends/cookies.extend.js"></script>
  <script src="./assets/extends/notify.extend.js"></script>
  <script src="./assets/extends/clipboard.extend.js"></script>

  {{-- Main Script for Setup the Service --}}
  <script src="assets/settings" type="text/javascript"></script>
  <script src="assets/js/settings.js?v={{ uniqid() }}"></script>
  <script src="./assets/js/session.js?v={{ uniqid() }}"></script>
  <script src="assets/js/modals.js?v={{ uniqid() }}"></script>
  <script src="assets/js/events.js?v={{ uniqid() }}"></script>
  <script src="assets/js/handlers.js?v={{ uniqid() }}"></script>

</body>

</html>
