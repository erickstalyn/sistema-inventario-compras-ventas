<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-hammer"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Mis Envios recibidos&nbsp;</span>
                <!-- <button type="button" class="btn btn-danger">
                    <i class="far fa-file-pdf"></i>&nbsp; PDF
                </button> -->
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div style="width: 8rem;" class="mr-1">
                    <div class="input-group"> 
                        <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.estado">
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

            <!-- Listado -->
            <div v-if="ListaEnvioRecibido.length" class="">
                <!-- Tabla -->
                <div class="ec-table overflow-auto">
                    <table class="table table-borderless table-sm text-gray-900">
                        <thead>
                            <tr class="table-info">
                                <th>Origen</th>
                                <th class="text-center">Fecha en que se envió</th>
                                <th class="text-center">Fecha en que se recibó</th>
                                <th>Estado</th>
                                <th class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="envio in ListaEnvioRecibido" :key="envio.id" >
                                <td v-text="!envio.abasto_id? envio.centro_origen : 'Administración'"></td>
                                <td v-text="formatearFecha(envio.fecha_envio)" class="text-center"></td>
                                <td v-text="envio.fecha_cambio ? formatearFecha(envio.fecha_cambio) : '---'" class="text-center"></td>
                                <td>
                                    <div v-if="envio.estado == 0">
                                        <span class="badge badge-primary">En espera</span>
                                    </div>
                                    <div v-else-if="envio.estado == 1">
                                        <span class="badge badge-success">Recibido</span>
                                    </div>
                                    <!-- <div v-else="">
                                        <span class="badge badge-danger">Rechazado</span>
                                    </div> -->
                                </td>
                                <td class="text-center">
                                    <button type="button" title="Ver" class="btn btn-sm btn-primary" @click="abrirModalVer(envio)">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    <template v-if="envio.estado == 0">
                                        <button type="button"  title="Recibir" class="btn btn-sm btn-outline-warning" @click="accion(envio)">
                                            <i class="fas fa-sort-amount-down-alt"></i>
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

        <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
            <div class="modal-dialog modal-dialog-centered animated bounceIn fast" :class="Modal.size">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div v-if="Modal.numero==2">
                                <div class="row shadow bg-white rounded p-2">
                                    <div class="col-md-12 ml-auto container">
                                        <div class="row">
                                            <span class="font-weight-bold">LISTA DE ITEMS</span>
                                        </div>
                                        <div class="row form-group ec-table-modal overflow-auto">
                                            <table class="table tableless table-striped table-sm text-gray-900">
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
                ListaEnvioRecibido: [],
                EnvioRecibido: {
                    id: 0,
                    abasto_id: 0,
                    centro_origen: '',
                    estado: 0,
                    fecha_envio: '',
                    fecha_cambio: '',
                    idCentro: $('meta[name="idCentro"]').attr('content')
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
                    ordenarPor: 'envio.id',
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
                // ListaDetalleProduccion:[
                // ],
                ListaDetalleEnvio: [],
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
            getTotal: function(){
                let total = 0;
                this.ListaDetalleEnvio.forEach( detalle => {
                    total = total + Number.parseInt(detalle.cantidad);
                });
                return total;
            }
        },
        methods: {
            listar(page = 1, ordenarPor = ''){
                if ( ordenarPor == this.Navegacion.ordenarPor ) {
                    this.Navegacion.orden = (this.Navegacion.orden == 'asc' ? 'desc' : 'asc');
                } else {
                    this.Navegacion.ordenarPor = (ordenarPor != '' ? ordenarPor: this.Navegacion.ordenarPor);
                }
                this.Paginacion.currentPage = page==1?1:page;

                var url = '/envioRecibido?page='+this.Paginacion.currentPage
                        +'&estado='+this.Busqueda.estado
                        +'&idCentro='+this.EnvioRecibido.idCentro
                        +'&texto='+this.Busqueda.texto
                        +'&filas='+this.Busqueda.filas
                        +'&dia='+this.Busqueda.dia
                        +'&mes='+this.Busqueda.mes
                        +'&year='+this.Busqueda.year
                        +'&ordenarPor='+this.Navegacion.ordenarPor
                        +'&orden='+this.Navegacion.orden;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaEnvioRecibido = response.data.envios.data;
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
            accion(envio = []){
                this.EnvioRecibido.id = envio['id'];
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                title: '¿La mercadería LLEGÓ al centro?',
                text: "Por favor, revise que toda la mercadería haya llegado completa",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'SI, la mercadería llegó',
                cancelButtonText: 'Cancelar',
                }).then((result) => {
                if (result.value) {
                    var me = this;
                    axios.put('/envioRecibido/setEstado', {
                        'id' : me.EnvioRecibido.id,
                        'estado' : 1
                    }).then(function(response){
                        me.cerrarModal();
                        me.listar();
                        swalWithBootstrapButtons.fire(
                        'Aceptado!',
                        'El envío ha sido aceptado, revise su inventario de productos',
                        'success'
                        )
                    }).catch(function(error){
                        console.log('Soy el error: ' + error);
                    });
                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // var me = this;
                    // axios.put('/envioRecibido/setEstado', {
                    //     'id' : me.EnvioRecibido.id,
                    //     'estado' : 2
                    // }).then(function(response){
                    //     me.cerrarModal();
                    //     me.listar();
                    //     swalWithBootstrapButtons.fire(
                    //     'Rechazado!',
                    //     'El envío ha sido rechazado, por favor infórmeselo al administrador',
                    //     'danger'
                    //     )
                    // }).catch(function(error){
                    //     console.log('Soy el error: ' + error);
                    // });
                }
                })
            },
            abrirModalVer(envio = []){
                this.EnvioRecibido.id = envio['id'];
                this.EnvioRecibido.centro_origen = envio['centro_origen'];
                this.EnvioRecibido.estado = envio['estado'];
                this.EnvioRecibido.abasto_id = envio['abasto_id'];

                this.EnvioRecibido.abasto_id ? this.list(2) : this.list(1);
                this.abrirModal(2, 'Ver Envío', '', 'Cerrar', '');
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
                this.BusquedaFiltro.texto = '';
            },
            list(numero){
                let me = this;
                let url;
                switch (numero) {
                    case 1:
                        // let me = this;
                        url = '/envioRealizado/getDetalles';

                        axios.get(url,{
                            params: {
                                'id': me.EnvioRecibido.id
                            }
                        }).then(function(response){
                            me.ListaDetalleEnvio = response.data;
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                    case 2:
                        console.log('soy el 2');
                        // let me = this;
                        url = '/abasto/getDetalles';

                        axios.get(url,{
                            params: {
                                'id': me.EnvioRecibido.abasto_id
                            }
                        }).then(function(response){
                            me.ListaDetalleEnvio = response.data;
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                }
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
                }
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
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

