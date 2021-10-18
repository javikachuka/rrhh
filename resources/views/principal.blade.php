<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Laravel Vue Js">
    <meta name="author" content="Incanatoit.com">
    <meta name="keyword" content="Sistema Laravel Vue Js">
    <!-- <link rel="shortcut icon" href="img/Unam-Logo.jpg"> -->
    <title>Sistema de nóminas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="{{ Auth::user()->load('rol') }}">
    <script src="https://unpkg.com/vue-select@latest"></script>
    {{-- <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('import/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('import/vue-select.css') }}">
    <link rel="stylesheet" href="{{ asset('import/vue-multiselect.min.css') }}">
    
    {{-- <script src="{{ asset('import/vue-select@latest') }}"></script> --}}
    <script src="{{ asset('import/toastr.min.js') }}"></script>

    <!-- Icons -->
    <link href="css/plantilla.css" rel="stylesheet">

</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
        <header class="app-header navbar">
            <!-- <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
                <span class="navbar-toggler-icon"></span>
            </button> -->
            <!-- <a class="navbar-brand" href="#"></a> -->
            <!-- <h2 class="navbar-brand" href="#">RRHH</h2> -->
            <!-- <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
                <span class="navbar-toggler-icon"></span>
            </button> -->
            <ul class="nav navbar-nav d-md-down-none">
                <li  @click="menu=0" class="nav-item px-3">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li  @click="menu=1" class="nav-item px-3">
                    <a class="nav-link" href="#">Empleados</a>
                </li>
                <li   @click="menu='empresa'" class="nav-item px-3">
                    <a class="nav-link" href="#">Empresas</a>
                </li>

                
            </ul>
            <ul class="nav navbar-nav ml-auto">
                
                <li class="nav-item dropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                    
                </li>
            </ul>
        </header>

        <div class="app-body">
            <!-- @include('plantilla.sidebar') -->

            <!-- Contenido Principal -->
            @yield('contenido')
            <!-- /Fin del contenido principal -->
        </div>
    </div>

<!-- 
    <footer class="app-footer">
        <span>...</span>
        <span class="ml-auto">...</a></span>
    </footer> -->

    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
</body>

</html>