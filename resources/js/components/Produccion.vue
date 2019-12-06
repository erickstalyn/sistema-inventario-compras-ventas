<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Mis Producciones&nbsp;</span>
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="fas fa-hammer"></i>&nbsp; Nuevo
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
                        <option v-for="item in getYear(2016)" :key="item" :value="item" v-text="item"></option>
                    </select>
                </div>
                <div class="col-md-1"></div>
                <!-- <div class="col-md-1" align="right">
                    <label>N° filas:</label>
                </div> -->
                <div class="col-md-1">
                    N° filas:
                    <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas">
                        <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                    </select>
                </div>
                <!-- <div class="col-md-1"></div> -->
                <!-- <button type="button" class="btn btn-primary" @click="listar()">
                    <i class="fa fa-search"></i>&nbsp; Buscar
                </button> -->
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
                                <td v-text="produccion.fecha_inicio"></td>
                                <td v-text="produccion.fecha_programada"></td>
                                <td v-text="produccion.fecha_fin ? produccion.fecha_fin : '----------------'"></td>
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
                                    <button type="button"  title="Ver" class="btn btn-primary btn-sm">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    <!-- Sin iniciar -->
                                    <template v-if="produccion.fecha_inicio > getFechaHoy()">
                                        <button type="button"  title="Editar" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"  title="Eliminar" class="btn btn-danger btn-sm">
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
                                        <button type="button"  title="Editar" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"  title="Eliminar" class="btn btn-danger btn-sm">
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
            <div class="modal-dialog modal-dialog-centered animated bounceIn fast" :class="[Modal.numero != 3 ? 'modal-xl modal-dialog-scrollable' : '']">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="container-fluid">
                        <!-- Modal Numero 1 de AGREGAR-->
                            <div v-if="Modal.numero==1">
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
                                                        <tr v-for="(detalle, indice) in ListaDetalleProduccion" :key="detalle.id">
                                                            <td class="text-center">
                                                                <button type="button" title="Editar" class="btn btn-circle btn-outline-danger btn-sm" @click="quitarDetalle(indice)">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>
                                                            </td>
                                                            <td v-text="detalle.nombre"></td>
                                                            <td>
                                                                <input type="number" v-model="detalle.cantidad" class="form-control form-control-sm" min="1">
                                                            </td>
                                                            <td v-text="detalle.costo_produccion"></td>
                                                            <td >
                                                                <!-- s/ {{(detalle.costo_produccion * detalle.cantidad).toFixed(2)}} -->
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
                            <!-- <div v-if="Modal.numero==3">
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
                                    <label class="col-md-5 font-weight-bold" for="des">Seleccione almacén&nbsp;<span class="text-danger">*</span></label>
                                    <div class="col-md-7">
                                        <select  class="custom-select">
                                            <option value="" disabled>Almacen</option>
                                            <option v-for="item in SelectAlmacen" :key="item.id" :value="item.id" v-text="item.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>

                    <div class="modal-footer" v-if="permisoModalFooter">
                        <div class="row form-group col-md-12 d-flex justify-content-around">
                            <button type="button" @click="accionar(Modal.accion)" class="btn btn-success" v-text="Modal.accion"></button>
                            <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cancelar</button>
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
                    numero: 0,
                    estado: 0,
                    titulo: '',
                    accion: ''
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
                    // {nombre: 'Camperita Silmar Roja Grande', stock: 56},
                    // {nombre: 'Mochila Porta Verde mediana', stock: 100}
                ],
                ListaDetalleProduccion:[
                    // {nombre: 'Camperita Silmar Roja Grande', stock: 56},
                    // {nombre: 'Mochila Porta Verde mediana', stock: 100}
                ],
                //DATOS PARA ENVIAR UNA PRODUCCION
                SelectAlmacen: [],
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
                    // console.log(Number.parseFloat(detalle.costo_produccion * detalle.cantidad).toFixed(2));
                    // this.Produccion.total = this.Produccion.total + detalle.costo_produccion * detalle.cantidad;
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
                        +'&orden='+this.Navegacion.orden;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaProduccion = response.data.producciones.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            listarFiltro(){
                if(this.BusquedaFiltro.texto != ''){
                    let me = this;
                    let url = '/libreria/getProductoFiltrado?texto=' + this.BusquedaFiltro.texto;
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
                    if(this.ListaDetalleProduccion[i].id == producto.id){
                        incluido = true;
                        //adiciono uno más a la cantidad de este producto en la tabla de detalles
                        this.ListaDetalleProduccion[i].cantidad ++;
                        break;
                    }
                }

                if(!incluido){
                    let elProducto = {
                        id: producto.id,
                        nombre: producto.nombre,
                        cantidad: 1,
                        costo_produccion: producto.costo_produccion,
                        subtotal: 0.00
                    }
                    this.ListaDetalleProduccion.push(elProducto);
                }
            },
            quitarDetalle(indice){
                this.ListaDetalleProduccion.splice(indice,1);
            },
            agregar(){
                if ( this.validar() ) return;
                
                var me = this;
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
            // editar(){
            //     if ( this.validar() ) return;

            //     var me = this;
            //     axios.put('/material/editar', {
            //         'id' : this.Material.id,
            //         'nombre' : this.Material.nombre,
            //         'subtipo' : this.Material.subtipo,
            //         'unidad' : this.Material.unidad,
            //         'costo' : this.Material.costo,
            //     }).then(function(response){
            //         me.cerrarModal();
            //         me.listar();
            //         Swal.fire({
            //             position: 'top-end',
            //             toast: true,
            //             type: 'success',
            //             title: 'El Material se ha EDITADO correctamente',
            //             showConfirmButton: false,
            //             timer: 4500,
            //             animation:false,
            //             customClass:{
            //                 popup: 'animated bounceIn fast'
            //             }
            //         });
            //     }).catch(function(error){
            //         console.log(error);
            //     });
            // },
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
                this.abrirModal(1, 'Registrar Produccion', 'Agregar');
                // let inputFiltro = document.getElementById('filtroProducto');
                // inputFiltro.focus();
            },
            // abrirModalEnviar(){
            //     this.abrirModal(3, 'Enviar Produccion', 'Enviar');
            //     if(this.SelectAlmacen == 0) this.selectAlmacen();
            // },
            // abrirModalEditar(data = []){
            //     this.abrirModal(2, 'Editar Material', 'Editar');
                
            //     this.Material.id = data['id'];
            //     this.Material.nombre = data['nombre'];
            //     this.Material.subtipo = data['subtipo'];
            //     this.Material.unidad = data['unidad'];
            //     this.Material.costo  = data['costo'];

            //     //Lleno los campos de mi Material Original
            //     this.MaterialOrigen.id = data['id'];
            //     this.MaterialOrigen.nombre = data['nombre'];
            //     this.MaterialOrigen.subtipo = data['subtipo'];
            //     this.MaterialOrigen.unidad = data['unidad'];
            //     this.MaterialOrigen.costo  = data['costo'];
                
            //     //Verifico si el arreglo SelectUnidad esta vacia
            //     if(!this.SelectUnidad.length) this.selectUnidad();
            // },
            abrirModal(numero, titulo, accion){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.accion = accion;
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
                    // case 'Activar': {
                    //     this.activar();
                    //     break;
                    // }
                    // case 'Desactivar': {
                    //     this.desactivar();
                    //     break;
                    // }
                }
            },
            // getTitulo(titulo){
            //     var seleccionada = 0;

            //     for (let i = 0; i < this.Headers.length; i++) {
            //         if ( titulo == this.Headers[i].titulo && this.Navegacion.ordenarPor == this.Headers[i].nombre ) {
            //             seleccionada = 1;
            //             break;
            //         }
            //     }

            //     if ( seleccionada == 1 ) {
            //         if ( this.Navegacion.orden == 'asc' ) {
            //             titulo = titulo + ' ^';
            //         } else {
            //             titulo = titulo + ' v';
            //         }
            //     }

            //     return titulo;
            // },
            validar(){
                this.Error.estado = 0;
                this.Error.mensaje = [];

                //Recorrere la lista de Material
                if(this.Modal.numero == 1){
                    //Modal agregar
                    if ( !this.ListaDetalleProduccion.length ) this.Error.mensaje.push("No existe ningun detalle de producción"); 
                    if ( !this.Produccion.fecha_inicio || !this.Produccion.fecha_programada){
                        this.Error.mensaje.push('Debe ingresar una fecha de inicio y una fecha programada de la producción');
                    }else {

                        let arrayfechaInicio = this.Produccion.fecha_inicio.split('-');
                        let arrayFechaProgramada = this.Produccion.fecha_programada.split('-');
                        let fecha_inicio = new Date(parseInt(arrayfechaInicio[0]),parseInt(arrayfechaInicio[1]-1),parseInt(arrayfechaInicio[2]));
                        let fecha_programada = new Date(parseInt(arrayFechaProgramada[0]),parseInt(arrayFechaProgramada[1]-1),parseInt(arrayFechaProgramada[2]));

                        let hoyBase =  new Date();
                        // console.log(hoy.getFullYear());
                        // console.log(hoy.getMonth()+1);
                        // console.log(hoy.getDate());

                        let hoyFirme = new Date(hoyBase.getFullYear(), hoyBase.getMonth(), hoyBase.getDate());

                        if(fecha_inicio <  hoyFirme){//Aqui me quede
                            this.Error.mensaje.push('La fecha de inicio es incorrecta');
                        }else if(fecha_inicio >= fecha_programada){
                            this.Error.mensaje.push('La fecha programada debe ser después que la fecha de inicio de la producción');
                        }
                    }
                }else{
                    //Modal editar
                }
                if ( this.Error.mensaje.length ) this.Error.estado = 1;
                return this.Error.estado;
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
            },
            // selectUnidad(){
            //     var me = this;
            //     var url = '/material/selectUnidad';

            //     axios.get(url).then(function(response){
            //         me.SelectUnidad = response.data;
            //     }).catch(function(error){
            //         console.log(error);
            //     });
            // },
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
                var url = '/produccion/selectAlmacen';

                axios.get(url).then(function(response){
                    me.SelectAlmacen = response.data;
                }).catch(function(error){
                    console.log(error);
                });
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

