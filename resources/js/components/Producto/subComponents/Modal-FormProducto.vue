<template>

    <div class="container-small col-md-12">
        <div class="col-md-12 shadow rounded bg-white pt-3 pb-3">
            <div class="col-md-12 input-group">
                <div class="col-md-6 input-group">
                    <label class="mt-1 mb-1" for="nom">Nombre&nbsp;<span class="text-danger">*</span>&nbsp;&nbsp;&nbsp;</label>
                    <input type="text" class="form-control form-control-sm" v-model="Producto.nombre" placeholder="El nombre se actualizara automaticamente" id="nom">
                </div>
                <div class="col-md-6 input-group">
                    <label class="mt-1 mb-1" for="descripcion">Descripcion&nbsp;&nbsp;&nbsp;</label>
                    <input type="text" class="form-control form-control-sm" v-model="Producto.descripcion" placeholder="Descripcion breve del producto" id="descripcion">
                </div>
            </div>
            <div class="col-md-12 input-group mt-2">
                <div class="col-md-3 input-group">
                    <label class="mt-1  mb-1">Categoria&nbsp;<span class="text-danger">*</span>&nbsp;&nbsp;&nbsp;</label>
                    <select class="form-control form-control-sm" v-model="Producto.categoria_id">
                        <option value="0" disabled>- seleccione -</option>
                        <option v-for="categoria in Categorias" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre" class="text-gray-900"></option>
                    </select>
                </div>
                <div class="col-md-3 input-group">
                    <label class="mt-1  mb-1">Marca&nbsp;&nbsp;&nbsp;</label>
                    <select class="form-control form-control-sm" v-model="Producto.marca_id">
                        <option value="0">- seleccione -</option>
                        <option v-for="marca in Marcas" :key="marca.id" :value="marca.id" v-text="marca.nombre" class="text-gray-900"></option>
                    </select>
                </div>
                <div class="col-md-3 input-group">
                    <label class="mt-1  mb-1" for="mod">Modelo&nbsp;<span class="text-danger">*</span>&nbsp;&nbsp;&nbsp;</label>
                    <input type="text" class="form-control form-control-sm" v-model="Producto.modelo" placeholder="Modelo de producto" id="mod">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-sm" title="Actualizar el nombre" @click="actualizarNombre()">
                        <i class="fas fa-sync-alt fa-lg text-primary"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                //datos de producto
                Categorias: [],
                Marcas: [],
                Producto: {
                    id: 0,
                    categoria_id: 0,
                    marca_id: 0,
                    modelo: '',
                    nombre: '',
                    descripcion: '',
                    created_at: ''
                },

                //datos de modales
                Modal: {
                    tipo: null,
                },

                //datos de la ruta de consultas
                Ruta: {
                    producto: '/producto'
                }
            }
        },
        watch: {
            'Producto.categoria_id': function (newCategoriaId, oldCategoriaId) {
                if ( newCategoriaId !== oldCategoriaId ) this.$emit('changeValue', 'producto.categoria_id', newCategoriaId);
            },
            'Producto.marca_id': function (newMarcaId, oldMarcaId) {
                if ( newMarcaId !== oldMarcaId ) this.$emit('changeValue', 'producto.marca_id', newMarcaId);
            },
            'Producto.modelo': function (newModelo, oldModelo) {
                if ( newModelo !== oldModelo ) this.$emit('changeValue', 'producto.modelo', newModelo);
            },
            'Producto.nombre': function (newNombre, oldNombre) {
                if ( newNombre !== oldNombre ) this.$emit('changeValue', 'producto.nombre', newNombre);
            },
            'Producto.descripcion': function (newDescripcion, oldDescripcion) {
                if ( newDescripcion !== oldDescripcion ) this.$emit('changeValue', 'producto.descripcion', newDescripcion);
            }
        },
        methods: {
            abrirModal(tipo, producto = {}){
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
                this.Producto.categoria_id = 0;
                this.Producto.marca_id = 0;
                this.Producto.modelo = '';
                this.Producto.nombre = '';
                this.Producto.descripcion = '';
                
                // this.$emit('changeValue', 'producto.categoria_id', this.Producto.categoria_id);
                // this.$emit('changeValue', 'producto.marca_id', this.Producto.marca_id);
                // this.$emit('changeValue', 'producto.modelo', this.Producto.modelo);
                // this.$emit('changeValue', 'producto.nombre', this.Producto.nombre);
                // this.$emit('changeValue', 'producto.descripcion', this.Producto.descripcion);
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
                this.Modal.tipo = null;

                this.Producto.id = 0;
                this.Producto.categoria_id = 0;
                this.Producto.marca_id = 0;
                this.Producto.modelo = '';
                this.Producto.nombre = '';
                this.Producto.descripcion = '';
                this.Producto.stock = 0;
                this.Producto.created_at = '';
            },
            actualizarNombre(){
                var nombre = '';

                this.Categorias.forEach(category => {
                    if ( this.Producto.categoria_id == category.id ) nombre += category.nombre;
                });
                this.Marcas.forEach(mark => {
                    if ( this.Producto.marca_id == mark.id ) nombre += ' ' + mark.nombre;
                });
                nombre +=  ' ' + this.Producto.modelo;

                if ( nombre.trim() != '' ) this.Producto.nombre = nombre;
            },
            getCategorias(){
                var me = this;
                var url = this.Ruta.producto+'/getCategorias';

                axios.get(url).then(function (response) {
                    me.Categorias = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getMarcas(){
                var me = this;
                var url = this.Ruta.producto+'/getMarcas';

                axios.get(url).then(function (response) {
                    me.Marcas = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
        },
        mounted() {
            this.getCategorias();
            this.getMarcas();

            this.$parent.$on('abrirModal', this.abrirModal);
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