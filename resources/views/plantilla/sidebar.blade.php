<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <!-- <li @click="menu=0" class="nav-item">
                <a class="nav-link active" href="main.html"><i class="icon-speedometer"></i> Escritorio</a>
            </li> -->
            <!-- <li class="nav-title">
                Sistema de Nóminas
            </li> -->
            @if (Auth::user()->rol->nombre == 'Administrador')
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-user"></i> Personas</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=1" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-people-carry"></i> Empleados</a>
                    </li>
                    <!-- <li @click="menu='personadependiente2'" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-people-carry"></i> P. Dependientes</a>
                    </li> -->
                    <li @click="menu=2" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-file-signature"></i> Contratos</a>
                    </li>
                    <!-- <li @click="menu=3" class="nav-item">
                        <a class="nav-link" href="#"><i></i> Incidencias</a>
                    </li> -->
                    <li @click="menu='inasistencia'" class="nav-item">
                        <a class="nav-link" href="#"><i ></i> Solicitudes</a>
                    </li>
                    <li @click="menu='competencia'" class="nav-item">
                        <a class="nav-link" href="#"><i ></i> Competencias</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-notebook"></i> Entidades</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu='empresa'" class="nav-item">
                        <a class="nav-link" href="#"><i ></i> Empresas</a>
                    </li>
                    <li @click="menu='area'" class="nav-item">
                        <a class="nav-link" href="#"><i ></i> Areas</a>
                    </li>
                    <li @click="menu='departamento'" class="nav-item">
                        <a class="nav-link" href="#"><i ></i> Departamentos</a>
                    </li>
                    <li @click="menu='puesto'" class="nav-item">
                        <a class="nav-link" href="#"><i ></i> Puestos</a>
                    </li>
                    <li @click="menu='tipoContrato'" class="nav-item">
                        <a class="nav-link" href="#"><i ></i> Tipo de Contratos</a>
                    </li>
                    <li @click="menu=3" class="nav-item">
                        <a class="nav-link" href="#"><i></i> Incidencias</a>
                    </li>
                    <!--                    
                    <li @click="menu='evento'" class="nav-item">
                        <a class="nav-link" href="#"><i ></i> Evento</a>
                    </li>
                    -->
                </ul>
            </li>

            <!-- <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-info"></i> Parametros</a>
                <ul class="nav-dropdown-items">
                   {{--  <li @click="menu='dianolaboral'" class="nav-item">
                        <a class="nav-link" href="i#"><i ></i> Dias No Laborables</a>
                    </li> --}}
                    <li @click="menu='calendario'" class="nav-item">
                        <a class="nav-link" href="main.html"><i ></i> Días no laborales </a>
                    </li>
                    <li @click="menu='tipoContrato'" class="nav-item">
                        <a class="nav-link" href="i#"><i ></i> Tipo de Contratos</a>
                    </li>

                </ul>
            </li> -->
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-people"></i> Sistema</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu='usuarios'" class="nav-item">
                        <a class="nav-link" href="i#"><i ></i> Usuarios</a>
                    </li>
                    
                </ul>
            </li>
            <!-- <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Graficos</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu='reporteEmpleado'" class="nav-item">
                        <a class="nav-link" href="i#"><i class="icon-chart"></i> Empleados</a>
                    </li>
                    
                </ul>
            </li>
            <li @click="menu='alarma2'" class="nav-item">
                <a class="nav-link" href="main.html"><i class="icon-book-open"></i> Alarma <span
                        class="badge badge-danger">!</span></a>
            </li> -->
            @endif
            @if (Auth::user()->rol->nombre == 'Empleado')
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i> Empleados</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=1" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-people-carry"></i> Empleados</a>
                    </li>
                    <!-- <li @click="menu='personadependiente2'" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-people-carry"></i> P. Dependientes</a>
                    </li>
                  
                    <li @click="menu='inasistencia'" class="nav-item">
                        <a class="nav-link" href="i#"><i ></i> Solicitudes</a>
                    </li> -->
                    
                </ul>
            </li>

            <!-- <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-notebook"></i> Estructura</a>
                <ul class="nav-dropdown-items">
                 
                    <li @click="menu='evento'" class="nav-item">
                        <a class="nav-link" href="#"><i ></i> Evento</a>
                    </li>
                   
                </ul>
            </li> -->

         
           
          
            @endif
           
            
            
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>