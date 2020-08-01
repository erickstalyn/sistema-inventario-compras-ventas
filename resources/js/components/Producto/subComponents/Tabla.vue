<template>
    
    
        <div v-if="ListaProducto.length" class="table-responsive">
            
            <!-- Tabla -->
            <div class="ec-table overflow-auto">
                <table v-if="!Loading" class="table table-bordered table-condensed table-striped table-sm text-gray-900">
                    <thead>
                        <tr class="table-success">
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th class="text-center">Stock Total</th>
                            <th v-if="Usuario.rol == 'administrador'" class="text-center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="producto in ListaProducto" :key="producto.id" >
                            <td v-text="producto.nombre"></td>
                            <td v-text="producto.descripcion==null?'-':producto.descripcion"></td>
                            <td v-text="producto.stock" class="text-right"></td>
                            <td v-if="Usuario.rol == 'administrador'" class="text-center">
                                <button type="button" @click="abrirModal('ver', producto)" title="VER" class="btn btn-primary btn-sm">
                                    <i class="far fa-eye"></i>&nbsp;Ver
                                </button>
                                <button type="button" @click="abrirModal('editar', producto)" title="EDITAR" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>&nbsp;Editar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="text-center">
                    <i class="fas fa-spinner fa-3x fa-spin text-primary"></i>
                </div>
            </div>
            

            <!-- Barra de navegacion -->
            <nav class="d-flex justify-content-center p-0">
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
    
    

</template>

<script>
    export default {
        props: {
            Loading: {
                type: Boolean,
                default: () => true,
                required: true
            },
            Usuario: {
                type: Object,
                default: () => {Rol: null},
                required: true
            },
            ListaProducto: {
                type: Array,
                default: () => [],
                required: true
            },
            Paginacion: {
                type: Object,
                default: () => {},
                required: true
            }
        },
        data(){
            return {
                Navegacion:{
                    offset: 3
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
            abrirModal(tipo, producto){
                this.$emit('abrirModal', tipo, producto);
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) this.$emit('listar', page);
            }
        }
    }
</script>

<style>
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