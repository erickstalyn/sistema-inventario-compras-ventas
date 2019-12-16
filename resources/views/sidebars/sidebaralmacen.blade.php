<!-- MENU LATERAL-->
<ul class="navbar-nav bg-gradient-warning sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Aqui va el Logo con su nombre -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
          <div class="sidebar-brand-icon rotate-n-15">
              <i class="fab fa-accusoft"></i>
          </div>
        <div class="sidebar-brand-text mx-3">
            {{ Auth::user()->getNombres() }}
        </div>
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

        <!-- Elemento de navegación: MENÚ INVENTARIO -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#inventario" aria-expanded="true" aria-controls="inventario">
              <i class="fas fa-store-alt"></i>
            <span>Inventario</span>
          </a>
          <div id="inventario" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item font-weight-bold" href="#" @click="menu=0">Productos</a>
            </div>
          </div>
        </li>

        <!-- Elemento de navegación - MENÚ PRODUCCIONES -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" @click="menu=1">
                <i class="fas fa-plane"></i>
                <span >Producciones</span>
            </a>
        </li>
        <!-- Elemento de navegación - MENÚ ENVIOS -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#envios" aria-expanded="true" aria-controls="envios">
              <i class="fas fa-cart-arrow-down"></i>
            <span >Envíos</span>
          </a>
          <div id="envios" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item font-weight-bold" href="#" @click="menu=2">Envíos Realizados</a>
              <a class="collapse-item font-weight-bold" href="#" @click="menu=3">Envíos Recibidos</a>
            </div>
          </div>
        </li>
    
        <!-- Elemento de navegación - MENÚ VENTAS -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#ventas" aria-expanded="true" aria-controls="ventas" @click="menu=4">
              <i class="fas fa-cart-arrow-down"></i><span >Ventas</span>
          </a>
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