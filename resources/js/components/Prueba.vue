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
                        <option v-for="unidad in Convertidor.unidades" :key="unidad.unidad_nombre" :value="unidad.unidad_nombre" v-text="unidad.unidad_nombre"></option>
                    </select>
                </div>
                
                <div class="col-md-12 input-group">
                    <label class="col-md-6">Cantidad inicial</label>
                    <input type="number" class="col-md-4" v-model="CantidadFrom">
                </div>
                <div class="col-md-12 d-flex align-items-center">
                    <button type="button" class="btn btn-primary" @click="realizar_conversion()">Convertir</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12 input-group">
                    <label class="col-md-6">Unidad de medida final</label>
                    <select class="col-md-4" v-model="UnidadTo">
                        <option v-for="unidad in Convertidor.unidades" :key="unidad.unidad_nombre" :value="unidad.unidad_nombre" v-text="unidad.unidad_nombre"></option>
                    </select>
                </div>
                <div class="col-md-12 input-group">
                    <label class="col-md-6">Cantidad final</label>
                    <label class="col-md-4" v-text="CantidadTo"></label>
                </div>
            </div>
        </div>

    </main>
</template>


<script>
    export default {
        data(){
            return {
                UnidadFrom: '',
                UnidadTo: '',
                CantidadFrom: '',
                CantidadTo: '',
                
                SelectUnidad: [],

                TipoConversion: 'backend',

                Convertidor: {
                    unidades: [],
                    conversiones: []
                },
            }
        },
        computed: {
        },
        methods: {
            realizar_conversion() {
                if ( this.TipoConversion == 'frontend' ) {
                    this.CantidadTo = this.convertir(this.UnidadFrom, this.UnidadTo, this.CantidadFrom);
                } else {
                    var me = this;
                    var url = '/unidad/calculateConversion';

                    axios.get(url, {
                        params: {
                            'unidad_from': this.UnidadFrom,
                            'unidad_to': this.UnidadTo,
                            'cantidad': this.CantidadFrom
                        }
                    }).then(response => {
                        me.CantidadTo = response.data
                    }).catch(error => {
                        console.log(error);
                    });
                }
            },
            getConvertidorData(){
                var me = this;
                var url = '/unidad/getConvertidorData';

                axios.get(url).then(function (response) {
                    if ( response.data.success == false ) return;
                    
                    me.Convertidor.unidades = response.data.unidades;
                    me.Convertidor.conversiones = response.data.conversiones;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getUnidades(){
                var me = this;
                var url = '/unidad/getUnidades';

                axios.get(url).then(function (response) {
                    if ( response.data.success == false ) return
                    
                    me.SelectUnidad = response.data.unidades
                }).catch(function (error) {
                    console.log(error);
                });
            },
            convertir(unidad_from, unidad_to, cantidad) {
                // VALIDACIONES
                
                // unidades y conversiones
                if ( this.Convertidor.unidades == null || this.Convertidor.unidades.length == 0 ) return null;
                var unidades = this.Convertidor.unidades;
                if ( this.Convertidor.conversiones == null || this.Convertidor.conversiones.length == 0 ) return null;
                var conversiones = this.Convertidor.conversiones;
                
                // parametros
                var found_from = false, found_to = false; 
                var found_tipo_from = null, found_tipo_to = null;
                
                for (let i = 0; i < unidades.length; i++) { 
                    if ( unidad_from == unidades[i]['unidad_nombre'] ) {
                        found_from = true;
                        found_tipo_from = unidades[i]['tipo_unidad_nombre'];
                    } 
                    if ( unidad_to == unidades[i]['unidad_nombre'] ) {
                        found_to = true;
                        found_tipo_to = unidades[i]['tipo_unidad_nombre'];
                    }
                    if ( found_from == true && found_to == true ) break;
                }

                try {
                    if ( found_from != true || found_to != true || cantidad == null || cantidad == '' || unidad_from == '' || unidad_to == '' || found_tipo_from != found_tipo_to ) return null;
                    if ( parseFloat(cantidad) < 0 ) return null;
                } catch (error) {
                    return null;
                }
                
                // CALCULAR CONVERSION
                var step = {
                    'nombre': unidad_from,
                    'valor': parseFloat(cantidad)
                }

                step = this.nextStep(step, conversiones, unidad_to);
                
                this.Convertidor.conversiones.forEach(conversion => conversion['revisado'] = 0);

                return step['valor'];
            },
            nextStep(step, conversiones, unidad_to) {
                if ( step['nombre'] == unidad_to ) return step;

                for (let i = 0; i < conversiones.length; i++) {
                    if ( conversiones[i]['revisado'] == 0 ) {
                        // ida
                        if ( conversiones[i]['nombre_unidad_from'] == step['nombre'] ) {
                            conversiones[i]['revisado'] = 1;
                            
                            var new_step = {
                                'nombre': conversiones[i]['nombre_unidad_to'],
                                'valor': step['valor'] / parseFloat(conversiones[i]['factor'])
                            }
                            
                            if ( new_step['nombre'] == unidad_to ) {
                                step = new_step; break;
                            }

                            new_step = this.nextStep(new_step, conversiones, unidad_to);
                            
                            if ( new_step['nombre'] == unidad_to ) {
                                step = new_step; break;
                            }
                        }
                        // vuelta
                        if ( conversiones[i]['nombre_unidad_to'] == step['nombre'] ) {
                            conversiones[i]['revisado'] = 1;

                            var new_step = {
                                'nombre': conversiones[i]['nombre_unidad_from'],
                                'valor': step['valor'] * parseFloat(conversiones[i]['factor'])
                            }
                            
                            if ( new_step['nombre'] == unidad_to ) {
                                step = new_step; break;
                            }

                            new_step = this.nextStep(new_step, conversiones, unidad_to);
                            
                            if ( new_step['nombre'] == unidad_to ) {
                                step = new_step; break;
                            }
                        }
                    }
                }

                return step;
            }
        },
        mounted() {
            this.getConvertidorData();
            this.getUnidades();
        }
    }

</script>