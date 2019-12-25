<template>
    <main>
        <!-- Interfaz Principal -->
        <div>
            <!-- Encabezado principal -->
            <div class="row form-group">
                <div class="col-md-6 text-center">
                    <!-- <i class="fas fa-hammer"></i>&nbsp; -->
                    <span class="h3 mb-0 text-gray-900 font-italic" v-text="Almacen.nombre"></span>&nbsp;&nbsp;
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <select class="custom-select text-gray-900" v-model="Almacen.id">
                            <option v-for="almacen in SelectAlmacen" :key="almacen.id" :value="almacen.id" v-text="almacen.nombre"></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <select class="custom-select text-gray-900" v-model="Almacen.quieroVer">
                            <option value="1">Inventario</option>
                            <option value="2">Envios Realizados</option>
                            <option value="3">Envios Recibidos</option>
                            <option value="4">Producciones</option>
                            <option value="5">Ventas</option>
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
            <div v-if="Almacen.mostrar == 1">
                <div class="row form-group">
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="search" class="form-control" v-model="Busqueda.texto" @keyup.enter="listar()" placeholder="Buscar por CODIGO o NOMBRE">
                            <button type="button" class="btn btn-primary" @click="listar()">
                                <i class="fa fa-search"></i>&nbsp; Buscar
                            </button>
                        </div>
                    </div>
                    <div class="col-md-5"></div>
                    <div class="col-md-1 text-right">
                        <label>N° filas:</label>
                    </div>
                    <select class="col-md-1 form-control text-gray-900" v-model="Busqueda.filas">
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
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">Opcion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="producto in ListaProducto" :key="producto.id" >
                                    <td v-text="producto.nombre"></td>
                                    <!-- <td v-text="producto.codigo" class="text-center"></td> -->
                                    <td v-text="producto.detalle.precio_menor" class="text-right"></td>
                                    <td v-text="producto.detalle.precio_mayor" class="text-right"></td>
                                    <td v-text="producto.detalle.substock" class="text-right"></td>
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
            <div v-else-if="Almacen.mostrar == 2">
                <div class="row form-group">
                    <div style="width: 8rem;" class="mr-1">
                        <div class="input-group"> 
                            <select class="custom-select text-gray-900" v-model="Busqueda.estadoEnviado">
                                <option value="3">Todos</option>
                                <option value="0">En espera</option>
                                <option value="1">Aceptados</option>
                                <option value="2">Rechazados</option>
                            </select>
                        </div>
                    </div>
                    <div style="width: 24rem;">
                        <input type="search" class="form-control" v-model="Busqueda.texto" @keyup.enter="listar()" placeholder="Buscar por CENTRO DE DESTINO">
                    </div>
                    <div class="col-md-1">
                        <label for="">Fecha de envío</label>
                    </div>
                    <div class="col-md-1">
                        Dia
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.dia">
                            <option value="">Todos</option>
                            <option v-for="item in getDia()" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div style="width: 8rem;">
                        Mes
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.mes">
                            <option value="">Todos</option>
                            <option v-for="item in getMes()" :key="item.valor" :value="item.valor" v-text="item.nombre"></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        Año
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.year">
                            <option value="">Todos</option>
                            <option v-for="item in getYear(2019)" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        N° filas:
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas">
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
                                    <td v-text="formatearFecha(envio.fecha_envio)" class="text-center"></td>
                                    <td v-text="envio.fecha_cambio ? formatearFecha(envio.fecha_cambio) : '-------------'" class="text-center"></td>
                                    <td>
                                        <div v-if="envio.estado == 0">
                                            <span class="badge badge-primary">En espera</span>
                                        </div>
                                        <div v-else-if="envio.estado == 1">
                                            <span class="badge badge-success">Aceptado</span>
                                        </div>
                                        <div v-else="">
                                            <span class="badge badge-danger">Rechazado</span>
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
            <div v-else-if="Almacen.mostrar == 3">
                <div class="row form-group">
                    <div style="width: 8rem;" class="mr-1">
                        <div class="input-group"> 
                            <select class="custom-select text-gray-900" v-model="Busqueda.estadoRecibido">
                                <option value="3">Todos</option>
                                <option value="0">En espera</option>
                                <option value="1">Aceptados</option>
                                <option value="2">Rechazados</option>
                            </select>
                        </div>
                    </div>
                    <div style="width: 24rem;">
                        <input type="search" class="form-control" v-model="Busqueda.texto" @keyup.enter="listar()" placeholder="Buscar por centro de origen">
                    </div>
                    <div class="col-md-1">
                        <label for="">Fecha de envío</label>
                    </div>
                    <div class="col-md-1">
                        Dia
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.dia">
                            <option value="">Todos</option>
                            <option v-for="item in getDia()" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div style="width: 8rem;">
                        Mes
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.mes">
                            <option value="">Todos</option>
                            <option v-for="item in getMes()" :key="item.valor" :value="item.valor" v-text="item.nombre"></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        Año
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.year">
                            <option value="">Todos</option>
                            <option v-for="item in getYear(2016)" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        N° filas:
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas">
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
                                    <td v-text="formatearFecha(envio.fecha_envio)" class="text-center"></td>
                                    <td v-text="envio.fecha_cambio ? formatearFecha(envio.fecha_cambio) : '-------------'" class="text-center"></td>
                                    <td>
                                        <div v-if="envio.estado == 0">
                                            <span class="badge badge-primary">En espera</span>
                                        </div>
                                        <div v-else-if="envio.estado == 1">
                                            <span class="badge badge-success">Aceptado</span>
                                        </div>
                                        <div v-else="">
                                            <span class="badge badge-danger">Rechazado</span>
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
            <div v-else-if="Almacen.mostrar == 4">
                <div class="row form-group">
                    <div class="col-md-2">
                        <div class="input-group"> 
                            <select class="custom-select text-gray-900" v-model="Busqueda.estadoProduccion">
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
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.dia">
                            <option value="">Todos</option>
                            <option v-for="item in getDia()" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        Mes
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.mes">
                            <option value="">Todos</option>
                            <option v-for="item in getMes()" :key="item.valor" :value="item.valor" v-text="item.nombre"></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        Año
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.year">
                            <option value="">Todos</option>
                            <option v-for="item in getYear(2019)" :key="item" :value="item" v-text="item"></option>
                        </select>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-1">
                        N° filas:
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas">
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
                                    <td v-text="formatearFecha(produccion.fecha_inicio)"></td>
                                    <td v-text="formatearFecha(produccion.fecha_programada)"></td>
                                    <td v-text="produccion.fecha_fin ? formatearFecha(produccion.fecha_fin) : '----------------'"></td>
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
            <div v-else-if="Almacen.mostrar == 5">
                <div class="row form-group">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-4">
                                <select class="custom-select text-gray-900" v-model="Busqueda.type">
                                    <option value="0">Todos</option>
                                    <option value="1">Contado</option>
                                    <option value="2">Credito</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <input type="search" class="form-control" v-model="Busqueda.texto" placeholder="Buscar por CLIENTE o CODIGO de venta" @keyup.enter="listar()">
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
                                <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.dia">
                                    <option value="">Todos</option>
                                    <option v-for="item in getDia()" :key="item" :value="item" v-text="item"></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                Mes
                                <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.mes">
                                    <option value="">Todos</option>
                                    <option v-for="item in getMes()" :key="item.valor" :value="item.valor" v-text="item.nombre"></option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                Año
                                <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.year">
                                    <option value="">Todos</option>
                                    <option v-for="item in getYear(2019)" :key="item" :value="item" v-text="item"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-5">
                                <button type="button" class="btn btn-primary" @click="listar()">
                                    <i class="fa fa-search"></i>&nbsp;Buscar
                                </button>
                            </div>
                            <div class="col-md-7 input-group">
                                <label class="">N° filas:</label>
                                <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas">
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
                                    <td class="text-center" v-text="fix(0, venta.created_at)"></td>
                                    <td class="text-center">
                                        <template>
                                            <button type="button" title="VER" class="btn btn-primary btn-sm" @click="abrirModalVer(venta)">
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

            <!-- Modales -->
            <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
                <div class="modal-dialog modal-dialog-centered animated bounceIn fast" :class="Modal.size">
                    <div class="modal-content modal-lg">
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
                        </div>
                        <div class="modal-footer">
                            <div class="row form-group col-md-12 d-flex justify-content-around">
                                <div v-if="Modal.accion">
                                    <button type="button" @click="accionar()" class="btn btn-success" v-text="Modal.accion"></button>
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
                    quieroVer: 1, // 1:Inventario, 2: Envios Realizados, 3: Envios Recibidos, 4: Producciones, 5: Ventas
                    titulo: '',
                    mostrar: 0 // 1:Inventario, 2: Envios Realizados, 3: Envios Recibidos, 4: Producciones, 5: Ventas
                },
                SelectAlmacen: [],
                ListaProducto: [],
                ListaProduccion: [],
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
                Producto: {
                    nombre: '',
                    codigo: '',
                    precio_menor: 0,
                    precio_mayor: 0,
                    stock: 0,
                },
                Venta:{
                },
                ListaVenta:[],
                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    filas: 5,
                    type: 0,
                    dia: '',
                    mes: this.getMesActual(),
                    year: this.getYearActual(),
                    estadoEnviado: 3,
                    estadoRecibido: 3,
                    estadoProduccion: 3
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
                    numero: 0, // 1: Editar producto, 2: Ver Envio Realizado, 3: Ver envio Recibido, 4: Ver produccion
                    titulo: '',
                    accion: '',
                    cancelar: '',
                    size: ''
                },
                //datos de ruta de consultas
                Ruta: {
                    detalle_producto: '/detalle_producto',
                    centro: '/centro',
                    venta: '/venta',
                    envioRealizado: '/envioRealizado',
                    envioRecibido: '/envioRecibido',
                    abasto: '/abasto',
                    produccion: '/produccion'
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
                me.Carga.numero = 1;
                me.Carga.mostrar = 1;
                me.Carga.clase = 'spinner-border spinner-border-sm text-primary';
                me.Carga.mensaje = 'Cargando...';
                me.Carga.alert = 'badge badge-info';
                switch (Number.parseInt(this.Almacen.quieroVer)) {
                    case 1: //Listar inventario
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
                        this.Busqueda.dia = '';
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
                        this.Busqueda.dia = '';
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
                        this.Busqueda.dia = '';
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
                        this.Busqueda.dia = this.getDiaActual();
                        url = this.Ruta.venta+'?'
                                +'page='+this.Paginacion.currentPage
                                +'&type='+this.Busqueda.type
                                +'&text='+this.Busqueda.texto
                                +'&rows='+this.Busqueda.filas
                                +'&dia='+this.Busqueda.dia
                                +'&mes='+this.Busqueda.mes
                                +'&year='+this.Busqueda.year
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
                }
                for (let i = 0; i < this.SelectAlmacen.length; i++) { //Busco el nombre
                    if(this.SelectAlmacen[i].id == this.Almacen.id) this.Almacen.nombre = this.SelectAlmacen[i].nombre;
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
                    case 1:
                        url = me.Ruta.envioRealizado + '/getDetalles';
                        axios.get(url,{
                            params: {
                                'id': me.EnvioRealizado.id
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
                    case 2:
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
                    case 3:
                        url = me.Ruta.envioRealizado + '/getDetalles';
                        axios.get(url,{
                            params: {
                                'id': me.EnvioRecibido.id
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
            abrirModalVerEnvioRealizado(envio = []){
                this.EnvioRealizado.id = envio['id'];
                this.EnvioRealizado.centro_destino = envio['centro_destino'];
                this.EnvioRealizado.estado = envio['estado'];

                this.listarDetalles(1);
                this.abrirModal(2, 'Ver Envio', '', 'Cerrar', '')
            },
            abrirModalVerEnvioRecibido(envio = []){
                this.EnvioRecibido.id = envio['id'];
                this.EnvioRecibido.centro_destino = envio['centro_destino'];
                this.EnvioRecibido.estado = envio['estado'];
                this.EnvioRecibido.abasto_id = envio['abasto_id'];

                this.EnvioRecibido.abasto_id ? this.listarDetalles(2) : this.listarDetalles(3);
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
            abrirModal(numero, titulo, accion, cancelar, size){
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

                this.Error.estado = 0;
                this.Error.mensaje = [];
            },
            accionar(){
                switch( this.Modal.numero ){
                    case 1 : this.editarProducto(); break;
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
                }

                if ( this.Error.mensaje.length ) this.Error.estado = 1;

                return this.Error.estado;
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
            },
            fix(numero, data){
                var fixed;

                switch (numero) {
                    case 0:
                        let fecha = data.split(' ')[0].split('-');
                        let hora = data.split(' ')[1].split(':');
                        let fecha_fixed = fecha[2]+'-'+fecha[1]+'-'+fecha[0];
                        let hora_fixed = (hora[0]>12?(hora[0]-12).toString().padStart(2, '0'):hora[0])+':'+hora[1]+':'+hora[2];
                        fixed = fecha_fixed+' '+hora_fixed;
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
            formatearFecha(fecha){
                let arrayFecha = fecha.split('-');
                let newFecha = arrayFecha[2] + '-' + arrayFecha[1] + '-' + arrayFecha[0];
                return newFecha;
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