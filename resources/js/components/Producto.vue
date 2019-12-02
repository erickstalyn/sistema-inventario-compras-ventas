<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-fluid">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Productos</span>
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="fas fa-user-plus"></i>&nbsp; Nuevo
                </button>
                <button type="button" class="btn btn-danger">
                    <i class="far fa-file-pdf"></i>&nbsp; PDF
                </button>
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-8">
                    <div class="input-group">
                        <select class="col-md-3 form-control text-gray-900" v-model="Busqueda.estado" @click="listar()">
                            <option value="2">Todos</option>
                            <option value="1">Activados</option>
                            <option value="0">Desactivados</option>
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
                    <select class="form-control text-gray-900" v-model="Busqueda.filas" @click="listar()">
                        <option v-for="fila in Filas" :key="fila" :value="fila" v-text="fila"></option>
                    </select>
                </div>
            </div>

            <!-- Listado -->
            <div v-if="ListaProducto.length" class="table-responsive">
                <!-- Tabla -->
                <div class="ec-table overflow-auto">
                    <table class="table table-bordered table-striped table-sm text-gray-900">
                        <thead>
                            <tr class="bg-success">
                                <th v-for="head in Headers" :key="head.nombre" @click="listar(1, head.nombre)" class="ec-cursor" v-text="getTitulo(head.titulo)"></th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="producto in ListaProducto" :key="producto.id" >
                                <td v-text="producto.nombre"></td>
                                <!-- <td v-text="producto.size"></td> -->
                                <!-- <td v-text="producto.color"></td> -->
                                <!-- <td v-text="producto.costo_produccion?producto.costo_producciopn:''"></td> -->
                                <td v-text="producto.precio_menor"></td>
                                <td v-text="producto.precio_mayor"></td>
                                <td v-text="producto.stock"></td>
                                <td>
                                    <div v-if="producto.estado">
                                        <span class="badge badge-success">Activado</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" @click="abrirModalVer(producto)" title="VER" class="btn btn-warning btn-sm">
                                        <span>Ver</span>
                                    </button>
                                    <button type="button" @click="abrirModalEditar(producto)" title="EDITAR" class="btn btn-warning btn-sm">
                                        <i class="fas fa-user-edit"></i>
                                    </button>
                                    <template v-if="producto.estado">
                                        <button type="button" @click="abrirModalSetEstado(producto)" title="DESACTIVAR" class="btn btn-danger btn-sm">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="abrirModalSetEstado(producto)" title="ACTIVAR" class="btn btn-success btn-sm">
                                            <i class="fas fa-user-check"></i>
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
            <div class="modal-dialog modal-lg modal-dialog-centered animated bounceIn fast">
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
                                <label class="col-md-3 font-weight-bold">Super Producto&nbsp;(<span class="text-danger">*</span>):</label>
                                <div class="col-md-9">
                                    <select v-model="Producto.superproducto_id" class="form-control">
                                        <option value="0" disabled>seleccione un superproducto</option>
                                        <option v-for="superproducto in SelectSuperProducto" :key="superproducto.id" :value="superproducto.id" v-text="superproducto.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Descripcion:</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Producto.descripcion" class="form-control" placeholder="ingrese la descripcion">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Tamaño&nbsp;(<span class="text-danger">*</span>):</label>
                                <div class="col-md-9">
                                    <select v-model="Producto.size" class="form-control">
                                        <option value="" disabled>seleccione un tamaño</option>
                                        <option v-for="size in SelectSize" :key="size.nombre" :value="size.nombre" v-text="size.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Color&nbsp;(<span class="text-danger">*</span>):</label>
                                <div class="col-md-9">
                                    <select v-model="Producto.color" class="form-control">
                                        <option value="" disabled>seleccione un color</option>
                                        <option v-for="color in SelectColor" :key="color.nombre" :value="color.nombre" v-text="color.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Precio normal&nbsp;(<span class="text-danger">*</span>):</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Producto.precio_menor" class="form-control" placeholder="ingrese el precio normal">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Precio mayor&nbsp;(<span class="text-danger">*</span>):</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Producto.precio_mayor" class="form-control" placeholder="ingrese el precio al por mayor">
                                </div>
                            </div>
                        </div>
                        <!-- Modal Numero 2 de VER-->
                        <div v-if="Modal.numero==2">
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Nombre</label>
                                <label class="col-md-3 font-weight-bold" v-text="Producto.nombre"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Nombre</label>
                                <label class="col-md-3 font-weight-bold" v-text="Producto.nombre"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Nombre</label>
                                <label class="col-md-3 font-weight-bold" v-text="Producto.nombre"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Nombre</label>
                                <label class="col-md-3 font-weight-bold" v-text="Producto.nombre"></label>
                            </div>
                        </div>
                        <!-- Modal Numero 3 de EDITAR-->
                        <div v-if="Modal.numero==3">
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
                                <label class="col-md-3 font-weight-bold" for="cat">Categoria&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select v-model="Producto.categoria_id" class="form-control" id="cat">
                                        <option value="0" disabled>seleccione una categoria</option>
                                        <option v-for="categoria in SelectCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Nombre&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Producto.nombre" class="form-control" placeholder="ingrese el nombre" id="nom">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Precio&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Producto.precio" class="form-control" placeholder="ingrese el precio" id="pre">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="des">Descripcion</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Producto.descripcion" class="form-control" placeholder="ingrese la descripcion" id="des">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" v-if="permisoModalFooter">
                        <div class="row form-group col-md-12 d-flex justify-content-around">
                            <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cancelar</button>
                            <button type="button" @click="accionar(Modal.accion)" class="btn btn-primary" v-text="Modal.accion"></button>
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
                    superproducto_id: 0,
                    nombre: 0,
                    codigo: 0,
                    descripcion: '',
                    size: '',
                    color: '',
                    costo_produccion: 0,
                    precio_menor: 0,
                    precio_mayor: 0,
                    stock: 0,
                    estado: 0,
                    created_at: ''
                },
                SelectSuperProducto: [],
                SelectSize: [],
                SelectColor:[],

                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 2,
                    filas: 5
                },

                //datos de modales
                Modal: {
                    numero: 1,
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
                    ordenarPor: 'nombre',
                    orden: 'desc' 
                },

                //datos de errores
                Error: {
                    estado: 0,
                    mensaje: []
                },

                //datos de ruta de consultas
                Ruta: '/producto'
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
            Headers: function(){
                var headers = [];

                headers.push({titulo: 'Nombre', nombre: 'nombre'});
                // headers.push({titulo: 'Tamaño', nombre: 'size'});
                // headers.push({titulo: 'Color', nombre: 'color'});
                // headers.push({titulo: 'Costo', nombre: 'costo_produccion'});
                headers.push({titulo: 'Precio normal', nombre: 'precio_menor'});
                headers.push({titulo: 'Precio al por mayor', nombre: 'precio_mayor'});
                headers.push({titulo: 'Stock', nombre: 'stock'});

                return headers;
            },
            permisoModalFooter: function(){
                if ( this.Modal.numero == 1 ) return true;
                if ( this.Modal.numero == 2 ) return true;
                if ( this.Modal.numero == 3 ) return true;

                return false;
            }
        },
        methods: {
            listar(page = 1, ordenarPor = ''){
                if ( ordenarPor == this.Navegacion.ordenarPor ) {
                    this.Navegacion.orden = (this.Navegacion.orden == 'asc'?'desc':'asc');
                } else {
                    this.Navegacion.ordenarPor = ordenarPor!=''?ordenarPor:this.Navegacion.ordenarPor;
                    // this.Navegacion.orden = 'asc';
                }
                this.Paginacion.currentPage = page;

                var url = this.Ruta+'?'
                        +'page='+this.Paginacion.currentPage
                        +'&estado='+this.Busqueda.estado
                        +'&texto='+this.Busqueda.texto
                        +'&filas='+this.Busqueda.filas
                        +'&ordenarPor='+this.Navegacion.ordenarPor
                        +'&orden='+this.Navegacion.orden;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaProducto = response.data.productos.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            agregar(){
                if ( this.validar() ) return;
                
                var me = this;
                var url = this.Ruta+'/agregar';
                axios.post(url , {
                    'superproducto_id' : this.Producto.supreproducto_id,
                    'descripcion' : this.Producto.descripcion,
                    'size': this.Producto.size,
                    'color' : this.Producto.color,
                    'precio_menor' : this.Producto.precio_menor,
                    'precio_mayor' : this.Producto.precio_mayor
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    var estado = response.data.estado;
                    if ( estado == 0 ) console.log(response.data.error);
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: estado==1?'success':'info',
                        title: 'El producto'+ (estado==1?' ':' NO ') +'se ha AGREGADO correctamente',
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
                if ( this.validar() ) return;

                var me = this;
                axios.put('/producto/editar', {
                    'id' : this.Producto.id,
                    'nombre' : this.Producto.nombre,
                    'categoria_id' : this.Producto.categoria_id,
                    'precio' : this.Producto.precio,
                    'stock': this.Producto.stock,
                    'descripcion' : this.Producto.descripcion
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
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
                }).catch(function(error){
                    console.log(error);
                });
            },
            activar(producto = []){
                this.Producto.id = producto['id'];
                this.Producto.nombre = producto['nombre'];

                var me = this;

                Swal.fire({ 
                    title: '¿Esta seguro de ACTIVAR el producto "'+this.Producto.nombre+'"?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.value) {
                        var me = this;
                
                        axios.put('/producto/activar', {
                            'id' : me.Producto.id
                        }).then(function (response) {
                            me.cerrarModal();
                            me.listar();
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                type: 'success',
                                title: 'El producto se ha ACTIVADO correctamente',
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
            desactivar(producto = []){
                this.Producto.id = producto['id'];
                this.Producto.nombre = producto['nombre'];

                var me = this;
                
                Swal.fire({
                    title: '¿Esta seguro de DESACTIVAR el producto "'+this.Producto.nombre+'"?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.value) {
                        var me = this;
                
                        axios.put('/producto/desactivar', {
                            'id' : me.Producto.id
                        }).then(function (response) {
                            me.cerrarModal();
                            me.listar();
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                type: 'success',
                                title: 'El producto se ha ACTIVADO correctamente',
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
                this.abrirModal(1, 'Nuevo Producto', 'Agregar');

                this.Producto.superproducto_id = 0;
                this.Producto.descripcion = '';
                this.Producto.size = '';
                this.Producto.color = '';
                this.Producto.precio_menor = 0;
                this.Producto.precio_mayor = 0;

                this.selectSuperProducto();
                this.selectSize();
                this.selectColor();
            },
            abrirModalVer(data = []){
                this.abrirModal(1, 'Ver Producto');
                
                this.Producto.nombre = data['nombre'];
                this.Producto.codigo = data['codigo'];
                this.Producto.descripcion = data['descripcion'];
                this.Producto.size = data['size'];
                this.Producto.color = data['color'];
                this.Producto.costo_produccion = data['costo_produccion'];
                this.Producto.precio_menor = data['precio_menor'];
                this.Producto.precio_mayor = data['precio_mayor'];
                this.Producto.stock = data['stock'];
                this.Producto.estado = data['estado'];
                this.Producto.created_at = data['created_at'];
            },
            abrirModalEditar(data = []){
                this.abrirModal(2, 'Editar Producto', 'Editar');
                
                this.Producto.id = data['id'];
                this.Producto.categoria_id = data['categoria_id'];
                this.Producto.nombre = data['nombre'];
                this.Producto.precio = data['precio'];
                this.Producto.stock = data['stock'];
                this.Producto.descripcion = data['descripcion'];

                this.selectCategoria();
            },
            abrirModal(numero, titulo, accion){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.accion = accion;
            },
            cerrarModal(){
                this.Modal.estado = 0;
                this.Modal.mensaje = [];

                this.Error.estado = 0;
                this.Error.mensaje = [];

                this.Producto.id = 0;
                this.Producto.superproducto_id = 0;
                this.Producto.nombre = '';
                this.Producto.codigo = '';
                this.Producto.descripcion = '';
                this.Producto.size = '';
                this.Producto.color = '';
                this.Producto.costo_produccion = 0;
                this.Producto.precio_menor = 0;
                this.Producto.stock = 0;
                this.Producto.estado = 0;
                this.Producto.created_at = '';

                this.SelectSuperProducto = [];
                this.SelectSize = [];
                this.SelectColor = [];
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
                    case 'Activar': {
                        this.activar();
                        break;
                    }
                    case 'Desactivar': {
                        this.desactivar();
                        break;
                    }
                }
            },
            selectSuperProducto(){
                var me = this;
                var url = this.Ruta+'/selectSuperProducto';

                axios.get(url).then(function(response){
                    me.SelectSuperProducto = response.data;
                }).catch(function(error){
                    console.log(error);
                });
            },
            selectSize(){
                var me = this;
                var url = this.Ruta+'/selectSize';

                axios.get(url).then(function(response){
                    me.SelectSize = response.data;
                }).catch(function(error){
                    console.log(error);
                });
            },
            selectColor(){
                var me = this;
                var url = this.Ruta+'/selectColor';

                axios.get(url).then(function(response){
                    me.SelectColor = response.data;
                }).catch(function(error){
                    console.log(error);
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
            validar(){
                this.Error.estado = 0;
                this.Error.mensaje = [];

                //superproducto
                if ( !this.Producto.superproducto == 0 ) this.Error.mensaje.push("Debe seleccionar un superproducto");
                //size
                if ( this.Producto.size == 0 ) this.Error.mensaje.push("Debe seleccionar un tamaño");
                //color
                if ( this.Producto.color == 0 ) this.Error.mensaje.push("Debe seleccionar un color");
                //precio_menor
                if ( this.Producto.precio_menor == 0) this.Error.mensaje.push("Debe ingresar un precio normal");
                //precio_mayor
                if ( this.Producto.precio_mayor == 0) this.Error.mensaje.push("Debe ingresar un precio al por mayor");

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