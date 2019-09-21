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
                                <input type="text" class="form-control" v-model="Busqueda.texto" @keyup.enter="listar(1)">
                                <button type="button" class="btn btn-primary" @click="listar(1)">
                                    <i class="fa fa-search"></i>&nbsp; Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Listado -->
                    <div v-if="ListaUsuario.length" class="table-responsive">
                        <!-- Tabla -->
                        <table class="table table-bordered table-striped table-sm text-gray-900">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
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
                                        <button type="button" @click="abrirModalVer(usuario)" class="btn btn-primary btn-sm">
                                            <i class="fas fa-user-lock"></i>
                                        </button>
                                        <button type="button" @click="abrirModalEditar(usuario)" class="btn btn-warning btn-sm">
                                            <i class="fas fa-user-edit"></i>
                                        </button>
                                        <template v-if="usuario.estado">
                                            <button type="button" @click="desactivar(usuario.id)" class="btn btn-danger btn-sm">
                                                <i class="fas fa-user-times"></i>
                                            </button>        
                                        </template>
                                        <template v-else>
                                            <button type="button" @click="activar(usuario.id)" class="btn btn-success btn-sm">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Barra de navegacion -->
                        <nav class="d-flex justify-content-center">
                            <ul class="pagination align-content-center">
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
                
                <!-- Modal Numero 1 -->
                <div v-if="Modal.numero==1">
                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title"></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    <div class="modal-body">
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
                        <div class="row form-group">
                            <label class="col-md-3">Rol</label>
                            <div class="col-md-9">
                                <select v-model="Usuario.rol_id" class="form-control">
                                    <option value="0">seleccione un rol</option>
                                    <option v-for="rol in SelectRol" :key="rol.id" v-text="rol.nombre"></option>
                                </select>
                            </div>
                        </div>
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
                    <div class="modal-footer">
                        <button @click="cerrarModal()">
                            Cancelar
                        </button>
                    </div>
                </div>
                <!-- Modal Numero 2 -->
                <div v-if="Modal.numero==2">
                    <div class="modal-header">
                        
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button @click="cerrarModal()">
                            Cerrar Modal 
                        </button>
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
                ListaUsuario: [],
                Usuario: {
                    id: 0,
                    usuario: '',
                    password: '',
                    estado: 0,
                    persona_id: 0,
                    nombre: '',
                    dni: '',
                    ruc: '',
                    direccion: '',
                    telefono: '',
                    email: '',
                    birthday: '',
                    observacion: '',
                    tipo: '',
                    rol_id: '',
                },
                SelectRol: [],

                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 1
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
            }
        },
        methods: {
            listar(page){
                var me = this;
                var url = '/usuario?page='+page+'&estado='+this.Busqueda.estado+'&texto='+this.Busqueda.texto;

                axios.get(url).then(function (response) {
                    me.ListaUsuario = response.data.usuarios.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            ver(){

            },
            agregar(){

            },
            editar(){

            },
            activar(){

            },
            desactivar(){

            },
            abrirModalVer(){

            },
            abrirModalAgregar(){
                this.abrirModal();
                
                this.Modal.titulo = 'Nuevo Usuario';
            },
            abrirModalEditar(){
                this.abrirModal();

                this.Modal.titulo = 'Editar Usuario';
            },
            abrirModal(){
                this.Modal.estado = 1;
                this.selectRol();
            },
            cerrarModal(){
                this.Modal.estado = 0;

                this.SelectRol = [];
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
