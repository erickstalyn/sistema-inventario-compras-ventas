<template>
    <div class="container-fluid">
        <div class="card">

            <div class="card-header">
                <i class="fa fa-align-justify"></i> Usuarios
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="icon-plus"></i>&nbsp; Nuevo
                </button>
                <button type="button" class="btn btn-danger">
                    <i class="icon-picture"></i>&nbsp; PDF
                </button>
            </div>

            <div class="card-body">
                <div class="row form-group">
                    <div class="col-md-8">
                        <div class="input-group">
                            <select class="col-md-3 form-control" v-model="Busqueda.estado">
                                <option value="1">Activados</option>
                                <option value="0">Desactivados</option>
                                <option value="2">Todos</option>
                            </select>
                            <input type="text" class="form-control" v-model="Busqueda.texto">
                            <button type="button" class="btn btn-primary" @click="listar(1, Busqueda.estado, Busqueda.texto)">
                                <i class="fa fa-search"></i>&nbsp; Buscar
                            </button>
                        </div>
                    </div>
                </div>
                <!-- <div class="table-responsive"> -->
                    <table class="table table-bordered table-striped table-sm">
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
                                <td v-text="usuario.estado"></td>
                                <td>
                                    <button type="button" @click="abrirModalVer(usuario)">
                                        <i class="icon-eye"></i>&nbsp;
                                    </button>
                                    <button type="button" @click="abrirModalEditar(usuario)">
                                        <i class="icon-pencil"></i>&nbsp;
                                    </button>
                                    <button type="button" @click="activar(usuario.id)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <!-- </div> -->

                <nav>
                    <ul class="pagination">
                        <li class="page-item">
                            <a href="#" class="page-link">Anterior</a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link"></a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link">Siguiente</a>
                        </li>
                    </ul>
                </nav>
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

                //datos de errores
                Error: {
                    estado: 0,
                    mensaje: []
                }
            }
        },
        methods: {
            abrirModalAgregar(){

            },
            abrirModalEditar(){

            },
            abrirModal(){

            },
            listar(page, estado, texto){
                let me = this;
                var url = '/usuario?page='+page+'&estado='+estado+'&texto='+texto;

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
            cambiarPagina(){

            }
        },
        mounted() {
            this.listar(1, this.Busqueda.estado, this.Busqueda.texto);
        }
    }
</script>

