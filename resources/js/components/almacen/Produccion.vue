<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-hammer"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Mis Producciones&nbsp;</span>
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    Nuevo
                </button>&nbsp;
                <button type="button" class="btn btn-danger">
                    <i class="far fa-file-pdf"></i>&nbsp; PDF
                </button>
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-2">
                    <div class="input-group"> 
                        <select class="custom-select text-gray-900" v-model="Busqueda.estado">
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

            <!-- Listado -->
            <div v-if="ListaProduccion.length" class="">
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
                                    <button type="button"  title="Ver" class="btn btn-primary btn-sm" @click="abrirModalVer(produccion)">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    <!-- Sin iniciar -->
                                    <template v-if="produccion.fecha_inicio > getFechaHoy()">
                                        <button type="button"  title="Editar" class="btn btn-warning btn-sm" @click="abrirModalEditar(produccion)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"  title="Eliminar" class="btn btn-danger btn-sm" @click="anularProduccion(produccion.id)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </template>
                                    <!-- Finalizado -->
                                    <template v-else-if="produccion.fecha_fin">
                                        <!-- <button type="button" title="Enviar" class="btn btn-secondary btn-sm" @click="abrirModalEnviar()">
                                            <i class="fas fa-plane"></i>
                                        </button> -->
                                    </template>
                                    <!-- En proceso -->
                                    <template v-else="">
                                        <button type="button"  title="Editar" class="btn btn-warning btn-sm" @click="abrirModalEditar(produccion)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"  title="Eliminar" class="btn btn-danger btn-sm" @click="anularProduccion(produccion.id)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <button type="button"  title="Finalizar" class="btn btn-outline-success btn-sm" @click="finalizar(produccion)">
                                            <i class="fas fa-check"></i>
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
                            <div v-if="Modal.numero==1 || Modal.numero == 2">
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
                                <div class="row shadow bg-white rounded p-2">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <h5 class="font-weight-bold">Productos</h5>
                                        </div>
                                        <div class="row">
                                            <div class="input-group"> 
                                                <input type="search" class="form-control form-control-sm" v-model="BusquedaFiltro.texto" @keyup.enter="listarFiltro()" id="filtroProducto" autofocus placeholder="Producto,marca,modelo,tamaño,color">
                                                <button type="button" class="btn btn-sm btn-primary" @click="listarFiltro()">
                                                    <i class="fa fa-search"></i>&nbsp; Buscar
                                                </button>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row form-group ec-table overflow-auto">
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
                                    </div>
                                    <div class="col-md-8 ml-auto container">
                                        <div class="row">
                                            <h5 class="font-weight-bold">Lista de items</h5>
                                        </div>
                                        <div class="row form-group ec-table-modal overflow-auto">
                                            <div v-if="ListaDetalleProduccion.length">
                                                <table class="table tableless table-striped table-sm text-gray-900">
                                                    <thead>
                                                        <tr class="table-success">
                                                            <th class="text-center" style="width: 3rem;">Quitar</th>
                                                            <th>Nombre</th>
                                                            <th style="width: 5rem;">Cant.</th>
                                                            <th>Costo Unit.</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="detalle in ListaDetalleProduccionFiltrada" :key="detalle.producto_id">
                                                            <td class="text-center">
                                                                <button type="button" title="Quitar" class="btn btn-circle btn-outline-danger btn-sm" @click="quitarDetalle(detalle.producto_id)">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>
                                                            </td>
                                                            <td v-text="detalle.nombre_producto"></td>
                                                            <td>
                                                                <input type="number" v-model="detalle.cantidad" class="form-control form-control-sm" min="1">
                                                            </td>
                                                            <td v-text="detalle.costo_produccion"></td>
                                                            <td >
                                                                {{detalle.subtotal = (detalle.costo_produccion * detalle.cantidad).toFixed(2)}}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div v-else>
                                                <br>
                                                <h5>Sin detalles de producción</h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                            </div>
                                            <div class="col-md-4">
                                                Inversión total: s/ {{getTotal}}
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
                            <div v-if="Modal.numero == 3">
                                <div class="row shadow bg-white rounded p-2">
                                    <div class="col-md-12 ml-auto container">
                                        <div class="row">
                                            <h5 class="font-weight-bold">Lista de items</h5>
                                        </div>
                                        <div class="row form-group ec-table-modal overflow-auto">
                                            <table class="table tableless table-striped table-sm text-gray-900">
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
                                        Fecha de inicio&nbsp;
                                        <input type="date" class="form-control" v-model="Produccion.fecha_inicio" readonly>
                                    </div>
                                    <div class="col-md-5 form-inline">
                                        Fecha prog. finalización&nbsp;
                                        <input type="date" class="form-control" v-model="Produccion.fecha_programada" readonly>
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
                ListaProduccion: [],
                Produccion: {
                    id: 0,
                    fecha_inicio : '',
                    fecha_programada : '',
                    fecha_fin: '',
                    total : 0.00,
                    // almacen_id: document.getElementById('idCentro').value
                    almacen_id: $('meta[name="idCentro"]').attr('content')
                },
                SelectUnidad: [],
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
                    numero: 0, // 1: Agregar, 2: Editar, 3: Ver más
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
                    ordenarPor: 'id',
                    orden: 'desc'
                },

                //datos de errores
                Error: {
                    estado: 0,
                    mensaje: []
                },
                //DATOS PARA AGREGAR UNA PRODUCCION
                BusquedaFiltro:{
                    texto: ''
                },
                ListaProducto:[
                ],
                ListaDetalleProduccion:[
                ],
                ListaDetalleProduccionFiltrada: [],
                //DATOS PARA ENVIAR UNA PRODUCCION
                SelectAlmacen: [],

                //datos de rutas
                Ruta: {
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
            permisoModalFooter: function(){
                if ( this.Modal.numero == 1 ) return true;
                if ( this.Modal.numero == 2 ) return true;
                if ( this.Modal.numero == 3 ) return true;

                return false;
            },
            selectUnidadFiltrado: function(){
                let selectUnidadFiltrado = [];
                this.SelectUnidad.forEach(unidad => {
                    if(unidad.subtipo == this.Material.subtipo){
                        selectUnidadFiltrado.push(unidad);
                        // console.log('Ingrese al if');
                    }
                });
                return selectUnidadFiltrado;
            },
            getTotal: function(){
                this.Produccion.total = 0.00;
                this.ListaDetalleProduccion.forEach( detalle => {
                    this.Produccion.total = this.Produccion.total + detalle.costo_produccion * detalle.cantidad;
                });
                return (this.Produccion.total).toFixed(2);
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

                var url = '/produccion?page='+this.Paginacion.currentPage
                        +'&estado='+this.Busqueda.estado
                        +'&texto='+this.Busqueda.texto
                        +'&filas='+this.Busqueda.filas
                        +'&dia='+this.Busqueda.dia
                        +'&mes='+this.Busqueda.mes
                        +'&year='+this.Busqueda.year
                        +'&ordenarPor='+this.Navegacion.ordenarPor
                        +'&orden='+this.Navegacion.orden
                        +'&idAlmacen='+this.Produccion.almacen_id;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaProduccion = response.data.producciones.data;
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
                    let url = this.Ruta.produccion+'/getProductoFiltrado?'
                                    +'texto='+this.BusquedaFiltro.texto
                                    +'&centro_id='+$('meta[name="idCentro"]').attr('content');

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
                for (let i = 0; i < this.ListaDetalleProduccion.length; i++) {
                    if(this.ListaDetalleProduccion[i].producto_id == producto.id){
                        incluido = true;
                        
                        if(this.ListaDetalleProduccion[i].estado == 0){
                            this.ListaDetalleProduccion[i].cantidad = 1;
                            this.ListaDetalleProduccion[i].estado = 1;
                        }else{
                            this.ListaDetalleProduccion[i].cantidad ++;
                        }
                        this.filtrarDetalleProduccion();
                        break;
                    }
                }

                if(!incluido){
                    let detalleProduccion = {
                        id: 0,
                        producto_id: producto.id,
                        nombre_producto: producto.nombre,
                        cantidad: 1,
                        costo_produccion: producto.costo_produccion,
                        subtotal: 0.00,
                        estado: 1 // 1: Muestra, 0: Se elimino
                    }
                    this.ListaDetalleProduccion.push(detalleProduccion);
                    this.filtrarDetalleProduccion();
                }
            },
            quitarDetalle(producto_id){
                for (let i = 0; i < this.ListaDetalleProduccion.length; i++) {
                    // let detalle = this.ListaDetalleProduccion[i];
                    if(this.ListaDetalleProduccion[i].producto_id == producto_id){
                        
                        if(this.ListaDetalleProduccion[i].id == 0){
                            this.ListaDetalleProduccion.splice(i,1);
                        }else{
                            this.ListaDetalleProduccion[i].estado = 0;
                        }
                        this.filtrarDetalleProduccion();
                        break;
                    }
                }
            },
            filtrarDetalleProduccion: function(){
                this.ListaDetalleProduccionFiltrada = [];
                for (let i = 0; i < this.ListaDetalleProduccion.length; i++) {
                    if ( this.ListaDetalleProduccion[i].estado == 1) {
                        this.ListaDetalleProduccionFiltrada.push(this.ListaDetalleProduccion[i]);
                    }
                }
            },
            agregar(){
                if ( this.validar(1) ) return;
                let me = this;
                axios.post('/produccion/agregar', {
                    //Datos de la produccion
                    'total' : this.Produccion.total,
                    'fecha_inicio' : this.Produccion.fecha_inicio,
                    'fecha_programada' : this.Produccion.fecha_programada,
                    'almacen_id': this.Produccion.almacen_id,
                    //Datos del detalle de venta
                    'listaDetalleProduccion' : this.ListaDetalleProduccion
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: 'success',
                        title: 'La produccion se ha REGISTRADO correctamente',
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
            editar(){
                if ( this.validar(1) ) return;
                if ( this.validar(2) ) return;
                let me = this;
                axios.put('/produccion/editar', {
                    //Datos de la produccion
                    'id' : this.Produccion.id,
                    'total' : this.Produccion.total,
                    'fecha_inicio' : this.Produccion.fecha_inicio,
                    'fecha_programada' : this.Produccion.fecha_programada,
                    'almacen_id': this.Produccion.almacen_id,
                    //Datos del detalle de venta
                    'listaDetalleProduccion' : this.ListaDetalleProduccion
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: 'success',
                        title: 'La produccion se ha EDITADO correctamente',
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
            validar(numero){
                this.Error.estado = 0;
                this.Error.mensaje = [];
                switch(numero){
                    case 1://Modal agregar y editar
                        if ( !this.ListaDetalleProduccion.length ) {
                            this.Error.mensaje.push('No existe ningun detalle de producción');
                        }else{//Valido si hay negativos en las cantidades de los detalles de producción
                            for (let i = 0; i < this.ListaDetalleProduccion.length; i++) {
                                const detalle = this.ListaDetalleProduccion[i];
                                if(detalle.cantidad<1){
                                    this.Error.mensaje.push('Las cantidades de los detalles deben ser mayores o iguales a 1'); break;
                                }
                            }
                        }
                        if ( !this.Produccion.fecha_inicio || !this.Produccion.fecha_programada){
                            this.Error.mensaje.push('Debe ingresar una fecha de inicio y una fecha programada de la producción');
                        }else {
                            let arrayfechaInicio = this.Produccion.fecha_inicio.split('-');
                            let arrayFechaProgramada = this.Produccion.fecha_programada.split('-');
                            let fecha_inicio = new Date(parseInt(arrayfechaInicio[0]),parseInt(arrayfechaInicio[1]-1),parseInt(arrayfechaInicio[2]));
                            let fecha_programada = new Date(parseInt(arrayFechaProgramada[0]),parseInt(arrayFechaProgramada[1]-1),parseInt(arrayFechaProgramada[2]));

                            let hoyBase =  new Date();
                            let hoyFirme = new Date(hoyBase.getFullYear(), hoyBase.getMonth(), hoyBase.getDate());

                            if(fecha_inicio <  hoyFirme){//Aqui me quede
                                this.Error.mensaje.push('La fecha de inicio es incorrecta');
                            }else if(fecha_inicio >= fecha_programada){
                                this.Error.mensaje.push('La fecha programada debe ser después que la fecha de inicio de la producción');
                            }
                        }
                        break;
                    case 2:
                        let found = 0;
                        for (let i = 0; i < this.ListaDetalleProduccion.length; i++) {
                            const detalle = this.ListaDetalleProduccion[i];
                            if(detalle.estado == 1) {
                                found = 1; break;
                            }
                        }
                        if(!found) this.Error.mensaje.push('No existe ningun detalle de producción');
                        break;
                }
                if ( this.Error.mensaje.length ) this.Error.estado = 1;
                return this.Error.estado;
            },
            finalizar(produccion = []){
                this.Produccion.id = produccion['id'];
                Swal.fire({
                    title: '¿Esta seguro de FINALIZAR la producción ',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, finalizar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.value) {
                        var me = this;
                
                        axios.put('/produccion/finalizar', {
                            'id' : me.Produccion.id
                        }).then(function (response) {
                            me.listar();
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                type: 'success',
                                title: 'La producción se ha FINALIZADO correctamente',
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
            abrirModalAgregar(){
                this.abrirModal(1, 'Registrar Produccion', 'Agregar', 'Cancelar', 'modal-xl modal-dialog-scrollable');
            },
            abrirModalEditar(produccion = []){

                this.Produccion.id = produccion['id'];
                this.Produccion.total = produccion['total'];
                this.Produccion.fecha_inicio = produccion['fecha_inicio'];
                this.Produccion.fecha_programada = produccion['fecha_programada'];
                this.listarDetallesProduccion(produccion['id']);

                this.abrirModal(2, 'Editar Producción', 'Editar', 'Cancelar', 'modal-xl modal-dialog-scrollable');
            },
            abrirModalVer(produccion = []){
                this.Produccion.id = produccion['id'];
                this.Produccion.total = produccion['total'];
                this.Produccion.fecha_inicio = produccion['fecha_inicio'];
                this.Produccion.fecha_programada = produccion['fecha_programada'];
                this.listarDetallesProduccion(produccion['id']);
                this.abrirModal(3, 'Ver Producción', '', 'Cerrar', 'modal-xl modal-dialog-scrollable');
            },
            abrirModal(numero, titulo, accion, cancelar, size){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.accion = accion;
                this.Modal.cancelar = cancelar;
                this.Modal.size = size;
            },
            anularProduccion(id){
                Swal.fire({
                    title: '¿Está seguro que desea ANULAR la producción?',
                    type: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Si, anular',
                    cancelButtonText: 'Cancelar',
                    // reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.value) {
                        var me = this;
                
                        axios.put('/produccion/anular', {
                            'id' : id
                        }).then(function (response) {
                            me.listar();
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                type: 'success',
                                title: 'La producción se ANULÓ correctamente',
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
            cerrarModal(){
                this.Modal.numero = 0;
                this.Modal.estado = 0;
                this.Modal.mensaje = [];

                this.Error.estado = 0;
                this.Error.mensaje = [];

                this.Produccion.id = 0;
                this.Produccion.total = 0.00;
                this.Produccion.fecha_inicio = '';
                this.Produccion.fecha_programada = '';

                this.ListaDetalleProduccion = [];
                this.BusquedaFiltro.texto = '';

            },
            accionar(accion){
                switch( accion ){
                    case 'Agregar': {
                        this.agregar();
                        break;
                    }
                    case 'Editar': {
                        this.editar();
                        break;
                    }
                    case 'Finalizar': {
                        this.finalizar();
                        break;
                    }
                }
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
            },
            listarDetallesProduccion(id){
                let me = this;
                let url = '/produccion/getDetalles';

                axios.get(url,{
                    params: {
                        'id': id
                    }
                }).then(function(response){
                    me.ListaDetalleProduccion = response.data;
                    console.log('Ejecute la consulta satisfactoriamente');
                    me.ListaDetalleProduccion.forEach(detalle => {
                        detalle.estado = 1;
                    });
                    if(me.Modal.numero == 2) me.filtrarDetalleProduccion();
                }).catch(function(error){
                    console.log(error);
                });
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
                let max = n.getFullYear() +1;
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

