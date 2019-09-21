<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;<span class="h3 mb-0 text-gray-900">Usuarios</span>
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="fa fa-user-plus"></i>&nbsp; Nuevo
                </button>
                <button type="button" class="btn btn-danger">
                    <i class="fa fa-user-tag"></i>&nbsp; PDF
                </button>
            </div>

            <div class="card-body">
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
                
                <div v-if="ListaUsuario.length" class="table-responsive">
                    <table class="table table-bordered table-striped table-sm text-gray-900">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Direccion</th>
                                <th>Rol</th>
                                <th>Fecha de creacion</th>
                                <th>Fecha de actualizacion</th>
                                <th>Fecha de eliminacion</th>
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
                                    <button type="button" @click="abrirModalEditar(usuario)" class="btn btn-warning btn-sm">
                                        <i class="fa fa-user-edit"></i>
                                    </button>
                                    <template v-if="usuario.estado">
                                        <button type="button" @click="desactivar(usuario.id)" class="btn btn-danger btn-sm">
                                            <i class="fa fa-user-times"></i>
                                        </button>        
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="activar(usuario.id)" class="btn btn-success btn-sm">
                                            <i class="fa fa-user-check"></i>
                                        </button>
                                        
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
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
</template>

<script>
    export default {
        data(){
            return {
                //datos generales
                ListaUsuario: [],
                Usuario: {
                    id: 0,
                    nombre: '',
                    dni: '',
                    ruc: '',
                    direccion: '',
                    telefono: '',
                    email: '',
                    birthday: '',
                    observacion: '',
                    tipo: '',
                    usuario: '',
                    password: '',
                    estado: 0,
                    rol: '',
                },
                SelectRol: [],

                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 1
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
            abrirModalAgregar(){

            },
            abrirModalEditar(){

            },
            abrirModal(){

            },
            listar(page){
                let me = this;
                var url = '/usuario?page='+page+'&estado='+this.Busqueda.estado+'&texto='+this.Busqueda.texto;

                axios.get(url).then(function (response) {
                    me.ListaUsuario = response.data.usuarios.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            agregar(){

            },
            editar(){

            },
            activar(){

            },
            desactivar(){

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

