{{-- CSS --}}
<link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg" type="image/x-icon') }}">
<link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg" type="image/png') }}">
{{-- Tabel --}}
<link rel="stylesheet" href="{{ asset('template/assets/compiled/css/table-datatable.css') }}">
<link rel="stylesheet" href="{{ asset('template/assets/extensions/simple-datatables/style.css') }}">
{{-- CSS --}}
<link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
<link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app-dark.css') }}">
<link rel="stylesheet" href="{{ asset('template/assets/compiled/css/iconly.css') }}">

{{-- Panggil pustaka Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
<!-- Include Leaflet Control Layers CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-layers/dist/leaflet.control-layers.min.css"/>
<style>
    #mapid {
        height: 485px;
        border-radius: 5px;
    }
    #menu-container {
      position: absolute;
      top: 90px;
      left: 80px;
      background-color: white;
      padding: 10px;
      border-radius: 5px;
      z-index: 1000;
    }

    #menu-toggle {
      cursor: pointer;
      vertical-align: middle;
      margin-left: 5px;
    }

    #menu h6 {
      display: inline-block;
      margin: 0;
    }

    .hidden {
      display: none;
    }
  </style>
{{-- Panggil pustaka Leaflet Routing Machine --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css"/>

{{-- Tabel DataTables--}}
<link rel="stylesheet" href="{{ asset('template/assets/compiled/css/datatables.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/datatables.min.css') }}"> --}}

{{-- Sub Menu --}}
<style>
.dropdown-menu li {
  position: relative;
  }
  .dropdown-menu .dropdown-submenu {
  display: none;
  position: absolute;
  left: 100%;
  top: -7px;
  }
  .dropdown-menu .dropdown-submenu-left {
  right: 100%;
  left: auto;
  }
  .dropdown-menu > li:hover > .dropdown-submenu {
  display: block;
  }
</style>