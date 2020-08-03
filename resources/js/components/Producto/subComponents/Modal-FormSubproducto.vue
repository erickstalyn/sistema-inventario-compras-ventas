<template>

    <div class="col-md-12 shadow rounded bg-white pt-3 pb-3">
        <div class="col-md-12">
            <label class="col-md-12 text-center h5 font-weight-bold">Agregar SUBPRODUCTO</label>
        </div>
        <div class="col-md-12 input-group mt-2" v-for="(tipo, index) in TiposCaracteristica" :key="tipo.nombre">
            <label class="col-md-5 pl-0 mt-1 mb-1">{{tipo.nombre}}&nbsp;<span v-if="tipo.required" class="text-danger">*</span></label>
            <select class="col-md-7 custom-select custom-select-sm" v-model="Subproducto.caracteristicas[index].caracteristica">
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
            <button type="button" class="btn btn-sm btn-primary btn-icon-split" @click="agregarSubproducto()">
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                <span class="text font-weight-bold">Agregar SUBPRODUCTO</span>
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
            'Subproducto.id': function (newId, oldId) {
                if ( newId !== oldId ) this.$emit('changeValue', 'subproducto.id', newId);
            },
            'Subproducto.caracteristicas': {
                deep: true,
                handler (newCaracteristicas, oldCaracteristicas) {
                    // if ( newCaracteristicas !== oldCaracteristicas ) this.$emit('changeValue', 'subproducto.caracteristicas', newCaracteristicas);
                    this.$emit('changeValue', 'subproducto.caracteristicas', newCaracteristicas);
                }
            },
            'Subproducto.precio_menor': function (newPrecioMenor, oldPrecioMenor) {
                if ( newPrecioMenor !== oldPrecioMenor ) this.$emit('changeValue', 'subproducto.precio_menor', newPrecioMenor);
            },
            'Subproducto.precio_mayor': function (newPrecioMayor, oldPrecioMayor) {
                if ( newPrecioMayor !== oldPrecioMayor ) this.$emit('changeValue', 'subproducto.precio_mayor', newPrecioMayor);
            }
        },
        methods: {
            agregarSubproducto(){
                if ( this.validar(5) ) return;
                if ( this.validar(2) ) return;
                
                var caracteristicas = [];
                this.Subproducto.caracteristicas.forEach(c => {
                    caracteristicas.push({
                        tipo_caracteristica: c.tipo_caracteristica,
                        caracteristica: c.caracteristica
                    })
                });
                let producto = {
                    id: null,
                    caracteristicas: caracteristicas,
                    precio_menor: parseFloat(this.Subproducto.precio_menor),
                    precio_mayor: parseFloat(this.Subproducto.precio_mayor)
                };
                this.Subproductos.push(producto);

                this.Subproducto.caracteristicas.forEach((caracteristica, index, self) => {
                    self[index].caracteristica = '';
                });
                this.Subproducto.precio_menor = '';
                this.Subproducto.precio_mayor = '';
            },
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
                this.Subproducto.id = null;
                for (let i = 0; i < this.Subproducto.caracteristicas.length; i++) {
                    this.Subproducto.caracteristicas[i].caracteristica = ''
                }
                this.Subproducto.precio_menor = '';
                this.Subproducto.precio_mayor = '';
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

                this.Subproducto.id = null;
                for (let i = 0; i < this.Subproducto.caracteristicas.length; i++) {
                    this.Subproducto.caracteristicas[i].caracteristica = ''
                }
                this.Subproducto.precio_menor = '';
                this.Subproducto.precio_mayor = '';
            },
            getCaracteristicas(){
                var me = this;
                var url = this.Ruta.subproducto+'/getTiposCaracteristica';

                axios.get(url).then(function (response) {
                    me.TiposCaracteristica = response.data;
                    me.TiposCaracteristica.forEach(tipo => {
                        me.Subproducto.caracteristicas.push({
                            tipo_caracteristica: tipo.nombre,
                            caracteristica: ''
                        });
                    });
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validar(numero){
                this.Error.estado = 0;
                this.Error.numero = numero;
                this.Error.mensaje = [];

                switch (numero) {
                    case 1: // Se validan los campos del formulario de producto
                        if ( this.Producto.categoria_id == 0 ) this.Error.mensaje.push("Debe seleccionar una categoria");   //categoria
                        if ( this.Producto.modelo.trim() == '' ) this.Error.mensaje.push("Debe ingresar un modelo");    //nombre
                        var pMenorEmpty = false; var pMenorNotNumber = false; var pMayorEmtpy = false; var pMayorNotNumber = false;
                        for (let i = 0; i < this.Subproductos.length; i++) {
                            if ( this.Subproductos[i].precio_menor == '' ) {    // precio al por menor
                                pMenorEmpty = true;
                            } else if ( isNaN(parseInt(this.Subproductos[i].precio_menor)) || parseInt(this.Subproductos[i].precio_menor) <= 0 ) {
                                pMenorNotNumber = true;
                            }
                            if ( this.Subproductos[i].precio_mayor == '' ) {    // precio al por mayor
                                pMayorEmtpy = true;
                            } else if ( isNaN(parseInt(this.Subproductos[i].precio_mayor)) || parseInt(this.Subproductos[i].precio_mayor) <= 0 ) {
                                pMayorNotNumber = true;
                            }
                            if ( pMenorEmpty && pMenorNotNumber && pMayorEmtpy && pMayorNotNumber ) break;
                        }
                        if ( pMenorEmpty ) this.Error.mensaje.push("Debe ingresar un precio por unidad en la lista");
                        if ( pMenorNotNumber ) this.Error.mensaje.push("El precio por unidad ingresado en la lista es incorrecto");
                        if ( pMayorEmtpy ) this.Error.mensaje.push("Debe ingresar un precio por mayor en la lista");
                        if ( pMayorNotNumber ) this.Error.mensaje.push("El precio por mayor ingresado en la lista es incorrecto");
                        break;
                    case 2: // Se validan los campos del formulario de agregar subproducto
                        var found = false;
                        for (let i = 0; i < this.Subproductos.length; i++) {
                            var founds = [];
                            this.TiposCaracteristica.forEach((tipo, index) => {
                                if ( this.Subproducto.caracteristicas[index].caracteristica == this.Subproductos[i].caracteristicas[index].caracteristica) founds.push(true);
                                else founds.push(false);
                            })
                            for (let j = 0; j < founds.length; j++) {
                                if ( founds[j] == 0 ) break;
                                if ( j == founds.length-1 && founds[j] == 1 ) found = true;
                            }
                            if ( found ) break;
                        }

                        if ( found ) {
                            this.Error.mensaje.push("Ese producto ya se encuentra en lista");                                           //producto existente
                        } else {
                            this.TiposCaracteristica.forEach((tipo, index) => {    // caracteristicas
                                if ( tipo.required ) {
                                    if ( this.Subproducto.caracteristicas[index].caracteristica == '' ) {
                                        this.Error.mensaje.push('Debe seleccionar un '+tipo.nombre.toLowerCase());
                                    }
                                }
                            });
                            if ( this.Subproducto.precio_menor == '' ) {    // precio al por menor
                                this.Error.mensaje.push("Debe ingresar un precio por unidad en el formulario")
                            } else if ( isNaN(parseInt(this.Subproducto.precio_menor)) || parseInt(this.Subproducto.precio_menor) <= 0 ) {
                                this.Error.mensaje.push("El precio por unidad ingresado en el formulario es incorrecto");
                            }
                            if ( this.Subproducto.precio_mayor == '' ) {    // precio al por mayor
                                this.Error.mensaje.push("Debe ingresar un precio por mayor en el formulario")
                            } else if ( isNaN(parseInt(this.Subproducto.precio_mayor)) || parseInt(this.Subproducto.precio_mayor) <= 0 ) {
                                this.Error.mensaje.push("El precio por mayor ingresado en el formulario es incorrecto");
                            }
                        }
                        break;
                    case 3: // El producto ya existe
                        this.Error.mensaje.push("El Producto ya esta registrado o ha ocurrido un error");    //error o producto existente
                        break;
                    case 4:
                        for (let i = 0; i < this.ListaSuperProducto.length; i++) {
                            if ( this.SuperProducto.id == this.ListaSuperProducto[i].id ) {
                                if ( this.SuperProducto.nombre == this.ListaSuperProducto[i].nombre && this.SuperProducto.descripcion == this.ListaSuperProducto[i].descripcion ) {
                                    this.Error.mensaje.push("Ningun cambio realizado");    //sin cambios
                                }
                                break;
                            }
                        }
                        break;
                    case 5:
                        if ( this.Subproductos.length >= this.maxSubproductos ) {
                            this.Error.mensaje.push('Por ahora no se pueden registrar mas subproductos, registre el producto y luego siga agregando mas subproductos');
                        }
                        break;
                }

                if ( this.Error.mensaje.length ) {
                    this.Error.estado = 1; 
                    this.Modal.loading = false;
                }

                return this.Error.estado;
            },
            closeError(){
                this.Error.estado = 0;
                this.Error.numero = 0;
                this.Error.mensaje = [];
            },
        },
        mounted() {
            this.getCaracteristicas();

            this.$parent.$on('abrirModal', this.abrirModal);
        }
    }
</script>

<style scoped>
</style>