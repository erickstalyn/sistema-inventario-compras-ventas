<template>

    <div  v-if="estadoModal" class="modal text-gray-900 mostrar">
        <div v-if="Modal.estado" class="modal-dialog modal-dialog-centered modal-dialog-scrollable animated bounceIn fast" :class="Modal.tamaño">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                    <button type="button" @click="cerrarModal()" class="close">X</button>
                </div>
                
                <div class="modal-body">
                    <!-- Modal Numero 1 de AGREGAR-->
                    <div v-if="Modal.tipo == 'agregar'">
                        <div v-if="Error.estado && (Error.numero==1 || Error.numero==3)" class="col-md-12 d-flex justify-content-center mb-3">
                            <div class="alert alert-danger m-0">
                                <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                                <strong>Corregir los siguentes errores:</strong>
                                <ul class="m-0 pl-5"> 
                                    <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                </ul>
                            </div>
                        </div>
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
                        <div class="col-md-12 input-group mt-3">
                            <div class="container-small col-md-4 pl-0">
                                <div class="col-md-12 shadow rounded bg-white pt-3 pb-3">
                                    <div v-if="Error.estado && (Error.numero==2 || Error.numero==5)" class="col-md-12 d-flex justify-content-center mb-3">
                                        <div class="alert alert-danger m-0">
                                            <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                                            <strong>Corregir los siguentes errores:</strong>
                                            <ul class="m-0 pl-4"> 
                                                <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                            </ul>
                                        </div>
                                    </div>
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
                    <!-- Modal Numero 2 de VER-->
                    <div v-if="Modal.tipo == 'ver'">
                        <div class="row form-group">
                            <label class="col-md-2">Nombre:</label>
                            <label class="col-md-5" v-text="SuperProducto.nombre"></label>
                            <label class="col-md-5">Fecha de creacion:   {{SuperProducto.created_at}}</label>
                            <label class="col-md-2">Descripcion:</label>
                            <label class="col-md-10" v-text="SuperProducto.descripcion?SuperProducto.descripcion:'-'"></label>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-9 font-weight-bold">LISTA DE PRODUCTOS</label>
                            <label class="col-md-3">Super Stock:   {{SuperProducto.superstock}}</label>
                            <div class="col-md-12" v-if="ListaProducto.length">
                                <table class="table table-bordered table-striped table-sm text-gray-900">
                                    <thead>
                                        <tr class="table-primary">
                                            <th>Tamaño</th>
                                            <th>Color</th>
                                            <th>Costo de produccion</th>
                                            <th>Precio al por menor</th>
                                            <th>Precio al por mayor</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(producto, indice) in ListaProducto" :key="indice" >
                                            <td v-text="producto.size"></td>
                                            <td v-text="producto.color"></td>
                                            <td v-text="producto.costo_produccion?producto.costo_produccion:'-'"></td>
                                            <td v-text="producto.precio_menor"></td>
                                            <td v-text="producto.precio_mayor"></td>
                                            <td v-text="producto.stock"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12" v-else>
                                <label class="text text-danger">Sin productos</label>
                            </div>
                        </div>
                        
                    </div>
                    <!-- Modal Numero 3 de EDITAR-->
                    <div v-if="Modal.tipo == 'editar'">
                        <div v-if="Error.estado && (Error.numero==1 || Error.numero==3 || Error.numero==4)" class="row d-flex justify-content-center">
                            <div class="alert alert-danger">
                                <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                                <strong>Corregir los siguentes errores:</strong>
                                <ul> 
                                    <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                </ul>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3" for="nom">Nombre&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="col-md-9 form-control form-control-sm" v-model="SuperProducto.nombre" placeholder="Ingrese el nombre" id="nom">
                        </div>
                        <div class="row form-group">
                            <label class="col-md-3" for="des">Descripcion</label>
                            <input type="text" class="col-md-9 form-control form-control-sm" v-model="SuperProducto.descripcion" placeholder="Ingrese la descripcion" id="des">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="col-md-12 form-group d-flex justify-content-around m-0">
                        <div v-if="Modal.btnSuccess!=null" class="col-md-3">
                            <button type="button" class="col-md-12 btn btn-success" @click="accionar()" :disabled="Modal.loading">
                                <span v-if="!Modal.loading" class="h5 m-2 mt-2 mb-2" v-text="Modal.btnSuccess"></span>
                                <span v-else class="fas fa-spinner fa-spin fa-lg" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div v-if="Modal.btnCancel!=null" class="col-md-3">
                            <button type="button" class="col-md-12 btn btn-secondary" @click="cerrarModal()">
                                <span class="h5 m-2" v-text="Modal.btnCancel"></span>
                            </button>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: {
            tipoModal: {
                type: String,
                default: () => null,
                required: false
            },
            initProducto: {
                type: Object,
                default: () => {},
                required: true
            }
        },
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

                //datos de los subproductos
                maxSubproductos: 9,
                Subproductos: [],
                Subproducto : {
                    id: 0,
                    caracteristicas: [],
                    precio_menor: '',
                    precio_mayor: '',
                },

                TiposCaracteristica: [],

                //datos de modales
                Modal: {
                    estado: 0,
                    tipo: null,
                    titulo: 0,
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
            estadoModal: function() {
                if ( this.tipoModal == null ) {
                    return 0;
                } else {
                    this.abrirModal(this.tipoModal);
                    return 1;
                }
            }
        },
        methods: {
            agregar(){
                if ( this.validar(1) ) return;
                
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
                        me.validar(3);
                    }
                }).catch(error => {
                    console.log(error);
                }).then(reponse => {
                    me.Modal.loading = false;
                });
            },
            editar(){
                if ( this.validar(1) ) return;
                if ( this.validar(4) ) return;

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
                        me.validar(3);
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
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
            abrirModal(tipo){
                this.Modal.tipo = tipo;
                
                switch ( this.Modal.tipo ) {
                    case 'agregar':
                        this.abrirModalAgregar(); break;
                    case 'ver':
                        this.abrirModalVer(this.initProducto); break;
                    case 'editar':
                        this.abrirModalEditar(this.initProducto); break;
                    default:
                        console.error('Tried to open a diferent modal type. (type = "' + modal.tipo + '")'); break;
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

                this.Producto.categoria_id = 0;
                this.Producto.marca_id = 0;
                this.Producto.modelo = '';
                this.Producto.nombre = '';
                this.Producto.descripcion = '';

                this.Subproducto.id = null;
                for (let i = 0; i < this.Subproducto.caracteristicas.length; i++) {
                    this.Subproducto.caracteristicas[i].caracteristica = ''
                }
                this.Subproducto.precio_menor = '';
                this.Subproducto.precio_mayor = '';

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
                this.Modal.tipo = 0;
                this.Modal.titulo = '';
                this.Modal.tamaño = '';
                this.Modal.loading = false;
                this.Modal.btnSuccess = null;
                this.Modal.btnCancel = null;

                this.Error.estado = 0;
                this.Error.numero = 0;
                this.Error.mensaje = [];

                this.Producto.id = 0;
                this.Producto.categoria_id = 0;
                this.Producto.marca_id = 0;
                this.Producto.modelo = '';
                this.Producto.nombre = '';
                this.Producto.descripcion = '';
                this.Producto.stock = 0;
                this.Producto.created_at = '';

                this.Subproducto.id = 0;
                for (let i = 0; i < this.Subproducto.caracteristicas.length; i++) {
                    this.Subproducto.caracteristicas[i].caracteristica = ''
                }
                this.Subproducto.precio_menor = '';
                this.Subproducto.precio_mayor = '';

                this.Subproductos = [];
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
            this.getCategorias();
            this.getMarcas();
            this.getCaracteristicas();
        }
    }
</script>

<style scoped>
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
</style>