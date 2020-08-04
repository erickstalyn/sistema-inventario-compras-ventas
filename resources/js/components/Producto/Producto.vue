<template>
    <main>

        <!-- Interfaz Principal -->
        <div>
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <div class="col-md-12">
                    <i class="fas fa-briefcase"></i>&nbsp;&nbsp;
                    <span class="h3 mb-0 text-gray-900">Inventario de PRODUCTOS</span>&nbsp;&nbsp;
                    <button type="button" v-if="Usuario.rol == 'administrador'" class="btn btn-success" @click="abrirModal('agregar')">
                        <i class="fas fa-user-plus"></i>&nbsp; Nuevo
                    </button>
                    <button type="button" v-if="Usuario.rol == 'administrador'" class="btn btn-danger" @click="generatePdf()">
                        <i class="far fa-file-pdf"></i>&nbsp; PDF
                    </button>
                </div>
            </div>

            <!-- Buscador y filtrador -->
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="input-group"> 
                        <input type="search" class="form-control form-control-sm" v-model="Busqueda.texto" @keyup.enter="listar()" @keyup="Busqueda.texto.length >= 5 || Busqueda.texto.length == 0 ? listar() : ''" placeholder="Buscar por NOMBRE" focus>
                        <button type="button" class="btn btn-primary btn-sm" @click="listar()">
                            <i class="fa fa-search"></i>&nbsp; Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3 input-group">
                    <label class="col-md-6 mt-1 mb-1 text-right">N° filas:</label>
                    <select class="col-md-6 form-control form-control-sm" v-model="Busqueda.filas">
                        <option v-for="item in Filas" :key="item" :value="item" v-text="item" class="text-gray-900"></option>
                    </select>
                </div>
            </div>

            <!-- Listado -->
            <tabla :Usuario="Usuario" :ListaProducto="ListaProducto" :Loading="Navegacion.loading" :Paginacion="Paginacion" @listar="listar" @abrirModal="abrirModal"></tabla>

        </div>

        <!-- Modal -->
        <modal @listar="listar"></modal>

    </main>
</template>

<script>

    import Tabla from './subComponents/Tabla.vue'
    import Modal from './subComponents/Modal.vue'

    export default {
        components: {
            Tabla,
            Modal
        },
        data(){
            return {
                //datos de producto
                ListaProducto: [],
                Producto: {},

                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 2,
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
                    loading: false
                },

                //datos de la ruta de consultas
                Ruta: {
                    subproducto: '/subproducto',
                    producto: '/producto',
                    data: '/data',
                    serverPhp: 'http://127.0.0.1:8000',
                    usuario: '/usuario'
                },
                Usuario: {
                    rol: null
                },
            }
        },
        computed: {
            Filas: function(){
                var min = 1;
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
                this.Navegacion.loading = true;

                var me = this;
                var url = this.Ruta.producto;
                
                axios.get(url, {
                    params: {
                        'page': this.Paginacion.currentPage,
                        'texto': this.Busqueda.texto,
                        'filas': this.Busqueda.filas
                    }
                }).then(function (response) {
                    me.ListaProducto = response.data.productos.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error);
                }).then(response => {
                    me.Navegacion.loading = false;
                });
            },
            abrirModal(tipo, producto = {}){
                this.$emit('abrirModal', tipo, producto);
            },
            // generatePdf(){
            //     window.open(this.Ruta.serverPhp + '/superproducto/generatePdf','_blank');
            //     this.Button.press = false;
            // },
            loadUserData(){
                var me = this;
                var url = this.Ruta.usuario + '/getRol';

                axios.get(url).then(response => {
                    me.Usuario.rol = response.data;
                }).catch(error => {
                    console.error(error)
                });
            }
        },
        mounted() {
            this.loadUserData();
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