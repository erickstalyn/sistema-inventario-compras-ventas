<template>

    <div class="modal text-gray-900" :class="{'show-modal': Modal.estado}">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animated bounceIn fast" :class="Modal.tamaño">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                    <button type="button" @click="cerrarModal()" class="close">X</button>
                </div>
                
                <div class="modal-body">
                    
                    <!-- Modal Numero 1 de AGREGAR-->
                    <!-- <div> -->

                    <div v-if="Error.estado" class="col-md-12 d-flex justify-content-center mb-3">
                        <div class="alert alert-danger m-0">
                            <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                            <strong>Corregir los siguentes errores:</strong>
                            <ul class="m-0 pl-5"> 
                                <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                            </ul>
                        </div>
                    </div>
                    
                    <form-producto @changeValue="changeValue" @addError="addError"></form-producto>

                    <div class="col-md-12 input-group mt-3">
                        <div class="container-small col-md-4 pl-0">

                            <form-subproducto @changeValue="changeValue" @addError="addError"></form-subproducto>

                        </div>
                        <div class="container-small col-md-8 pr-0 d-flex">
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
                                                    <td v-for="caracteristica in subproducto.caracteristicas" :key="caracteristica.caracteristica" v-text="caracteristica.caracteristica"></td>
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
                        </div>
                    </div>

                </div>

                <div class="modal-footer justify-content-around">
                    <div v-if="Modal.btnSuccess!=null" >
                        <button type="button" class="btn btn-success" @click="accionar()" :disabled="Modal.loading">
                            <span v-if="!Modal.loading"  v-text="Modal.btnSuccess"></span>
                            <span v-else class="fas fa-spinner fa-spin fa-lg" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                    <div v-if="Modal.btnCancel!=null" >
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">
                            <span  v-text="Modal.btnCancel"></span>
                        </button>
                    </div>
                </div>
            
            </div>
        </div>
    </div>

</template>

<script>

    import FormProducto from './Modal-FormProducto.vue';
    import FormSubproducto from './Modal-FormSubproducto.vue';
    
    export default {
        components: {
            FormProducto,
            FormSubproducto
        },
        data(){
            return {
                //datos de producto
                Categorias: [],
                Marcas: [],
                Producto: {
                    id: null,
                    categoria_id: 0,
                    marca_id: 0,
                    modelo: '',
                    nombre: '',
                    descripcion: '',
                    created_at: ''
                },

                //datos de los subproductos
                maxSubproductos: 9,
                Subproductos: [],
                Subproducto : {
                    id: null,
                    caracteristicas: [],
                    precio_menor: '',
                    precio_mayor: '',
                },

                TiposCaracteristica: [],

                //datos de modales
                Modal: {
                    estado: 0,
                    tipo: null,
                    titulo: '',
                    tamaño: '',
                    loading: false,
                    btnSuccess: '', //Boton de aceptar
                    btnCancel: '' //Boton de cancelar
                },

                //datos de paginacion
                Error: {
                    estado: 0,
                    numero: 0,
                    mensaje: []
                },

                //datos de la ruta de consultas
                Ruta: {
                    subproducto: '/subproducto',
                    producto: '/producto'
                }
            }
        },
        computed: {
        },
        methods: {
            agregar(){
                if ( this.validate(1) ) return;
                
                var me = this;
                var url = this.Ruta.producto+'/agregar';
                
                axios.post(url, {
                    'producto' : this.Producto,
                    'subproductos' : this.Subproductos
                }).then(response => {
                    if ( response.data.success ) {
                        me.cerrarModal();
                        me.$emit('listar');
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'El super producto se ha AGREGADO correctamente',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    } else {
                        console.log(response.data.error);
                        me.validate(3);
                    }
                }).catch(error => {
                    console.log(error);
                }).then(reponse => {
                    me.Modal.loading = false;
                });
            },
            editar(){
                if ( this.validate(1) ) return;
                if ( this.validate(4) ) return;

                var me = this;
                var url = this.Ruta.superproducto+'/editar';
                axios.put(url, {
                    'id' : this.SuperProducto.id,
                    'nombre' : this.SuperProducto.nombre,
                    'descripcion' : this.SuperProducto.descripcion
                }).then(function(response){
                    var estado = response.data.estado;
                    if ( estado ) {
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'El super producto se ha EDITADO correctamente',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    } else {
                        console.log(response.data.error);
                        me.validate(3);
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            agregarSubproducto(){
                if ( this.validate(5) ) return;
                if ( this.validate(2) ) return;
                
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
            eliminarSubproducto(index){
                this.Subproductos.splice(index, 1);
            },
            // setEstado(){
            //     var me = this;
            //     var url = this.Ruta+'/setEstado';

            //     axios.put(url, {
            //         'id' : me.SuperProducto.id,
            //         'estado': me.SuperProducto.estado
            //     }).then(function (response) {
            //         me.listar();
            //         var estado = response.data.estado;
            //         if ( estado == 0 ) console.log(response.data.error);    
            //         Swal.fire({
            //             position: 'top-end',
            //             toast: true,
            //             type: estado==1?'success':'info',
            //             title: 'El super producto'+ (estado==1?' se ha':' NO se ha') + (me.SuperProducto.estado==1?' ACTIVADO':' DESACTIVADO') +' correctamente',
            //             showConfirmButton: false,
            //             timer: 4500,
            //             animation:false,
            //             customClass:{
            //                 popup: 'animated bounceIn fast'
            //             }
            //         });
            //     }).catch(function(error){
            //         console.log(error);
            //     });
            // },
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
            prepararModal(modal){
                this.Modal.titulo = modal.titulo;
                this.Modal.tamaño = modal.tamaño!=undefined?modal.tamaño:'';
                this.Modal.loading = false;
                this.Modal.btnSuccess = modal.btnSuccess!=undefined?modal.btnSuccess:null;
                this.Modal.btnCancel = modal.btnCancel!=undefined?modal.btnCancel:null;
            },
            ejecutarModal(){
                this.Modal.estado = true;
            },
            abrirModalAgregar(){
                this.prepararModal({
                    titulo: 'Agregar nuevo PRODUCTO',
                    tamaño: 'modal-xl',
                    btnSuccess: 'Agregar PRODUCTO',
                    btnCancel: 'Cancelar'
                });
                
                this.$emit('abrirModal', this.Modal.tipo);

                this.Subproductos = [];

                this.ejecutarModal();
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
            // abrirModalSetEstado(data = [], estado){
            //     this.SuperProducto.id = data['id'];
            //     this.SuperProducto.nombre = data['nombre'];
            //     this.SuperProducto.estado = estado;

            //     Swal.fire({
            //         title: '¿Esta seguro de '+ (this.SuperProducto.estado?'ACTIVAR':'DESACTIVAR') +' el super producto "'+this.SuperProducto.nombre+'"?',
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonText: 'Aceptar',
            //         cancelButtonText: 'Cancelar',
            //         reverseButtons: false,
            //         customClass: {
            //             confirmButton: 'btn btn-success',
            //             cancelButton: 'btn btn-secondary'
            //         },
            //         buttonsStyling: false
            //     }).then((result) => {
            //         if (result.value) {
            //             this.setEstado();
            //         } else if ( result.dismiss === Swal.DismissReason.cancel ) {
            //         }
            //     });
            // },
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
            changeValue(variable, valor){
                switch ( variable ) {
                    case 'producto':
                        this.Producto = valor; break;
                    case 'subproducto':
                        this.Subproducto = valor; break;
                    default:
                        console.error("Option '"+variable+"-"+valor+"' don't found in changeValue() function"); break;
                }
            },
            listaProducto(){
                var me = this;
                var url = this.Ruta.producto+'/listaProducto?id='+this.SuperProducto.id;
                
                axios.get(url).then(function (response) {
                    me.ListaProducto = response.data;
                });
            },
            accionar(){
                this.Modal.loading = true;
                switch( this.Modal.tipo ){
                    case 'agregar': 
                        this.agregar(); break;
                    case 'editar': 
                        this.editar(); break;
                    default:
                        console.error("action type don't found"); break;
                }
            },
            validate(component = '', numero){
                this.Error.numero = numero;
                this.Error.mensaje = [];

                this.$emit('validate', component, numero);
                
                const errors = [];

                switch (numero) {
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
                            errors.push("Ese producto ya se encuentra en lista");                                           //producto existente
                        } else {
                            this.TiposCaracteristica.forEach((tipo, index) => {    // caracteristicas
                                if ( tipo.required ) {
                                    if ( this.Subproducto.caracteristicas[index].caracteristica == '' ) {
                                        errors.push('Debe seleccionar un '+tipo.nombre.toLowerCase());
                                    }
                                }
                            });
                            if ( this.Subproducto.precio_menor == '' ) {    // precio al por menor
                                errors.push("Debe ingresar un precio por unidad en el formulario")
                            } else if ( isNaN(parseInt(this.Subproducto.precio_menor)) || parseInt(this.Subproducto.precio_menor) <= 0 ) {
                                errors.push("El precio por unidad ingresado en el formulario es incorrecto");
                            }
                            if ( this.Subproducto.precio_mayor == '' ) {    // precio al por mayor
                                errors.push("Debe ingresar un precio por mayor en el formulario")
                            } else if ( isNaN(parseInt(this.Subproducto.precio_mayor)) || parseInt(this.Subproducto.precio_mayor) <= 0 ) {
                                errors.push("El precio por mayor ingresado en el formulario es incorrecto");
                            }
                        }
                        break;
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
                    case 5:
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
            addError(errors = [], type = ''){
                if ( errors.length == 0 ) return;

                if ( type === 'unique' ) this.Error.mensaje = [];
                
                this.Error.mensaje.concat(errors);

                this.Modal.loading = false;
                this.Error.estado = 1;
            },
            closeError(){
                this.Error.estado = 0;
                this.Error.numero = 0;
                this.Error.mensaje = [];
            },
        },
        mounted() {
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