<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Vale&nbsp;</span>
                <!-- <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="fas fa-hammer"></i>&nbsp; Nuevo
                </button>&nbsp; -->
                <button type="button" class="btn btn-danger" @click="generatePdf()">
                    <i class="far fa-file-pdf"></i>&nbsp; PDF
                </button>
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
                        <input type="search" class="form-control" v-model="Busqueda.texto" @keyup.enter="listar()">
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
                                        <button type="button" @click="abrirModalVerVenta(vale.venta_generada_id)" title="VER VENTA ORIGEN" class="btn btn-outline-primary btn-sm">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </template>
                                    <!-- <template v-if="vale.estado">
                                        <button type="button" @click="desactivar(vale)" title="Desactivar" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="activar(vale)" title="Activar" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    </template> -->
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
            <div class="modal-dialog modal-dialog-centered animated bounceIn fast">
                <div class="modal-content modal-lg">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Modal Numero 1 de AGREGAR-->
                        <div v-if="Modal.numero==1">
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
                                <label class="col-md-3 font-weight-bold" for="nom">Nombre&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Vale.nombre" class="form-control" placeholder="ingrese el nombre" autofocus>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="des">Unid.Medida&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <select v-model="Vale.subtipo" class="custom-select">
                                        <option value="" disabled>Tipo</option>
                                        <option v-for="item in SelectTipoFiltrado" :key="item" :value="item" v-text="item"></option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select v-model="Vale.unidad" class="custom-select" id="cat">
                                        <option value="" disabled>Subtipo</option>
                                        <option v-for="unidad in selectUnidadFiltrado" :key="unidad.id" :value="unidad.nombre" v-text="unidad.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Costo por Unidad&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <input type="number" v-model="Vale.costo" class="form-control" min="0">
                                </div>
                            </div>
                        </div>
                        <!-- Modal Numero 2 de EDITAR-->
                        <div v-if="Modal.numero==2">
                            <div v-if="Error.estado" class="row d-flex justify-content-center">
                                <div class="alert alert-danger">
                                    <button type="button" @click="Error.estado=0" class="close" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Nombre&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Vale.nombre" class="form-control" placeholder="ingrese el nombre">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="des">Unid.Medida&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <select v-model="Vale.subtipo" class="custom-select" id="cat">
                                        <option value="" disabled>Tipo</option>
                                        <option v-for="item in SelectTipoFiltrado" :key="item" :value="item" v-text="item"></option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select v-model="Vale.unidad" class="custom-select" id="cat">
                                        <option value="" disabled>Subtipo</option>
                                        <option v-for="unidad in selectUnidadFiltrado" :key="unidad.id" :value="unidad.nombre" v-text="unidad.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Costo por Unidad&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <input type="number" v-model="Vale.costo" class="form-control" min="0">
                                </div>
                            </div>
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
                ListaVale: [],
                Vale: {
                    id: 0,
                    monto: 0,
                    cliente: '',
                    created_at: '',
                    updated_at: '',
                    venta_codigo: ''
                },
                SelectUnidad: [],
                SelectTipoFiltrado: [],
                YaIngrese: 0,
                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 3,
                    filas: 5
                },

                //datos de modales
                Modal: {
                    numero: 0, // 1: VerVenta
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
            permisoModalFooter: function(){
                if ( this.Modal.numero == 1 ) return true;
                if ( this.Modal.numero == 2 ) return true;

                return false;
            },
            selectUnidadFiltrado: function(){
                // console.log('Soy el computado selectUnidadFiltrado');
                let selectUnidadFiltrado = [];
                // console.log('tamaño de SelectUnidad: ' + this.SelectUnidad.length);
                if(this.SelectUnidad.length){
                    this.SelectUnidad.forEach(unidad => {
                        if(unidad.subtipo == this.Vale.subtipo){
                            selectUnidadFiltrado.push(unidad);
                        }
                    });

                    if(this.Modal.numero == 1  || this.YaIngrese) {//Es moda nuevo o ya ingrese
                        this.Vale.unidad = '';
                    }else{
                        this.YaIngrese = 1;
                    }
                    return selectUnidadFiltrado;
                }
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
            abrirModalVerVenta(data){
                this.Venta.id = data.id;
                this.Venta.total = data.total;
                this.Venta.total_venta = data.total_venta;
                this.Venta.total_descuento = data.total_descuento;
                this.Venta.total_faltante = data.total_faltante==null?0:data.total_faltante;
                this.Venta.tipo_pago = data.tipo.charAt(0);
                this.Venta.tipo_entrega = data.tipo.charAt(1);
                this.Venta.tipo_precio = data.tipo.charAt(2);
                this.Venta.created_at = data.created_at;
                
                this.Cliente.id = data.cliente_id;
                this.Cliente.dni = data.dni;
                this.Cliente.nombres = data.nombres;
                this.Cliente.apellidos = data.apellidos;
                this.Cliente.ruc = data.ruc;
                this.Cliente.razon_social = data.razon_social;
                this.Cliente.tipo = data.cliente_tipo;

                this.ListaPago = [];
                this.ListaDetalle = []; 
                
                this.list('detalle_venta', 'Ver');
                this.list('pago');

                this.abrirModal(2, 'Ver Venta', 'modal-xl', '', 'Cerrar');
            },
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

                this.Vale.id = 0;
                this.Vale.nombre = '';
                this.Vale.unidad = '';
                this.Vale.subtipo = '';
                this.Vale.costo = 0;

                this.YaIngrese = 0;
            },
            accionar(accion){
                switch( accion ){
                    case 'Agregar': {
                        this.agregar();
                        break;
                    }
                }
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
            validar(){
                this.Error.estado = 0;
                this.Error.mensaje = [];

                //Recorrere la lista de Vale
                if(this.Modal.numero == 1){
                    //Modal agregar
                    if ( !this.Vale.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");
                    if ( !this.Vale.unidad ) this.Error.mensaje.push("Debe seleccionar una Unid. Medida");
                    if ( this.Vale.costo == 0 || this.Vale.costo < 0) this.Error.mensaje.push("Debe ingresar un costo válido");
                }else{
                    //Modal editar
                    if(this.Vale.nombre == this.ValeOrigen.nombre && this.Vale.subtipo == this.ValeOrigen.subtipo && this.Vale.unidad == this.ValeOrigen.unidad && this.Vale.costo == this.ValeOrigen.costo){
                        this.Error.mensaje.push("Ningun cambio realizado");
                    }else{ 
                        if ( !this.Vale.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");
                        if ( !this.Vale.unidad ) this.Error.mensaje.push("Debe seleccionar una Unid. Medida");
                        if ( this.Vale.costo == 0 || this.Vale.costo < 0) this.Error.mensaje.push("Debe ingresar un costo válido");
                    }
                }

                if ( this.Error.mensaje.length ) this.Error.estado = 1;
                return this.Error.estado;
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
                    default:
                        fixed = '';
                        break;
                }

                return fixed;
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

