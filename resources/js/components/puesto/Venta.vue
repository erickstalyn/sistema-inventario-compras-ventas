<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Ventas&nbsp;</span>
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="fas fa-hammer"></i>&nbsp;Nuevo
                </button>&nbsp;
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-6 input-group"> 
                    <select class="custom-select text-gray-900 w4rem" v-model="Busqueda.type">
                        <option value="2">Todos</option>
                        <option value="0">Contado</option>
                        <option value="1">Credito</option>
                    </select>
                    <input type="search" class="form-control" v-model="Busqueda.text">
                    <button type="button" class="btn btn-primary" @click="listar()">
                        <i class="fa fa-search"></i>&nbsp;Buscar
                    </button>
                </div>
                <div class="col-md-3"></div>
                <label class="col-md-2 text-right">N° filas:</label>
                <select class="col-md-1 text-right custom-select custom-select-sm text-gray-900" v-model="Busqueda.rows">
                    <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                </select>
            </div>

            <!-- Listado -->
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
                                    <label v-if="venta.tipo==0">Contado</label>
                                    <label v-if="venta.tipo==1">Credito</label>
                                </td>
                                <td v-text="venta.razon_social!=null?venta.razon_social:(venta.nombres!=null?(venta.nombres+' '+venta.apellidos):'---')"></td>
                                <td class="text-right" v-text="venta.total"></td>
                                <td class="text-center" v-text="venta.created_at"></td>
                                <td class="text-center">
                                    <template>
                                        <button type="button" title="VER" class="btn btn-primary btn-sm" @click="abrirModalVer(venta)">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </template>
                                    <template>
                                        <button type="button" title="EDITAR" class="btn btn-warning btn-sm" @click="abrirModalEditar(venta)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </template>
                                    <template v-if="venta.tipo==1">
                                        <button type="button"  title="PAGAR" class="btn btn-info btn-sm" @click="abrirModalPagar(venta)">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </button>
                                    </template>
                                    <template>
                                        <button type="button" title="ANULAR" class="btn btn-danger btn-sm" @click="abrirModalAnular(venta.id)">
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


        <!-- Modales de Agregar/Editar/Pagar -->
        <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
            <div class="modal-dialog modal-dialog-centered animated bounceIn fast" :class="Modal.size">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Modal Numero 1 de AGREGAR-->
                        <div v-if="Modal.numero==1" class="container">
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
                                <label class="col-md-2 font-weight-bold">PROVEEDOR</label>
                                <label class="col-md-1 font-weight-bold">RUC/DNI&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-2 input-group">
                                    <input type="text" class="form-control form-control-sm" v-model="Service.document" @keyup.enter="consultar()" maxlength="11">
                                    <button type="button" class="btn btn-sm btn-primary" @click="consultar()">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <h5>
                                        <span role="status" :class="Service.loadclass"></span>&nbsp;
                                        <span v-text="Service.msm" :class="Service.msmclass"></span>
                                    </h5>
                                </div>
                            </div>
                            <div>
                                <div v-if="Cliente.tipo==1" class="row form-group">
                                    <div class="col-md-2 input-group">
                                        <label class="col-md-4">DNI</label>
                                        <input type="text" class="col-md-8 form-control form-control-sm" readonly v-model="Cliente.documento">
                                    </div>
                                    <div class="col-md-5 input-group">
                                        <label class="col-md-3">Nombres</label>
                                        <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.nombres">
                                    </div>
                                    <div class="col-md-5 input-group">
                                        <label class="col-md-3">Apellidos</label>
                                        <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.apellidos">
                                    </div>
                                </div>
                                <div v-else-if="Cliente.tipo==2" class="row form-group">
                                    <div class="col-md-3 input-group">
                                        <label class="col-md-3">RUC</label>
                                        <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.documento">
                                    </div>
                                    <div class="col-md-9 input-group">
                                        <label class="col-md-2">Razón social</label>&nbsp;
                                        <input type="text" class="col-md-10 form-control form-control-sm" v-model="Cliente.razon_social" :readonly="Service.readonly">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row shadow bg-white rounded p-2">
                                <div class="col-md-4">
                                    <label class="row col-md-12 form-group font-weight-bold">PRODUCTOS</label>
                                    <div class="row input-group form-group"> 
                                        <input type="search" class="form-control form-control-sm" v-model="Service.text" @keyup.enter="list()" id="filtroProducto" placeholder="Producto,marca,modelo,tamaño,color">
                                        <button type="button" class="btn btn-sm btn-primary" @click="list()">
                                            <i class="fa fa-search"></i>&nbsp; Buscar
                                        </button>
                                    </div>
                                    <div class="row overflow-auto" style="height: 16rem;">
                                        <div v-if="ListaProducto.length">
                                            <table class="table table-borderless table-striped table-sm text-gray-900">
                                                <thead>
                                                    <tr class="table-danger">
                                                        <th class="text-center" style="width: 10%;">Agregar</th>
                                                        <th>Nombre</th>
                                                        <th>Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="producto in ListaProducto" :key="producto.id" >
                                                        <td class="text-center">
                                                            <button type="button" title="AGREGAR" class="btn btn-circle btn-sm btn-outline-success" @click="add(0, producto)">
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
                                            <label class="col-md-12 text-danger">No se han encontrado resultados</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 ml-auto container">
                                    <div class="row">
                                        <label class="col-md-3 font-weight-bold">LISTA DE ITEMS</label>
                                        <div class="col-md-6"></div>
                                        <div class="col-md-3 input-group">
                                            <label class="col-md-4 font-weight-bold">Tipo</label>&nbsp;<span class="text-danger">*</span>
                                            <select class="col-md-8 custom-select custom-select-sm" v-model="Venta.tipo">
                                                <option value="0">Contado</option>
                                                <option value="1">Credito</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group ec-table-modal overflow-auto">
                                        <div v-if="ListaDetalle.length">
                                            <table class="table tableless table-striped table-sm text-gray-900">
                                                <thead>
                                                    <tr class="table-success">
                                                        <th class="text-center" style="width: 3rem;">Quitar</th>
                                                        <th class="text-center">Nombre</th>
                                                        <th class="text-center">Cantidad</th>
                                                        <th class="text-center">Precio</th>
                                                        <th class="text-center">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(detalle, indice) in ListaDetalle" :key="indice">
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-circle btn-outline-danger btn-sm" title="QUITAR" @click="quitarDetalle(indice)">
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
                                            <p>Sin detalles de venta</p>
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group" style="width: 12.4rem;" v-if="Venta.tipo == '1'"> 
                                                <label for="">Pago inicial</label>&nbsp;<span class="text-danger">*</span>&nbsp;
                                                <input type="number" class="form-control form-control-sm" v-model="Pago.pagoInicial" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                        </div>
                                        <div class="col-md-5">
                                            <p class="text-right pr-1">Desembolso total: s/ {{getDesembolso}}</p>
                                        </div>
                                    </div> -->
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
                                        <button type="button" class="btn btn-sm btn-primary" @click="agregarListaPago()">
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
                                                    <td class="text-center" v-text="pago.created_at"></td>
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
                                    <span class="text-success">Monto pagado: s/{{getSumaPagos}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right pr-5">
                                    <span class="text-danger">Monto faltante: s/{{this.Abasto.total_faltante = Abasto.total - getSumaPagos}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right pr-5">
                                    <span>Costo total: s/{{Abasto.total}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row form-group col-md-12 d-flex justify-content-around">
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
                ListaVenta: [],
                Venta: {
                    id: 0,
                    codigo: '',
                    total: 0.00,
                    total_faltante: 0.00,
                    tipo: 0, // 0: Contado, 1: Credito
                    created_at : '',
                },
                ListaProducto:[],
                Producto: {},
                ListaDetalle: [],
                
                
                Cliente:{
                    id: 0,
                    documento: '',
                    nombres: '',
                    apellidos: '',
                    razon_social: '',
                    tipo: 0
                },

                //datos de busqueda y filtracion general
                Busqueda: {
                    text: '',
                    type: 2,
                    rows: 5
                },

                //datos de modales
                Modal: {
                    numero: 0, // 1 Agregar, 2 Ver, 3 Pagar
                    estado: 0,
                    titulo: '',
                    size: '',
                    btnA: '',
                    btnC: ''
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
                    offset: 3
                },

                //datos de errores
                Error: {
                    estado: 0,
                    numero: 0,
                    mensaje: []
                },

                //datos de servicios necesarios
                Service: {
                    type: 0, //1->PERSONA, 2-> EMPRESA
                    document: '',
                    msm: '',
                    msmclass: '',
                    loadclass: '',
                    readonly: false,
                    text: ''
                },

                //datos de pagos
                ListaPago: [],
                Pago:{
                    monto: '',
                    sumaPagos: 0.00,
                },

                //datos de Rutas
                Ruta: {
                    venta: '/venta',
                    persona: '/persona',
                    producto: '/producto',
                    detalle_producto: '/detalle_producto'
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
                return sumaPagos;
            }
        },
        methods: {
            listar(page = 1){
                this.Paginacion.currentPage = page;

                var me = this;
                var url = this.Ruta.venta+'?'
                        +'page='+this.Paginacion.currentPage
                        +'&type='+this.Busqueda.type
                        +'&text='+this.Busqueda.text
                        +'&rows='+this.Busqueda.rows;
                
                axios.get(url).then(function (response) {
                    me.ListaVenta = response.data.ventas.data;
                    me.Paginacion = response.data.pagination;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            agregar(){
                if ( this.validar() ) return;
                
                var me = this;
                axios.post('/abasto/agregar', {
                    //Datos del abasto
                    'id' : this.Abasto.id,
                    'total': this.Abasto.total,
                    'tipo': this.Abasto.tipo, 
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
            abrirModalAgregar(){
                this.abrirModal(1, 'Registrar Venta', 'modal-xl', 'Registrar', 'Cancelar');

                this.Error.numero = 0;
                this.Error.estado = 0;
                this.Error.mensaje = [];

                this.Cliente.id = 0;
                this.Cliente.documento = '';
                this.Cliente.nombres = '';
                this.Cliente.apellidos = '';
                this.Cliente.razon_social = '';
                this.Cliente.tipo = 0;
                
                this.Service.type = 0;
                this.Service.document = '';
                this.Service.msm = '';
                this.Service.msmclass = '';
                this.Service.loadclass = '';
                this.Service.readonly = false;
                this.Service.text = '';
            },
            abrirModalPagar(abasto = []){
                this.Abasto.id = abasto['id'];
                this.Abasto.total = abasto['total'];
                this.abrirModal(3, 'Realizar Pago', 'Guardar', '');
                this.listarPagos(abasto['id'], abasto['total']);
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
                        confirmButton: 'btn btn-success',
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
                                title: 'El abasto se anuló correctamente',
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
            abrirModal(numero, titulo, size, btnA, btnC){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.size = size;
                this.Modal.btnA = btnA;
                this.Modal.btnC = btnC;
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
                this.Abasto.tipo = 1;

                this.DatosServicio.tipo = 0;
                this.DatosProveedor.id = 0;
                this.DatosProveedor.documento = '';
                this.DatosProveedor.nombres = '';
                this.DatosProveedor.apellidos = '';
                this.DatosProveedor.razon_social = '';

                this.ListaPago = [];
                this.Pago.monto = '';

                this.ListaDetalleAbasto = [];
                this.BusquedaFiltro.texto = '';
            },
            accionar(){
                switch( this.Modal.numero ){
                    case 1: this.agregar(); break;
                    case 'Guardar': this.agregarPago(); break;
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

                    if(this.Abasto.tipo == 1){
                        if(this.Abasto.pagoInicial<0 || this.Abasto.pagoInicial == ''){
                            this.Error.mensaje.push('El pago inicial debe ser mayor o igual a 0')
                        }else if(this.Abasto.pagoInicial > this.Abasto.total){
                            this.Error.mensaje.push('El pago inicial no debe ser mayor al desembolso total')
                        }else if(this.Abasto.pagoInicial == this.Abasto.total){
                            this.Error.mensaje.push('El pago inicial es igual al desembolso total, se recomienda cambiarlo a una venta al contado')
                        }
                    }

                }else{ //Modal editar
                    if (this.Pago.monto == '' || this.Pago.monto <= 0 || this.Pago.monto > this.Abasto.total_faltante) this.Error.mensaje.push('Debe ingresar un monto válido');
                }
                if ( this.Error.mensaje.length ) this.Error.estado = 1;
                return this.Error.estado;
            },
            consultar(){
                this.Service.msm = '';
                this.Service.msmclass = '';

                switch (this.Service.document.length) {
                    case 0:
                        this.Service.msm = 'Ingrese un DNI o RUC';
                        this.Service.msmclass = 'badge badge-warning';
                        break;
                    case 8: 
                    case 11:
                        this.Service.msm = 'Consultado...';
                        this.Service.msmclass = 'badge badge-info';
                        this.Service.loadclass = 'spinner-border spinner-border-sm text-success';
                        this.consultarDB();
                        break;
                    default:
                        this.Service.msm = 'Documento inválido'
                        this.Service.msmclass = 'badge badge-primary';
                        break;
                }
            },
            consultarDB(){
                var me = this;
                var url = this.Ruta.persona+'/getPersona';

                axios.get(url, {
                    params: {
                        'documento': me.Service.document
                    }
                }).then(function(response){
                    if (response.data.persona.length){ //Si existe la persona en la db
                        me.Service.msm = '';
                        me.Service.msmclass = '';
                        me.Service.loadclass = '';

                        const persona = response.data.persona[0];
                        
                        if (persona.razon_social){//Es una EMPRESA
                            me.Service.type = 2;
                            me.Service.readonly = true;
                            
                            me.Cliente.tipo = 2;
                            me.Cliente.razon_social = persona.razon_social;
                        } else {//Es una PERSONA
                            me.Service.type = 1;

                            me.Cliente.tipo = 1;
                            me.Cliente.nombres = persona.nombres;
                            me.Cliente.apellidos = persona.apellidos;
                        }

                        me.Cliente.id = persona.id;
                        me.Cliente.documento = me.Service.document;

                        me.Service.document = '';
                    } else {//No esxiste la persona en la db
                        if ( me.Service.document.length == 8 ){
                            me.consultarDNI();
                        }else{
                            me.consultarRUC();
                        }
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            consultarDNI(){
                let me = this;
                let dni = me.Service.document;

                $.ajax({
                    type: 'GET',
                    url: "http://localhost:8080/Reniec/demo.php",
                    data: "dni="+dni,
                    beforeSend(){
                        me.Service.msm = 'Consultado...';
                        me.Service.msmclass = 'badge badge-info';
                        me.Service.loadclass = 'spinner-border spinner-border-sm text-primary';
                    },
                    success: function (data, textStatus, jqXHR) {
                        let persona = JSON.parse(data);
                        if (persona.estado == true){
                            me.Service.type = 1;
                            me.Service.document = '';
                            me.Service.msm = '';
                            me.Service.msmclass = '';
                            me.Cliente.tipo = 1;
                            me.Cliente.documento = persona.dni;
                            me.Cliente.nombres = persona.nombres;
                            me.Cliente.apellidos = persona.apellidos;
                        } else {
                            me.Service.msm = 'El DNI no existe';
                            me.Service.msmclass = 'badge badge-primary';
                        }
                        me.Service.loadclass = '';
                    }
                }).fail(function(){
                });
            },
            consultarRUC(){
                let me = this;
                let ruc = me.Service.document;

                $.ajax({
                    type: 'GET',
                    url: "http://localhost:8080/SunatPHP/demo.php",
                    data: "ruc="+ruc,
                    beforeSend(){
                        me.Service.msm = 'Consultado...';
                        me.Service.msmclass = 'badge badge-info';
                        me.Service.loadclass = 'spinner-border spinner-border-sm text-primary';
                    },
                    success: function (data, textStatus, jqXHR) {
                        if (data.RazonSocial){
                            me.Service.type = 2;
                            me.Service.document = '';
                            me.Service.msm = '';
                            me.Service.msmclass = '';
                            me.Service.readonly = false;
                            me.Cliente.tipo = 2;
                            me.Cliente.documento = data.RUC;
                            me.Cliente.razon_social = data.RazonSocial;
                        } else {
                            me.Service.msm = 'El RUC no existe';
                            me.Service.msmclass = 'badge badge-primary';
                        }
                        me.Service.loadclass = '';
                    }
                }).fail(function(){
                });
            },
            list(){
                if ( this.Service.text == '' ) return;

                let me = this;
                let url = this.Ruta.detalle_producto+'/listProductos?'
                                +'text='+this.Service.text
                                +'&centro_id='+$('meta[name="idCentro"]').attr('content');

                axios.get(url).then(function(response){
                    if ( response.data.productos.length == 1 && me.Service.text == response.data.productos[0].codigo){
                        me.add(0, response.data.productos[0]);
                        me.Service.text = '';
                    } else {
                        me.ListaProducto = response.data.productos;
                    }
                    let inputFiltro = document.getElementById('filtroProducto');
                    inputFiltro.focus();
                }).catch(function(error){
                    console.log(error);
                });
            },
            add(numero, data){
                switch (numero) {
                    case 0:
                        let found = false;
                        for (let i = 0; i < this.ListaDetalle.length; i++) {
                            if ( this.ListaDetalle[i].id == data.id ){
                                this.ListaDetalle[i].cantidad++; 
                                found = true; break;
                            }
                        }

                        if ( !found ){
                            let producto = {
                                id: data.id,
                                nombre: data.nombre,
                                cantidad: 1,
                                costo_abasto: 0.00,
                                subtotal: 0.00
                            }
                            this.ListaDetalle.push(producto);
                        }
                        break;
                    case 1:
                        break;
                }
            },
            formatearFecha(fecha){
                let arrayFecha = fecha.split('-');
                let newFecha = arrayFecha[2] + '-' + arrayFecha[1] + '-' + arrayFecha[0];
                return newFecha;
            },
            quitarDetalle(indice){
                this.ListaDetalleAbasto.splice(indice,1);
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
            listarPagos(id,total){
                let me = this;
                let url = '/abasto/getPagos';

                axios.get(url,{
                    params: {
                        'id': id
                    }
                }).then(function(response){
                    // me.Abasto.total_faltante = total;
                    me.ListaPago = response.data;
                }).catch(function(error){
                    console.log(error);
                });
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
                var url = '/centro/selectAlmacen';

                axios.get(url).then(function(response){
                    me.SelectAlmacen = response.data;
                }).catch(function(error){
                    console.log(error);
                });
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
    .w10rem{
        width: 4rem;
    }
</style>

