<template>
    <main>
        <!-- Interfaz Principal -->
        <div>
            <!-- Encabezado principal -->
            <div class="row form-group">
                <div class="col-md-1">
                    <button class="btn btn-outline-info" @click="abrirModalSettings()">
                        <i class="fas fa-cogs"></i>
                    </button>
                </div>
                <div class="col-md-6 text-center">
                    <!-- <i class="fas fa-hammer"></i>&nbsp; -->
                    <span class="h3 mb-0 text-gray-900 font-italic" v-text="Almacen.nombre"></span>&nbsp;&nbsp;
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <select class="custom-select text-gray-900" v-model="Almacen.id" @change="listar()">
                            <option v-for="almacen in SelectAlmacen" :key="almacen.id" :value="almacen.id" v-text="almacen.nombre"></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <select class="custom-select text-gray-900" v-model="Almacen.quieroVer" @change="listar()">
                            <option value="1">Inventario</option>
                            <option value="2">Envios Realizados</option>
                            <option value="3">Envios Recibidos</option>
                            <option value="4">Producciones</option>
                            <option value="7">Cajas</option>
                            <option value="5">Ventas</option>
                            <option value="6">Vales</option>
                        </select>&nbsp;
                        <button type="button" class="btn btn-success" @click="listar()">
                            <i class="fas fa-sync-alt"></i>&nbsp; Actualizar
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <i class="fas fa-warehouse"></i>&nbsp;&nbsp;
                <span class="h5 mb-0 text-gray-900" v-text="Almacen.titulo"></span>&nbsp;&nbsp;
            </div>
            <div v-if="Almacen.mostrar == 1"> <!--Listar inventario -->
                <div class="row form-group">
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="search" class="form-control" v-model="Busqueda.texto" @keyup="Busqueda.texto.length >=10 || Busqueda.texto.length == 0 ? listar() : ''" placeholder="Buscar por NOMBRE o CÓDIGO">
                            <button type="button" class="btn btn-primary" @click="listar()">
                                <i class="fa fa-search"></i>&nbsp; Buscar
                            </button>
                        </div>
                    </div>
                    <div class="col-md-5"></div>
                    <div class="col-md-1 text-right">
                        <label>N° filas:</label>
                    </div>
                    <select class="col-md-1 form-control text-gray-900" v-model="Busqueda.filas" @change="listar()">
                        <option v-for="fila in Filas" :key="fila" :value="fila" v-text="fila"></option>
                    </select>
                </div>
                <h5 v-if="Carga.mostrar">
                    <span role="status" :class="Carga.clase"></span>&nbsp;
                    <span v-text="Carga.mensaje" :class="Carga.alert"></span>
                </h5>
                <!-- Listado -->
                <div v-if="ListaProducto.length" class="table-responsive">
                    <!-- Tabla -->
                    <div class="table-scroll-20 overflow-auto">
                        <table class="table table-bordered table-striped table-sm text-gray-900">
                            <thead>
                                <tr class="bg-success">
                                    <th class="text-center">Nombre</th>
                                    <!-- <th class="text-center">Codigo</th> -->
                                    <th class="text-center">Precio al por menor</th>
                                    <th class="text-center">Precio al por mayor</th>
                                    <th class="text-center">Disponible</th>
                                    <th class="text-center">Reservados</th>
                                    <th class="text-center">Fallidos</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Opcion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="producto in ListaProducto" :key="producto.id" :class="{'table-danger': producto.detalle.substock === 1, 'table-warning': producto.detalle.substock===2}">
                                    <td v-text="producto.nombre"></td>
                                    <!-- <td v-text="producto.codigo" class="text-center"></td> -->
                                    <td v-text="producto.detalle.precio_menor" class="text-right"></td>
                                    <td v-text="producto.detalle.precio_mayor" class="text-right"></td>
                                    <td v-text="producto.detalle.substock" class="text-right"></td>
                                    <td v-text="producto.detalle.reservados==0?'---':producto.detalle.reservados" class="text-right"></td>
                                    <td v-text="producto.detalle.fallidos==0?'---':producto.detalle.fallidos" class="text-right"></td>
                                    <td v-text="producto.detalle.substock + producto.detalle.reservados + producto.detalle.fallidos" class="text-right"></td>
                                    <td class="text-center">
                                        <button type="button"  title="Editar Precios" class="btn btn-warning btn-sm" @click="abrirModaEditar(producto)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Barra de navegacion -->
                    <nav class="d-flex justify-content-center">
                        <ul class="pagination">
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage-1)" class="page-link">Anterior</a>
                            </li>
                            <li class="page-item" v-for="page in Paginas" :key="page" :class="[page==Paginacion.currentPage?'active':'']">
                                <a href="#" @click="cambiarPagina(page)" v-text="page" class="page-link"></a>
                            </li>
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage+1)" class="page-link">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div v-else-if="!Carga.mostrar">
                    <h5>No se han encontrado resultados</h5>
                </div>
            </div>
            <div v-else-if="Almacen.mostrar == 2"> <!--Listar Envio Realizados -->
                <div class="row form-group">
                    <div style="width: 8rem;" class="mr-1">
                        <div class="input-group"> 
                            <select class="custom-select text-gray-900" v-model="Busqueda.estadoEnviado" @change="listar()">
                                <option value="3">Todos</option>
                                <option value="0">En espera</option>
                                <option value="1">Recibidos</option>
                            </select>
                        </div>
                    </div>
                    <div style="width: 24rem;">
                        <input type="search" class="form-control" v-model="Busqueda.texto" @keyup="Busqueda.texto.length >=5 || Busqueda.texto.length == 0 ? listar() : ''" placeholder="Buscar por CENTRO DE DESTINO">
                    </div>
                    <div class="col-md-1">
                        <label for="">Fecha de envío</label>
                    </div>
                    <div class="col-md-1">
                        Dia
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.dia" @change="listar()">
                            <option value="">Todos</option>
                            <option v-for="item in getDia()" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div style="width: 8rem;">
                        Mes
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.mes" @change="listar()">
                            <option value="">Todos</option>
                            <option v-for="item in getMes()" :key="item.valor" :value="item.valor" v-text="item.nombre"></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        Año
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.year" @change="listar()">
                            <option value="">Todos</option>
                            <option v-for="item in getYear(2019)" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        N° filas:
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas" @change="listar()">
                            <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary" @click="listar()">
                            <i class="fa fa-search"></i>&nbsp; Buscar
                        </button>
                    </div>
                </div>
                <h5 v-if="Carga.mostrar && Carga.numero ==1">
                    <span role="status" :class="Carga.clase"></span>&nbsp;
                    <span v-text="Carga.mensaje" :class="Carga.alert"></span>
                </h5>
                <div v-if="ListaEnvio.length">
                    <!-- Tabla -->
                    <div class="ec-table overflow-auto">
                        <table class="table table-borderless table-sm text-gray-900">
                            <thead>
                                <tr class="table-info">
                                    <th>Centro de destino</th>
                                    <th class="text-center">Fecha de envio</th>
                                    <th class="text-center">Fecha Aceptado/Rechazado</th>
                                    <th>Estado</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="envio in ListaEnvio" :key="envio.id" >
                                    <td v-text="envio.centro_destino"></td>
                                    <td v-text="fix('date', envio.fecha_envio)" class="text-center"></td>
                                    <td v-text="envio.fecha_cambio ? fix('date', envio.fecha_cambio) : '-------------'" class="text-center"></td>
                                    <td>
                                        <div v-if="envio.estado == 0">
                                            <span class="badge badge-primary">En espera</span>
                                        </div>
                                        <div v-else-if="envio.estado == 1">
                                            <span class="badge badge-success">Recibido</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" title="VER" class="btn btn-sm btn-primary" @click="abrirModalVerEnvioRealizado(envio)">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Barra de navegacion -->
                    <nav class="d-flex justify-content-center">
                        <ul class="pagination">
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage-1)" class="page-link">Anterior</a>
                            </li>
                            <li class="page-item" v-for="page in Paginas" :key="page" :class="[page==Paginacion.currentPage?'active':'']">
                                <a href="#" @click="cambiarPagina(page)" v-text="page" class="page-link"></a>
                            </li>
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage+1)" class="page-link">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div v-else-if="!Carga.mostrar">
                    <h5>No se han encontrado resultados</h5>
                </div>
            </div>
            <div v-else-if="Almacen.mostrar == 3"> <!--Listar Envios Recibidos -->
                <div class="row form-group">
                    <div style="width: 8rem;" class="mr-1">
                        <div class="input-group"> 
                            <select class="custom-select text-gray-900" v-model="Busqueda.estadoRecibido" @change="listar()">
                                <option value="3">Todos</option>
                                <option value="0">En espera</option>
                                <option value="1">Recibidos</option>
                            </select>
                        </div>
                    </div>
                    <div style="width: 24rem;">
                        <input type="search" class="form-control" v-model="Busqueda.texto" @keyup="Busqueda.texto.length >=5 || Busqueda.texto.length == 0 ? listar() : ''" placeholder="Buscar por CENTRO DE ORIGEN">
                    </div>
                    <div class="col-md-1">
                        <label for="">Fecha de envío</label>
                    </div>
                    <div class="col-md-1">
                        Dia
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.dia" @change="listar()">
                            <option value="">Todos</option>
                            <option v-for="item in getDia()" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div style="width: 8rem;">
                        Mes
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.mes" @change="listar()">
                            <option value="">Todos</option>
                            <option v-for="item in getMes()" :key="item.valor" :value="item.valor" v-text="item.nombre"></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        Año
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.year" @change="listar()">
                            <option value="">Todos</option>
                            <option v-for="item in getYear(2016)" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        N° filas:
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas" @change="listar()">
                            <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary" @click="listar()">
                            <i class="fa fa-search"></i>&nbsp; Buscar
                        </button>
                    </div>
                </div>
                <h5 v-if="Carga.mostrar && Carga.numero ==1">
                    <span role="status" :class="Carga.clase"></span>&nbsp;
                    <span v-text="Carga.mensaje" :class="Carga.alert"></span>
                </h5>
                <div v-if="ListaEnvio.length" >
                    <!-- Tabla -->
                    <div class="ec-table overflow-auto">
                        <table class="table table-borderless table-sm text-gray-900">
                            <thead>
                                <tr class="table-info">
                                    <th>Origen</th>
                                    <th class="text-center">Fecha en que se envió</th>
                                    <th class="text-center">Fecha Aceptado/Rechazado</th>
                                    <th>Estado</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="envio in ListaEnvio" :key="envio.id" >
                                    <td v-text="!envio.abasto_id? envio.centro_origen : 'Administración'"></td>
                                    <td v-text="fix('date', envio.fecha_envio)" class="text-center"></td>
                                    <td v-text="envio.fecha_cambio ? fix('date', envio.fecha_cambio) : '-------------'" class="text-center"></td>
                                    <td>
                                        <div v-if="envio.estado == 0">
                                            <span class="badge badge-primary">En espera</span>
                                        </div>
                                        <div v-else-if="envio.estado == 1">
                                            <span class="badge badge-success">Recibido</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" title="Ver" class="btn btn-sm btn-primary" @click="abrirModalVerEnvioRecibido(envio)">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Barra de navegacion -->
                    <nav class="d-flex justify-content-center">
                        <ul class="pagination">
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage-1)" class="page-link">Anterior</a>
                            </li>
                            <li class="page-item" v-for="page in Paginas" :key="page" :class="[page==Paginacion.currentPage?'active':'']">
                                <a href="#" @click="cambiarPagina(page)" v-text="page" class="page-link"></a>
                            </li>
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage+1)" class="page-link">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div v-else-if="!Carga.mostrar">
                    <h5>No se han encontrado resultados</h5>
                </div>
            </div>
            <div v-else-if="Almacen.mostrar == 4"> <!--Listar Producciones -->
                <div class="row form-group">
                    <div class="col-md-2">
                        <div class="input-group"> 
                            <select class="custom-select text-gray-900" v-model="Busqueda.estadoProduccion" @change="listar()">
                                <option value="3">Todos</option>
                                <option value="2">Sin iniciar</option>
                                <option value="1">En Proceso</option>
                                <option value="0">Finalizado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label for="">Fecha de inicio</label>
                    </div>
                    <div class="col-md-1">
                        Dia
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.dia" @change="listar()">
                            <option value="">Todos</option>
                            <option v-for="item in getDia()" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        Mes
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.mes" @change="listar()">
                            <option value="">Todos</option>
                            <option v-for="item in getMes()" :key="item.valor" :value="item.valor" v-text="item.nombre"></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        Año
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.year" @change="listar()">
                            <option value="">Todos</option>
                            <option v-for="item in getYear(2019)" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-1">
                        N° filas:
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas" @change="listar()">
                            <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary" @click="listar()">
                            <i class="fa fa-search"></i>&nbsp; Buscar
                        </button>
                    </div>
                </div>
                <h5 v-if="Carga.mostrar && Carga.numero ==1">
                    <span role="status" :class="Carga.clase"></span>&nbsp;
                    <span v-text="Carga.mensaje" :class="Carga.alert"></span>
                </h5>
                <div v-if="ListaProduccion.length">
                    <!-- Tabla -->
                    <div class="ec-table overflow-auto">
                        <table class="table table-borderless table-sm text-gray-900">
                            <thead>
                                <tr class="table-info">
                                    <th>Fecha de inicio</th>
                                    <th>Fecha programada</th>
                                    <th>Fecha finalizada</th>
                                    <th>Inversión en materiales</th>
                                    <th>Estado</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="produccion in ListaProduccion" :key="produccion.id" >
                                    <td v-text="fix('date', produccion.fecha_inicio)"></td>
                                    <td v-text="fix('date', produccion.fecha_programada)"></td>
                                    <td v-text="produccion.fecha_fin ? fix('date', produccion.fecha_fin) : '----------------'"></td>
                                    <td v-text="produccion.total"></td>
                                    <td>
                                        <div v-if="produccion.fecha_inicio > getFechaHoy()">
                                            <span class="badge badge-primary">Sin iniciar</span>
                                        </div>
                                        <div v-else-if="produccion.fecha_fin">
                                            <span class="badge badge-danger">Finalizado</span>
                                        </div>
                                        <div v-else="">
                                            <span class="badge badge-success">En proceso</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"  title="Ver" class="btn btn-primary btn-sm" @click="abrirModalVerProduccion(produccion)">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Barra de navegacion -->
                    <nav class="d-flex justify-content-center">
                        <ul class="pagination">
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage-1)" class="page-link">Anterior</a>
                            </li>
                            <li class="page-item" v-for="page in Paginas" :key="page" :class="[page==Paginacion.currentPage?'active':'']">
                                <a href="#" @click="cambiarPagina(page)" v-text="page" class="page-link"></a>
                            </li>
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage+1)" class="page-link">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div v-else-if="!Carga.mostrar">
                    <h5>No se han encontrado resultados</h5>
                </div>
            </div>
            <div v-else-if="Almacen.mostrar == 5"> <!--Listar ventas -->
                <div class="row form-group">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-4">
                                <select class="custom-select text-gray-900" v-model="Busqueda.type" @change="listar()">
                                    <option value="0">Todos</option>
                                    <option value="1">Contado</option>
                                    <option value="2">Credito</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <input type="search" class="form-control" v-model="Busqueda.texto" placeholder="Buscar por CLIENTE o CODIGO de venta" @keyup="Busqueda.texto.length >=5 || Busqueda.texto.length == 0 ? listar() : ''">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="">Fecha </label>
                            </div>
                            <div class="col-md-3">
                                Dia
                                <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.dia" @change="listar()">
                                    <option value="">Todos</option>
                                    <option v-for="item in getDia()" :key="item" :value="item" v-text="item"></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                Mes
                                <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.mes" @change="listar()">
                                    <option value="">Todos</option>
                                    <option v-for="item in getMes()" :key="item.valor" :value="item.valor" v-text="item.nombre"></option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                Año
                                <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.year" @change="listar()">
                                    <option value="">Todos</option>
                                    <option v-for="item in getYear(2019)" :key="item" :value="item" v-text="item"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <!-- <div class="col-md-5">
                                <button type="button" class="btn btn-primary" @click="listar()">
                                    <i class="fa fa-search"></i>&nbsp;Buscar
                                </button>
                            </div> -->
                            <div class="col-md-5 input-group">
                                <label class="">N° filas:</label>
                                <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas" @change="listar()">
                                    <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 v-if="Carga.mostrar && Carga.numero ==1">
                    <span role="status" :class="Carga.clase"></span>&nbsp;
                    <span v-text="Carga.mensaje" :class="Carga.alert"></span>
                </h5>
                <div v-if="ListaVenta.length">
                    <!-- Tabla -->
                    <div class="ec-table overflow-auto">
                        <table class="table table-striped table-condensed table-bordered table-sm text-gray-900">
                            <thead>
                                <tr class="table-danger">
                                    <th class="text-center">Codigo</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Realizado</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="venta in ListaVenta" :key="venta.id" >
                                    <td class="text-center" v-text="venta.codigo"></td>
                                    <td>
                                        <label v-if="venta.tipo.substring(0, 1)==1">Contado</label>
                                        <label v-if="venta.tipo.substring(0, 1)==2 || venta.tipo.substring(0, 1)==3">Credito</label>
                                    </td>
                                    <td v-text="venta.razon_social!=null?venta.razon_social:(venta.nombres!=null?(venta.nombres+' '+venta.apellidos):'---')"></td>
                                    <td class="text-right" v-text="venta.total"></td>
                                    <td class="text-center" v-text="fix('fecha_hora', venta.created_at)"></td>
                                    <td class="text-center">
                                        <template>
                                            <button type="button" title="VER" class="btn btn-primary btn-sm" @click="abrirModalVerVenta(1, venta)">
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Barra de navegacion -->
                    <nav class="d-flex justify-content-center">
                        <ul class="pagination">
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage-1)" class="page-link">Anterior</a>
                            </li>
                            <li class="page-item" v-for="page in Paginas" :key="page" :class="[page==Paginacion.currentPage?'active':'']">
                                <a href="#" @click="cambiarPagina(page)" v-text="page" class="page-link"></a>
                            </li>
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage+1)" class="page-link">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div v-else-if="!Carga.mostrar">
                    <h5>No se han encontrado resultados</h5>
                </div>
            </div>
            <div v-else-if="Almacen.mostrar == 6"> <!--Listar vales -->
                <div class="row form-group">
                    <div class="col-md-8">
                        <div class="input-group"> 
                            <select class="col-md-3 custom-select text-gray-900" v-model="Busqueda.estadoVale" @change="listar()">
                                <option value="3">Todos</option>
                                <option value="1">Usado</option>
                                <option value="2">Sin usar</option>
                            </select>
                            <input type="search" class="form-control" v-model="Busqueda.texto" @keyup="Busqueda.texto.length >=5 || Busqueda.texto.length == 0 ? listar() : ''" placeholder="Buscar por DNI, RUC, NOMBRES , RAZÓN SOCIAL">
                            <button type="button" class="btn btn-primary" @click="listar()">
                                <i class="fa fa-search"></i>&nbsp; Buscar
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-1" align="right">
                        <label>N° filas:</label>
                    </div>
                    <div class="col-md-1">
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas" @change="listar()">
                            <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                </div>
                <h5 v-if="Carga.mostrar">
                    <span role="status" :class="Carga.clase"></span>&nbsp;
                    <span v-text="Carga.mensaje" :class="Carga.alert"></span>
                </h5>
                <div v-if="ListaVale.length">
                    <div class="ec-table overflow-auto">
                        <table class="table table-borderless table-sm text-gray-900">
                            <thead>
                                <tr class="table-info">
                                    <th>Cliente</th>
                                    <th>Monto</th>
                                    <th class="text-center">Generado</th>
                                    <th class="text-center">Usado</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="vale in ListaVale" :key="vale.id" >
                                    <td v-text="vale.razon_social ? vale.razon_social: (vale.nombres ? vale.nombres + ' ' + vale.apellidos : '---')"></td>
                                    <td v-text="vale.monto"></td>
                                    <td class="text-center" v-text="fix('fecha_hora', vale.created_at)"></td>
                                    <td class="text-center" v-text="vale.updated_at ? fix('fecha_hora', vale.updated_at) : '---'"></td>
                                    <td class="text-center">
                                        <div v-if="vale.updated_at">
                                            <span class="badge badge-primary">Usado</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-success">Sin usar</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <template>
                                            <button type="button" @click="abrirModalVerVenta(2, vale.venta_generada_id)" title="VER VENTA ORIGEN" class="btn btn-outline-primary btn-sm">
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Barra de navegacion -->
                    <nav class="d-flex justify-content-center">
                        <ul class="pagination">
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage-1)" class="page-link">Anterior</a>
                            </li>
                            <li class="page-item" v-for="page in Paginas" :key="page" :class="[page==Paginacion.currentPage?'active':'']">
                                <a href="#" @click="cambiarPagina(page)" v-text="page" class="page-link"></a>
                            </li>
                            <li class="page-item">
                                <a href="#" @click="cambiarPagina(Paginacion.currentPage+1)" class="page-link">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div v-else-if="!Carga.mostrar">
                    <h5>No se han encontrado resultados</h5>
                </div>
            </div>
            <div v-else-if="Almacen.mostrar == 7"> <!-- Busqueda de CAJAS -->
                <div class="col-md-12 container-small"> <!-- Barra de busqueda -->
                    <div class="input-group pt-3">
                        <div class="col-md-2 input-group form-group">
                            <label class="font-weight-bold text-gray-900 mt-2 mb-2">BUSCAR</label>
                        </div>
                        <div class="col-md-8 input-group form-group d-flex justify-content-center">
                            <label class="col-md-4 mt-2 mb-0 font-weight-bold text-gray-900">Seleccione la fecha:</label>
                            <input type="date" v-model="TheDate.date" :max="TheDate.max" class="col-md-5 form-control">
                        </div>
                        <div class="col-md-2 form-group">
                            <button class="btn btn-primary" :disabled="Button.press" @click="listar()">
                                <div v-if="!Button.press"  class="p-0 m-0">
                                    <i class="fa fa-search"></i>&nbsp;&nbsp;Buscar Caja
                                </div>
                                <div v-else class="p-0 m-0">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="Button.press" class="col-md-12" style="height: 26rem;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </div>
                <div v-else-if="Box.id==0" class="col-md-12 pt-3" style="height: 26rem;">
                    <label class="h5 text-primary">Indique la fecha de la caja que desea ver</label>
                </div>
                <div v-else-if="Box.id!=null" class="col-md-12 p-0 m-0 input-group" style="height: 26rem;"> <!-- Si la caja encontrada es correcta entonces -->
                    <div class="col-md-6 container-small"> <!-- Lista de ingresos -->
                        <div class="shadow rounded input-group form-group table-success" style="border: 1px solid; height: 20rem;">
                            <div class="col-md-12 pt-3 form-group">
                                <label class="h5 font-weight-bold text-gray-900 m-0">Lista de ingresos</label>
                            </div>
                            <div class="col-md-12 form-group overflow-auto" style="height: 13rem;">
                                <table v-if="Box.ListIngress.length" class="table table-condensed table-bordered table-sm bg-white text-gray-900">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Hora</th>
                                            <th class="text-center">Descripcion</th>
                                            <th class="text-center">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(concept, index) in Box.ListIngress" :key="index">
                                            <td class="text-center" v-text="index+1"></td>
                                            <td v-text="fix('time', concept.created_at)" class="text-center"></td>
                                            <td v-text="concept.description"></td>
                                            <td v-text="Number.parseFloat(concept.amount).toFixed(2)" class="text-right"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h5 v-else>No se han registrado ingresos</h5>
                            </div>
                            <div class="col-md-12 input-group">
                                <label class="col-md-7 h5 text-right font-weight-bold text-gray-900">Total de ingresos:</label>
                                <label class="col-md-2 h5 text-right font-weight-bold text-gray-900">S/.</label>
                                <label class="col-md-3 h5 text-right font-weight-bold text-gray-900" v-text="Number.parseFloat(Box.total_ingress).toFixed(2)"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 container-small"> <!-- Lista de egresos -->
                        <div class="shadow rounded input-group form-group table-warning" style="border: 1px solid; height: 20rem;">
                            <div class="col-md-12 pt-3 form-group">
                                <label class="h5 font-weight-bold text-gray-900 m-0">Lista de egresos</label>
                            </div>
                            <div class="col-md-12 form-group overflow-auto" style="height: 13rem;">
                                <table v-if="Box.ListEgress.length" class="table table-condensed table-bordered table-sm bg-white text-gray-900">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Hora</th>
                                            <th class="text-center">Descripcion</th>
                                            <th class="text-center">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(concept, index) in Box.ListEgress" :key="index">
                                            <td class="text-center" v-text="index+1"></td>
                                            <td v-text="fix('time', concept.created_at)" class="text-center"></td>
                                            <td v-text="concept.description"></td>
                                            <td v-text="Number.parseFloat(concept.amount).toFixed(2)" class="text-right"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h5 v-else>No se han registrado egresos</h5>
                            </div>
                            <div class="col-md-12 input-group">
                                <label class="col-md-7 h5 text-right font-weight-bold text-gray-900">Total de egresos:</label>
                                <label class="col-md-2 h5 text-right font-weight-bold text-gray-900">S/.</label>
                                <label class="col-md-3 h5 text-right font-weight-bold text-gray-900" v-text="Number.parseFloat(Box.total_egress).toFixed(2)"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 container-small"> <!-- Datos de totales -->
                        <div class="shadow rounded table-primary input-group pt-3" style="border: 1px solid;">
                            <div class="col-md-6 input-group d-flex align-content-center">
                                <div class="col-md-6">
                                    <label class="h5 font-weight-bold text-gray-900">Fecha:&nbsp;&nbsp;<span class="h5 text-primary" v-text="fix('date', Box.date)"></span></label>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold text-gray-900">Apertura:&nbsp;&nbsp;<span class="font-weight-normal text-primary" v-text="fix('time', Box.start_at)"></span></label>
                                    <label v-if="Box.finish_at!=null" class="font-weight-bold text-gray-900">Cierre:&nbsp;&nbsp;<span class="font-weight-normal text-primary" v-text="fix('time', Box.finish_at)"></span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-10 input-group">
                                    <label class="col-md-7 text-right h5 font-weight-bold text-gray-900">Efectivo inicial</label>
                                    <label class="col-md-2 text-right h5 font-weight-bold text-gray-900">S/.</label>
                                    <label class="col-md-3 text-right h5 font-weight-bold text-gray-900" v-text="Number.parseFloat(Box.total_start).toFixed(2)"></label>
                                </div>
                                <div class="col-md-10 input-group">
                                    <label class="col-md-7 text-right h5 font-weight-bold text-gray-900">Efectivo final</label>
                                    <label class="col-md-2 text-right h5 font-weight-bold text-gray-900">S/.</label>
                                    <label class="col-md-3 text-right h5 font-weight-bold text-gray-900" v-text="Number.parseFloat(Box.total_finish).toFixed(2)"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else-if="Box.id==null" class="col-md-12 pt-3" style="height: 26rem;">
                    <label class="h5 text-danger">No se ha encontrado una caja con la fecha indicada</label>
                </div>
            </div>

            <!-- Modales -->
            <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
                <div class="modal-dialog modal-dialog-centered animated bounceIn fast" :class="Modal.size">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                            <button type="button" @click="cerrarModal()" class="close">X</button>
                        </div>

                        <div class="modal-body">
                            <!-- Modal Editar producto-->
                            <div v-if="Modal.numero == 1"> 
                                <div v-if="Error.estado" class="row d-flex justify-content-center">
                                    <div class="alert alert-danger">
                                        <button type="button" @click="Error.estado=0" class="close text-primary" data-dismiss="alert">×</button>
                                        <strong>Corregir los siguentes errores:</strong>
                                        <ul> 
                                            <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                        </ul>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3 font-weight-bold">Nombre&nbsp;</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="Producto.nombre" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-5 font-weight-bold" for="precio_menor">Precio al por menor&nbsp;<span class="text-danger">*</span></label>
                                    <div class="col-md-4">
                                        <input type="number" v-model="Producto.precio_menor" class="form-control" min="0" id="precio_menor">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-5 font-weight-bold" for="precio_mayor">Precio al por mayor&nbsp;<span class="text-danger">*</span></label>
                                    <div class="col-md-4">
                                        <input type="number" v-model="Producto.precio_mayor" class="form-control" min="0" id="precio_mayor">
                                    </div>
                                </div>
                            </div>
                            <!-- Modal ver envio Realizado-->
                            <div v-else-if="Modal.numero==2">
                                <div class="row shadow bg-white rounded p-2">
                                    <div class="col-md-12 ml-auto container">
                                        <div class="row">
                                            <span class="font-weight-bold">LISTA DE ITEMS</span>
                                        </div>
                                        <div class="row form-group ec-table-modal overflow-auto">
                                            <h5 v-if="Carga.mostrar && Carga.numero ==2">
                                                <span role="status" :class="Carga.clase"></span>&nbsp;
                                                <span v-text="Carga.mensaje" :class="Carga.alert"></span>
                                            </h5>
                                            <table class="table tableless table-striped table-sm text-gray-900" v-else>
                                                <thead>
                                                    <tr class="table-success">
                                                        <th class="text-center" >#</th>
                                                        <th class="text-center">Nombre</th>
                                                        <th style="width: 5rem;" class="text-center">Cantidad</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(detalle, indice) in ListaDetalleEnvio" :key="indice">
                                                        <td class="text-center">{{indice+1}}</td>
                                                        <td v-text="detalle.nombre_producto"></td>
                                                        <td class="text-right pr-3" v-text="detalle.cantidad"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group"> 
                                                    <label class="font-weight-bold">Centro de destino:</label>&nbsp;
                                                    {{EnvioRealizado.centro_destino}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="text-right"><span class="font-weight-bold">Total de productos: </span>{{getTotal}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal ver envio Recibido -->
                            <div v-else-if="Modal.numero==3">
                                <div class="row shadow bg-white rounded p-2">
                                    <div class="col-md-12 ml-auto container">
                                        <div class="row">
                                            <span class="font-weight-bold">LISTA DE ITEMS</span>
                                        </div>
                                        <div class="row form-group ec-table-modal overflow-auto">
                                            <h5 v-if="Carga.mostrar && Carga.numero ==2">
                                                <span role="status" :class="Carga.clase"></span>&nbsp;
                                                <span v-text="Carga.mensaje" :class="Carga.alert"></span>
                                            </h5>
                                            <table class="table tableless table-striped table-sm text-gray-900" v-else>
                                                <thead>
                                                    <tr class="table-success">
                                                        <th class="text-center" >#</th>
                                                        <th class="text-center">Nombre</th>
                                                        <th style="width: 5rem;" class="text-center">Cantidad</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(detalle, indice) in ListaDetalleEnvio" :key="indice">
                                                        <td class="text-center">{{indice+1}}</td>
                                                        <td v-text="detalle.nombre_producto"></td>
                                                        <td class="text-right pr-3" v-text="detalle.cantidad"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group"> 
                                                    <label class="font-weight-bold">Centro de origen:</label>&nbsp;
                                                    {{EnvioRecibido.centro_origen ? EnvioRecibido.centro_origen : 'Administración'}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="text-right"><span class="font-weight-bold">Total de productos: </span>{{getTotal}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal de ver detalle de produccion -->
                            <div v-else-if="Modal.numero == 4">
                                <div class="row shadow bg-white rounded p-2">
                                    <div class="col-md-12 ml-auto container">
                                        <div class="row">
                                            <h5 class="font-weight-bold">Lista de items</h5>
                                        </div>
                                        <div class="row form-group ec-table-modal overflow-auto">
                                            <h5 v-if="Carga.mostrar && Carga.numero ==2">
                                                <span role="status" :class="Carga.clase"></span>&nbsp;
                                                <span v-text="Carga.mensaje" :class="Carga.alert"></span>
                                            </h5>
                                            <table class="table tableless table-striped table-sm text-gray-900" v-else>
                                                <thead>
                                                    <tr class="table-success">
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">Nombre</th>
                                                        <th style="width: 5rem;">Cant.</th>
                                                        <th>Costo Unit.</th>
                                                        <th class="text-right pr-4">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(detalle, indice) in ListaDetalleProduccion" :key="indice">
                                                        <td class="text-center">{{indice+1}}</td>
                                                        <td class="text-left pl-5" v-text="detalle.nombre_producto"></td>
                                                        <td v-text="detalle.cantidad"></td>
                                                        <td v-text="detalle.costo_produccion"></td>
                                                        <td class="text-right pr-4">
                                                            {{detalle.subtotal = (detalle.costo_produccion * detalle.cantidad).toFixed(2)}}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                            </div>
                                            <div class="col-md-4">
                                                <p class="text-right pr-2">Inversión total: s/ {{getTotal}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-4 form-inline">
                                        Fecha de inicio&nbsp;<span class="text-danger">*</span>
                                        <input type="date" class="form-control form-control-sm" v-model="Produccion.fecha_inicio">
                                    </div>
                                    <div class="col-md-5 form-inline">
                                        Fecha prog. finalización&nbsp;<span class="text-danger">*</span>
                                        <input type="date" class="form-control form-control-sm" v-model="Produccion.fecha_programada">
                                    </div>
                                </div>
                            </div>
                            <!-- Modal de ver VENTA -->
                            <div v-else-if="Modal.numero == 5" class="input-group">
                                <div class="container-small col-md-12 input-group form-group">
                                    <button type="button" class="col-md-6 btn btn-warning btn" @click="Step.number=0" style="border: 1px solid; border-color: black;">
                                        <label class="font-weight-bold m-0" style="color: black;">DETALLES DE VENTA</label>
                                    </button>
                                    <button type="button" class="col-md-6 btn btn-success" @click="Step.number=1" style="border: 1px solid; border-color: black;">
                                        <label class="font-weight-bold m-0" style="color: black;">CLIENTE Y PAGOS</label>
                                    </button>
                                </div>
                                <div class="col-md-12 p-0 m-0 input-group" v-if="Step.number==0" style="height: 26rem;">
                                    <div class="container-small col-md-4" v-if="Venta.tipo_pago=='2'">
                                        <div class="shadow rounded pt-2 bg-warning" style="border: 1px solid; height: 26rem;">
                                            <div class="col-md-12 form-group">
                                                <div class="col-md-12 p-0 form-group">
                                                    <label class="p-0 h5 font-weight-bold">LISTA DE PAGOS</label>
                                                </div>
                                                <div class="col-md-12 form-group overflow-auto pr-0 pl-0" style="height: 17rem;" v-if="ListaPago.length">
                                                    <table class="table table-bordered table-striped table-sm text-gray-900 bg-white">
                                                        <thead>
                                                            <tr class="table-info">
                                                                <th class="text-center">#</th>
                                                                <th class="text-center">Fecha de pago</th>
                                                                <th class="text-center">Monto</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(pago, index) in ListaPago" :key="index" :class="pago.color">
                                                                <td class="text-right">{{index+1}}</td>
                                                                <td class="text-center" v-text="fix('fecha_hora', pago.created_at)"></td>
                                                                <td class="text-right" v-text="pago.monto"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-12 form-group" style="height: 17rem;" v-else>
                                                    <label class="text-primary">Ningun pago registrado</label>
                                                </div>
                                                <div class="col-md-12 input-group" v-if="Number.parseFloat(Venta.total_faltante)>0">
                                                    <div class="col-md-12 p-0 m-0 input-group">
                                                        <label class="col-md-7 p-0 h5 font-weight-bold">Monto pagado:</label>
                                                        <label class="col-md-1 p-0 h5 text-right text-success">S/.</label>
                                                        <label class="col-md-4 p-0 h5 text-right text-success" v-text="Number.parseFloat(Venta.total-Venta.total_faltante).toFixed(2)"></label>
                                                    </div>
                                                    <div class="col-md-12 p-0 m-0 input-group">
                                                        <label class="col-md-7 p-0 h5 font-weight-bold">Monto faltante:</label>
                                                        <label class="col-md-1 p-0 h5 text-right text-danger">S/.</label>
                                                        <label class="col-md-4 p-0 h5 text-right text-danger" v-text="Number.parseFloat(Venta.total_faltante).toFixed(2)"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 input-group" v-else>
                                                    <label class="col-md-12 p-0 d-flex justify-content-center font-weight-bold text-primary">-- PAGADO COMPLETAMENTE --</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-small" :class="Venta.tipo_pago=='2'?'col-md-8':'col-md-12'">
                                        <div class="shadow rounded pt-2 bg-warning" style="border: 1px solid; height: 26rem;">
                                            <div class="col-md-12 form-group input-group">
                                                <div class="col-md-7 p-0">
                                                    <label class="p-0 h5 mb-0 font-weight-bold">LISTA DE ITEMS</label>
                                                </div>
                                                <div class="col-md-5 input-group">
                                                    <label class="col-md-6 font-weight-bold p-0">Tipo de precio</label>
                                                    <label class="col-md-6 text-primary p-0" v-text="fix('tipo_precio')"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 overflow-auto" style="height: 19rem;">
                                                <table class="table table-bordered table-striped table-sm text-gray-900 bg-white">
                                                    <thead>
                                                        <tr class="table-success">
                                                            <th class="text-center">#</th>
                                                            <th class="text-center">Nombre</th>
                                                            <th class="text-center">Fallidos</th>
                                                            <th class="text-center">Cantidad</th>
                                                            <th class="text-center">Precio</th>
                                                            <th class="text-center">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(detalle, indice) in ListaDetalle" :key="indice">
                                                            <td class="text-right">{{indice+1}}</td>
                                                            <td v-text="detalle.nombre_producto"></td>
                                                            <td class="text-right" v-text="detalle.cantidad_fallido==null?'---':detalle.cantidad_fallido"></td>
                                                            <td class="text-right" v-text="detalle.cantidad"></td>
                                                            <td class="text-right" v-text="detalle.precio"></td>
                                                            <td class="text-right" v-text="detalle.subtotal"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12 input-group mt-2">
                                                <div class="col-md-6 input-group">
                                                    <label class="col-md-4 font-weight-bold p-0">Registrado:</label>
                                                    <label class="col-md-8 text-primary p-0" v-text="fix('fecha_hora', Venta.created_at)"></label>
                                                </div>
                                                <div class="col-md-6 input-group">
                                                    <label class="col-md-6 text-right font-weight-bold h5 p-0">Monto de venta:</label>
                                                    <label class="col-md-6 text-right text-primary h5 p-0" v-text="'S/. '+Number.parseFloat(Venta.total_venta).toFixed(2)"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 p-0 m-0 input-group" v-if="Step.number==1" style="height: 26rem;">
                                    <div class="container-small col-md-12 form-group" style="height: 7rem;">
                                        <div class="shadow rounded pt-3 bg-success" style="border: 1px solid; height: 7rem;">
                                            <div class="col-md-12 form-group input-group">
                                                <label class="col-md-2 font-weight-bold h5">CLIENTE</label>
                                            </div>
                                            <div class="col-md-12 form-group input-group" v-if="Cliente.tipo=='P'">
                                                <div class="col-md-3 input-group">
                                                    <label class="col-md-3">DNI</label>
                                                    <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.dni">
                                                </div>
                                                <div class="col-md-4 input-group">
                                                    <label class="col-md-4">Nombres</label>
                                                    <input type="text" class="col-md-8 form-control form-control-sm" readonly v-model="Cliente.nombres">
                                                </div>
                                                <div class="col-md-4 input-group">
                                                    <label class="col-md-4">Apellidos</label>
                                                    <input type="text" class="col-md-8 form-control form-control-sm" readonly v-model="Cliente.apellidos">
                                                </div>
                                            </div>
                                            <div class="col-md-12 form-group input-group" v-else-if="Cliente.tipo=='E'">
                                                <div class="col-md-3 input-group">
                                                    <label class="col-md-3">RUC</label>
                                                    <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.ruc">
                                                </div>
                                                <div class="col-md-8 input-group">
                                                    <label class="col-md-3">Razón social</label>&nbsp;
                                                    <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.razon_social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-small col-md-3" style="height: 18rem;">
                                        <div class="shadow rounded pt-3 bg-success" style="border: 1px solid; height: 18rem;">
                                            <div class="col-md-12 form-group">
                                                <label class="font-weight-bold h5">TIPO DE VENTA</label>
                                            </div>
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-7 p-0 font-weight-bold">Tipo de pago</label>
                                                <label class="col-md-5 p-0 text-white" v-text="fix('tipo_pago')"></label>
                                            </div>
                                            <div class="col-md-12 input-group form-group" v-if="Venta.tipo_pago=='2'">
                                                <label class="col-md-7 p-0 font-weight-bold">Tipo de entrega</label>
                                                <label class="col-md-5 p-0 text-white" v-text="fix('tipo_entrega')"></label>
                                            </div>
                                            <div class="col-md-12 pt-6 form-group">
                                                <label class="col-md-12 p-0 text-center font-weight-bold">-- Codigo de venta --</label>
                                                <label class="col-md-12 p-0 text-center text-white" v-text="Venta.codigo"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-small col-md-3">
                                        <div class="shadow rounded pt-3 bg-success" style="border: 1px solid; height: 18rem;">
                                            <div class="col-md-12 form-group">
                                                <label class="font-weight-bold h5">BENEFICIOS</label>
                                            </div>
                                            <div v-if="Vale.generado.monto!=null" class="col-md-12 form-group p-0">
                                                <div class="col-md-12 text-center">
                                                    <label class="font-weight-bold">-- VALE GENERADO --</label>
                                                </div>
                                                <div class="col-md-12 input-group">
                                                    <label class="col-md-5 m-0 font-weight-bold">Monto</label>
                                                    <label class="col-md-7 m-0 text-white" v-text="'S/. '+Vale.generado.monto"></label>
                                                </div>
                                                <div class="col-md-12 input-group">
                                                    <label class="col-md-5 m-0 font-weight-bold">Fecha</label>
                                                    <label class="col-md-7 m-0 text-white" v-text="fix('date', Vale.generado.created_at)"></label>
                                                </div>
                                                <div class="col-md-12 input-group form-group">
                                                    <label class="col-md-5 m-0 font-weight-bold">Hora</label>
                                                    <label class="col-md-7 m-0 text-white" v-text="fix('time', Vale.generado.created_at)"></label>
                                                </div>
                                                <div class="col-md-12 text-center mt-3">
                                                    <button class="btn btn-danger" @click="generatePdfVale()">Imprimir vale</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-small col-md-3">
                                        <div class="shadow rounded pt-3 bg-success" style="border: 1px solid; height: 18rem;">
                                            <div class="col-md-12 form-group">
                                                <label class="font-weight-bold h5">DESCUENTOS</label>
                                            </div>
                                            <div v-if="Vale.usado.monto!=null" class="col-md-12 form-group p-0">
                                                <div class="col-md-12 input-group form-group">
                                                    <label class="col-md-12 d-flex justify-content-center">-- VALE USADO --</label>
                                                </div>
                                                <div class="col-md-12 input-group form-group">
                                                    <label class="col-md-4 font-weight-bold">Monto</label>
                                                    <label class="col-md-8 text-white" v-text="'S/. '+Vale.usado.monto"></label>
                                                </div>
                                                <div class="col-md-12 input-group form-group">
                                                    <label class="col-md-4 font-weight-bold">Fecha</label>
                                                    <label class="col-md-8 text-white" v-text="fix('date', Vale.usado.created_at)"></label>
                                                </div>
                                                <div class="col-md-12 input-group form-group">
                                                    <label class="col-md-4 font-weight-bold">Hora</label>
                                                    <label class="col-md-8 text-white" v-text="fix('time', Vale.usado.created_at)"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-small col-md-3">
                                        <div class="shadow rounded pt-3 bg-success" style="border: 1px solid; height: 18rem;">
                                            <div class="col-md-12 form-group">
                                                <label class="font-weight-bold h5">TOTALES</label>
                                            </div>
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-7 p-0 font-weight-bold">Monto de venta</label>
                                                <label class="col-md-1 p-0 text-white text-right">S/.</label>
                                                <label class="col-md-4 p-0 text-white text-right" v-text="Number.parseFloat(Venta.total_venta).toFixed(2)"></label>
                                            </div>
                                            <div class="col-md-12 input-group form-group" v-if="Venta.total_descuento!=null">
                                                <label class="col-md-7 p-0 font-weight-bold">Monto de descuento</label>
                                                <label class="col-md-1 p-0 text-white text-right p-0">S/.</label>
                                                <label class="col-md-4 p-0 text-white text-right" v-text="'-'+Number.parseFloat(Venta.total_descuento).toFixed(2)"></label>
                                            </div>
                                            <div class="col-md-10 bg-white m-4"><hr></div>
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-6 font-weight-bold h5">Total</label>
                                                <label class="col-md-6 p-0 text-white text-right h5" v-text="'S/. '+Number.parseFloat(Venta.total).toFixed(2)"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal configurar ALMACEN-->
                            <div v-else-if="Modal.numero == 6"> 
                                <div v-if="Error.estado" class="row d-flex justify-content-center">
                                    <div class="alert alert-danger">
                                        <button type="button" @click="Error.estado=0" class="close text-primary" data-dismiss="alert">×</button>
                                        <strong>Corregir los siguentes errores:</strong>
                                        <ul> 
                                            <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                        </ul>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3 font-weight-bold" id="nom">Nombre&nbsp;<span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="AlmacenForSettings.nombre" class="form-control" id="nom">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3 font-weight-bold" for="dir">Dirección&nbsp;</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="AlmacenForSettings.direccion" class="form-control" min="0" id="dir">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="row form-group col-md-12 d-flex justify-content-around">
                                <div v-if="Modal.accion">
                                    <button type="button" @click="accionar()" class="btn btn-success" :disabled="Button.press">
                                        <div v-if="!Button.press">{{Modal.accion}}</div>
                                        <span v-else class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <button type="button" @click="cerrarModal()" class="btn btn-secondary" v-text="Modal.cancelar"></button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </main>
</template>

<script>
    export default {
        data(){
            return {
                //datos generales
                Almacen: {
                    id: 4,
                    nombre: '',
                    direccion: '',
                    quieroVer: 1, // 1:Inventario, 2: Envios Realizados, 3: Envios Recibidos, 4: Producciones, 5: Ventas, 6: vales, 7: Caja
                    titulo: '',
                    mostrar: 0 // 1:Inventario, 2: Envios Realizados, 3: Envios Recibidos, 4: Producciones, 5: Ventas, 6: vales, 7: Caja
                },
                AlmacenForSettings:{
                    id: 0,
                    nombre: '',
                    direccion: ''
                },
                SelectAlmacen: [],
                ListaProducto: [],
                ListaProduccion: [],
                Envio: {
                    id: 0
                },
                EnvioRealizado: {
                    id: 0,
                    estado: 0,
                    centro_destino: '',
                },
                EnvioRecibido: {
                    id: 0,
                    centro_origen: '',
                    estado: 0,
                    abasto_id: 0
                },
                Produccion:{
                    id: 0,
                    fecha_inicio : '',
                    fecha_programada : '',
                    fecha_fin: '',
                    total : 0.00,
                },
                ListaDetalleEnvio: [],
                ListaDetalleProduccion: [],
                ListaEnvio: [],
                ListaVale: [],
                Producto: {
                    nombre: '',
                    codigo: '',
                    precio_menor: 0,
                    precio_mayor: 0,
                    stock: 0,
                },

                //para el ver CAJAS
                TheDate: {
                    date: this.getFechaHoy(),
                    max: this.getFechaHoy()
                },
                Box: {
                    id: 0,
                    total_start: null,
                    total_finish: null,
                    total_ingress: null,
                    total_egress: null,
                    ListIngress: [],
                    ListEgress: []
                },

                Venta: {
                    id: null,
                    codigo: null,
                    centro_id: null,
                    total: null,
                    total_start: null,
                    total_faltante: null,
                    total_faltante_start: null,
                    total_descuento: null,
                    total_descuento_start: null,
                    total_venta: null,
                    total_venta_start: null,
                    tipo: null, // 1: contado, 2: credito
                    tipo_pago: null, // 1: contado, 2: credito
                    tipo_entrega: null, // 1: prepago, 2: postpago
                    tipo_precio: null, // 1: al por menor, 2: al por mayor
                    created_at : null,
                },
                Cliente:{
                    id: -1,
                    documento: '',
                    nombres: '',
                    apellidos: '',
                    razon_social: '',
                    tipo: 0
                },
                Vale: {
                    usado: {
                        id: null,
                        monto: null,
                        venta_usada_id: null,
                        created_at: null
                    },
                    generado: {
                        id: null,
                        monto: null,
                        created_at: null
                    }
                },
                Step: {
                    number: null
                },
                ListaPago: null,
                ListaVenta:[],

                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    filas: 5,
                    type: 0,
                    dia: this.getDiaActual(),
                    mes: this.getMesActual(),
                    year: this.getYearActual(),
                    estadoEnviado: 3,
                    estadoRecibido: 3,
                    estadoProduccion: 3,
                    estadoVale: 3
                },
                Error: {
                    estado: 0,
                    mensaje: [],
                },
                Carga:{
                    numero: 0,
                    mostrar: 0,
                    clase: '',
                    mensaje: '',
                    alert: ''
                },
                //datos de paginacion
                Paginacion: {
                    total: 0,
                    currentPage: 0,
                    lastPage: 0,
                    perPage: 0,
                    firstItem: 0,
                    lastItem: 0
                },
                Navegacion:{
                    offset: 3,
                },
                Modal: {
                    estado: 0,
                    numero: 0, // 1: Editar producto, 2: Ver Envio Realizado, 3: Ver envio Recibido, 4: Ver produccion, 5: Ver venta
                    titulo: '',
                    accion: '',
                    cancelar: '',
                    size: ''
                },
                Button: {
                    press: false
                },
                //datos de ruta de consultas
                Ruta: {
                    caja: '/caja',
                    detalle_producto: '/detalle_producto',
                    centro: '/centro',
                    venta: '/venta',
                    vale: '/vale',
                    envio: '/envio',
                    envioRealizado: '/envioRealizado',
                    envioRecibido: '/envioRecibido',
                    abasto: '/abasto',
                    produccion: '/produccion',
                    detalle_venta: '/detalle_venta',
                    pago: '/pago',
                    ventaWithAll: '/venta/getVentaWithAll',
                    serverPhp: 'http://127.0.0.1:8000'
                }
            }
        },
        computed: {
            Paginas: function(){
                if ( !this.Paginacion.lastItem ) {
                    return [];
                }

                var from = this.Paginacion.currentPage - this.Navegacion.offset;
                if ( from < 1) {
                    from = 1;
                }

                var to = this.Paginacion.currentPage + this.Navegacion.offset*2;
                if ( to > this.Paginacion.lastPage ) {
                    to = this.Paginacion.lastPage;
                }

                var pagesArray = [];
                while ( from <= to ) {
                    pagesArray.push(from);
                    from++;
                }
                
                return pagesArray;
            },
            Filas: function(){
                var min = 3;
                var max = 20;
                var filas = [];

                while ( min <= max) {
                    filas.push(min);
                    min++;
                }

                return filas;
            },
            getTotal: function(){
                let total = 0;
                this.ListaDetalleEnvio.forEach( detalle => {
                    total = total + Number.parseInt(detalle.cantidad);
                });
                return total;
            }
        },
        methods: {
            listar(page = 1){
                this.Paginacion.currentPage = page;
                let url;
                let me = this;
                me.ListaVenta = [];
                me.ListaProducto = [];
                me.ListaEnvio = [];
                me.ListaProduccion = [];
                me.ListaVale = [];
                me.Carga.numero = 1;
                me.Carga.mostrar = 1;
                me.Carga.clase = 'spinner-border spinner-border-sm text-primary';
                me.Carga.mensaje = 'Cargando...';
                me.Carga.alert = 'badge badge-info';

                switch (Number.parseInt(this.Almacen.quieroVer)) {
                    case 1: //Listar inventario
                        me.Busqueda.dia = '';
                        url = this.Ruta.centro+'/listProductos?'
                                +'page='+this.Paginacion.currentPage
                                +'&texto='+this.Busqueda.texto
                                +'&rows='+this.Busqueda.filas
                                +'&centro_id='+this.Almacen.id;
                        
                        axios.get(url).then(function (response) {
                            me.ListaProducto = response.data.list.data;
                            me.Paginacion = response.data.paginate;
                            me.Almacen.titulo = 'Inventario'
                            me.Almacen.mostrar = 1;

                            me.Carga.mostrar = 0;
                            me.Carga.clase = '';
                            me.Carga.mensaje = '';
                            me.Carga.alert = '';
                        }).catch(function (error) {
                            console.log(error)
                        });
                        break;
                    case 2: //Listar Envio realizados
                        url = this.Ruta.envioRealizado + '?'
                                +'page='+this.Paginacion.currentPage
                                +'&estado='+this.Busqueda.estadoEnviado
                                +'&idCentro='+this.Almacen.id
                                +'&texto='+this.Busqueda.texto
                                +'&filas='+this.Busqueda.filas
                                +'&dia='+this.Busqueda.dia
                                +'&mes='+this.Busqueda.mes
                                +'&year='+this.Busqueda.year;
                        
                        axios.get(url).then(function (response) {
                            me.ListaEnvio = response.data.envios.data;
                            me.Paginacion = response.data.paginacion;

                            me.Almacen.titulo = 'Envios Realizados'
                            me.Almacen.mostrar = 2;

                            me.Carga.mostrar = 0;
                            me.Carga.clase = '';
                            me.Carga.mensaje = '';
                            me.Carga.alert = '';
                        }).catch(function (error) {
                            console.log(error)
                        });
                        break;
                    case 3: //Listar Envios Recibidos
                        url = this.Ruta.envioRecibido + '?'
                            +'page='+this.Paginacion.currentPage
                            +'&estado='+this.Busqueda.estadoRecibido
                            +'&idCentro='+this.Almacen.id
                            +'&texto='+this.Busqueda.texto
                            +'&filas='+this.Busqueda.filas
                            +'&dia='+this.Busqueda.dia
                            +'&mes='+this.Busqueda.mes
                            +'&year='+this.Busqueda.year;
                    
                        axios.get(url).then(function (response) {
                            me.ListaEnvio = response.data.envios.data;
                            me.Paginacion = response.data.paginacion;

                            me.Almacen.titulo = 'Envios Recibidos'
                            me.Almacen.mostrar = 3;

                            me.Carga.mostrar = 0;
                            me.Carga.clase = '';
                            me.Carga.mensaje = '';
                            me.Carga.alert = '';
                        }).catch(function (error) {
                            console.log(error)
                        });
                        break;
                    case 4: //Listar Produccion
                        url = this.Ruta.produccion +'?'
                                +'page='+this.Paginacion.currentPage
                                +'&estado='+this.Busqueda.estadoProduccion
                                +'&texto='+this.Busqueda.texto
                                +'&filas='+this.Busqueda.filas
                                +'&dia='+this.Busqueda.dia
                                +'&mes='+this.Busqueda.mes
                                +'&year='+this.Busqueda.year
                                +'&idAlmacen='+this.Almacen.id;
                        axios.get(url).then(function (response) {
                            me.ListaProduccion = response.data.producciones.data;
                            me.Paginacion = response.data.paginacion;

                            me.Almacen.titulo = 'Producciones'
                            me.Almacen.mostrar = 4;

                            me.Carga.mostrar = 0;
                            me.Carga.clase = '';
                            me.Carga.mensaje = '';
                            me.Carga.alert = '';
                        }).catch(function (error) {
                            console.log(error)
                        });
                        break;
                    case 5:
                        url = this.Ruta.venta+'?'
                                +'page='+this.Paginacion.currentPage
                                +'&type='+this.Busqueda.type
                                +'&text='+this.Busqueda.texto
                                +'&rows='+this.Busqueda.filas
                                +'&dia='+this.Busqueda.dia
                                +'&mes='+this.Busqueda.mes
                                +'&year='+this.Busqueda.year
                                +'&rol_id='+$('meta[name="rol_id"]').attr('content')
                                +'&centro_id='+this.Almacen.id;
                        
                        axios.get(url).then(function (response) {
                            me.ListaVenta = response.data.ventas.data;
                            me.Paginacion = response.data.pagination;
                            me.Almacen.titulo = 'Ventas'
                            me.Almacen.mostrar = 5;

                            me.Carga.mostrar = 0;
                            me.Carga.clase = '';
                            me.Carga.mensaje = '';
                            me.Carga.alert = '';
                        }).catch(function (error) {
                            console.log(error);
                        });
                        break;
                    case 6://Listar vales
                        url = this.Ruta.vale + '?page='+this.Paginacion.currentPage
                        +'&estado='+this.Busqueda.estadoVale
                        +'&allMonth='+ 1
                        +'&texto='+this.Busqueda.texto
                        +'&centro_id='+ this.Almacen.id
                        +'&filas='+this.Busqueda.filas;
                
                        axios.get(url).then(function (response) {
                            me.ListaVale = response.data.vales.data;
                            me.Paginacion = response.data.paginacion;
                            me.Almacen.titulo = 'Vales'
                            me.Almacen.mostrar = 6;

                            me.Carga.mostrar = 0;
                            me.Carga.clase = '';
                            me.Carga.mensaje = '';
                            me.Carga.alert = '';
                        }).catch(function (error) {
                            console.log(error)
                        });
                        break;
                    case 7:  // Buscar caja chica
                        this.Button.press = true;

                        url = this.Ruta.caja + '/search'

                        axios.get(url, {
                            params: {
                                center_id: this.Almacen.id,
                                date: this.TheDate.date
                            }
                        }).then(function (response) {
                            me.Almacen.titulo = 'Caja Chica'
                            me.Almacen.mostrar = 7;

                            if ( response.data.state == 'transaction-success') {
                                me.fix('found.box', response.data.box);
                                me.fix('found.concepts', response.data.box.get_conceptos);
                                return;
                            }

                            me.Box.id = null;
                            me.Box.total_start = null;
                            me.Box.total_finish = null;
                            me.Box.total_ingress = null;
                            me.Box.total_egress = null;
                            me.Box.start_at = null;
                            me.Box.finish_at = null;

                            console.log(response);
                        }).catch(function (error) {
                            console.log(error);
                        }).then( function (resopnse) {
                            me.Button.press = false;
                        });
                        break;
                }

                for (let i = 0; i < this.SelectAlmacen.length; i++) { //Busco el nombre
                    if(this.SelectAlmacen[i].id == this.Almacen.id) {
                        this.Almacen.nombre = this.SelectAlmacen[i].nombre;
                        this.Almacen.direccion = this.SelectAlmacen[i].direccion;
                    }
                }
            },
            listarDetalles(numero){
                let url;
                let me = this;
                me.Carga.numero = 2;
                me.Carga.mostrar = 1;
                me.Carga.clase = 'spinner-border spinner-border-sm text-primary';
                me.Carga.mensaje = 'Cargando...';
                me.Carga.alert = 'badge badge-info';
                switch (numero) {
                    case 1:// Listar detalles de envios
                        url = me.Ruta.envio + '/getDetalles';
                        axios.get(url,{
                            params: {
                                'id': me.Envio.id
                            }
                        }).then(function(response){
                            me.ListaDetalleEnvio = response.data;
                            me.Carga.mostrar = 0;
                            me.Carga.clase = '';
                            me.Carga.mensaje = '';
                            me.Carga.alert = '';
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                    case 2://Listar detalles de abastos
                        url = this.Ruta.abasto + '/getDetalles';
                        axios.get(url,{
                            params: {
                                'id': me.EnvioRecibido.abasto_id
                            }
                        }).then(function(response){
                            me.ListaDetalleEnvio = response.data;
                            me.Carga.mostrar = 0;
                            me.Carga.clase = '';
                            me.Carga.mensaje = '';
                            me.Carga.alert = '';
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                    case 4:
                        url = this.Ruta.produccion +'/getDetalles';
                        axios.get(url,{
                            params: {
                                'id': me.Produccion.id
                            }
                        }).then(function(response){
                            me.ListaDetalleProduccion = response.data;
                            me.Carga.mostrar = 0;
                            me.Carga.clase = '';
                            me.Carga.mensaje = '';
                            me.Carga.alert = '';
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                    case 5://Listar Detalle de venta
                        url = this.Ruta.detalle_venta+'/list?'
                                            +'venta_id='+this.Venta.id;
                        axios.get(url).then(function(response){
                            me.fix('detalle_venta', response.data);
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                    break;
                    case 6://Lista de pagos de las Ventas
                        url = this.Ruta.pago+'/listVenta?'
                                        +'venta_id='+this.Venta.id;

                        axios.get(url).then(function(response){
                            me.ListaPago = response.data;
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                }
            },
            select(numero){
                let me = this;
                let url;
                switch (numero) {
                    case 1: //los puestos
                        url = this.Ruta.centro + '/selectCentro?tipo=A';
                        axios.get(url).then(function(response){
                            me.SelectAlmacen = response.data;
                            me.listar();
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                }
            },
            abrirModaEditar(producto = []){
                this.Producto.id = producto['detalle']['id'];
                this.Producto.nombre = producto['nombre'];
                this.Producto.precio_menor = producto['detalle']['precio_menor'];
                this.Producto.precio_mayor = producto['detalle']['precio_mayor'];

                this.abrirModal(1, 'Editar Producto', 'Editar', 'Cancelar', '');
            },
            abrirModalSettings(){
                this.AlmacenForSettings.id = this.Almacen.id;
                this.AlmacenForSettings.nombre = this.Almacen.nombre;
                this.AlmacenForSettings.direccion = this.Almacen.direccion;
                this.abrirModal(6, 'Configurar Almacén', 'Guardar', 'Cerrar', '')
            },
            abrirModalVerEnvioRealizado(envio = []){
                this.EnvioRealizado.id = envio['id'];
                this.Envio.id = envio['id'];
                this.EnvioRealizado.centro_destino = envio['centro_destino'];
                this.EnvioRealizado.estado = envio['estado'];

                this.listarDetalles(1);
                this.abrirModal(2, 'Ver Envio', '', 'Cerrar', '')
            },
            abrirModalVerEnvioRecibido(envio = []){
                this.EnvioRecibido.id = envio['id'];
                this.Envio.id = envio['id'];
                this.EnvioRecibido.centro_origen = envio['centro_origen'];
                this.EnvioRecibido.estado = envio['estado'];
                this.EnvioRecibido.abasto_id = envio['abasto_id'];

                this.EnvioRecibido.abasto_id ? this.listarDetalles(2) : this.listarDetalles(1);
                this.abrirModal(3, 'Ver Envio', '', 'Cerrar', '')
            },
            abrirModalVerProduccion(produccion = []){
                this.Produccion.id = produccion['id'];
                this.Produccion.total = produccion['total'];
                this.Produccion.fecha_inicio = produccion['fecha_inicio'];
                this.Produccion.fecha_programada = produccion['fecha_programada'];

                this.listarDetalles(4);
                this.abrirModal(4, 'Ver Producción', '', 'Cerrar', 'modal-xl modal-dialog-scrollable');
            },
            abrirModalVerVenta(numero, data){

                switch(numero){
                    case 1:
                        this.Venta.id = data.id;
                        this.Venta.total = data.total;
                        this.Venta.total_venta = data.total_venta;
                        this.Venta.total_descuento = data.total_descuento;
                        this.Venta.total_faltante = data.total_faltante;
                        this.Venta.tipo_pago = data.tipo.charAt(0);
                        this.Venta.tipo_entrega = data.tipo.charAt(1);
                        this.Venta.tipo_precio = data.tipo.charAt(2);
                        this.Venta.created_at = data.created_at;
                        this.Venta.codigo = data.codigo;
                        
                        this.Cliente.id = data.cliente_id;
                        this.Cliente.dni = data.dni;
                        this.Cliente.nombres = data.nombres;
                        this.Cliente.apellidos = data.apellidos;
                        this.Cliente.ruc = data.ruc;
                        this.Cliente.razon_social = data.razon_social;
                        this.Cliente.tipo = data.cliente_tipo;

                        this.Vale.generado.id = data.vale_generada_id;
                        this.Vale.generado.monto = data.vale_generada_monto;
                        this.Vale.generado.created_at = data.vale_generada_created_at;
                        
                        this.Vale.usado.id = data.vale_usada_id;
                        this.Vale.usado.monto = data.vale_usada_monto;
                        this.Vale.usado.created_at = data.vale_usada_created_at;

                        this.ListaPago = [];
                        this.ListaDetalle = [];
                        
                        this.abrirModal(5, 'Ver Venta', 'Generar Comprobante', 'Cerrar', 'modal-xl');
                        
                        this.listarDetalles(5, 'Ver');
                        this.listarDetalles(6);
                        break;
                    case 2:
                        console.log('Ingrese al case 2');
                        let url = this.Ruta.ventaWithAll + '?venta_id='+data;
                        let me = this;
                        axios.get(url).then(function (response) {
                            let venta = response.data.venta[0];
                            me.Venta.id = venta.id;
                            me.Venta.total = venta.total;
                            me.Venta.total_venta = venta.total_venta;
                            me.Venta.total_descuento = venta.total_descuento;
                            me.Venta.total_faltante = venta.total_faltante==null?0:venta.total_faltante;
                            me.Venta.tipo_pago = venta.tipo.charAt(0);
                            me.Venta.tipo_entrega = venta.tipo.charAt(1);
                            me.Venta.tipo_precio = venta.tipo.charAt(2);
                            me.Venta.created_at = venta.created_at;

                            me.Cliente.id = venta.cliente_id;
                            me.Cliente.dni = venta.dni;
                            me.Cliente.nombres = venta.nombres;
                            me.Cliente.apellidos = venta.apellidos;
                            me.Cliente.ruc = venta.ruc;
                            me.Cliente.razon_social = venta.razon_social;
                            me.Cliente.tipo = venta.cliente_tipo;

                            me.Vale.generado.id = venta.vale_generada_id;
                            me.Vale.generado.monto = venta.vale_generada_monto;
                            me.Vale.generado.created_at = venta.vale_generada_created_at;
                            
                            me.Vale.usado.id = venta.vale_usada_id;
                            me.Vale.usado.monto = venta.vale_usada_monto;
                            me.Vale.usado.created_at = venta.vale_usada_created_at;

                            me.ListaPago = [];
                            me.ListaDetalle = []; 

                            me.abrirModal(5, 'Ver Venta', 'Generar Comprobante', 'Cerrar', 'modal-xl');

                            me.fix('detalle_venta', response.data.detalles);//listar los detalles de venta
                            me.ListaPago = response.data.pagos;//listar los pagos
                        }).catch(function (error) {
                            console.log(error);
                        });
                        break;
                    default:
                        break;
                }
                
            },
            abrirModal(numero, titulo, accion, cancelar, size){
                this.Step.number = 0;

                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.accion = accion;
                this.Modal.cancelar = cancelar;
                this.Modal.size = size;
            },
            cerrarModal(){
                this.Modal.numero = 0;
                this.Modal.estado = 0;
                this.Modal.mensaje = [];
                this.AlmacenForSettings.id = 0;
                this.AlmacenForSettings.nombre = '';
                this.AlmacenForSettings.direccion = '';

                this.Error.estado = 0;
                this.Error.mensaje = [];
                
                this.Button.press = false;
            },
            accionar(){
                this.Button.press = true;
                switch( this.Modal.numero ){
                    case 1 : this.editarProducto(); break;
                    case 5 : this.generatePdfSpecificVenta(); //Generar comprobante de venta
                    case 6 : this.saveSettingsPuesto(); break;
                }
            },
            editarProducto(){
                if ( this.validar(1) ) return;
                if ( this.validar(2) ) return;
                
                var me = this;
                var url = this.Ruta.detalle_producto+'/editar';
                axios.put(url , {
                    'id': this.Producto.id,
                    'precio_menor' : this.Producto.precio_menor,
                    'precio_mayor' : this.Producto.precio_mayor
                }).then(function(response){
                    var estado = response.data.estado;
                    if ( estado == 1 ) {
                        me.cerrarModal();
                        me.listar(1);
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'El producto se ha EDITADO correctamente',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            saveSettingsPuesto(){
                if ( this.validar(3) ) return;
                
                var me = this;
                var url = this.Ruta.centro +'/editar';
                axios.put(url , {
                    'id': this.AlmacenForSettings.id,
                    'nombre' : this.AlmacenForSettings.nombre,
                    'direccion' : this.AlmacenForSettings.direccion
                }).then(function(response){
                    var estado = response.data.estado;
                    if ( estado == 1 ) {
                        me.select(1);
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'El almacén se ha configurado con éxito',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            validar(numero){
                this.Error.estado = 0;
                this.Error.numero = numero;
                this.Error.mensaje = [];

                switch (numero) {
                    case 1:
                        if ( this.Producto.precio_menor == 0) this.Error.mensaje.push("Debe ingresar un precio al por menor");      //precio_menor
                        if ( this.Producto.precio_menor < 0) this.Error.mensaje.push("El precio al por menor debe ser positivo");   //precio_menor
                        if ( this.Producto.precio_mayor == 0) this.Error.mensaje.push("Debe ingresar un precio al por mayor");      //precio_mayor
                        if ( this.Producto.precio_mayor < 0) this.Error.mensaje.push("El precio al por mayor debe ser positivo");   //precio_mayor
                        break;
                    case 2:
                        for (let i = 0; i < this.ListaProducto.length; i++) {
                            if ( this.Producto.id == this.ListaProducto[i].detalle.id ) {
                                if ( this.Producto.precio_menor == this.ListaProducto[i].detalle.precio_menor && this.Producto.precio_mayor == this.ListaProducto[i].detalle.precio_mayor) {
                                    this.Error.mensaje.push("Ningun cambio realizado");
                                }
                                break;
                            }
                        }
                        break;
                    case 3:
                        if ( this.Almacen.nombre == '') this.Error.mensaje.push("Debe ingresar un nombre para el almacén");
                        break;
                }

                if ( this.Error.mensaje.length ) {this.Error.estado = 1; this.Button.press = false;}

                return this.Error.estado;
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
            },
            fix(option, data){
                var fixed;
                let fecha, hora, fecha_fixed, hora_fixed;

                switch (option) {
                    case 'fecha_hora':
                        fecha = data.split(' ')[0].split('-');
                        hora = data.split(' ')[1].split(':');
                        fecha_fixed = fecha[2]+'-'+fecha[1]+'-'+fecha[0];
                        hora_fixed = (hora[0]>12?(hora[0]-12).toString().padStart(2, '0'):hora[0])+':'+hora[1]+' '+(hora[0]>12?'pm':'am') ;
                        fixed = fecha_fixed+' '+hora_fixed;
                        break;
                    case 'date':
                        if ( data.length != 10 && data.lenth != 19){
                            fixed = 'lenght!=10&&length!=19'; break;
                        };

                        if ( data.length == 10 ) {
                            data = data.split('-');
                        } else {
                            data = data.split(' ')[0].split('-');
                        }

                        fixed = data[2]+'-'+data[1]+'-'+data[0];
                        break;
                    case 'time':
                        if ( data.length != 8 && data.length != 19 ) {
                            fixed = 'length!=8&&length!=19'; break;
                        }

                        if ( data.length == 8 ) {
                            data = data.split(':');
                        } else {
                            data = data.split(' ')[1].split(':');
                        }

                        fixed = (data[0]-12>12?data[0]-12:data[0]).toString().padStart(2, '0')+':'+data[1]+' '+(data[0]>12?'pm':'am');
                        break;
                    case 'detalle_venta':
                        console.log('on fix(detalle_venta)');

                        for (let i = 0; i < data.length; i++) {
                            this.ListaDetalle.push({
                                detalle_producto_id: data[i].detalle.id,
                                nombre_producto: data[i].detalle.nombre_producto,
                                cantidad: data[i].detalle.cantidad,
                                cantidad_fallido: data[i].detalle.cantidad_fallido,
                                precio: data[i].detalle.precio,
                                subtotal: data[i].detalle.subtotal
                            });
                        }
                        break;
                    case 'found.box':
                        this.Box.id = data.id;
                        this.Box.total_ingress = data.total_ingress;
                        this.Box.total_egress = data.total_egress;
                        this.Box.total_start = data.total_start;
                        this.Box.total_finish = data.total_finish;
                        this.Box.date = data.start_at.substring(0, 10);
                        this.Box.start_at = data.start_at;
                        this.Box.finish_at = data.finish_at;
                        break;
                    case 'found.concepts':
                        let listIngress = [], listEgress = [];

                        data.forEach(concept => {
                            let item = {
                                created_at: concept.created_at,
                                description: concept.descripcion,
                                amount: concept.monto
                            }

                            if ( concept.type == 1 ) {
                                listIngress.push(item);
                            } else if ( concept.type == 0 ) {
                                listEgress.push(item);
                            } else {
                                this.error();
                            }
                        });

                        this.Box.ListIngress = listIngress;
                        this.Box.ListEgress = listEgress;
                        break;
                    default:
                        fixed = '';
                        break;
                }

                return fixed;
            },
            addCero(i) {
                if (i < 10) {
                    i = '0' + i;
                }
                return i;
            },
            getDia(){
                let min = 1;
                let max = 31;
                let lista = [];
                while(min <= max){
                    lista.push(this.addCero(min));
                    min++;
                }
                return lista;
            },
            getMes(){
                let nombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                let min = 1;
                let max = 12;
                let lista = [];
                while(min <= max){
                    lista.push({nombre: nombres[min-1], valor: this.addCero(min)});
                    min++;
                }
                return lista;
            },
            getYear(min){
                let n =  new Date();
                let max = n.getFullYear();
                let lista = [];
                while(min <= max){
                    lista.push(min);
                    min++;
                }
                return lista;
            },
            getDiaActual(){
                let n =  new Date();
                let dia = this.addCero(n.getDate());
                return dia;
            },
            getMesActual(){
                let n =  new Date();
                let mes = this.addCero(n.getMonth() + 1);
                return mes;
            },
            getYearActual(){
                let n =  new Date();
                let year = n.getFullYear();
                return year;
            },
            getFechaHoy(){
                let n =  new Date();
                //Año
                let y = n.getFullYear();
                //Mes
                let m = this.addCero(n.getMonth() + 1);
                //Día
                let d = this.addCero(n.getDate());
                let hoy = y + "-" + m + "-" + d;
                return hoy;
            },
            generatePdfSpecificVenta(){
                window.open(this.Ruta.serverPhp + '/venta/generatePdfSpecific?id=' + this.Venta.id,'_blank');
                this.Button.press = false;
            },
            generatePdfVale(){
                let url = this.Ruta.vale + '/generatePdfSpecific?cliente_nom=' + this.Cliente.nombres +
                                            '&cliente_ape='+this.Cliente.apellidos + '&cliente_dni='+ this.Cliente.dni+
                                            '&cliente_ruc=' + this.Cliente.ruc + '&cliente_razon_social=' + this.Cliente.razon_social+
                                            '&venta_codigo=' + this.Venta.codigo + '&venta_created_at='+ this.Venta.created_at+
                                            '&vale_monto='+ this.Vale.generado.monto + '&vale_fecha='+ this.Vale.generado.created_at;
                window.open(url, '_blank');
            }
        },
        mounted() {
            this.select(1);
        }
    }
</script>

<style>
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .ec-cursor{
        cursor: pointer;
    }
    .table-scroll-20{
        overflow: auto;
        height: 20rem;
    }
    .table-scroll-15{
        overflow: auto;
        height: 15rem;
    }
    .ec-th{
        background-color: skyblue;
    }
</style>