<template>
    <main>

        <!-- Interfaz Principal -->
        <div>
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <div class="col-md-12">
                    <i class="fas fa-briefcase"></i>&nbsp;&nbsp;
                    <span class="h3 mb-0 text-gray-900">Categorias</span>&nbsp;&nbsp;
                    <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                        <i class="fas fa-user-plus"></i>&nbsp; Nuevo
                    </button>
                    <button type="button" class="btn btn-danger" @click="generatePdf()">
                        <i class="far fa-file-pdf"></i>&nbsp; PDF
                    </button>
                </div>
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-5">
                    <div class="input-group"> 
                        <input type="search" class="form-control" v-model="Busqueda.texto" @keyup="Busqueda.texto.length >=5 || Busqueda.texto.length == 0 ? listar() : ''" placeholder="Buscar por NOMBRE">
                        <button type="button" class="btn btn-primary" @click="listar()">
                            <i class="fa fa-search"></i>&nbsp; Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-5"></div>
                <div class="col-md-1" align="right">
                    <label>N° filas:</label>
                </div>
                <div class="col-md-1">
                    <select class="form-control text-gray-900" v-model="Busqueda.filas">
                        <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                    </select>
                </div>
            </div>

            <!-- Listado -->
            <div v-if="ListaCategorias.length" class="table-responsive">
                <!-- Tabla -->
                <div class="ec-table overflow-auto">
                    <table class="table table-bordered table-condensed table-striped table-sm text-gray-900">
                        <thead>
                            <tr class="ec-th">
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th>Stock Total</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="categoria in ListaCategorias" :key="categoria.id" >
                                <td v-text="categoria.nombre"></td>
                                <td v-text="categoria.descripcion==null?'-':categoria.descripcion"></td>
                                <td v-text="categoria.stock"></td>
                                <td>
                                    <button type="button" @click="abrirModalVer(categoria)" title="VER" class="btn btn-primary btn-sm">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    <button type="button" @click="abrirModalEditar(categoria)" title="EDITAR" class="btn btn-warning btn-sm">
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

        <!-- Modal: Agregar, Ver, Editar -->
        <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animated bounceIn fast" :class="Modal.tamaño">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Modal Numero 1 de AGREGAR-->
                        <div v-if="Modal.numero==1">
                            <div v-if="Error.estado && (Error.numero==1 || Error.numero==3)" class="row d-flex justify-content-center">
                                <div class="alert alert-danger">
                                    <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4 input-group">
                                    <label class="col-md-4" for="nom">Nombre&nbsp;<span class="text-danger">*</span></label>
                                    <input type="text" class="col-md-8 form-control form-control-sm" v-model="Categoria.nombre" placeholder="Ingrese el nombre" id="nom">   
                                </div>
                                <div class="col-md-8 input-group">
                                    <label class="col-md-2" for="des">Descripción</label>
                                    <input type="text" class="col-md-10 form-control form-control-sm" v-model="Categoria.descripcion" placeholder="Ingrese la descripcion" id="des">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <div v-if="Error.estado && Error.numero==2" class="row d-flex justify-content-center">
                                        <div class="alert alert-danger">
                                            <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                                            <strong>Corregir los siguentes errores:</strong>
                                            <ul> 
                                                <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-12 font-weight-bold">AGREGAR PRODUCTO</label>
                                    </div>
                                    <div class="row form-group">
                                        <label class="col-md-6" for="pme">Precio al por menor&nbsp;<span class="text-danger">*</span></label>
                                        <input type="number" class="col-md-6 form-control form-control-sm" min="0" v-model="Producto.precio_menor" placeholder="Ingrese el precio al por menor" id="pme">
                                    </div>
                                    <div class="row form-group">
                                        <label class="col-md-6" for="pma">Precio al por mayor&nbsp;<span class="text-danger">*</span></label>
                                        <input type="number" class="col-md-6 form-control form-control-sm" min="0" v-model="Producto.precio_mayor" placeholder="Ingrese el precio al por mayor" id="pma">
                                    </div>
                                    <div class="row d-flex justify-content-center">
                                        <button type="button" class="btn btn-sm btn-primary btn-icon-split" @click="agregarProducto()">
                                            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                                            <span class="text font-weight-bold">Agregar</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row col-md-12">
                                        <label class="font-weight-bold">LISTA DE PRODUCTOS</label>
                                    </div>
                                    <div class="row col-md-12">
                                        <div v-if="ListaProducto.length" class="col-md-12">
                                            <table class="table table-bordered table-striped table-sm text-gray-900">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <th>Quitar</th>
                                                        <th>Precio al por menor</th>
                                                        <th>Precio al por mayor</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(producto, indice) in ListaProducto" :key="indice" >
                                                        <td>
                                                            <button type="button" class="btn btn-circle btn-outline-danger btn-sm" @click="eliminarProducto(indice)" title="QUITAR">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </td>
                                                        <td v-text="producto.precio_menor"></td>
                                                        <td v-text="producto.precio_mayor"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div v-else>
                                            <label class="col-md-12 text text-danger">Sin productos</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Numero 2 de VER-->
                        <div v-if="Modal.numero==2">
                            <div class="row form-group">
                                <label class="col-md-2">Nombre:</label>
                                <label class="col-md-5" v-text="Categoria.nombre"></label>
                                <label class="col-md-5">Fecha de creacion:   {{Categoria.created_at}}</label>
                                <label class="col-md-2">Descripcion:</label>
                                <label class="col-md-10" v-text="Categoria.descripcion?Categoria.descripcion:'-'"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-9 font-weight-bold">LISTA DE PRODUCTOS</label>
                                <label class="col-md-3">Stock Total:   {{Categoria.stock}}</label>
                                <div class="col-md-12" v-if="ListaProducto.length">
                                    <table class="table table-bordered table-striped table-sm text-gray-900">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Costo de produccion</th>
                                                <th>Precio al por menor</th>
                                                <th>Precio al por mayor</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(producto, indice) in ListaProducto" :key="indice" >
                                                <td v-text="producto.costo_produccion?producto.costo_produccion:'-'"></td>
                                                <td v-text="producto.precio_menor"></td>
                                                <td v-text="producto.precio_mayor"></td>
                                                <td v-text="producto.stock"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12" v-else>
                                    <label class="text text-danger">Sin productos</label>
                                </div>
                            </div>
                            
                        </div>
                        <!-- Modal Numero 3 de EDITAR-->
                        <div v-if="Modal.numero==3">
                            <div v-if="Error.estado && (Error.numero==1 || Error.numero==3 || Error.numero==4)" class="row d-flex justify-content-center">
                                <div class="alert alert-danger">
                                    <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3" for="nom">Nombre&nbsp;<span class="text-danger">*</span></label>
                                <input type="text" class="col-md-9 form-control form-control-sm" v-model="Categoria.nombre" placeholder="Ingrese el nombre" id="nom">
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3" for="des">Descripcion</label>
                                <input type="text" class="col-md-9 form-control form-control-sm" v-model="Categoria.descripcion" placeholder="Ingrese la descripcion" id="des">
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
                                <button type="button" @click="cerrarModal()" class="btn btn-secondary font-weight-bold" v-text="Modal.btnC"></button>
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
                //datos de Categorias
                ListaCategorias: [],
                Categoria: {
                    id: 0,
                    nombre: '',
                    descripcion: '',
                    stock: 0,
                    created_at: ''
                },

                //datos de producto
                ListaProducto: [],
                Producto : {
                    id: 0,
                    precio_menor: 0.00,
                    precio_mayor: 0.00,
                },

                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 2,
                    filas: 5
                },

                //datos de modales
                Modal: {
                    numero: 0,
                    estado: 0,
                    titulo: '',
                    btnA: '', //Boton de aceptar
                    btnC: '', //Boton de cancelar
                    tamaño: ''
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
                    numero: 0,
                    mensaje: []
                },

                Button: {
                    press: false
                },
                //datos de la ruta de consultas
                Ruta: {
                    categoria: '/categoria',
                    producto: '/producto',
                    data: '/data',
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
            }
        },
        methods: {
            listar(page = 1){
                this.Paginacion.currentPage = page;

                var me = this;
                var url = this.Ruta.categoria+'?'
                        +'page='+this.Paginacion.currentPage
                        +'&texto='+this.Busqueda.texto
                        +'&filas='+this.Busqueda.filas;
                
                axios.get(url).then(response => {
                    this.ListaCategorias = response.data.categorias.data;
                    this.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            agregar(){
                if ( this.validar(1) ) return;
                
                var me = this;
                var url = this.Ruta.categoria+'/agregar';
                axios.post(url, {
                    'nombre' : this.Categoria.nombre,
                    'descripcion' : this.Categoria.descripcion,
                    'listaproducto': this.ListaProducto
                }).then(function(response){
                    var estado = response.data.estado;
                    if ( estado ) {
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'La categoria se ha AGREGADO correctamente',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    } else {
                        console.log(response.data.error);
                        me.validar(3);
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            editar(){
                if ( this.validar(1) ) return;
                if ( this.validar(4) ) return;

                var me = this;
                var url = this.Ruta.categoria+'/editar';
                axios.put(url, {
                    'id' : this.Categoria.id,
                    'nombre' : this.Categoria.nombre,
                    'descripcion' : this.Categoria.descripcion
                }).then(function(response){
                    var estado = response.data.estado;
                    if ( estado ) {
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'La categoria se ha EDITADO correctamente',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    } else {
                        console.log(response.data.error);
                        me.validar(3);
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            agregarProducto(){
                if ( this.validar(2) ) return;
                
                let producto = {
                    id: 0,
                    precio_menor: Number.parseFloat(this.Producto.precio_menor).toFixed(2),
                    precio_mayor: Number.parseFloat(this.Producto.precio_mayor).toFixed(2)
                };
                
                this.ListaProducto.push(producto);

                this.Producto.precio_menor = 0.00;
                this.Producto.precio_mayor = 0.00;
            },
            eliminarProducto(indice){
                this.ListaProducto.splice(indice, 1);
            },
            // setEstado(){
            //     var me = this;
            //     var url = this.Ruta+'/setEstado';

            //     axios.put(url, {
            //         'id' : me.Categoria.id,
            //         'estado': me.Categoria.estado
            //     }).then(function (response) {
            //         me.listar();
            //         var estado = response.data.estado;
            //         if ( estado == 0 ) console.log(response.data.error);    
            //         Swal.fire({
            //             position: 'top-end',
            //             toast: true,
            //             type: estado==1?'success':'info',
            //             title: 'La categoria '+ (estado==1?' se ha':' NO se ha') + (me.Categoria.estado==1?' ACTIVADO':' DESACTIVADO') +' correctamente',
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
            abrirModalAgregar(){
                this.abrirModal(1, 'Nueva Categoria', 'modal-xl', 'Agregar Categoria', 'Cancelar');

                this.Categoria.nombre = '';
                this.Categoria.descripcion = '';

                this.Producto.id = 0;
                this.Producto.precio_menor = 0.00;
                this.Producto.precio_mayor = 0.00;

                this.ListaProducto = [];
            },
            abrirModalVer(data = []){
                this.abrirModal(2, 'Ver Categoria', 'modal-lg', '', 'Cerrar');
                
                this.Categoria.id = data['id'];
                this.Categoria.nombre = data['nombre'];
                this.Categoria.descripcion = data['descripcion'];
                this.Categoria.stock = data['stock'];
                this.Categoria.estado = data['estado'];
                this.Categoria.created_at = data['created_at'];

                this.listaProducto();
            },
            abrirModalEditar(data = []){
                this.abrirModal(3, 'Editar Categoria', '', 'Editar', 'Cancelar');
                
                this.Categoria.id = data['id'];
                this.Categoria.nombre = data['nombre'];
                this.Categoria.descripcion = data['descripcion'];

                this.DataCategoria = data;
            },
            // abrirModalSetEstado(data = [], estado){
            //     this.Categoria.id = data['id'];
            //     this.Categoria.nombre = data['nombre'];
            //     this.Categoria.estado = estado;

            //     Swal.fire({
            //         title: '¿Esta seguro de '+ (this.Categoria.estado?'ACTIVAR':'DESACTIVAR') +' la categoria "'+this.Categoria.nombre+'"?',
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonText: 'Aceptar',
            //         cancelButtonText: 'Cancelar',
            //         reverseButtons: false,
            //         customClass: {
            //             confirmButton: 'btn btn-success',
            //             cancelButton: 'btn btn-secondary'
            //         },
            //         buttonsStyling: false
            //     }).then((result) => {
            //         if (result.value) {
            //             this.setEstado();
            //         } else if ( result.dismiss === Swal.DismissReason.cancel ) {
            //         }
            //     });
            // },
            abrirModal(numero, titulo, tamaño, btnA, btnC){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.tamaño = tamaño;
                this.Modal.btnA = btnA;
                this.Modal.btnC = btnC;
            },
            cerrarModal(){
                this.Modal.estado = 0;
                this.Modal.numero = 0;
                this.Modal.titulo = '';
                this.Modal.tamaño = '';
                this.Modal.btnC = '';
                this.Modal.btnA = '';

                this.Error.estado = 0;
                this.Error.numero = 0;
                this.Error.mensaje = [];

                this.Categoria.id = 0;
                this.Categoria.nombre = '';
                this.Categoria.descripcion = '';
                this.Categoria.stock = 0;
                this.Categoria.created_at = '';

                this.Producto.id = 0;
                this.Producto.precio_menor = 0;
                this.Producto.precio_mayor = 0;

                this.Button.press = false;

                this.ListaProducto = [];
            },
            listaProducto(){
                var me = this;
                var url = this.Ruta.producto+'/listaProducto?id='+this.Categoria.id;
                
                axios.get(url).then(function (response) {
                    me.ListaProducto = response.data;
                });
            },
            accionar(){
                this.Button.press = true;
                switch( this.Modal.numero ){
                    case 1: 
                        this.agregar();
                        break;
                    case 3: 
                        this.editar();
                        break;
                }
            },
            validar(numero){
                this.Error.estado = 0;
                this.Error.numero = 0;
                this.Error.mensaje = [];

                switch (numero) {
                    case 1:
                        if ( !this.Categoria.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");   //nombre
                        break;
                    case 2:
                        var found = 0;
                        for (let i = 0; i < this.ListaProducto.length; i++) {
                            if ( this.Producto.nombre === this.ListaProducto[i].nombre ) {found = 1; break;}
                        }

                        if ( found ) {
                            this.Error.mensaje.push("Ese producto ya se encuentra en lista");                                           //producto existente
                        } else {
                            if ( this.Producto.precio_menor == 0 ) this.Error.mensaje.push("Debe ingresar un precio al por menor");     //precio_menor
                            if ( this.Producto.precio_menor < 0 ) this.Error.mensaje.push("El precio al por menor debe ser positivo");  //precio_menor
                            if ( this.Producto.precio_mayor == 0 ) this.Error.mensaje.push("Debe ingresar un precio al por mayor");     //precio_mayor
                            if ( this.Producto.precio_mayor < 0 ) this.Error.mensaje.push("El precio al por mayor debe ser positivo");  //precio_mayor
                        }
                        break;
                    case 3:
                        this.Error.mensaje.push("La categoria ya esta registrado");    //Categoria existente
                        break;
                    case 4:
                        for (let i = 0; i < this.ListaCategorias.length; i++) {
                            if ( this.Categoria.id == this.ListaCategorias[i].id ) {
                                if ( this.Categoria.nombre == this.ListaCategorias[i].nombre && this.Categoria.descripcion == this.ListaCategorias[i].descripcion ) {
                                    this.Error.mensaje.push("Ningun cambio realizado");    //sin cambios
                                }
                                break;
                            }
                        }
                        break;
                }

                if ( this.Error.mensaje.length ) {this.Error.estado = 1; this.Button.press = false;}
                this.Error.numero = numero;

                return this.Error.estado;
            },
            closeError(){
                this.Error.estado = 0;
                this.Error.numero = 0;
                this.Error.mensaje = [];
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
            },
            generatePdf(){
                window.open(this.Ruta.serverPhp + '/categoria/generatePdf','_blank');
                this.Button.press = false;
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
    .modal-length{
        width: 900px !important;
    }
</style>