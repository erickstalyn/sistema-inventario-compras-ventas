<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="EasyCode">
  <meta name="idCentro" content="{{Auth::check() ? Auth::user()->centro_id : ''}}">
  <meta name="userId" content="{{Auth::check() ? Auth::user()->id : ''}}">
  <meta name="rol_id" content="{{Auth::check() ? Auth::user()->rol_id : ''}}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SILMAR - Control de inventarios</title>
  <link href="fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="stylesheet" href="css/plantilla.css">
</head>

<body id="page-top">

  <div id="app">
  {{-- <input type="hidden" value="{{ Auth::user()->centro_id }}" id="idCentro"> --}}
  
    <!-- Page Wrapper -->
    <div id="wrapper">

      <!-- BARRA LATERAL DE OPCIONES SEGUN EL TIPO SE USUARIO -->
      
      @if(Auth::check())
          @if(Auth::user()->getRol->id===1)
              @include('sidebars.sidebaradministrador')
          @elseif(Auth::user()->getRol->id===2)
              @include('sidebars.sidebarpuesto')
          @elseif(Auth::user()->getRol->id===3)
              @include('sidebars.sidebaralmacen')
          @else

          @endif
      @endif
      




      <!-- INICIO BARRA SUPERIOR -->
      <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
          <nav class="navbar navbar-expand navbar-light bg-white topbar mb-2 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
              <i class="fa fa-bars"></i>
            </button>

            <!-- INICIO DEL BUSCADOR-->
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
              <!-- Nav Item - Search Dropdown (Visible Only XS) -->
              <li class="nav-item dropdown no-arrow d-sm-none">
                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-search fa-fw"></i>
                </a>
                <!-- Dropdown - Messages -->
                {{-- <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                  <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                      <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar por..." aria-label="Search" aria-describedby="basic-addon2">
                      <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                          <i class="fas fa-search fa-sm"></i>
                        </button>
                      </div>
                    </div>
                  </form>
                </div> --}}
              </li>
              <!-- FIN DEL BUSCADOR-->
          
              <!-- INICIO DE ALERTAS -->
              @if(Auth::check())
                  @if(Auth::user()->getRol->id==1)
                    <li class="nav-item dropdown no-arrow mx-1">
                      <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{-- <i class="fas fa-bell fa-fw"></i> --}}
                        <i class="fas fa-cart-arrow-down"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter" v-text="notifications.length ? 'nuevo': ''"></span>
                      </a>
                      <!-- Dropdown - Alerts -->
                      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">
                          Ventas de hoy
                        </h6>
                        <noti_venta_puesto :notifications="notifications"></noti_venta_puesto>
                      </div>
                    </li>
                    <li class="nav-item dropdown no-arrow mx-1">
                      <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{-- <i class="fas fa-bell fa-fw"></i> --}}
                        <i class="fas fa-store-alt"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter" v-text="notifications.length ? 'nuevo': ''"></span>
                      </a>
                      <!-- Dropdown - Alerts -->
                      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">
                          Ventas de hoy
                        </h6>
                        <noti_venta_alm :notifications="notifications"></noti_venta_alm>
                      </div>
                    </li>
                  @endif
              @endif
              
              <!-- FIN DE ALERTAS -->

              <!-- INICIO DE LOS MENSAJES -->
              {{-- <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-envelope fa-fw"></i>
                  <!-- Counter - Messages -->
                  <span class="badge badge-danger badge-counter">7</span>
                </a>
                <!-- Dropdown - Messages -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                  <h6 class="dropdown-header">
                    Message Center
                  </h6>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                      <div class="status-indicator bg-success"></div>
                    </div>
                    <div class="font-weight-bold">
                      <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                      <div class="small text-gray-500">Emily Fowler · 58m</div>
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img class="rounded-circle" src="https://source.unsplash.com/AU4VPcFN4LE/60x60" alt="">
                      <div class="status-indicator"></div>
                    </div>
                    <div>
                      <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
                      <div class="small text-gray-500">Jae Chun · 1d</div>
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img class="rounded-circle" src="https://source.unsplash.com/CS2uCrpNzJY/60x60" alt="">
                      <div class="status-indicator bg-warning"></div>
                    </div>
                    <div>
                      <div class="text-truncate">Last month's report looks great, I am very happy with the progress so far, keep up the good work!</div>
                      <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="">
                      <div class="status-indicator bg-success"></div>
                    </div>
                    <div>
                      <div class="text-truncate">Am I a good boy? The reason I ask is because someone told me that people say this to all dogs, even if they aren't good...</div>
                      <div class="small text-gray-500">Chicken the Dog · 2w</div>
                    </div>
                  </a>
                  <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                </div>
              </li> --}}
              <!-- FIN DE LOS MENSAJES -->

              <div class="topbar-divider d-none d-sm-block"></div>

              <!-- INICIO DE LA INFORMACIÓN DEL USUARIO -->
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                  Hola, {{ Auth::user()->getNombres() }}
                </span>
                  <img class="img-profile rounded-circle" src="">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Cerrar Sesión
                  </a>
                </div>
              </li>
              <!-- FIN DE LA INFORMACIÓN DEL USUARIO -->
            </ul>
          </nav>

          <div class="container-fluid">
            <!-- CONTENIDO PRINCIPAL SEGUN EL TIPO DE USUARIO -->
              @yield('contenidoPrincipal')
              <!-- FIN DEL CONTENIDO PRINCIPAL -->


          </div>



        </div>
        <!-- End of Main Content -->
        <!-- Footer -->
        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright &copy; EasyCode 2019</span>
            </div>
          </div>
        </footer>
        <!-- End of Footer -->
      </div>
      <!-- FIN DE LA BARRA SUPERIOR -->

    </div>
    <!-- End of Page Wrapper -->
  </div>
  <!-- FIN DEL DIV con id = 'app' -->

  



  <!-- BOTON PARA EL SCROLL RAPIDO-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <!-- MODAL CUANDO PRESIONAS CERRAR SESIÓN-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Listo para irse?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión actual.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
        <a class="btn btn-primary" href="{{route('logout')}}">Cerrar sesión</a>
        </div>
      </div>
    </div>
  </div>
  <!-- FIN DEL MODAL-->




  <!--todos los scritps-->
  <!--<script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.min.js"></script>
  <script src="js/Chart.min.js"></script>-->

  
  
  
  
  <script src="js/app.js"></script>
  <script src="js/plantilla.js"></script>
  
</body>

</html>