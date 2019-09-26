<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-fluid">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Usuarios</span>
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
                            <option value="1">Activados</option>
                            <option value="0">Desactivados</option>
                            <option value="2">Todos</option>
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
                    <select class="form-control text-gray-900" v-model="Busqueda.items" @click="listar()">
                        <option v-for="item in Items" :key="item" :value="item" v-text="item"></option>
                    </select>
                </div>
            </div>

            <!-- Listado -->
            <div v-if="ListaUsuario.length" class="table-responsive">
                <!-- Tabla -->
                <div id="ec-table">
                    <table class="table table-bordered table-striped table-sm text-gray-900">
                        <thead>
                            <tr>
                                <th v-for="head in Headers" :key="head.nombre" @click="listar(1, head.nombre)" class="ec-cursor" v-text="getTitulo(head.titulo)"></th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="usuario in ListaUsuario" :key="usuario.id" >
                                <td v-text="usuario.nombre"></td>
                                <td v-text="usuario.usuario"></td>
                                <td v-text="usuario.direccion==null?'-':usuario.direccion"></td>
                                <td v-text="usuario.rol"></td>
                                <td v-text="usuario.fecha_creacion==null?'-':usuario.fecha_creacion"></td>
                                <td v-text="usuario.fecha_actualizacion==null?'-':usuario.fecha_actualizacion"></td>
                                <td v-text="usuario.fecha_eliminacion==null?'-':usuario.fecha_eliminacion"></td>
                                <td>
                                    <div v-if="usuario.estado">
                                        <span class="badge badge-success">Activado</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" @click="abrirModalEditar(usuario)" title="Editar" class="btn btn-warning btn-sm">
                                        <i class="fas fa-user-edit"></i>
                                    </button>
                                    <template v-if="usuario.estado">
                                        <button type="button" @click="abrirModalDesactivar(usuario)" title="Desactivar" class="btn btn-danger btn-sm">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="abrirModalActivar(usuario)" title="Activar" class="btn btn-success btn-sm">
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
                                    <input type="text" v-model="Usuario.nombre" class="form-control" placeholder="ingrese el nombre" id="nom">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="dir">Direccion</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Usuario.direccion" class="form-control" placeholder="ingrese la direccion" id="dir">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="rol">Rol&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select v-model="Usuario.rol_id" class="form-control" id="rol">
                                        <option value="0" disabled>seleccione un rol</option>
                                        <option v-for="rol in SelectRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="user">Usuario&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Usuario.usuario" class="form-control" placeholder="ingrese el usuario" id="user">
                                </div>
                            </div>
                            <div class="row form-group font-weight-bold">
                                <label class="col-md-3" for="contra">Contraseña&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="password" v-model="Usuario.password" class="form-control" placeholder="ingrese la contraseña" id="contra">
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
                                    <input type="text" v-model="Usuario.nombre" class="form-control" placeholder="ingrese el nombre" id="nom">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="dir">Direccion</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Usuario.direccion" class="form-control" placeholder="ingrese la direccion" id="dir">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="rol">Rol&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select v-model="Usuario.rol_id" class="form-control" id="rol">
                                        <option value="0" disabled>seleccione un rol</option>
                                        <option v-for="rol in SelectRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div v-if="Credencial.comprobado==0">
                                <div class="row form-group">
                                    <label class="col-md-12 alert alert-warning">Si desea editar las credenciales, ingrese su contraseña de administrador</label>
                                    <label class="col-md-3 font-weight-bold" for="cont">Contraseña</label>
                                    <div class="col-md-5">
                                        <input type="password" v-model="Credencial.password" class="form-control" id="cont" @keyup.enter="comprobar()">
                                    </div>
                                    <button type="button" @click="comprobar()" class="btn btn-success">Comprobar</button>
                                </div>
                            </div>
                            <div v-else>
                                <label class="col-md-12 alert alert-success">Tiene acceso a la edición de las credenciales</label>
                                <div class="row form-group">
                                    <label class="col-md-3 font-weight-bold" for="user">Usuario&nbsp;<span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="Usuario.usuario" class="form-control" placeholder="ingrese el usuario" id="user">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3 font-weight-bold" for="contra">Contraseña&nbsp;<span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="Usuario.password" class="form-control" placeholder="ingrese la contraseña" id="contra">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Numero 3 de ACTIVAR-->
                        <div v-if="Modal.numero==3">
                            <div v-if="Credencial.comprobado==0">
                                <div class="row form-group">
                                    <label class="col-md-12 alert alert-success">Si desea ACTIVAR el usuario "{{Usuario.nombre}}", ingrese su contraseña de administrador</label>
                                    <label class="col-md-3 font-weight-bold" for="cont">Contraseña</label>
                                    <div class="col-md-5">
                                        <input type="password" v-model="Credencial.password" class="form-control" id="cont" @keyup.enter="comprobar()">
                                    </div>
                                    <button type="button" @click="comprobar()" class="btn btn-success">Comprobar</button>
                                </div>
                            </div>
                            <div v-else>
                                <label class="col-md-12 alert alert-success">De click en ACTIVAR para confirmar la activacion del usuario "{{Usuario.nombre}}"</label>
                            </div>
                        </div>
                        <!-- Modal Numero 4 de DESACTIVAR-->
                        <div v-if="Modal.numero==4">
                            <div v-if="Credencial.comprobado==0">
                                <div class="row form-group">
                                    <label class="col-md-12 alert alert-danger">Si desea DESACTIVAR el usuario "{{Usuario.nombre}}", ingrese su contraseña de administrador</label>
                                    <label class="col-md-3 font-weight-bold" for="cont">Contraseña</label>
                                    <div class="col-md-5">
                                        <input type="password" v-model="Credencial.password" class="form-control" id="cont" @keyup.enter="comprobar()">
                                    </div>
                                    <button type="button" @click="comprobar()" class="btn btn-success">Comprobar</button>
                                </div>
                            </div>
                            <div v-else>
                                <label class="col-md-12 alert alert-success">De click en DESACTIVAR para confirmar la desactivacion del usuario "{{Usuario.nombre}}"</label>
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
                //datos de administrador
                Credencial:{
                    password: '',
                    comprobado: 0
                },

                //datos generales
                ListaUsuario: [],
                Usuario: {
                    id: 0,
                    usuario: '',
                    password: '',
                    nombre: '',
                    direccion: '',
                    tipo: '',
                    rol_id: 0,
                },
                SelectRol: [],

                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 2,
                    items: 5
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
                    ordenarPor: 'rol.nombre',
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
            Items: function(){
                var min = 3;
                var max = 20;
                var items = [];

                while ( min <= max) {
                    items.push(min);
                    min++;
                }

                return items;
            },
            Headers: function(){
                var headers = [];

                headers.push({titulo: 'Nombre', nombre: 'persona.nombre'});
                headers.push({titulo: 'Usuario', nombre: 'usuario.usuario'});
                headers.push({titulo: 'Direccion', nombre: 'persona.direccion'});
                headers.push({titulo: 'Rol', nombre: 'rol.nombre'});
                headers.push({titulo: 'F. Creacion', nombre: 'persona.created_at'});
                headers.push({titulo: 'F. Modificacion', nombre: 'persona.updated_at'});
                headers.push({titulo: 'F. Eliminacion', nombre: 'persona.deleted_at'});

                return headers;
            },
            permisoModalFooter: function(){
                if ( this.Modal.numero == 1 ) return true;
                if ( this.Modal.numero == 2 ) return true;
                if ( this.Modal.numero == 3 && this.Credencial.comprobado == 1 ) return true;
                if ( this.Modal.numero == 4 && this.Credencial.comprobado == 1 ) return true;

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

                var url = '/usuario?page='+this.Paginacion.currentPage
                        +'&estado='+this.Busqueda.estado
                        +'&texto='+this.Busqueda.texto
                        +'&items='+this.Busqueda.items
                        +'&ordenarPor='+this.Navegacion.ordenarPor
                        +'&orden='+this.Navegacion.orden;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaUsuario = response.data.usuarios.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            agregar(){
                if ( this.validar() ) return;
                
                this.Usuario.tipo = this.getTipo(this.Usuario.rol_id);

                var me = this;
                axios.post('usuario/agregar', {
                    'usuario' : this.Usuario.usuario,
                    'password' : this.Usuario.password,
                    'nombre' : this.Usuario.nombre,
                    'direccion' : this.Usuario.direccion,
                    'tipo': this.Usuario.tipo,
                    'rol_id' : this.Usuario.rol_id
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: 'success',
                        title: 'El usuario se ha AGREGADO correctamente',
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

                this.Usuario.tipo = this.getTipo(this.Usuario.rol_id);
                if ( this.Credencial.comprobado == 0){
                    this.Usuario.usuario = '';
                    this.Usuario.password = '';
                } 

                var me = this;
                axios.put('usuario/editar', {
                    'id' : this.Usuario.id,
                    'usuario' : this.Usuario.usuario,
                    'password' : this.Usuario.password,
                    'nombre' : this.Usuario.nombre,
                    'direccion' : this.Usuario.direccion,
                    'tipo': this.Usuario.tipo,
                    'rol_id' : this.Usuario.rol_id
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: 'success',
                        title: 'El usuario se ha EDITADO correctamente',
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
            activar(){
                var me = this;

                if ( this.Credencial.comprobado == 1 ) {
                    axios.put('/usuario/activar', {
                        'id' : this.Usuario.id
                        
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'El usuario se ha ACTIVADO correctamente',
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
                }
            },
            desactivar(){
                var me = this;

                if ( this.Credencial.comprobado == 1 ) {
                    axios.put('/usuario/desactivar', {
                        'id' : this.Usuario.id
                        
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'El usuario se ha DESACTIVADO correctamente',
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
                }
            },
            abrirModalAgregar(){
                this.abrirModal(1, 'Nuevo Usuario', 'Agregar');

                this.Usuario.usuario = '';
                this.Usuario.password = '';
                this.Usuario.nombre = '';
                this.Usuario.direccion = '';
                this.Usuario.tipo = '';
                this.Usuario.rol_id = 0;

                this.selectRol();
            },
            abrirModalEditar(data = []){
                this.abrirModal(2, 'Editar Usuario', 'Editar');
                
                this.Usuario.id = data['id'];
                this.Usuario.usuario = data['usuario'];
                this.Usuario.password = '';
                this.Usuario.nombre = data['nombre'];
                this.Usuario.direccion = data['direccion'];
                this.Usuario.tipo = '';
                this.Usuario.rol_id = data['rol_id'];

                this.selectRol();
            },
            abrirModalActivar(data = []){
                this.abrirModal(3, 'Activar Usuario', 'Activar');

                this.Credencial.password = '';

                this.Usuario.id = data['id'];
                this.Usuario.nombre = data['nombre'];
            },
            abrirModalDesactivar(data = []){
                this.abrirModal(4, 'Desactivar Usuario', 'Desactivar');

                this.Credencial.password = '';

                this.Usuario.id = data['id'];
                this.Usuario.nombre = data['nombre'];
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

                this.Credencial.password = '';
                this.Credencial.comprobado = 0;

                this.Usuario.id = 0;
                this.Usuario.usuario = '';
                this.Usuario.password = '';
                this.Usuario.nombre = '';
                this.Usuario.direccion = '';
                this.Usuario.tipo = '';
                this.Usuario.rol_id = 0;

                this.SelectRol = [];
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
            selectRol(){
                var me = this;
                var url = 'usuario/selectRol';

                axios.get(url).then(function(response){
                    me.SelectRol = response.data;
                }).catch(function(error){
                    console.log(error);
                });
            },
            comprobar(){
                var me = this;
                var url = 'usuario/comprobar?password='+this.Credencial.password;

                axios.get(url).then(function(response){
                    me.Credencial.comprobado = response.data;
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
                if ( !this.Usuario.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");
                //rol
                if ( this.Usuario.rol_id == 0 ) this.Error.mensaje.push("Debe seleccionar un rol");

                if ( this.Modal.accion == 'Agregar' || (this.Modal.accion == 'Editar' && this.Credencial.comprobado == 1) ) {
                    //usuario
                    if ( !this.Usuario.usuario ) this.Error.mensaje.push("Debe ingresar un usuario");
                    //password
                    if ( !this.Usuario.password ) this.Error.mensaje.push("Debe ingresar un password");
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
    #ec-table{
        overflow: scroll;
        height: 20rem;
        width: 100%;
    }
</style>