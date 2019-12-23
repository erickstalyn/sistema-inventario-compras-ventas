<template>
    <main>
        <!-- Interfaz Principal -->
        <div>
            <!-- Encabezado principal -->
            <div class="row form-group">
                <div class="col-md-6 text-center">
                    <!-- <i class="fas fa-hammer"></i>&nbsp; -->
                    <span class="h3 mb-0 text-gray-900 font-italic" v-text="Puesto.nombre"></span>&nbsp;&nbsp;
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <select class="custom-select text-gray-900" v-model="Puesto.id">
                            <option v-for="puesto in SelectPuesto" :key="puesto.id" :value="puesto.id" v-text="puesto.nombre"></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <select class="custom-select text-gray-900" v-model="Puesto.quieroVer">
                            <option value="1">Inventario</option>
                            <option value="2">Ventas</option>
                            <option value="3">Envios Realizados</option>
                            <option value="4">Envios Recibidos</option>
                        </select>&nbsp;
                        <button type="button" class="btn btn-success" @click="listar()">
                            <i class="fas fa-sync-alt"></i>&nbsp; Actualizar
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <i class="fas fa-warehouse"></i>&nbsp;&nbsp;
                <span class="h5 mb-0 text-gray-900" v-text="Puesto.titulo"></span>&nbsp;&nbsp;
            </div>
            <div v-if="Puesto.mostrar == 1">
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
                <div v-else>
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
                            <!-- Modal Numero 1 de AGREGAR-->
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
                Puesto: {
                    id: 1,
                    nombre: '',
                    quieroVer: 1, // 1:Inventario, 2: Ventas, 3: Envios Realizados, 4: Envios Recibidos
                    titulo: '',
                    mostrar: 0 // 1:Inventario, 2: Ventas, 3: Envios Realizados, 4: Envios Recibidos
                },
                SelectPuesto: [],
                ListaProducto: [],
                Producto: {
                    nombre: '',
                    codigo: '',
                    precio_menor: 0,
                    precio_mayor: 0,
                    stock: 0,
                },

                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    filas: 5
                },
                Error: {
                    estado: 0,
                    mensaje: []
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
                    numero: 0,
                    titulo: '',
                    accion: '',
                    cancelar: '',
                    size: ''
                },
                //datos de ruta de consultas
                Ruta: {
                    detalle_producto: '/detalle_producto',
                    centro: '/centro',
                    venta: '/venta'
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
                let url;
                let me = this;
                me.Puesto.mostrar = 0;
                switch (Number.parseInt(this.Puesto.quieroVer)) {
                    case 1: //Listar inventario
                        url = this.Ruta.centro+'/listProductos?'
                                +'page='+this.Paginacion.currentPage
                                +'&texto='+this.Busqueda.texto
                                +'&rows='+this.Busqueda.filas
                                +'&centro_id='+this.Puesto.id;
                        
                        axios.get(url).then(function (response) {
                            me.ListaProducto = response.data.list.data;
                            me.Paginacion = response.data.paginate;
                            me.Puesto.titulo = 'Inventario'
                            me.Puesto.mostrar = 1;
                        }).catch(function (error) {
                            console.log(error)
                        });
                        break;
                    case 2: //Listar Ventas
                        url = this.Ruta.centro+
                                +'page='+this.Paginacion.currentPage
                                +'&texto='+this.Busqueda.texto
                                +'&rows='+this.Busqueda.filas
                                +'&centro_id='+this.Puesto.id;
                        
                        axios.get(url).then(function (response) {
                            me.ListaProducto = response.data.list.data;
                            me.Paginacion = response.data.paginate;
                            me.Puesto.titulo = 'Inventario'
                            me.Puesto.mostrar = 1;
                        }).catch(function (error) {
                            console.log(error)
                        });
                        break;
                }
                for (let i = 0; i < this.SelectPuesto.length; i++) { //Busco el nombre
                    if(this.SelectPuesto[i].id == this.Puesto.id) this.Puesto.nombre = this.SelectPuesto[i].nombre;
                }
            },
            select(numero){
                let me = this;
                let url;
                switch (numero) {
                    case 1: //los puestos
                        url = this.Ruta.centro + '/selectCentro?tipo=P';
                        axios.get(url).then(function(response){
                            me.SelectPuesto = response.data;
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