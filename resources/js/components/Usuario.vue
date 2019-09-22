<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-fluid">
            <div class="card">
                <!-- Encabezado principal -->
                <div class="card-header">
                    <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                    <span class="h3 mb-0 text-gray-900">Usuarios</span>
                    <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                        <i class="fas fa-user-plus"></i>&nbsp; Nuevo
                    </button>
                    <button type="button" class="btn btn-danger">
                        <i class="far fa-file-pdf"></i>&nbsp; PDF
                    </button>
                </div>

                <div class="card-body">
                    <!-- Inputs de busqueda -->
                    <div class="row form-group">

                        <div class="col-md-8">
                            <div class="input-group">
                                <select class="col-md-3 form-control text-gray-900" v-model="Busqueda.estado" @click="listar(1)">
                                    <option value="1">Activados</option>
                                    <option value="0">Desactivados</option>
                                    <option value="2">Todos</option>
                                </select>
                                <input type="search" class="form-control" v-model="Busqueda.texto" @keyup.enter="listar(1)">
                                <button type="button" class="btn btn-primary" @click="listar(1)">
                                    <i class="fa fa-search"></i>&nbsp; Buscar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-1" align="right">
                            <label>N° filas:</label>
                        </div>
                        <div class="col-md-1">
                            <select class="form-control text-gray-900" v-model="Busqueda.items" @click="listar(1)">
                                <option v-for="item in Items" :key="item" :value="item" v-text="item"></option>
                            </select>
                        </div>
                    </div>
                    <!-- Listado -->
                    <div v-if="ListaUsuario.length" class="table-responsive">
                        <!-- Tabla -->
                        <table class="table table-bordered table-striped table-sm text-gray-900">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Usuario</th>
                                    <th>Direccion</th>
                                    <th>Rol</th>
                                    <th>F.creación</th>
                                    <th>F.modificación</th>
                                    <th>F.desactivación</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="usuario in ListaUsuario" :key="usuario.id" >
                                    <td v-text="usuario.nombre"></td>
                                    <td v-text="usuario.usuario"></td>
                                    <td v-text="usuario.direccion"></td>
                                    <td v-text="usuario.rol"></td>
                                    <td v-text="usuario.fecha_creacion"></td>
                                    <td v-text="usuario.fecha_actualizacion"></td>
                                    <td v-text="usuario.fecha_eliminacion"></td>
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
                                            <button type="button" @click="desactivar(usuario.id)" title="Desactivar" class="btn btn-danger btn-sm">
                                                <i class="fas fa-user-times"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" @click="activar(usuario.id)" title="Activar" class="btn btn-success btn-sm">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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

            </div>
        </div>

        <!-- Modales -->
        <div class="modal" :class="{'mostrar': Modal.estado}">
            <div class="modal-content modal-dialog modal-lg">
                
                <div class="modal-header">
                    <h3 v-text="Modal.titulo" class="modal-title"></h3>
                    <button type="button" @click="cerrarModal()" class="close">X</button>
                </div>
                
                <div class="modal-body">
                    <!-- Modal Numero 1 de AGREGAR-->
                    <div v-if="Modal.numero==1">
                        <div v-if="Error.estado" class="row form-group">
                            <div class="alert alert-danger">
                                <button type="button" @click="Error.estado=0" class="close" data-dismiss="alert">×</button>
                                <strong>Corregir los siguentes errores:</strong>
                                <ul> 
                                    <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                </ul>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Rol (*)</label>
                            <div class="col-md-9">
                                <select v-model="Usuario.rol_id" class="form-control">
                                    <option value="0" disabled>seleccione un rol</option>
                                    <option v-for="rol in SelectRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Nombre (*)</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.nombre" class="form-control" placeholder="ingrese el nombre">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Direccion</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.direccion" class="form-control" placeholder="ingrese la direccion">
                            </div>
                        </div>

                        <!-- <div class="row form-group">
                            <label class="col-md-3">DNI</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.dni" class="form-control" placeholder="ingrese el dni">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">RUC</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.ruc" class="form-control" placeholder="ingrese el ruc">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Telefono</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.telefono" class="form-control" placeholder="ingrese el telefono">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Email</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.email" class="form-control" placeholder="ingrese el email">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Fecha de cumpleaño</label>
                            <div class="col-md-9">
                                <input type="date" v-model="Usuario.birthday" class="form-control" placeholder="seleccione la fecha de cumpleaño">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Observacion</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.observacion" class="form-control" placeholder="ingrese la observacion">
                            </div>
                        </div> -->

                        <div class="row form-group">
                            <label class="col-md-3">Usuario (*)</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.usuario" class="form-control" placeholder="ingrese el usuario">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Contraseña (*)</label>
                            <div class="col-md-9">
                                <input type="password" v-model="Usuario.password" class="form-control" placeholder="ingrese la contraseña">
                            </div>
                        </div>
                    </div>
                    <!-- Modal Numero 2 de EDITAR-->
                    <div v-if="Modal.numero==2">
                        <div v-if="Error.estado" class="row form-group">
                            <div class="alert alert-danger">
                                <button type="button" @click="Error.estado=0" class="close" data-dismiss="alert">×</button>
                                <strong>Corregir los siguentes errores:</strong>
                                <ul> 
                                    <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                </ul>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Rol (*)</label>
                            <div class="col-md-9">
                                <select v-model="Usuario.rol_id" class="form-control">
                                    <option value="0" disabled>seleccione un rol</option>
                                    <option v-for="rol in SelectRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Nombre (*)</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.nombre" class="form-control" placeholder="ingrese el nombre">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Direccion</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.direccion" class="form-control" placeholder="ingrese la direccion">
                            </div>
                        </div>
                        
                        <!-- <div class="row form-group">
                            <label class="col-md-3">DNI</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.dni" class="form-control" placeholder="ingrese el dni">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">RUC</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.ruc" class="form-control" placeholder="ingrese el ruc">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Telefono</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.telefono" class="form-control" placeholder="ingrese el telefono">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Email</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.email" class="form-control" placeholder="ingrese el email">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Fecha de cumpleaño</label>
                            <div class="col-md-9">
                                <input type="date" v-model="Usuario.birthday" class="form-control" placeholder="seleccione la fecha de cumpleaño">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3">Observacion</label>
                            <div class="col-md-9">
                                <input type="text" v-model="Usuario.observacion" class="form-control" placeholder="ingrese la observacion">
                            </div>
                        </div> -->

                        <div v-if="Credencial.comprobado==0">
                            <div class="row form-group">
                                <label class="col-md-12">Si desea editar las credenciales, ingrese su contraseña de administrador</label>
                                <label class="col-md-3">Contraseña</label>
                                <div class="col-md-5">
                                    <input type="password" v-model="Credencial.password" class="form-control">
                                </div>
                                <button type="button" @click="comprobar()" class="btn btn-behance">Comprobar</button>
                            </div>
                        </div>
                        <div v-else>
                            <div class="row form-group">
                                <label class="col-md-3">Usuario (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Usuario.usuario" class="form-control" placeholder="ingrese el usuario">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3">Contraseña (*)</label>
                                <div class="col-md-9">
                                    <input type="password" v-model="Usuario.password" class="form-control" placeholder="ingrese la contraseña">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cancelar</button>
                    <button type="button" @click="accionar(Modal.accion)" class="btn btn-primary" v-text="Modal.accion"></button>        
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
                    // dni: '',
                    // ruc: '',
                    direccion: '',
                    // telefono: '',
                    // email: '',
                    // birthday: '',
                    // observacion: '',
                    tipo: '',
                    rol_id: 0,
                },
                SelectRol: [],

                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 1,
                    items: 4
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
                    offset: 3
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
                var max = 20
                var items = [];

                while ( min <= max) {
                    items.push(min);
                    min++;
                }

                return items;
            }
        },
        methods: {
            listar(page){
                var me = this;
                var url = '/usuario?page='+page+'&estado='+this.Busqueda.estado+'&texto='+this.Busqueda.texto+'&items='+this.Busqueda.items;

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
                    // 'dni' : this.Usuario.dni,
                    // 'ruc' : this.Usuario.ruc,
                    'direccion' : this.Usuario.direccion,
                    // 'birthday' : this.Usuario.birthday,
                    // 'telefono' : this.Usuario.telefono,
                    // 'email': this.Usuario.email,
                    // 'observacion' : this.Usuario.observacion,
                    'tipo': this.Usuario.tipo,
                    'rol_id' : this.Usuario.rol_id
                }).then(function(response){
                    me.cerrarModal();
                    me.listar(1);
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
                    // 'dni' : this.Usuario.dni,
                    // 'ruc' : this.Usuario.ruc,
                    'direccion' : this.Usuario.direccion,
                    // 'birthday' : this.Usuario.birthday,
                    // 'telefono' : this.Usuario.telefono,
                    // 'email': this.Usuario.email,
                    // 'observacion' : this.Usuario.observacion,
                    'tipo': this.Usuario.tipo,
                    'rol_id' : this.Usuario.rol_id
                }).then(function(response){
                    me.cerrarModal();
                    me.listar(me.Paginacion.currentPage);
                }).catch(function(error){
                    console.log(error);
                });
            },
            activar(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: '¿Esta seguro de ACTIVAR este usuario?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        var me = this;
                
                        axios.put('/usuario/activar', {
                            'id' : id
                        }).then(function (response) {
                            me.listar(me.Paginacion.currentPage);
                            swalWithBootstrapButtons.fire(
                                'ACTIVADO',
                                'El usuario se ha activado correctamente',
                                'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else if ( result.dismiss === Swal.DismissReason.cancel ) {
                    }
                })
            },
            desactivar(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: '¿Esta seguro de DESACTIVAR este usuario?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        var me = this;
                
                        axios.put('/usuario/desactivar', {
                            'id' : id
                        }).then(function (response) {
                            me.listar(me.Paginacion.currentPage);
                            swalWithBootstrapButtons.fire(
                                'DESACTIVADO',
                                'El usuario se ha desactivado correctamente',
                                'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else if ( result.dismiss === Swal.DismissReason.cancel ) {
                    }
                })
            },
            abrirModalAgregar(){
                this.abrirModal(1);
                this.Modal.titulo = 'Nuevo Usuario';
                this.Modal.accion = 'Agregar';

                this.Usuario.usuario = '';
                this.Usuario.password = '';
                this.Usuario.nombre = '';
                // this.Usuario.dni = '';
                // this.Usuario.ruc = '';
                this.Usuario.direccion = '';
                // this.Usuario.telefono = '';
                // this.Usuario.email = '';
                // this.Usuario.birthday = '';
                // this.Usuario.observacion = '';
                this.Usuario.tipo = '';
                this.Usuario.rol_id = 0;

                this.selectRol();
            },
            abrirModalEditar(data = []){
                this.abrirModal(2);
                this.Modal.titulo = 'Editar Usuario';
                this.Modal.accion = 'Editar';
                
                this.Usuario.id = data['id'];
                this.Usuario.usuario = data['usuario'];
                this.Usuario.password = '';
                this.Usuario.nombre = data['nombre'];
                // this.Usuario.dni = data['dni'];
                // this.Usuario.ruc = data['ruc'];
                this.Usuario.direccion = data['direccion'];
                // this.Usuario.telefono = data['telefono'];
                // this.Usuario.email = data['email'];
                // this.Usuario.birthday = data['birthday'];
                // this.Usuario.observacion = data['observacion'];
                this.Usuario.tipo = '';
                this.Usuario.rol_id = data['rol_id'];

                this.selectRol();
            },
            abrirModal(numero){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
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
                // this.Usuario.dni = '';
                // this.Usuario.ruc = '';
                this.Usuario.direccion = '';
                // this.Usuario.telefono = '';
                // this.Usuario.email = '';
                // this.Usuario.birthday = '';
                // this.Usuario.observacion = '';
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
            getRol(id){
                for (let i = 0; i < this.SelectRol.length; i++) {
                    if ( this.SelectRol[i].id == id ) return this.SelectRol[i];
                }
            },
            getTipo(rol_id){
                switch ( this.getRol(rol_id).nombre ) {
                    case 'Administrador': {
                        return 'N';
                    }
                    case 'Puesto': {
                        return 'P';
                    }
                    case 'Almacén': {
                        return 'A';
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
                    this.Paginacion.currentPage = page;
                    this.listar(page);
                } else {
                    if ( page <= 0 ) {
                        this.Paginacion.currentPage = 1;
                    } else {
                        this.Paginacion.currentPage = this.Paginacion.lastPage;
                    }
                }
            }
        },
        mounted() {
            this.listar(1);
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
</style>
