<template>

    <div class="col-md-12 shadow rounded bg-white pt-3 pb-3">
        <div class="col-md-12">
            <label class="h5 font-weight-bold">Lista de SUBPRODUCTOS</label>
        </div>
        <div class="col-md-12 d-flex">
            <div v-if="Subproductos.length" class="col-md-12 d-flex p-0">
                <table class="table table-bordered table-condensed table-sm text-gray-900 m-0">
                    <thead>
                        <tr class="table-primary">
                            <th class="text-center">Quitar</th>
                            <th v-for="tipo in TiposCaracteristica" :key="tipo.nombre" v-text="tipo.nombre"></th>
                            <th class="text-center">Precio por unidad</th>
                            <th class="text-center">Precio por mayor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(subproducto, index) in Subproductos" :key="index">
                            <td class="text-center">
                                <button type="button" class="btn btn-circle btn-outline-danger btn-sm" @click="eliminarSubproducto(index)" title="QUITAR">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </td>
                            <td v-for="caracteristica in subproducto.caracteristicas" :key="caracteristica.nombre" v-text="caracteristica.nombre==''?'---':caracteristica.nombre"></td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-right" min="0" v-model="subproducto.precio_menor">
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-right" min="0" v-model="subproducto.precio_mayor">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <label class="col-md-12 text text-danger">Sin productos</label>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                //datos de los subproductos
                maxSubproductos: 10,
                Subproductos: [],

                //datos de modales
                Modal: {
                    tipo: null
                },

                // tipos de caracteristicas que van en las tablas
                TiposCaracteristica: [],

                //datos de la ruta de consultas
                Ruta: {
                    subproducto: '/subproducto'
                }
            }
        },
        watch: {
            Subproductos: {
                deep: true,
                handler(newSubproductos, oldSubproductos) {
                    this.$emit('changeValue', {
                        variable: 'subproductos', 
                        value: newSubproductos
                    });
                }
            }
        },
        methods: {
            runChildMethod({method = '', component = 'all', data = {}}) {
                if ( component != 'list-subproductos' && component != 'all' ) return;

                switch ( method ) {
                    case 'abrirModal':
                        this.abrirModal(data); break;
                    case 'cerrarModal':
                        this.cerrarModal(); break;
                    case 'addSubproducto':
                        this.addSubproducto(data); break;
                    default:
                        console.error("Method '"+method+"' don't found in runChildMethod() function on Modal-ListSubproductos.vue")
                        break;
                }
            },
            abrirModal({tipo = '', producto = {}}){
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
                        console.error("Type '"+tipo+"' don't found in abrirModal() function on Modal-ListSubproductos.vue"); break;
                }
            },
            abrirModalAgregar(){
                this.Modal.tipo = 'agregar';
                this.Subproductos = [];
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
                this.Subproductos = [];
            },
            getTiposCaracteristica(){
                var me = this;
                var url = this.Ruta.subproducto+'/getTiposCaracteristica';

                axios.get(url).then(function (response) {
                    me.TiposCaracteristica = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            addSubproducto(subproducto){
                this.Subproductos.push(subproducto);
            },
            eliminarSubproducto(index){
                this.Subproductos.splice(index, 1);
            },
            listaProducto(){
                var me = this;
                var url = this.Ruta.producto+'/listaProducto?id='+this.SuperProducto.id;
                
                axios.get(url).then(function (response) {
                    me.ListaProducto = response.data;
                });
            },
            validate({component, type = '', data = {}}){
                if ( component != undefined && component != 'list-subproductos') return;
                
                const errors = [];

                switch ( type ) {
                    case 3: // El producto ya existe
                        errors.push("El Producto ya esta registrado o ha ocurrido un error");    //error o producto existente
                        break;
                    case 4:
                        for (let i = 0; i < this.ListaSuperProducto.length; i++) {
                            if ( this.SuperProducto.id == this.ListaSuperProducto[i].id ) {
                                if ( this.SuperProducto.nombre == this.ListaSuperProducto[i].nombre && this.SuperProducto.descripcion == this.ListaSuperProducto[i].descripcion ) {
                                    errors.push("Ningun cambio realizado");    //sin cambios
                                }
                                break;
                            }
                        }
                        break;
                    case 'top-list-subproductos':
                        if ( this.Subproductos.length >= this.maxSubproductos ) {
                            errors.push('Por ahora no se pueden registrar mas subproductos, registre el producto y luego siga agregando mas subproductos');
                        }
                        break;
                }

                if ( errors.length ) {
                    this.addError(errors);
                }

                return this.Error.estado;
            },
        },
        mounted() {
            this.getTiposCaracteristica();

            this.$parent.$on('runChildMethod', this.runChildMethod);
        }
    }
</script>

<style scoped>
    .mostrar{
        display: block !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
</style>