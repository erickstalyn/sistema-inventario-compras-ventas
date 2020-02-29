<!-- MENU LATERAL-->
<ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Aqui va el Logo con su nombre -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fab fa-accusoft"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Control de inventario</div>
    </a>

    <!-- Divisor -->
    <hr class="sidebar-divider my-0">

    <!-- Elemento de navegación: panel de control-->
    <li class="nav-item active">
    <a class="nav-link" href="#">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Panel de control</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Elemento de navegación - MENÚ INVENTARIO -->
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#inventario" aria-expanded="true" aria-controls="inventario">
        <i class="fas fa-store-alt"></i>
        <span>Inventario</span>
    </a>
    <div id="inventario" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item font-weight-bold" href="#" @click="menu=1">Materiales</a>
            <a class="collapse-item font-weight-bold" href="#" @click="menu=2">Super Productos</a>
            <a class="collapse-item font-weight-bold" href="#" @click="menu=3">Productos</a>
        </div>
    </div>
    </li>

    <!-- Elemento de navegación: MENÚ OPERACIONES -->
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#operaciones" aria-expanded="true" aria-controls="operaciones">
        <i class="fas fa-truck-moving"></i>
        <span>Operaciones</span>
    </a>
    <div id="operaciones" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        {{-- <a class="collapse-item font-weight-bold" href="#" @click="menu=3">Producciones</a> --}}
        <a class="collapse-item font-weight-bold" href="#" @click="menu=4">Abastecimiento</a>
        {{-- <h6 class="collapse-header ">Mis proveedores</h6>
        <a class="collapse-item font-weight-bold" href="#" @click="menu=4">Proveedores</a>
        </div> --}}
    </div>
    </li>

    <!-- Elemento de navegación: MENÚ CENTROS -->
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#centros" aria-expanded="true" aria-controls="centros">
        <i class="fas fa-warehouse"></i>
        <span>Centros</span>
    </a>
    <div id="centros" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item font-weight-bold" href="#" @click="menu=5">Mis Puestos</a>
            <a class="collapse-item font-weight-bold" href="#" @click="menu=6">Mis Almacenes</a>
        </div>
    </div>
    </li>

    <!-- Elemento de navegación: MENÚ PERSONAS -->
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#personas" aria-expanded="true" aria-controls="personas">
        <i class="fas fa-users"></i>
        <span>Personas</span>
    </a>
    <div id="personas" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item font-weight-bold" href="#" @click="menu=7">Clientes</a>
            <a class="collapse-item font-weight-bold" href="#" @click="menu=8">Proveedores</a>
        </div>
    </div>
    </li>
    <!-- Elemento de navegación: MENÚ ACCESO -->
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#acceso" aria-expanded="true" aria-controls="acceso">
        <i class="fas fa-users"></i>
        <span>Acceso</span>
    </a>
    <div id="acceso" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            {{-- <a class="collapse-item font-weight-bold" href="#" @click="menu=9">Trabajadores</a> --}}
            <a class="collapse-item font-weight-bold" href="#" @click="menu=10">Usuarios</a>
        </div>
    </div>
    </li>

    <!-- Elemento de navegación: MENÚ ESTADISTICAS -->
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" @click="menu=11">
        <i class="fas fa-chart-pie"></i>
        <span>Estadisticas</span>
    </a>
    </li>

    <!-- Divisor -->
    {{-- <hr class="sidebar-divider"> --}}

    <!-- Título -->
    {{-- <div class="sidebar-heading">
    Extras
    </div> --}}

    <!-- N  av Item - Pages Collapse Menu -->
    {{-- <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#redes" aria-expanded="true" aria-controls="redes">
        <i class="fas fa-thumbs-up"></i>
        <span>Nuestras Redes</span>
    </a>
    <div id="redes" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="#"><i class="far fa-file"></i>&nbsp;Mi pagina web oficial</a>
        <a class="collapse-item" href="#"><i class="fab fa-facebook"></i><span>&nbsp;Facebook Oficial</span></a>
        </div>
    </div>
    </li> --}}

    <!-- Divisor -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- BOTON QUE OCULTA EL MENÚ-->
    <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- FIN DEL MENÚ LATERAL -->