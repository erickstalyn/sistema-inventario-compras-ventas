<template>

    <div class="col-md-12 shadow rounded bg-white pt-3 pb-3">
        <div class="col-md-12">
            <label class="col-md-12 text-center h5 font-weight-bold">Agregar SUBPRODUCTO</label>
        </div>
        <div class="col-md-12 input-group mt-2" v-for="(tipo, index) in TiposCaracteristica" :key="tipo.nombre">
            <label class="col-md-5 pl-0 mt-1 mb-1">{{tipo.nombre}}&nbsp;<span v-if="tipo.required" class="text-danger">*</span></label>
            <select class="col-md-7 custom-select custom-select-sm" v-model="Subproducto.caracteristicas[index].nombre">
                <option value="" :disabled="tipo.required">- seleccione -</option>
                <option class="text-gray-900" v-for="caracteristica in tipo.caracteristicas" :key="caracteristica.nombre" :value="caracteristica.nombre" v-text="caracteristica.nombre"></option>
            </select>
        </div>
        <div class="col-md-12 input-group mt-2">
            <label class="col-md-5 pl-0 mt-1 mb-1" for="pme">P. por unidad&nbsp;<span class="text-danger">*</span></label>
            <input type="number" class="col-md-7 form-control form-control-sm text-right" min="0" v-model="Subproducto.precio_menor" placeholder="Ingrese precio por unidad" id="pme">
        </div>
        <div class="col-md-12 input-group mt-2">
            <label class="col-md-5 pl-0 mt-1 mb-1" for="pma">P. por mayor&nbsp;<span class="text-danger">*</span></label>
            <input type="number" class="col-md-7 form-control form-control-sm text-right" min="0" v-model="Subproducto.precio_mayor" placeholder="Ingrese precio por mayor" id="pma">
        </div>
        <div class="col-md-12 mt-3 d-flex justify-content-center">
            <button type="button" class="btn btn-sm btn-primary btn-icon-split" @click="addSubproducto()">
                <span class="text font-weight-bold">Agregar</span>
                <span class="icon text-white-50"><i class="fas fa-arrow-right"></i></span>
            </button>
        </div>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                //datos de los subproductos
                Subproducto : {
                    id: null,
                    caracteristicas: [],
                    precio_menor: '',
                    precio_mayor: '',
                },

                TiposCaracteristica: [],

                //datos de modales
                Modal: {
                    tipo: null
                },

                //datos de la ruta de consultas
                Ruta: {
                    subproducto: '/subproducto'
                }
            }
        },
        watch: {
            'Subproducto': {
                deep: true,
                handler (newSubproducto, oldSubproducto) {
                    this.$emit('changeValue', {
                        variable: 'subproducto', 
                        value: newSubproducto
                    });
                }
            }
        },
        methods: {
            runChildMethod({method = '', component = 'all', data = {}}) {
                if ( component != 'form-subproducto' && component != 'all' ) return;

                switch( method ) {
                    case 'abrirModal':
                        this.abrirModal(data); break;
                    case 'cerrarModal':
                        this.cerrarModal(); break;
                    case 'clearForm':
                        this.clearForm(); break;
                    default: 
                        console.error("Method '"+method+"' don't found in runChildMethod() function on Modal-FormSubproducto.vue"); break;
                }
            },
            abrirModal({tipo = ''}){
                this.Modal.tipo = tipo;
                
                switch ( this.Modal.tipo ) {
                    case 'agregar':
                        this.abrirModalAgregar(); break;
                    case 'ver':
                        this.abrirModalVer(producto); break;
                    case 'editar':
                        this.abrirModalEditar(producto); break;
                    default:
                        this.Modal.tipo = null;
                        console.error("Type '"+tipo+"' don't found in abrirModal() function on Modal-FormSubproducto.vue"); break;
                }
            },
            abrirModalAgregar(){
                this.clearForm();
            },
            abrirModalVer(data = []){
                this.abrirModal(2, 'Ver Super Producto', 'modal-lg', '', 'Cerrar');
                
                this.SuperProducto.id = data['id'];
                this.SuperProducto.nombre = data['nombre'];
                this.SuperProducto.descripcion = data['descripcion'];
                this.SuperProducto.superstock = data['superstock'];
                this.SuperProducto.estado = data['estado'];
                this.SuperProducto.created_at = data['created_at'];

                this.listaProducto();
            },
            abrirModalEditar(data = []){
                this.abrirModal(3, 'Editar Super Producto', '', 'Editar', 'Cancelar');
                
                this.SuperProducto.id = data['id'];
                this.SuperProducto.nombre = data['nombre'];
                this.SuperProducto.descripcion = data['descripcion'];

                this.DataSuperProducto = data;
            },
            cerrarModal(){
                this.clearForm();
            },
            addSubproducto(){
                this.$emit('runParentMethod', {
                    method: 'addSubproducto'
                });
            },
            clearForm() {
                this.Subproducto.id = null;
                this.Subproducto.caracteristicas.forEach((c, i, self) => {
                    self[i].nombre = '';
                });
                this.Subproducto.precio_menor = '';
                this.Subproducto.precio_mayor = '';
            },
            getTiposCaracteristica(){
                var me = this;
                var url = this.Ruta.subproducto+'/getTiposCaracteristica';

                axios.get(url).then(function (response) {
                    me.TiposCaracteristica = response.data;
                    me.TiposCaracteristica.forEach(tipo => {
                        me.Subproducto.caracteristicas.push({
                            tipo_caracteristica: tipo.nombre,
                            nombre: ''
                        });
                    });
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validate({component, type = ''}){
                if ( component != 'form-subproducto' && component != undefined ) return;

                const errors = [];

                switch ( type ) {
                    case 'add-subproducto': // Se validan los campos del formulario de agregar subproducto
                        
                        break;
                    default:
                        console.error("Type '"+type+"' don't found in validate() function");
                        break;
                }

                if ( errors.length > 0 ) this.$emit('addError', {'errors': errors});
            },
        },
        mounted() {
            this.getTiposCaracteristica();

            this.$parent.$on('runChildMethod', this.runChildMethod);
        }
    }
</script>

<style scoped>
</style>