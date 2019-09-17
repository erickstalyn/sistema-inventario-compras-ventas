<!-- MENU LATERAL-->
<ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Aqui va el Logo con su nombre -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
          <div class="sidebar-brand-icon rotate-n-15">
              <i class="fab fa-accusoft"></i>
          </div>
          <div class="sidebar-brand-text mx-3">Puesto A</div>
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
    
        <!-- Elemento de navegación - MENÚ VENTAS -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#ventas" aria-expanded="true" aria-controls="ventas">
              <i class="fas fa-cart-arrow-down"></i>
            <span >Ventas</span>
          </a>
          <div id="ventas" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item font-weight-bold" href="#">Al contado</a>
              <a class="collapse-item font-weight-bold" href="#">Al crédito</a>
              <a class="collapse-item font-weight-bold" href="#">Especiales</a>
            </div>
          </div>
        </li>
    
        <!-- Elemento de navegación: MENÚ INVENTARIO -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#inventario" aria-expanded="true" aria-controls="inventario">
              <i class="fas fa-store-alt"></i>
            <span>Inventario</span>
          </a>
          <div id="inventario" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item font-weight-bold" href="#">Categorías</a>
              <a class="collapse-item font-weight-bold" href="#">Productos</a>
              
            </div>
          </div>
        </li>
    
        <!-- Elemento de navegación: MENÚ REGISTROS -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#registros" aria-expanded="true" aria-controls="registros">
              <i class="far fa-clipboard"></i>
            <span>Registros</span>
          </a>
          <div id="registros" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Mis ventas</h6>
                <a class="collapse-item font-weight-bold" href="#">Ventas del día</a>
                <h6 class="collapse-header font-weight-bold">Mis abastos</h6>
                <a class="collapse-item font-weight-bold" href="#">Abastecimientos del día</a>
            </div>
          </div>
        </li>
    
        <!-- Divisor -->
        <hr class="sidebar-divider">
    
        <!-- Título -->
        <div class="sidebar-heading">
          Extras
        </div>
    
        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#redes" aria-expanded="true" aria-controls="redes">
              <i class="fas fa-thumbs-up"></i>
            <span>Nuestras Redes</span>
          </a>
          <div id="redes" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="#"><i class="far fa-file"></i>&nbsp;&nbsp;Pagina web oficial</a>
              <a class="collapse-item" href="#"><i class="fab fa-facebook"></i><span>&nbsp;Facebook Oficial</span></a>
            </div>
          </div>
        </li>
    
        <!-- Divisor -->
        <hr class="sidebar-divider d-none d-md-block">
    
        <!-- BOTON QUE OCULTA EL MENÚ-->
        <div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    
      </ul>
      <!-- FIN DEL MENÚ LATERAL -->