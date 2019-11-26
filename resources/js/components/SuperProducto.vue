<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-briefcase"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Super Producto</span>&nbsp;&nbsp;
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
                        <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                    </select>
                </div>
            </div>

            <!-- Listado -->
            <div v-if="ListaSuperProducto.length" class="table-responsive">
                <!-- Tabla -->
                <div class="ec-table overflow-auto">
                    <table class="table table-bordered table-striped table-sm text-gray-900">
                        <thead>
                            <tr class="ec-th">
                                <th v-for="head in Headers" :key="head.nombre" @click="listar(1, head.nombre)" class="ec-cursor" v-text="getTitulo(head.titulo)"></th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="superproducto in ListaSuperProducto" :key="superproducto.id" >
                                <td v-text="superproducto.nombre"></td>
                                <td v-text="superproducto.descripcion==null?'-':superproducto.descripcion"></td>
                                <td v-text="superproducto.superstock"></td>
                                <td>
                                    <div v-if="superproducto.estado">
                                        <span class="badge badge-success">Activado</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" @click="abrirModalVer(superproducto)" title="VER" class="btn btn-warning btn-sm">
                                        <i class="fas fa-user-lupa"></i>
                                    </button>
                                    <button type="button" @click="abrirModalEditar(superproducto)" title="EDITAR" class="btn btn-warning btn-sm">
                                        <i class="fas fa-user-edit"></i>
                                    </button>
                                    <template v-if="superproducto.estado">
                                        <button type="button" @click="desactivar(superproducto)" title="DESACTIVAR" class="btn btn-danger btn-sm">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="activar(superproducto)" title="ACTIVAR" class="btn btn-success btn-sm">
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
                <div class="modal-content">

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
                            <div>SUPERPRODUCTO</div>
                            <div class="row form-group">
                                <label class="col-md-1 font-weight-bold" for="nom">Nombre&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" v-model="SuperProducto.nombre" class="form-control" placeholder="ingrese el nombre" id="nom">
                                </div>
                                <label class="col-md-2 font-weight-bold" for="des">Descripcion</label>
                                <div class="col-md-4">
                                    <input type="text" v-model="SuperProducto.descripcion" class="form-control" placeholder="ingrese la descripcion" id="des">
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
                                    <input type="text" v-model="SuperProducto.nombre" class="form-control" placeholder="ingrese el nombre" id="nom">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="des">Descripcion</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="SuperProducto.descripcion" class="form-control" placeholder="ingrese la descripcion" id="des">
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
                ListaSuperProducto: [],
                SuperProducto: {
                    id: 0,
                    nombre: '',
                    descripcion: '',
                    estado: 0
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
                    ordenarPor: 'superproducto.created_at',
                    orden: 'desc' 
                },

                //datos de errores
                Error: {
                    estado: 0,
                    mensaje: []
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
            Headers: function(){
                var headers = [];

                headers.push({titulo: 'Nombre', nombre: 'nombre'});
                headers.push({titulo: 'Descripcion', nombre: 'descripcion'});
                headers.push({titulo: 'Stock total', nombre: 'superstock'});

                return headers;
            },
            permisoModalFooter: function(){
                if ( this.Modal.numero == 1 ) return true;
                if ( this.Modal.numero == 2 ) return true;

                return false;
            }
        },
        methods: {
            listar(page = 1, ordenarPor = ''){
                if ( ordenarPor == this.Navegacion.ordenarPor ) {
                    this.Navegacion.orden = (this.Navegacion.orden == 'asc'?'desc':'asc');
                } else {
                    this.Navegacion.ordenarPor = ordenarPor!=''?ordenarPor:this.Navegacion.ordenarPor;
                    this.Navegacion.orden = 'asc';
                }
                this.Paginacion.currentPage = page==1?1:page;

                var url = '/superproducto?'
                        +'page='+this.Paginacion.currentPage
                        +'&estado='+this.Busqueda.estado
                        +'&texto='+this.Busqueda.texto
                        +'&filas='+this.Busqueda.filas
                        +'&ordenarPor='+this.Navegacion.ordenarPor
                        +'&orden='+this.Navegacion.orden;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaSuperProducto = response.data.superproductos.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            agregar(){
                if ( this.validar() ) return;
                
                var me = this;
                axios.post('/superproducto/agregar', {
                    'nombre' : this.SuperProducto.nombre,
                    'descripcion' : this.SuperProducto.descripcion,
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    var estado = response.data.estado;
                    if ( estado == 0 ) console.log(response.data.error);
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: estado==1?'success':'info',
                        title: estado==1?'El super producto se ha AGREGADO correctamente':'El super producto NO se ha AGREGADO correctamente',
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
                axios.put('/superproducto/editar', {
                    'id' : this.SuperProducto.id,
                    'nombre' : this.SuperProducto.nombre,
                    'descripcion' : this.SuperProducto.descripcion
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    var estado = response.data.estado;
                    if ( estado == 0 ) console.log(response.data.error);
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: estado==1?'success':'info',
                        title: estado==1?'El super producto se ha EDITADO correctamente':'El super producto NO se ha EDITADO correctamente',
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
            activar(superproducto = []){
                this.SuperProducto.id = superproducto['id'];
                this.SuperProducto.nombre = superproducto['nombre'];

                Swal.fire({
                    title: '¿Esta seguro de ACTIVAR el super producto "'+this.SuperProducto.nombre+'"?',
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
                
                        axios.put('/superproducto/setEstado', {
                            'id' : me.SuperProducto.id,
                            'estado': 1
                        }).then(function (response) {
                            me.listar();
                            var estado = response.data.estado;
                            if ( estado == 0 ) console.log(response.data.error);    
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                type: estado==1?'success':'info',
                                title: estado==1?'El super producto se ha ACTIVADO correctamente':'El super producto NO se ha ACTIVADO correctamente',
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
            desactivar(superproducto = []){
                this.SuperProducto.id = superproducto['id'];
                this.SuperProducto.nombre = superproducto['nombre'];

                Swal.fire({
                    title: '¿Esta seguro de DESACTIVAR el super producto "'+this.SuperProducto.nombre+'"?',
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
                
                        axios.put('/superproducto/setEstado', {
                            'id' : me.SuperProducto.id,
                            'estado': 0
                        }).then(function (response) {
                            me.listar();
                            var estado = response.data.estado;
                            if ( estado == 0 ) console.log(response.data.error);    
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                type: estado==1?'success':'info',
                                title: estado==1?'El super producto se ha DESACTIVADO correctamente':'El super producto NO se ha DESACTIVADO correctamente',
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
                this.abrirModal(1, 'Nuevo Super Producto', 'Agregar');

                this.SuperProducto.nombre = '';
                this.SuperProducto.descripcion = '';
            },
            abrirModalEditar(data = []){
                this.abrirModal(2, 'Editar Super Producto', 'Editar');
                
                this.SuperProducto.id = data['id'];
                this.SuperProducto.nombre = data['nombre'];
                this.SuperProducto.descripcion = data['descripcion'];
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

                this.SuperProducto.id = 0;
                this.SuperProducto.nombre = '';
                this.SuperProducto.descripcion = '';
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
            getTipo(rol_id){
                var nombre = '';

                for (let i = 0; i < this.SelectRol.length; i++) {
                    if ( this.SelectRol[i].id == rol_id ){
                        nombre = this.SelectRol[i].nombre;
                        break;
                    }
                }

                switch ( nombre ) {
                    case 'Administrador': {
                        return 'N';
                    }
                    case 'Puesto': {
                        return 'P';
                    }
                    case 'Almacén': {
                        return 'A';
                    }
                    default: {
                        console.log('ERROR: no se encontro el nombre de rol para definir el tipo de usuario');
                    }
                }
            },
            validar(){
                this.Error.estado = 0;
                this.Error.mensaje = [];

                //nombre
                if ( !this.SuperProducto.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");

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
    .modal-length{
        width: 900px !important;
    }
</style>