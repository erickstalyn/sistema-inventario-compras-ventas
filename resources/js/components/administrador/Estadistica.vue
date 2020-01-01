<template>
    <main>
        <!-- Interfaz Principal -->
        <div>
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Mis Estadísticas</span>&nbsp;&nbsp;
            </div>
            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <select class="col-md-4 custom-select text-gray-900" v-model="Busqueda.numero">
                            <option value="1">Ver ventas totales mensuales</option>
                            <option value="2">Ver ingresos totales mensuales</option>
                        </select>&nbsp;&nbsp;
                        <button type="button" class="btn btn-outline-warning" @click="listar()">
                            <i class="fa fa-search"></i>&nbsp; Ver estadistica
                        </button>
                    </div>
                </div>
            </div>
            <!-- contenido -->
            <dashboard :num_mostrar="Number.parseInt(Busqueda.numero)"></dashboard>
        </div>
    </main>
</template>

<script>
    export default {
        data(){
            return {
                Busqueda: {
                    numero : 1
                },
                Ruta: {

                }
            }
        },
        computed: {
            // getNumero: function(){
            //     return this.numero;
            // }
        },
        methods: {
            
        },
        mounted() {
            // this.listar();
        }
    }
</script>

<style>
    
</style>