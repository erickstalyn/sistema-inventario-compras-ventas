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
                            <td v-for="caracteristica in subproducto.caracteristicas" :key="caracteristica.caracteristica" v-text="caracteristica.caracteristica==''?'---':caracteristica.caracteristica"></td>
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
        props: {
            Error: {
                type: Object,
                default: {estado: 0},
            }
        },
        data(){
            return {
                //datos de los subproductos
                maxSubproductos: 10,
                Subproductos: [],

                //datos de modales
                Modal: {
                    tipo: null,
                    estado: 0
                },

                //datos de la ruta de consultas
                Ruta: {
                }
            }
        },
        watch: {
            Subproductos: {
                deep: true,
                handler(newSubproductos, oldSubproductos) {
                    this.$emit('changeValue', 'subproductos', newSubproductos);
                }
            }
        },
        methods: {
            runChildMethod(method, data) {
                switch ( method ) {
                    case 'addSubproducto-ListSubproductos':
                        this.addSubproducto(data); break;
                }
            },
            addSubproducto(subproducto){
                this.validate({
                    type: 'add-subproducto',
                    data: subproducto
                });

                if ( this.Error.estado ) return;

                this.validate({
                    type: 'top-subproductos'
                });

                if ( this.Error.estado ) return;
                
                let {caracteristicas, precio_menor, precio_mayor} = subproducto;
                
                this.Subproductos.push({
                    id: null,
                    caracteristicas: caracteristicas,
                    precio_menor: parseFloat(precio_menor),
                    precio_mayor: parseFloat(precio_mayor)
                });

                this.$emit('runParentMethod', 'clearFormSubproducto');
            },
            eliminarSubproducto(index){
                this.Subproductos.splice(index, 1);
            },
            abrirModal(tipo, producto){
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
                        console.error('Tried to open a diferent modal type. (type = "' + tipo + '")'); break;
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
                this.$emit('cerrarModal');

                this.Modal.estado = 0;
                this.Modal.tipo = null;
                this.Modal.titulo = '';
                this.Modal.tamaño = '';
                this.Modal.loading = false;
                this.Modal.btnSuccess = null;
                this.Modal.btnCancel = null;

                this.Error.estado = 0;
                this.Error.numero = 0;
                this.Error.mensaje = [];

                this.Subproductos = [];
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
            this.$parent.$on('runChildMethod', this.abrirModal);
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