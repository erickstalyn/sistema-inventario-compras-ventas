<template>
    <main>

        <div class="container-small input-group">
            <div class="col-md-12">
                <h1 class="h1 col-md-12 text-center">Conversiones</h1>
            </div>
            <div class="col-md-6">
                <div class="col-md-12 input-group">
                    <label class="col-md-6">Unidad de medida inicial</label>
                    <select class="col-md-4" v-model="UnidadFrom">
                        <option v-for="unidad in SelectUnidad" :key="unidad.id" :value="unidad.nombre" v-text="unidad.nombre"></option>
                    </select>
                </div>
                <div class="col-md-12 input-group">
                    <label class="col-md-6">Unidad de medida final</label>
                    <select class="col-md-4" v-model="UnidadTo">
                        <option v-for="unidad in SelectUnidad" :key="unidad.id" :value="unidad.nombre" v-text="unidad.nombre"></option>
                    </select>
                </div>
                <div class="col-md-12 input-group">
                    <label class="col-md-6">Cantidad a convertir</label>
                    <input type="number" class="col-md-4" v-model="Cantidad">
                </div>
                <div class="col-md-12 d-flex align-items-center">
                    <button type="button" class="btn btn-primary" @click="convertir()">Convertir</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12 text-center">RESULTADO</div>
                <div class="col-md-12 text-center" v-text="Resultado"></div>
            </div>
        </div>

    </main>
</template>


<script>
    export default {
        data(){
            return {
                Conversiones: [],
                UnidadFrom: {
                    'nombre': ''
                },
                UnidadTo: {
                    'nombre': ''
                },
                Cantidad: '',
                Resultado: '',

                SelectUnidad: [],
            }
        },
        computed: {
        },
        methods: {
            convertir() {
                var me = this;
                var url = '/calcular'

                axios.get(url, {
                    'unidad_from': this.UnidadFrom,
                    'unidad_to': this.UnidadTo,
                    'cantidad': parseInt(this.Cantidad)
                }).then(function (response) {
                    me.Resultado = response.data
                }).catch(function (error) {
                    console.log(error);
                });
            },
            get(option, data = ''){
                var me = this;
                var url = '';

                switch ( option ) {
                    case 'unidad':
                        url = '/unidad/getAll'

                        axios.get(url).then(function (response) {
                            me.SelectUnidad = response.data
                        }).catch(function (error) {
                            console.log(error);
                        });
                        break;
                    default:
                        console.error('opcion no definida en metodo get()')
                        break;
                }
                
                return null;
            },
        },
        mounted() {
            this.get('unidad')
        }
    }

</script>