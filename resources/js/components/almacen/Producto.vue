<template>
    <main>

        <!-- Interfaz Principal -->
        <div>
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Productos</span>&nbsp;&nbsp;
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="search" class="form-control" v-model="Busqueda.texto" @keyup="Busqueda.texto.length >=10 || Busqueda.texto.length == 0 ? listar() : ''" placeholder="Buscar por NOMBRE O CÓDIGO">
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

            <!-- Listado -->
            <div v-if="ListaProducto.length" class="table-responsive">
                <!-- Tabla -->
                <div class="table-scroll-20 overflow-auto">
                    <table class="table table-bordered table-striped table-sm text-gray-900">
                        <thead>
                            <tr class="bg-success">
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Codigo</th>
                                <th class="text-center">Precio al por menor</th>
                                <th class="text-center">Precio al por mayor</th>
                                <th class="text-center">Disponible</th>
                                <th class="text-center">Reservados</th>
                                <th class="text-center">Fallidos</th>
                                <th class="text-center">Traslado</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Opción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="producto in ListaProducto" :key="producto.id" :class="{'table-danger': producto.detalle.substock === 1, 'table-warning': producto.detalle.substock===2}">
                                <td v-text="producto.nombre"></td>
                                <td v-text="producto.codigo" class="text-center"></td>
                                <td v-text="producto.detalle.precio_menor" class="text-right"></td>
                                <td v-text="producto.detalle.precio_mayor" class="text-right"></td>
                                <td v-text="producto.detalle.substock" class="text-right"></td>
                                <td v-text="producto.detalle.reservados==0?'---':producto.detalle.reservados" class="text-right"></td>
                                <td v-text="producto.detalle.fallidos==0?'---':producto.detalle.fallidos" class="text-right"></td>
                                <td v-text="producto.detalle.traslado==0?'---':producto.detalle.traslado" class="text-right"></td>
                                <td v-text="producto.detalle.substock + producto.detalle.reservados + producto.detalle.fallidos + producto.detalle.traslado" class="text-right"></td>
                                <td class="text-center">
                                    <template v-if="producto.detalle.fallidos">
                                        <button type="button"  title="REPARAR" class="btn btn-info btn-sm" @click="abrirModalReparar(producto)"><i class="fas fa-tools"></i></button>
                                    </template>
                                    <div v-else>---</div>
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

        <!-- Modales: Agregar, Ver, Editar, Materiales -->
        <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animated bounceIn fast" :class="Modal.tamaño">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Modal Numero 1 de REPARAR-->
                        <div v-if="Modal.numero==1" class="container">
                            <div v-if="Error.estado && (Error.numero==1 || Error.numero==2)" class="row d-flex justify-content-center">
                                <div class="alert alert-danger">
                                    <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Nombre</label>
                                <label class="col-md-9 text-info" v-text="Producto.nombre"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-6 font-weight-bold">Codigo</label>
                                <label class="col-md-6" v-text="Producto.codigo?Producto.codigo:'---'"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-6 font-weight-bold">Cant. Productos Fallidos</label>
                                <label class="col-md-6 text-danger" v-text="Producto.fallidos"></label>
                            </div>
                            <div class="row">
                                <label class="col-md-6 font-weight-bold">Cantidad de reparados&nbsp;<span class="text-danger">*</span></label>
                                <input type="number" class="col-md-6 text-left form-control form-control-sm" v-model="Producto.cant_reparar" placeholder="Ingrese la cantidad reparada" min="1" :max="Producto.fallidos">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row form-group col-md-12 d-flex justify-content-around">
                            <div v-if="Modal.btnA">
                                <button type="button" @click="accionar()" class="btn btn-success font-weight-bold" :disabled="Button.press">
                                    <div v-if="!Button.press">{{Modal.btnA}}</div>
                                    <span v-else class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                            <div v-if="Modal.btnC">
                                <button type="button" @click="cerrarModal()" class="btn btn-secondary" v-text="Modal.btnC"></button>
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
                ListaProducto: [],
                Producto: {
                    id: 0,
                    nombre: '',
                    codigo: '',
                    precio_menor: 0,
                    precio_mayor: 0,
                    fallidos: 0,
                    cant_reparar: null
                },

                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    filas: 5
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
                    numero: 0,
                    estado: 0,
                    titulo: '',
                    tamaño: '',
                    btnA: '',
                    btnC: ''
                },
                Error: {
                    estado: 0,
                    numero: 0,
                    mensaje: []
                },
                Button: {
                    press: false
                },
                //datos de ruta de consultas
                Ruta: {
                    centro: '/centro',
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
                this.Paginacion.currentPage = page;

                var me = this;
                var url = this.Ruta.centro+'/listProductos?'
                        +'page='+this.Paginacion.currentPage
                        +'&texto='+this.Busqueda.texto
                        +'&rows='+this.Busqueda.filas
                        +'&centro_id='+$('meta[name="idCentro"]').attr('content');
                
                axios.get(url).then(function (response) {
                    me.ListaProducto = response.data.list.data;
                    me.Paginacion = response.data.paginate;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
            },
            abrirModal(numero, titulo, tamaño, btnA, btnC){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.tamaño = tamaño;
                this.Modal.btnA = btnA;
                this.Modal.btnC = btnC;
            },
            cerrarModal(){
                this.Modal.numero = 0;
                this.Modal.estado = 0;
                this.Modal.titulo = '';
                this.Modal.tamaño = '';
                this.Modal.btnA = '';
                this.Modal.btnC = '';

                this.Producto.cant_reparar = null;
                this.Button.press = false;

                this.Error.estado = 0;
                this.Error.mensaje = [];
            },
            accionar(){
                this.Button.press = true;
                switch( this.Modal.numero ){
                    case 1: this.reparar(); break;
                }
            },
            abrirModalReparar(data){
                this.Producto.id = data['detalle'].id;
                this.Producto.nombre = data['nombre'];
                this.Producto.codigo = data['codigo'];
                this.Producto.fallidos = data['detalle'].fallidos;
                this.abrirModal(1, 'Registrar reparación', '', 'Reparar', 'Cancelar');
            },
            reparar(){
                if ( this.validar(1) ) return;
                var me = this;
                axios.put('/detalle_producto/reparar', {
                    'detalle_producto_id' : me.Producto.id,
                    'cant_reparar': me.Producto.cant_reparar,
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: 'success',
                        title: 'Se registro la REPARACIÓN satisfactoriamente' ,
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
                this.Error.numero = numero;
                this.Error.mensaje = [];
                switch(numero){
                    case 1:
                        if(this.Producto.cant_reparar<=0 || this.Producto.cant_reparar==null || this.Producto.cant_reparar> this.Producto.fallidos) this.Error.mensaje.push('Debe ingresar una cantidad correcta');
                        break;
                }
                if ( this.Error.mensaje.length ) {this.Error.estado = 1; this.Button.press = false;}
                return this.Error.estado;
            },
            closeError(){
                this.Error.estado = 0;
                this.Error.numero = 0;
                this.Error.mensaje = [];
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