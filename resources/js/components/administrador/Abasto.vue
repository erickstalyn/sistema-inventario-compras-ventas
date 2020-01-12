<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Mis Abastecimientos&nbsp;</span>
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="fas fa-hammer"></i>&nbsp; Nuevo
                </button>&nbsp;
                <button type="button" class="btn btn-danger">
                    <i class="far fa-file-pdf"></i>&nbsp; PDF
                </button>
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group"> 
                                <select class="custom-select text-gray-900" v-model="Busqueda.estado">
                                    <option value="3">Todos</option>
                                    <option value="0">Enviados</option>
                                    <option value="1">Aceptados</option>
                                    <option value="2">Rechazados</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <input type="search" class="form-control" v-model="Busqueda.texto" @keyup.enter="listar()" placeholder="Buscar por proveedor">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">Fecha de envío</label>
                        </div>
                        <div class="col-md-2">
                            Dia
                            <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.dia">
                                <option value="">Todos</option>
                                <option v-for="item in getDia()" :key="item" :value="item" v-text="item"></option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            Mes
                            <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.mes">
                                <option value="">Todos</option>
                                <option v-for="item in getMes()" :key="item.valor" :value="item.valor" v-text="item.nombre"></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Año
                            <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.year">
                                <option value="">Todos</option>
                                <option v-for="item in getYear(2016)" :key="item" :value="item" v-text="item"></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            N° filas:
                            <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas">
                                <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" @click="listar()">
                        <i class="fa fa-search"></i>&nbsp; Buscar
                    </button>
                </div>
            </div>

            <!-- Listado -->
            <div v-if="ListaAbasto.length" class="">
                <!-- Tabla -->
                <div class="ec-table overflow-auto">
                    <table class="table table-borderless table-sm text-gray-900">
                        <thead>
                            <tr class="table-info">
                                <th class="pl-5">Proveedor</th>
                                <th >Almacén de destino</th>
                                <th>Fecha de envío</th>
                                <th>Costo total</th>
                                <th>Tipo</th>
                                <th class="text-center">Estado de envío</th>
                                <th class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="abasto in ListaAbasto" :key="abasto.id" >
                                <td class="pl-2" v-text="abasto.proveedor_persona ? abasto.proveedor_persona : abasto.proveedor_empresa"></td>
                                <td v-text="abasto.nombre_centro"></td>
                                <td v-text="formatearFecha(abasto.fecha_envio)"></td>
                                <td v-text="abasto.total"></td>
                                <td v-text="abasto.tipo_abasto? 'Crédito' : 'Contado'">
                                </td>
                                <td class="text-center">
                                    <div v-if="abasto.estado_envio == 0">
                                        <span class="badge badge-primary">Enviado</span>
                                    </div>
                                    <div v-else-if="abasto.estado_envio == 1">
                                        <span class="badge badge-success">Aceptado</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Rechazado</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    
                                    <template v-if="abasto.tipo_abasto == 1 && abasto.total_faltante != 0">
                                        <button type="button"  title="Pagar Cuota" class="btn btn-warning btn-sm" @click="abrirModalPagar(abasto)">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </button>
                                    </template>
                                    <template>
                                        <button type="button"  title="Ver abasto" class="btn btn-primary btn-sm" @click="abrirModalVer(abasto)">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </template>
                                    <template v-if="abasto.estado_envio == 0">
                                        <button type="button"  title="Anular" class="btn btn-danger btn-sm" @click="anularAbasto(abasto.id)">
                                                <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </template>
                                    <template v-else-if="abasto.estado_envio == 2">
                                        <button type="button"  title="Reenviar" class="btn btn-info btn-sm">
                                            <i class="fas fa-plane"></i>
                                        </button>
                                        <button type="button"  title="Anular" class="btn btn-danger btn-sm" @click="anularAbasto(abasto.id)">
                                                <i class="fas fa-trash-alt"></i>
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
            <div v-else>
                <h5>No se han encontrado resultados</h5>
            </div>

        </div>

        <!-- Modales de Agregar/Editar -->
        <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
            <div class="modal-dialog modal-dialog-centered animated bounceIn fast" :class="Modal.size">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div v-if="Modal.numero == 1">
                                <!-- Filtro de productos -->
                                <div v-if="Error.estado" class="row d-flex justify-content-center">
                                    <div class="alert alert-danger">
                                        <button type="button" @click="Error.estado=0" class="close text-primary" data-dismiss="alert">×</button>
                                        <strong>Corregir los siguentes errores:</strong>
                                        <ul> 
                                            <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 pl-2">
                                        <p class="font-weight-bold">PROVEEDOR</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group"> 
                                            RUC/DNI&nbsp;<span class="text-danger">*</span>&nbsp;
                                            <input type="text" class="form-control form-control-sm" v-model="DatosServicio.documento" autofocus @keyup.enter="consultar()">
                                            <button type="button" class="btn btn-sm btn-primary" @click="consultar()">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <h5>
                                            <span role="status" :class="Carga.clase"></span>&nbsp;
                                            <span v-text="DatosServicio.mensaje" :class="DatosServicio.alert"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div v-if="DatosServicio.tipo == 1">
                                    <div class="row form-group">
                                        <div class="col-md-2">
                                            <div class="input-group"> 
                                                DNI&nbsp;
                                                <input type="text" class="form-control form-control-sm" readonly v-model="DatosProveedor.documento">
                                            </div>
                                        </div>
                                        <div class="" :class="Modal.numero == 2 ? 'col-md-3': 'col-md-4'">
                                            <div class="input-group">
                                                <label >Nombres</label>&nbsp;
                                                <input type="text" class="form-control form-control-sm" readonly v-model="DatosProveedor.nombres">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label >Apellidos</label>&nbsp;
                                                <input type="text" class="form-control form-control-sm" readonly v-model="DatosProveedor.apellidos">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="DatosServicio.tipo == 2">
                                    <div class="row form-group">
                                        <div class="col-md-2">
                                            <div class="input-group"> 
                                                RUC&nbsp;
                                                <input type="text" class="form-control form-control-sm" readonly v-model="DatosProveedor.documento">
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <label >Razón social</label>&nbsp;
                                                <input type="text" class="form-control form-control-sm" v-model="DatosProveedor.razon_social" :readonly="DatosServicio.readonly">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row shadow bg-white rounded p-2">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <p class="font-weight-bold">PRODUCTOS</p>
                                        </div>
                                        <div class="row">
                                            <div class="input-group"> 
                                                <input type="search" class="form-control form-control-sm" v-model="BusquedaFiltro.texto" @keyup.enter="listarFiltro()" id="filtroProducto" placeholder="Producto,marca,modelo,tamaño,color">
                                                <button type="button" class="btn btn-sm btn-primary" @click="listarFiltro()">
                                                    <i class="fa fa-search"></i>&nbsp; Buscar
                                                </button>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row form-group overflow-auto" style="height: 17.5rem;">
                                            <div v-if="ListaProducto.length">
                                                <table class="table table-borderless table-striped table-sm text-gray-900">
                                                    <thead>
                                                        <tr class="table-danger">
                                                            <th class="text-center" style="width: 3rem;">Agregar</th>
                                                            <th>Nombre</th>
                                                            <th>Stock</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="producto in ListaProducto" :key="producto.id" >
                                                            <td class="text-center">
                                                                <button type="button" title="Editar" class="btn btn-circle btn-sm btn-outline-success" @click="agregarDetalle(producto)">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            </td>
                                                            <td v-text="producto.nombre"></td>
                                                            <td v-text="producto.stock"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div v-else>
                                                <p>No se han encontrado resultados</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group"> 
                                                    <label for="">Enviar a</label>&nbsp;<span class="text-danger">*</span>&nbsp;
                                                    <select v-model="Abasto.centro_to_id" class="custom-select custom-select-sm text-gray-900">
                                                        <option value="0">Seleccione</option>
                                                        <option v-for="item in SelectAlmacen" :key="item.id" :value="item.id" v-text="item.nombre" ></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 ml-auto container">
                                        <div class="row">
                                            <div class="col-md-3 p-0">
                                                <p class="font-weight-bold">LISTA DE ITEMS</p>
                                            </div>
                                            <div class="col-md-6"></div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <label for="tipo" class="font-weight-bold">Tipo</label>&nbsp;<span class="text-danger">*</span>&nbsp;
                                                    <select v-model="Abasto.tipo_abasto" class="custom-select custom-select-sm" id="tipo">
                                                        <option value="0">Contado</option>
                                                        <option value="1">Credito</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group ec-table-modal overflow-auto">
                                            <div v-if="ListaDetalleAbasto.length">
                                                <table class="table tableless table-striped table-sm text-gray-900">
                                                    <thead>
                                                        <tr class="table-success">
                                                            <th class="text-center" style="width: 3rem;">Quitar</th>
                                                            <th>Nombre</th>
                                                            <th style="width: 5rem;">Cant.</th>
                                                            <th style="width: 5rem;">P. Unit.</th>
                                                            <th class="text-center">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(detalle, indice) in ListaDetalleAbasto" :key="detalle.id">
                                                            <td class="text-center">
                                                                <button type="button" title="Editar" class="btn btn-circle btn-outline-danger btn-sm" @click="quitarDetalle(indice)">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>
                                                            </td>
                                                            <td v-text="detalle.nombre"></td>
                                                            <td >
                                                                <input type="number" v-model="detalle.cantidad" class="form-control form-control-sm" min="1">
                                                            </td>
                                                            <td>
                                                                <input type="number" v-model="detalle.costo_abasto" class="form-control form-control-sm" min="0">
                                                            </td>
                                                            <td class="text-right pr-3">
                                                                {{detalle.subtotal = (detalle.costo_abasto * detalle.cantidad).toFixed(2)}}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div v-else>
                                                <br>
                                                <p>Sin detalles de abasto</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group" style="width: 12.4rem;" v-if="Abasto.tipo_abasto == '1'"> 
                                                    <label for="">Pago inicial</label>&nbsp;<span class="text-danger">*</span>&nbsp;
                                                    <input type="number" class="form-control form-control-sm" v-model="Abasto.pagoInicial" min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-5">
                                                <p class="text-right pr-1">Desembolso total: s/ {{getDesembolso}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="Modal.numero == 2">
                                <div class="row">
                                    <div class="col-md-2 pl-2">
                                        <p class="font-weight-bold">PROVEEDOR</p>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="font-weight-bold">Enviado a:</span>&nbsp;{{Abasto.nombre_centro}}
                                        </div>
                                    </div>
                                </div>
                                <div v-if="DatosProveedor.documento.length == 8">
                                    <div class="row form-group">
                                        <div class="col-md-2">
                                            <div class="input-group"> 
                                                DNI&nbsp;
                                                <input type="text" class="form-control form-control-sm" readonly v-model="DatosProveedor.documento">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label >Nombres</label>&nbsp;
                                                <input type="text" class="form-control form-control-sm" readonly v-model="DatosProveedor.nombres">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="font-weight-bold">Estado de envío:</span>&nbsp;&nbsp;
                                                <h5>
                                                    <div v-if="Abasto.estado_envio == 0">
                                                        <span class="badge badge-primary">Enviado</span>
                                                    </div>
                                                    <div v-else-if="Abasto.estado_envio == 1">
                                                        <span class="badge badge-success">Aceptado</span>
                                                    </div>
                                                    <div v-else>
                                                        <span class="badge badge-danger">Rechazado</span>
                                                    </div>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else="">
                                    <div class="row form-group">
                                        <div class="col-md-2">
                                            <div class="input-group"> 
                                                RUC&nbsp;
                                                <input type="text" class="form-control form-control-sm" readonly v-model="DatosProveedor.documento">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label >Razón social</label>&nbsp;
                                                <input type="text" class="form-control form-control-sm" v-model="DatosProveedor.razon_social" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="font-weight-bold">Estado de envío:</span>&nbsp;&nbsp;
                                                <h5>
                                                    <div v-if="Abasto.estado_envio == 0">
                                                        <span class="badge badge-primary">Enviado</span>
                                                    </div>
                                                    <div v-else-if="Abasto.estado_envio == 1">
                                                        <span class="badge badge-success">Aceptado</span>
                                                    </div>
                                                    <div v-else>
                                                        <span class="badge badge-danger">Rechazado</span>
                                                    </div>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row shadow bg-white rounded p-2">
                                    <div class="col-md-4" v-if="Abasto.tipo_abasto == 1">
                                        <div class="row">
                                            <p class="font-weight-bold">PAGOS</p>
                                        </div>
                                        <br>
                                        <div class="row form-group overflow-auto" style="height: 15.5rem;">
                                            <div class="col-md-12">
                                                <div v-if="ListaPago.length">
                                                    <table class="table table-borderless table-striped table-sm text-gray-900">
                                                        <thead>
                                                            <tr class="table-info">
                                                                <th class="text-center">#</th>
                                                                <th class="text-center">Fecha de pago</th>
                                                                <th class="text-right pr-4">Monto</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(pago, index) in ListaPago" :key="index" :class="pago.color">
                                                                <td class="text-center">{{index+1}}</td>
                                                                <td class="text-center" v-text="fix(0, pago.created_at)"></td>
                                                                <td class="text-right pr-4" v-text="pago.monto"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div v-else>
                                                    <p>Ningun pago registrado</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-right pr-5">
                                                <span class="font-weight-bold">Monto pagado: </span><span class="text-success">s/{{getSumaPagos}}</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-right pr-5">
                                                <span class="font-weight-bold">Monto faltante: </span><span class="text-danger">s/{{(Abasto.total_ver - getSumaPagos).toFixed(2)}}</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-right pr-5">
                                                <span class="font-weight-bold">Costo total: </span><span>s/{{Abasto.total_ver}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-auto container" :class="[Abasto.tipo_abasto ? 'col-md-8' : 'col-md-12']">
                                        <div class="row">
                                            <div class="col-md-3 p-0">
                                                <p class="font-weight-bold">LISTA DE ITEMS</p>
                                            </div>
                                            <div class="col-md-6"></div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <label for="tipo" class="font-weight-bold">Tipo: </label>&nbsp;
                                                    <div v-if="Abasto.tipo_abasto">
                                                        Crédito
                                                    </div>
                                                    <div v-else>
                                                        Contado
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group ec-table-modal overflow-auto">
                                            <table class="table tableless table-striped table-sm text-gray-900">
                                                <thead>
                                                    <tr class="table-success">
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">Nombre</th>
                                                        <th style="width: 5rem;">Cant.</th>
                                                        <th style="width: 5rem;">P. Unit.</th>
                                                        <th class="text-right pr-4">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(detalle, index) in ListaDetalleAbastoVer" :key="index">
                                                        <td class="text-center">{{index+1}}</td>
                                                        <td class="text-left pl-5" v-text="detalle.nombre_producto"></td>
                                                        <td v-text="detalle.cantidad"></td>
                                                        <td v-text="detalle.costo_abasto"></td>
                                                        <td class="text-right pr-4" v-text="detalle.subtotal"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7">
                                            </div>
                                            <div class="col-md-5">
                                                <p class="text-right pr-2">Desembolso total: s/ {{Abasto.total_ver}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="Modal.numero == 3">
                                <div v-if="Error.estado" class="row d-flex justify-content-center">
                                    <div class="alert alert-danger" style="height: 4.5rem;">
                                        <button type="button" @click="Error.estado=0" class="close text-primary" data-dismiss="alert">×</button>
                                        <strong>Corregir los siguentes errores:</strong>
                                        <ul> 
                                            <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                        </ul>
                                    </div>
                                </div>
                                <div class="row form-group ">
                                    <div class="col-md-3">
                                        <span class="font-weight-bold">Registrar</span>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-7">
                                        <div class="input-group"> 
                                            Monto&nbsp;
                                            <input type="number" class="form-control form-control-sm" v-model="Pago.monto" @keyup.enter="agregarListaPago()">&nbsp;
                                            <button type="button" class="btn btn-sm btn-primary" @click="agregarListaPago()" :disabled="getSumaPagos == Abasto.total_ver">
                                                Registrar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                </div>
                                <div class="row form-group overflow-auto" style="height: 17.5rem;">
                                    <div class="col-md-12">
                                        <div v-if="ListaPago.length">
                                            <table class="table table-borderless table-striped table-sm text-gray-900">
                                                <thead>
                                                    <tr class="table-info">
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">Fecha de pago</th>
                                                        <th class="text-right pr-5">Monto</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(pago, index) in ListaPago" :key="index" :class="pago.color">
                                                        <td class="text-center">{{index+1}}</td>
                                                        <td class="text-center" v-text="fix(0, pago.created_at)"></td>
                                                        <td class="text-right pr-5" v-text="pago.monto"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div v-else>
                                            <p>Ningun pago registrado</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right pr-5">
                                        <span class="font-weight-bold">Monto pagado: </span><span class="text-success">s/{{getSumaPagos}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right pr-5">
                                        <span class="font-weight-bold">Monto faltante: </span><span class="text-danger">s/{{(Abasto.total_ver - getSumaPagos).toFixed(2)}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right pr-5">
                                        <span class="font-weight-bold">Costo total: </span><span>s/{{Abasto.total_ver}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" v-if="permisoModalFooter">
                        <div class="row form-group col-md-12 d-flex justify-content-around">
                            <div v-if="Modal.accion">
                                <button type="button" @click="accionar(Modal.accion)" class="btn btn-success" v-text="Modal.accion"></button>
                            </div>
                            <button type="button" @click="cerrarModal()" class="btn btn-secondary" v-text="Modal.cancelar"></button>
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
                ListaAbasto: [],
                Abasto: {
                    id: 0,
                    total: 0.00,
                    total_ver: 0.00,
                    total_faltante: 0.00,
                    tipo_abasto: 1, // 0: Contado, 1: Credito
                    centro_to_id: 0, //Almacén donde se enviará el abasto
                    created_at : '',
                    pagoInicial: '',
                    nombre_centro: '', //Nombre del centro al que se envia
                    estado_envio: -1
                },
                DatosProveedor:{
                    id: 0,
                    documento: '',
                    nombres: '',
                    apellidos: '',
                    razon_social: ''
                },
                //datos de busqueda y filtracion general
                Busqueda: {
                    texto: '',
                    estado: 3,
                    filas: 5,
                    dia: '',
                    mes: this.getMesActual(),
                    year: this.getYearActual()
                },

                //datos de modales
                Modal: {
                    numero: 0, // 1: Agregar, 2: Ver, 3: Pagar
                    estado: 0,
                    titulo: '',
                    accion: '',
                    cancelar: '',
                    size: ''
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
                    ordenarPor: 'abasto.id',
                    orden: 'desc'
                },

                //datos de errores
                Error: {
                    estado: 0,
                    mensaje: []
                },
                Carga: {
                    clase: ''
                },
                //DATOS PARA AGREGAR UNA PRODUCCION
                BusquedaFiltro:{
                    texto: ''
                },
                ListaProducto:[
                ],
                ListaDetalleAbasto:[],
                ListaDetalleAbastoVer:[],
                //DATOS PARA ENVIAR UNA PRODUCCION
                SelectAlmacen: [],
                //DATOS PARA CONSULTA SUNAT Y RENIEC
                DatosServicio: {
                    documento: '',
                    tipo: 0, //1->PERSONA, 2-> EMPRESA
                    mensaje: '',
                    alert: '',
                    readonly: false
                },
                ListaPago: [],
                Pago:{
                    monto: '',
                    sumaPagos: 0.00,
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
            permisoModalFooter: function(){
                if ( this.Modal.numero == 1 ) return true;
                if ( this.Modal.numero == 2 ) return true;
                if ( this.Modal.numero == 3 ) return true;

                return false;
            },
            getDesembolso: function(){
                this.Abasto.total = 0.00;
                this.ListaDetalleAbasto.forEach( detalle => {
                    this.Abasto.total = this.Abasto.total + detalle.costo_abasto * detalle.cantidad;
                });
                return (this.Abasto.total).toFixed(2);
            },
            getSumaPagos: function(){
                let sumaPagos = 0.00;
                this.ListaPago.forEach( detalle => {
                    sumaPagos = sumaPagos + Number.parseFloat(detalle.monto);
                });
                return (sumaPagos).toFixed(2);
            }
        },
        methods: {
            listar(page = 1, ordenarPor = ''){
                if ( ordenarPor == this.Navegacion.ordenarPor ) {
                    this.Navegacion.orden = (this.Navegacion.orden == 'asc' ? 'desc' : 'asc');
                } else {
                    this.Navegacion.ordenarPor = (ordenarPor != '' ? ordenarPor: this.Navegacion.ordenarPor);
                    // this.Navegacion.orden = 'asc';
                }
                this.Paginacion.currentPage = page==1?1:page;

                var url = '/abasto?page='+this.Paginacion.currentPage
                        +'&estado='+this.Busqueda.estado
                        +'&texto='+this.Busqueda.texto
                        +'&filas='+this.Busqueda.filas
                        +'&dia='+this.Busqueda.dia
                        +'&mes='+this.Busqueda.mes
                        +'&year='+this.Busqueda.year
                        +'&ordenarPor='+this.Navegacion.ordenarPor
                        +'&orden='+this.Navegacion.orden;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaAbasto = response.data.abastos.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            formatearFecha(fecha){
                let arrayFecha = fecha.split('-');
                let newFecha = arrayFecha[2] + '-' + arrayFecha[1] + '-' + arrayFecha[0];
                return newFecha;
            },
            listarFiltro(){
                if(this.BusquedaFiltro.texto != ''){
                    let me = this;
                    let url = '/producto/getProductoFiltrado?texto=' + this.BusquedaFiltro.texto;
                    axios.get(url).then(function(response){
                        if(response.data.productos.length == 1 && me.BusquedaFiltro.texto == response.data.productos[0].codigo){
                            me.agregarDetalle(response.data.productos[0]);
                            me.BusquedaFiltro.texto = '';
                        }else{
                            me.ListaProducto = response.data.productos;
                        }
                        let inputFiltro = document.getElementById('filtroProducto');
                        inputFiltro.focus();
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            },
            agregarDetalle(producto){
                //Verifico si el producto ya esta en la lista de detalle
                let incluido = false;
                for (let i = 0; i < this.ListaDetalleAbasto.length; i++) {
                    if(this.ListaDetalleAbasto[i].id == producto.id){
                        incluido = true;
                        this.ListaDetalleAbasto[i].cantidad ++;
                        break;
                    }
                }

                if(!incluido){
                    let elProducto = {
                        id: producto.id,
                        nombre: producto.nombre,
                        cantidad: 1,
                        costo_abasto: 0.00,
                        subtotal: 0.00
                    }
                    this.ListaDetalleAbasto.push(elProducto);
                }
            },
            quitarDetalle(indice){
                this.ListaDetalleAbasto.splice(indice,1);
            },
            consultar(){
                this.DatosServicio.alert = '';
                this.DatosServicio.mensaje = '';
                switch (this.DatosServicio.documento.length) {
                    case 0:
                        this.DatosServicio.alert = 'badge badge-warning';
                        this.DatosServicio.mensaje = 'Ingrese un DNI o RUC';
                        break;
                    case 8: case 11:
                        this.consultarDB();
                        break;
                    default:
                        // this.DatosServicio.alert = 'alert alert-danger';
                        this.DatosServicio.alert = 'badge badge-primary';
                        this.DatosServicio.mensaje = 'Documento inválido'
                        break;
                }
            },
            consultarDB(){
                var me = this;
                var url = '/persona/getPersona';

                me.DatosServicio.alert = 'badge badge-info';
                me.DatosServicio.mensaje = 'Consultado...';
                me.Carga.clase = 'spinner-border spinner-border-sm text-success';
                axios.get(url,{
                    params: {
                        'documento': me.DatosServicio.documento
                    }
                }).then(function(response){
		    
                    if(response.data.persona.length){//Si existe la persona en la db
                        me.DatosServicio.alert = '';
                        me.DatosServicio.mensaje = '';
                        me.Carga.clase = '';

                        const persona = response.data.persona[0];
                        me.DatosProveedor.id = persona.id;
                        if(persona.razon_social){//Es una EMPRESA
                            me.DatosServicio.tipo = 2;
                            me.DatosServicio.readonly = true;
                            me.DatosProveedor.documento = me.DatosServicio.documento;
                            me.DatosProveedor.razon_social = persona.razon_social;

                            me.DatosServicio.documento = '';
                        }else{//Es una PERSONA
                            me.DatosServicio.tipo = 1;
                            me.DatosProveedor.documento = me.DatosServicio.documento;
                            me.DatosProveedor.nombres = persona.nombres;
                            me.DatosProveedor.apellidos = persona.apellidos;
                            
                            me.DatosServicio.documento = '';
                        }
                    }else{//No esxiste la persona en la db
                        me.DatosProveedor.id = 0;
                        if(me.DatosServicio.documento.length == 8){
                            me.consultarDNI();
                        }else{
                            me.consultarRUC();
                        }
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            consultarRUC(){
                let me = this;
                let ruc = me.DatosServicio.documento;
                $.ajax({
                    type: 'GET',
                    url: "http://67.205.189.29:80/SunatPHP/demo.php",
                    data: "ruc="+ruc,
                    beforeSend(){
                        me.Carga.clase = 'spinner-border spinner-border-sm text-primary';
                        me.DatosServicio.alert = 'badge badge-info';
                        me.DatosServicio.mensaje = 'Consultado...';
                    },
                    success: function (data, textStatus, jqXHR) {
                        let empresa = JSON.parse(data);
                        console.log(empresa);
                        if(empresa.RUC || empresa.RazonSocial){
                            me.DatosServicio.documento = '';
                            me.DatosServicio.alert = '';
                            me.DatosServicio.mensaje = '';
                            me.DatosServicio.tipo = 2;
                            me.DatosServicio.readonly = false;
                            me.DatosProveedor.documento = empresa.RUC;
                            me.DatosProveedor.razon_social = empresa.RazonSocial;
                        }else{
                            me.DatosServicio.alert = 'badge badge-primary';
                            me.DatosServicio.mensaje = 'El RUC no existe';
                        }
                        me.Carga.clase = '';
                    }
                }).fail(function(){
                });
            },
            consultarDNI(){
                let me = this;
                let dni = me.DatosServicio.documento;
                $.ajax({
                    type: 'GET',
                    url: "http://67.205.189.29:80/Reniec/demo.php",
                    data: "dni="+dni,
                    beforeSend(){
                        me.Carga.clase = 'spinner-border spinner-border-sm text-primary';
                        me.DatosServicio.alert = 'badge badge-info';
                        me.DatosServicio.mensaje = 'Consultado...';
                    },
                    success: function (data, textStatus, jqXHR) {
                        let persona = JSON.parse(data);
                        console.log(persona);
                        if(persona.estado == true){
                            me.DatosServicio.documento = '';
                            me.DatosServicio.alert = '';
                            me.DatosServicio.mensaje = '';
                            me.DatosServicio.tipo = 1;
                            me.DatosProveedor.documento = persona.dni;
                            me.DatosProveedor.nombres = persona.nombres;
                            me.DatosProveedor.apellidos = persona.apellidos;
                        }else{
                            me.DatosServicio.alert = 'badge badge-primary';
                            me.DatosServicio.mensaje = 'El DNI no existe';
                        }
                        me.Carga.clase = '';
                    }
                }).fail(function(){
                });
            },
            abrirModalAgregar(){
                this.abrirModal(1, 'Registrar Abasto', 'Agregar', 'Cancelar', 'modal-xl modal-dialog-scrollable');
                if(!this.SelectAlmacen.length) this.selectAlmacen();
            },
            abrirModalVer(abasto = []){
                if(abasto['dni']){
                    this.DatosProveedor.documento = abasto['dni'];
                    this.DatosProveedor.nombres = abasto['proveedor_persona'];
                }else{
                    this.DatosProveedor.documento = abasto['ruc'];
                    this.DatosProveedor.razon_social = abasto['proveedor_empresa'];
                }
                this.Abasto.nombre_centro = abasto['nombre_centro'];
                this.Abasto.estado_envio = abasto['estado_envio'];
                this.Abasto.tipo_abasto = abasto['tipo_abasto'];
                this.Abasto.total_ver = abasto['total'];

                this.listarPagos(abasto['id']);
                this.listarDetallesAbasto(abasto['id']);
                this.abrirModal(2, 'Ver Abasto', '', 'Cerrar', 'modal-xl modal-dialog-scrollable');
            },
            abrirModal(numero, titulo, accion, cancelar, size){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.accion = accion;
                this.Modal.cancelar = cancelar;
                this.Modal.size = size;
            },
            accionar(accion){
                switch( accion ){
                    case 'Agregar': {
                        this.agregar();
                        break;
                    }
                    case 'Guardar': {
                        this.agregarPago();
                        break;
                    }
                }
            },
            validar(){
                this.Error.estado = 0;
                this.Error.mensaje = [];

                //Recorrere la lista de Material
                if(this.Modal.numero == 1){ //Modal agregar
                    if (this.DatosProveedor.documento == '') this.Error.mensaje.push('Debe ingresar datos del proveedor');
                    if (!this.ListaDetalleAbasto.length ) {
                        this.Error.mensaje.push("No existe ningun detalle de abasto");
                    }else if(this.Abasto.centro_to_id == 0){
                        this.Error.mensaje.push('Debe seleccionar el almacén receptor');
                    }else{
                        this.validarNegativos();
                    }

                    if(this.Abasto.tipo_abasto == 1){
                        if(this.Abasto.pagoInicial<0 || this.Abasto.pagoInicial == ''){
                            this.Error.mensaje.push('El pago inicial debe ser mayor o igual a 0')
                        }else if(this.Abasto.pagoInicial > this.Abasto.total){
                            this.Error.mensaje.push('El pago inicial no debe ser mayor al desembolso total')
                        }else if(this.Abasto.pagoInicial == this.Abasto.total){
                            this.Error.mensaje.push('El pago inicial es igual al desembolso total, se recomienda cambiarlo a una venta al contado')
                        }
                    }

                }else{ //Modal editar
                    if (this.Pago.monto == '' || this.Pago.monto <= 0 || this.Pago.monto > (this.Abasto.total_ver - this.getSumaPagos)) this.Error.mensaje.push('Debe ingresar un monto válido');
                }
                if ( this.Error.mensaje.length ) this.Error.estado = 1;
                return this.Error.estado;
            },
            validarNegativos(){
                for (let i = 0; i < this.ListaDetalleAbasto.length; i++) {
                    const detalle = this.ListaDetalleAbasto[i];
                    if(detalle.cantidad <1){
                        this.Error.mensaje.push('Las cantidades de los detalles deben ser mayores o iguales a 1');
                        break;
                    }else if(detalle.costo_abasto <= 0){
                        this.Error.mensaje.push('Los precios de los detalles debe ser mayores a 0');
                        break;
                    }
                }
            },
            agregar(){
                if ( this.validar() ) return;
                
                var me = this;
                axios.post('/abasto/agregar', {
                    //Datos del abasto
                    'id' : this.Abasto.id,
                    'total': this.Abasto.total,
                    'tipo': this.Abasto.tipo_abasto, 
                    'centro_to_id': this.Abasto.centro_to_id,
                    'pagoInicial': this.Abasto.pagoInicial,
                    'proveedor': this.DatosProveedor,
                    //Datos del detalle de abasto
                    'listaDetalleAbasto': this.ListaDetalleAbasto
                    
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: 'success',
                        title: 'El abasto se ha REGISTRADO correctamente',
                        showConfirmButton: false,
                        timer: 4500,
                        animation:false,
                        customClass:{
                            popup: 'animated bounceIn fast'
                        }
                    });
                }).catch(function(error){
                    console.log(error);
                });
            },
            listarPagos(id){
                let me = this;
                let url = '/abasto/getPagos';

                axios.get(url,{
                    params: {
                        'id': id
                    }
                }).then(function(response){
                    me.ListaPago = response.data;
                }).catch(function(error){
                    console.log(error);
                });
            },
            listarDetallesAbasto(id){
                let me = this;
                let url = '/abasto/getDetalles';

                axios.get(url,{
                    params: {
                        'id': id
                    }
                }).then(function(response){
                    me.ListaDetalleAbastoVer = response.data;
                }).catch(function(error){
                    console.log(error);
                });
            },
            abrirModalPagar(abasto = []){
                this.Abasto.id = abasto['id'];
                this.Abasto.total_ver = abasto['total'];
                this.listarPagos(abasto['id']);

                this.abrirModal(3, 'Realizar Pago', 'Guardar', 'Cancelar', '');
            },
            agregarListaPago(){//Agrega pagos a la lista de pagos
                if ( this.validar() ) return;
                let pago = {
                    monto: Number.parseFloat(this.Pago.monto).toFixed(2),
                    created_at: this.getFechaHoraHoy(),
                    color: 'table-success',
                    estado: 1 // 1: nuevo
                }
                this.ListaPago.push(pago);
                this.Pago.monto = '';
            },
            agregarPago(){
                let me = this;
                let pagosNuevos = me.filtrarPagosNuevos();
                axios.post('/pago/agregar', {
                    'idAbasto': this.Abasto.id,
                    'listaPagos': pagosNuevos
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: 'success',
                        title: 'El pago se ha REGISTRADO correctamente',
                        showConfirmButton: false,
                        timer: 4500,
                        animation:false,
                        customClass:{
                            popup: 'animated bounceIn fast'
                        }
                    });
                }).catch(function(error){
                    console.log(error);
                });
            },
            filtrarPagosNuevos(){
                let pagosNuevos = [];
                this.ListaPago.forEach(pago => {
                    if(pago.estado) pagosNuevos.push(pago);
                });
                return pagosNuevos;
            },
            cerrarModal(){
                this.Modal.numero = 0;
                this.Modal.estado = 0;
                this.Modal.mensaje = [];

                this.Error.estado = 0;
                this.Error.mensaje = [];

                this.DatosServicio.documento = '';
                this.DatosServicio.alert = '';
                this.DatosServicio.mensaje = '';

                this.Abasto.id = 0;
                this.Abasto.total = 0.00;
                this.Abasto.pagoInicial = '';
                this.Abasto.centro_to_id = 0;
                this.Abasto.tipo_abasto = 1;

                this.DatosServicio.tipo = 0;
                this.DatosProveedor.id = 0;
                this.DatosProveedor.documento = '';
                this.DatosProveedor.nombres = '';
                this.DatosProveedor.apellidos = '';
                this.DatosProveedor.razon_social = '';

                this.ListaPago = [];
                this.Pago.monto = '';

                this.ListaDetalleAbasto = [];
                this.ListaDetalleAbastoVer = [];
                this.BusquedaFiltro.texto = '';
            },
            anularAbasto(id){
                Swal.fire({
                    title: '¿Está seguro que desea ANULAR el abasto?',
                    type: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Si, anular',
                    cancelButtonText: 'Cancelar',
                    // reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.value) {
                        var me = this;
                
                        axios.put('/abasto/anular', {
                            'id' : id
                        }).then(function (response) {
                            me.listar();
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                type: 'success',
                                title: 'El abasto se ANULÓ correctamente',
                                showConfirmButton: false,
                                timer: 4500,
                                animation:false,
                                customClass:{
                                    popup: 'animated bounceIn fast'
                                }
                            });
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else if ( result.dismiss === Swal.DismissReason.cancel ) {
                    }
                });
            },
            getTitulo(titulo){
                var seleccionada = 0;

                for (let i = 0; i < this.Headers.length; i++) {
                    if ( titulo == this.Headers[i].titulo && this.Navegacion.ordenarPor == this.Headers[i].nombre ) {
                        seleccionada = 1;
                        break;
                    }
                }

                if ( seleccionada == 1 ) {
                    if ( this.Navegacion.orden == 'asc' ) {
                        titulo = titulo + ' ^';
                    } else {
                        titulo = titulo + ' v';
                    }
                }

                return titulo;
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
            },
            getFechaHoraHoy(){
                let n =  new Date();
                let y = n.getFullYear();
                let m = this.addCero(n.getMonth() + 1);
                let d = this.addCero(n.getDate());
                let h = n.getHours();
                let minu = n.getMinutes();
                let seg = n.getSeconds();
                let hoy =  y + '-' + m + '-' + d + ' ' + h + ':' + this.addCero(minu) + ':' + this.addCero(seg);
                return hoy;
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
            //Metodos de envios
            selectAlmacen(){
                var me = this;
                var url = '/centro/selectCentro?tipo=A';

                axios.get(url).then(function(response){
                    me.SelectAlmacen = response.data;
                }).catch(function(error){
                    console.log(error);
                });
            },
            fix(numero, data = ''){
                var fixed;

                switch (numero) {
                    case 0:
                        let fecha = data.split(' ')[0].split('-');
                        let hora = data.split(' ')[1].split(':');
                        let fecha_fixed = fecha[2]+'-'+fecha[1]+'-'+fecha[0];
                        let hora_fixed = (hora[0]>12?(hora[0]-12).toString().padStart(2, '0'):hora[0])+':'+hora[1]+':'+hora[2]+' '+(hora[0]>12?'pm':'am') ;
                        fixed = fecha_fixed+' '+hora_fixed;
                        break;
                }
                return fixed;
            }
        },
        mounted() {
            this.listar();
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
    .ec-table{
        overflow: scroll;
        height: 20rem;
    }
    .ec-table-modal{
        overflow: scroll;
        height: 21rem;
    }
    .ec-th{
        background-color: skyblue;
    }
</style>

