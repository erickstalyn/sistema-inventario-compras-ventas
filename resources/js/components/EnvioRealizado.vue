<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-hammer"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Mis Envios realizados&nbsp;</span>
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    Nuevo
                </button>&nbsp;
                <button type="button" class="btn btn-danger">
                    <i class="far fa-file-pdf"></i>&nbsp; PDF
                </button>
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
                    <input type="search" class="form-control" v-model="Busqueda.texto" @keyup.enter="listar()" placeholder="Buscar por centro de destino">
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
            <div v-if="ListaEnvioRealizado.length" class="">
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
                            <tr v-for="envio in ListaEnvioRealizado" :key="envio.id" >
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
                                    <button type="button" title="Ver" class="btn btn-sm btn-primary">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    <template v-if="envio.estado == 2">
                                        <button type="button"  title="Reenviar" class="btn btn-info btn-sm">
                                            <i class="fas fa-plane"></i>
                                        </button>
                                    </template>
                                    <template v-if="envio.estado != 1">
                                        <button type="button"  title="Anular" class="btn btn-danger btn-sm">
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

        <!-- Modales de Agregar -->
        <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
            <div class="modal-dialog modal-dialog-centered animated bounceIn fast" :class="Modal.size">
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
                                    <div class="col-md-6">
                                        <div class="row">
                                            <h5 class="font-weight-bold">Productos</h5>
                                        </div>
                                        <div class="row">
                                            <div class="input-group"> 
                                                <input type="search" class="form-control form-control-sm" v-model="BusquedaFiltro.texto" @keyup.enter="listarFiltro()" id="filtroProducto" autofocus placeholder="Guia: Producto - marca - modelo - tamaño -color">
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
                                                            <th style="width: 24rem;">Nombre</th>
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
                                                            <td v-text="producto.substock"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div v-else>
                                                <p>No se han encontrado resultados</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ml-auto container">
                                        <div class="row">
                                            <h5 class="font-weight-bold">Lista de items</h5>
                                        </div>
                                        <div class="row form-group ec-table-modal overflow-auto">
                                            <div v-if="ListaDetalleEnvio.length">
                                                <table class="table tableless table-striped table-sm text-gray-900">
                                                    <thead>
                                                        <tr class="table-success">
                                                            <th class="text-center" style="width: 3rem;">Quitar</th>
                                                            <th >Nombre</th>
                                                            <th style="width: 5rem;" class="text-center">Cantidad</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(detalle, indice) in ListaDetalleEnvio" :key="detalle.id">
                                                            <td class="text-center">
                                                                <button type="button" title="Editar" class="btn btn-circle btn-outline-danger btn-sm" @click="quitarDetalle(indice)">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>
                                                            </td>
                                                            <td v-text="detalle.nombre"></td>
                                                            <td >
                                                                <input type="number" v-model="detalle.cantidad" class="form-control form-control-sm text-right" min="1">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div v-else>
                                                <br>
                                                <h5>Sin detalles del envío</h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group"> 
                                                    <label for="">Enviar a</label>&nbsp;<span class="text-danger">*</span>&nbsp;
                                                    <select v-model="EnvioRealizado.centro_to_id" class="custom-select custom-select-sm text-gray-900">
                                                        <option value="0">Seleccione</option>
                                                        <option v-for="item in SelectCentro" :key="item.id" :value="item.id" v-text="item.nombre" ></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-2"></div> -->
                                            <div class="col-md-6">
                                                <p class="text-right pr-3">Total de productos: {{getTotal}}</p>
                                            </div>
                                        </div>
                                    </div>
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
                ListaEnvioRealizado: [],
                EnvioRealizado: {
                    id: 0,
                    centro_to_id: 0,
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
                ListaDetalleEnvio:[
                ],
                //ALMACENES PARA REALIZAR EL ENVIO
                SelectCentro: [],
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

                var url = '/envioRealizado?page='+this.Paginacion.currentPage
                        +'&estado='+this.Busqueda.estado
                        +'&idCentro='+this.EnvioRealizado.idCentro
                        +'&texto='+this.Busqueda.texto
                        +'&filas='+this.Busqueda.filas
                        +'&dia='+this.Busqueda.dia
                        +'&mes='+this.Busqueda.mes
                        +'&year='+this.Busqueda.year
                        +'&ordenarPor='+this.Navegacion.ordenarPor
                        +'&orden='+this.Navegacion.orden;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaEnvioRealizado = response.data.envios.data;
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
                    let url = '/detalle_producto/getDetalle_productoFiltrado?texto=' 
                            + this.BusquedaFiltro.texto
                            + '&idCentro=' + this.EnvioRealizado.idCentro;
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
            selectCentro(){
                var me = this;
                var url = '/centro/selectCentro';

                axios.get(url,{
                    params: {
                        'idCentro': me.EnvioRealizado.idCentro
                    }
                }).then(function(response){
                    me.SelectCentro = response.data;
                }).catch(function(error){
                    console.log(error);
                });
            },
            agregarDetalle(producto){
                //Verifico si el producto ya esta en la lista de detalle
                let incluido = false;
                for (let i = 0; i < this.ListaDetalleEnvio.length; i++) {
                    if(this.ListaDetalleEnvio[i].id == producto.id){
                        incluido = true;
                        //adiciono uno más a la cantidad de este producto en la tabla de detalles
                        this.ListaDetalleEnvio[i].cantidad ++;
                        break;
                    }
                }

                if(!incluido){
                    let elProducto = {
                        id: producto.id,
                        nombre: producto.nombre,
                        cantidad: 1,
                    }
                    this.ListaDetalleEnvio.push(elProducto);
                }
            },
            quitarDetalle(indice){
                this.ListaDetalleEnvio.splice(indice,1);
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
            validar(){
                this.Error.estado = 0;
                this.Error.mensaje = [];

                //Recorrere la lista de Material
                if(this.Modal.numero == 1){
                    //Modal agregar
                    if ( !this.ListaDetalleEnvio.length ) {
                        this.Error.mensaje.push("No existe ningun detalle de producción");
                    }else{//Valido si hay negativos en las cantidades de los detalles de producción
                        this.ValidarNegativosCantidades();
                    }
                    if(!this.EnvioRealizado.centro_to_id) this.Error.mensaje.push('Debe seleccionar el centro receptor');
                }else{
                    //Modal editar
                }
                if ( this.Error.mensaje.length ) this.Error.estado = 1;
                return this.Error.estado;
            },
            ValidarNegativosCantidades(){
                for (let i = 0; i < this.ListaDetalleEnvio.length; i++) {
                    const detalle = this.ListaDetalleEnvio[i];
                    if(detalle.cantidad<1){
                        this.Error.mensaje.push('Las cantidades de los detalles deben ser mayores o iguales a 1');
                        break;
                    }
                }
            },
            abrirModalAgregar(){
                this.abrirModal(1, 'Registrar Envío', 'Agregar', 'modal-xl');
                if(!this.SelectCentro.length) this.selectCentro();
            },
            abrirModal(numero, titulo, accion, size){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.accion = accion;
                this.Modal.size = size;
            },
            cerrarModal(){
                this.Modal.numero = 0;
                this.Modal.estado = 0;
                this.Modal.mensaje = [];

                this.Error.estado = 0;
                this.Error.mensaje = [];

                // this.Produccion.id = 0;
                // this.Produccion.total = 0.00;
                // this.Produccion.fecha_inicio = '';
                // this.Produccion.fecha_programada = '';

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

