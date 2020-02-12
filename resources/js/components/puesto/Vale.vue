<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Vales&nbsp;</span>
                <!-- <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="fas fa-hammer"></i>&nbsp; Nuevo
                </button>&nbsp; -->
                <!-- <button type="button" class="btn btn-danger" @click="generatePdf()">
                    <i class="far fa-file-pdf"></i>&nbsp; PDF
                </button> -->
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-8">
                    <div class="input-group"> 
                        <select class="col-md-3 custom-select text-gray-900" v-model="Busqueda.estado">
                            <option value="3">Todos</option>
                            <option value="1">Usado</option>
                            <option value="2">Sin usar</option>
                        </select>
                        <input type="search" class="form-control" v-model="Busqueda.texto" @keyup.enter="listar()" placeholder="Buscar por dni, ruc, nombres , razón social">
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
                    <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas">
                        <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                    </select>
                </div>
            </div>

            <!-- Listado -->
            <div v-if="ListaVale.length" class="table-responsive">
                <!-- Tabla -->
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
                                <td class="text-center" v-text="fix(0, vale.created_at)"></td>
                                <td class="text-center" v-text="vale.updated_at ? vale.updated_at : '---'"></td>
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
                                        <button type="button" @click="abrirModalVerVenta(vale.venta_generada_id, vale)" title="VER VENTA ORIGEN" class="btn btn-outline-primary btn-sm">
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
            <div v-else>
                <h5>No se han encontrado resultados</h5>
            </div>

        </div>

        <!-- Modales de Agregar/Editar/Pagar -->
        <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animated bounceIn fast" :class="Modal.size">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Modal Numero 2 de VER-->
                        <div v-if="Modal.numero==2" class="input-group">
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
                                                            <td class="text-center" v-text="fix(0, pago.created_at)"></td>
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
                                                        <td class="text-right" v-text="detalle.fallidos==null?'---':detalle.fallidos"></td>
                                                        <td class="text-right" v-text="detalle.cantidad"></td>
                                                        <td class="text-right" v-text="detalle.precio"></td>
                                                        <td class="text-right" v-text="detalle.subtotal"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-12 input-group mt-2">
                                            <div class="col-md-6">
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
                                    <div class="shadow rounded pt-2 bg-success" style="border: 1px solid; height: 7rem;">
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
                                    </div>
                                </div>
                                <div class="container-small col-md-3">
                                    <div class="shadow rounded pt-3 bg-success" style="border: 1px solid; height: 18rem;">
                                        <div class="col-md-12 form-group">
                                            <label class="font-weight-bold h5">BENEFICIOS</label>
                                        </div>
                                        <div v-if="ValeU.monto!=null" class="col-md-12 form-group p-0">
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-12 d-flex justify-content-center">-- VALE GENERADO --</label>
                                            </div>
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-6 font-weight-bold">Monto</label>
                                                <label class="col-md-6 text-white" v-text="'S/. '+ValeU.monto"></label>
                                            </div>
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-6 font-weight-bold">Fecha</label>
                                                <label class="col-md-6 text-white" v-text="fix(7, ValeU.created_at)"></label>
                                            </div>
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-6 font-weight-bold">Hora</label>
                                                <label class="col-md-6 text-white" v-text="fix(8, ValeU.created_at)"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-small col-md-3">
                                    <div class="shadow rounded pt-3 bg-success" style="border: 1px solid; height: 18rem;">
                                        <div class="col-md-12 form-group">
                                            <label class="font-weight-bold h5">DESCUENTOS</label>
                                        </div>
                                        <div v-if="ValeU.monto!=null" class="col-md-12 form-group p-0">
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-12 d-flex justify-content-center">-- VALE USADO --</label>
                                            </div>
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-6 font-weight-bold">Monto</label>
                                                <label class="col-md-6 text-white" v-text="'S/. '+ValeU.monto"></label>
                                            </div>
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-6 font-weight-bold">Fecha</label>
                                                <label class="col-md-6 text-white" v-text="fix(7, ValeU.created_at)"></label>
                                            </div>
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-6 font-weight-bold">Hora</label>
                                                <label class="col-md-6 text-white" v-text="fix(8, ValeU.created_at)"></label>
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
                    </div>

                    <div class="modal-footer">
                        <div class="row col-md-12 d-flex justify-content-around">
                            <button type="button" v-if="Modal.btnA" @click="accionar()" class="btn btn-success" v-text="Modal.btnA"></button>
                            <button type="button" v-if="Modal.btnC" @click="cerrarModal()" class="btn btn-secondary" v-text="Modal.btnC"></button>
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
                ListaVale: [],
                Vale: {
                    id: null,
                    monto: null,
                    cliente: null,
                    created_at: null,
                    updated_at: null,
                    venta_codigo: null
                },
                Venta: {
                    id: null,
                    codigo: null,
                    centro_id: null,
                    total: null,
                    total_start: null,
                    total_faltante: null,
                    total_descuento: null,
                    total_descuento_start: null,
                    total_venta: null,
                    tipo_pago: null, // 1: contado, 2: credito
                    tipo_entrega: null, // 1: prepago, 2: postpago
                    tipo_precio: null, // 1: al por menor, 2: al por mayor
                    created_at : null,
                },
                Cliente:{
                    id: null,
                    documento: null,
                    dni: null,
                    ruc: null,
                    nombres: null,
                    apellidos: null,
                    razon_social: null,
                    tipo: null,
                    search: null,
                    required: null,
                    trash: null
                },
                ListaDetalle: null,
                ListaPago: null,
                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 3,
                    filas: 5
                },

                ValeU: {
                    id: null,
                    monto: null,
                    venta_usada_id: null,
                    created_at: null
                },
                //datos de modales
                Modal: {
                    numero: null, // 1 Agregar, 2 Ver, 3 Pagar
                    estado: 0,
                    titulo: null,
                    size: null,
                    btnA: null,
                    btnC: null
                },
                Step: {
                    number: null
                },
                //datos de errores
                Error: {
                    estado: null,
                    numero: null,
                    mensaje: null
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
                    // ordenarPor: 'id',
                    // orden: 'desc'
                },

                //datos de errores
                Error: {
                    estado: 0,
                    mensaje: []
                },
                Ruta: {
                    vale: '/vale',
                    ventaWithDetalle: '/venta/getVentaWithDetalle',
                    serverPhp: 'http://127.0.0.1:8000',
                    detalle_venta: '/detalle_venta',
                    pago: '/pago',
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
            }
        },
        methods: {
            listar(page = 1){
                this.Paginacion.currentPage = page==1?1:page;

                var url = this.Ruta.vale + '?page='+this.Paginacion.currentPage
                        +'&estado='+this.Busqueda.estado
                        +'&texto='+this.Busqueda.texto
                        +'&centro_id='+$('meta[name="idCentro"]').attr('content')
                        +'&filas='+this.Busqueda.filas;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaVale = response.data.vales.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            abrirModalVerVenta(venta_id, data){
                let url = this.Ruta.ventaWithDetalle + '?venta_id='+venta_id;
                let me = this;
                axios.get(url).then(function (response) {
                    let venta = response.data.venta;
                    me.Venta.id = venta.id;
                    me.Venta.total = venta.total;
                    me.Venta.total_venta = venta.total_venta;
                    me.Venta.total_descuento = venta.total_descuento;
                    me.Venta.total_faltante = venta.total_faltante==null?0:venta.total_faltante;
                    me.Venta.tipo_pago = venta.tipo.charAt(0);
                    me.Venta.tipo_entrega = venta.tipo.charAt(1);
                    me.Venta.tipo_precio = venta.tipo.charAt(2);
                    me.Venta.created_at = venta.created_at;

                    // me.Cliente.id = data[cliente_id];
                    me.Cliente.dni = data['dni'];
                    me.Cliente.nombres = data['nombres'];
                    me.Cliente.apellidos = data['apellidos'];
                    me.Cliente.ruc = data['ruc'];
                    me.Cliente.razon_social = data['razon_social'];
                    me.Cliente.tipo = data['tipo'];

                    me.ListaPago = [];
                    me.ListaDetalle = []; 

                    me.abrirModal(2, 'Ver Venta', 'modal-xl', '', 'Cerrar');

                    // me.fix('detalle_venta', venta.get_detalle_venta);
                    me.fix('detalle_venta', response.data.detalle);
                    me.list('pago');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            abrirModal(numero, titulo, size, btnA, btnC){
                this.Step.number = 0;

                this.Error.numero = 0;
                this.Error.estado = 0;
                this.Error.mensaje = [];
                
                this.Modal.titulo = titulo;
                this.Modal.size = size;
                this.Modal.btnA = btnA;
                this.Modal.btnC = btnC;
                this.Modal.numero = numero;
                this.Modal.estado = 1;
            },
            cerrarModal(){
                this.Modal.estado = 0;
                this.Modal.numero = null;
                this.Modal.titulo = null;
                this.Modal.size = null;
                this.Modal.btnA = null;
                this.Modal.btnC = null;
                
                this.Step.number = null;

                this.Error.numero = null;
                this.Error.estado = null;
                this.Error.mensaje = null;

                this.Venta.id = null;
                this.Venta.total_venta = null;
                this.Venta.total_venta_start = null;
                this.Venta.total_descuento = null;
                this.Venta.total = null;
                this.Venta.total_start = null;
                this.Venta.total_faltante = null;
                this.Venta.total_faltante_start = null;
                this.Venta.centro_id = null;
                this.Venta.tipo = null;
                this.Venta.tipo_pago = null;
                this.Venta.tipo_entrega = null;
                this.Venta.tipo_precio = null;
                this.Venta.created_at = null;

                this.Cliente.id = null;
                this.Cliente.documento = null;
                this.Cliente.dni = null;
                this.Cliente.nombres = null;
                this.Cliente.apellidos = null;
                this.Cliente.ruc = null;
                this.Cliente.razon_social = null;
                this.Cliente.tipo = null;
                this.Cliente.search = null;
                this.Cliente.trash = null;

                this.ListaPago = null;
                // this.Pago.monto = null;

                this.ListaDetalle = null;
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
            },
            generatePdf(){
                window.open(this.Ruta.serverPhp + '/material/generatePdf','_blank');
            },
            fix(numero, data = ''){
                var fixed;

                let fecha, hora, fecha_fixed, hora_fixed;

                switch (numero) {
                    case 0:
                        fecha = data.split(' ')[0].split('-');
                        hora = data.split(' ')[1].split(':');
                        fecha_fixed = fecha[2]+'/'+fecha[1]+'/'+fecha[0];
                        hora_fixed = (hora[0]>12?(hora[0]-12).toString().padStart(2, '0'):hora[0])+':'+hora[1]+' '+(hora[0]>12?'pm':'am') ;
                        fixed = fecha_fixed+' '+hora_fixed;
                        break;
                    case 'tipo_pago': //para conseguir el nombre del tipo de pago
                        if ( this.Venta.tipo_pago == '1' ) fixed = "Contado";
                        if ( this.Venta.tipo_pago == '2' ) fixed = "Credito";
                        break;
                    case 'tipo_entrega': //para conseguir el nombre del tipo de entrega
                        if ( this.Venta.tipo_entrega == '1' ) fixed = "Prepago";
                        if ( this.Venta.tipo_entrega == '2' ) fixed = "Postpago";
                        break;
                    case 'tipo_precio': //para conseguir el nombre del tipo de precio
                        break;
                    case 'detalle_venta':
                        if ( this.Modal.numero == 2 ) {
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
                        }
                    default:
                        fixed = '';
                        break;
                }

                return fixed;
            },
            list(numero, data = ''){
                var me = this;
                var url;

                switch (numero) {
                    case 'pago':
                        url = me.Ruta.pago+'/listVenta?'+'venta_id='+me.Venta.id;
                        axios.get(url).then(function(response){
                            me.ListaPago = response.data;
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                }
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
    .ec-th{
        background-color: skyblue;
    }
</style>

