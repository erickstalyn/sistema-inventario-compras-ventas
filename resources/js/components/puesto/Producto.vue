<template>
    <main>

        <!-- Interfaz Principal -->
        <div>
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Productos</span>&nbsp;&nbsp;
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="search" class="form-control" v-model="Busqueda.texto" @keyup="Busqueda.texto.length >=10 || Busqueda.texto.length == 0 ? listar() : ''" placeholder="Buscar por CODIGO o NOMBRE">
                        <button type="button" class="btn btn-primary" @click="listar()">
                            <i class="fa fa-search"></i>&nbsp; Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-5"></div>
                <div class="col-md-1 text-right">
                    <label>N° filas:</label>
                </div>
                <select class="col-md-1 form-control text-gray-900" v-model="Busqueda.filas" @change="listar()">
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
                                <th class="text-center">Precio al por menor</th>
                                <th class="text-center">Precio al por mayor</th>
                                <th class="text-center">Disponible</th>
                                <th class="text-center">Reservados</th>
                                <th class="text-center">Fallidos</th>
                                <th class="text-center">Traslado</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="producto in ListaProducto" :key="producto.id" :class="producto.class">
                                <td v-text="producto.producto_nombre"></td>
                                <td v-text="producto.precio_menor" class="text-right"></td>
                                <td v-text="producto.precio_mayor" class="text-right"></td>
                                <td v-text="producto.substock" class="text-right"></td>
                                <td v-text="producto.reservados==0?'---':producto.reservados" class="text-right"></td>
                                <td v-text="producto.fallidos==0?'---':producto.fallidos" class="text-right"></td>
                                <td v-text="producto.traslado==0?'---':producto.traslado" class="text-right"></td>
                                <td v-text="producto.total" class="text-right"></td>
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

    </main>
</template>

<script>
    export default {
        data(){
            return {
                //datos generales
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

                //datos de ruta de consultas
                Ruta: {
                    centro: '/centro',
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
                var url = this.Ruta.centro+'/listProductos?'
                        +'page='+this.Paginacion.currentPage
                        +'&texto='+this.Busqueda.texto
                        +'&rows='+this.Busqueda.filas
                        +'&centro_id='+$('meta[name="idCentro"]').attr('content');
                
                axios.get(url).then(function (response) {
                    me.fix('lista_detalle_producto', response.data.list.data);
                    me.Paginacion = response.data.paginate;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            fix(option, data){
                switch (option) {
                    case 'lista_detalle_producto':
                        var list = [];
                        
                        if ( data.length != 0) {
                            data.forEach(detalle => {
                                list.push({
                                    producto_nombre: detalle.nombre,
                                    precio_menor: Number.parseFloat(detalle.precio_menor),
                                    precio_mayor: Number.parseFloat(detalle.precio_mayor),
                                    substock: detalle.detalle.substock,
                                    reservados: detalle.detalle.reservados,
                                    fallidos: detalle.detalle.fallidos,
                                    traslado: detalle.detalle.traslado,
                                    total: detalle.detalle.substock + detalle.detalle.reservados + detalle.detalle.fallidos + detalle.detalle.traslado,
                                    class: {
                                        'table-secondary': detalle.detalle.substock==0?true:false,
                                        'table-danger': detalle.detalle.substock==1?true:false,
                                        'table-warning': detalle.detalle.substock==2?true:false
                                    }
                                });
                            })
                        }
                        
                        this.ListaProducto = list;
                        break;
                    default:
                        console.log('DEFAULT_ERROR:onfix');
                        break;
                }
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