<template>
    <main>

        <!-- Interfaz Principal -->
        <div>
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Productos</span>&nbsp;&nbsp;
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="fas fa-user-plus"></i>&nbsp; Nuevo
                </button>
                <button type="button" class="btn btn-danger">
                    <i class="far fa-file-pdf"></i>&nbsp; PDF
                </button>
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="search" class="form-control" v-model="Busqueda.texto" @keyup.enter="listar()">
                        <button type="button" class="btn btn-primary" @click="listar()">
                            <i class="fa fa-search"></i>&nbsp; Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-5"></div>
                <div class="col-md-1" align="right">
                    <label>N° filas:</label>
                </div>
                <div class="col-md-1">
                    <select class="form-control text-gray-900" v-model="Busqueda.filas">
                        <option v-for="fila in Filas" :key="fila" :value="fila" v-text="fila"></option>
                    </select>
                </div>
            </div>

            <!-- Listado -->
            <div v-if="ListaProducto.length" class="table-responsive">
                <!-- Tabla -->
                <div class="table-scroll-20 overflow-auto">
                    <table class="table table-bordered table-striped table-sm text-gray-900">
                        <thead>
                            <tr class="bg-success">
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Costo de produccion</th>
                                <th class="text-center">Precio al por menor</th>
                                <th class="text-center">Precio al por mayor</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="producto in ListaProducto" :key="producto.id" >
                                <td v-text="producto.nombre"></td>
                                <td v-text="producto.costo_produccion" class="text-right"></td>
                                <td v-text="producto.precio_menor" class="text-right"></td>
                                <td v-text="producto.precio_mayor" class="text-right"></td>
                                <td v-text="producto.stock" class="text-right"></td>
                                <td class="text-center">
                                    <button type="button" @click="abrirModalVer(producto)" title="VER" class="btn btn-primary btn-sm">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    <button type="button" @click="abrirModalEditar(producto)" title="EDITAR" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" @click="abrirModalMaterial(producto)" title="MATERIALES" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-hammer"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Barra de navegacion -->
                <nav class="d-flex justify-content-center">
                    <ul class="pagination">
                        <li class="page-item">
                            <a href="#" @click="cambiarPagina(Paginacion.currentPage-1)" class="page-link">Anterior</a>
                        </li>
                        <li class="page-item" v-for="page in Paginas" :key="page" :class="[page==Paginacion.currentPage?'active':'']">
                            <a href="#" @click="cambiarPagina(page)" v-text="page" class="page-link"></a>
                        </li>
                        <li class="page-item">
                            <a href="#" @click="cambiarPagina(Paginacion.currentPage+1)" class="page-link">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div v-else>
                <h5>No se han encontrado resultados</h5>
            </div>

        </div>

        <!-- Modales: Agregar, Ver, Editar, Materiales -->
        <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animated bounceIn fast" :class="Modal.tamaño">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Modal Numero 1 de AGREGAR-->
                        <div v-if="Modal.numero==1" class="container">
                            <div v-if="Error.estado && (Error.numero==1 || Error.numero==2)" class="row d-flex justify-content-center">
                                <div class="alert alert-danger">
                                    <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Nombre</label>
                                <label class="col-md-9 text-info" v-text="updateNombre"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Super Producto&nbsp;<span class="text-danger">*</span></label>
                                <select class="col-md-7 custom-select custom-select-sm" v-model="Producto.superproducto_id">
                                    <option value="0" disabled>Seleccione un superproducto</option>
                                    <option class="text-gray-900" v-for="superproducto in SelectSuperProducto" :key="superproducto.id" :value="superproducto.id" v-text="superproducto.nombre"></option>
                                </select>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Tamaño&nbsp;<span class="text-danger">*</span></label>
                                <select class="col-md-7 custom-select custom-select-sm" v-model="Producto.size">
                                    <option value="" disabled>Seleccione un tamaño</option>
                                    <option class="text-gray-900" v-for="size in SelectSize" :key="size.nombre" :value="size.nombre" v-text="size.nombre"></option>
                                </select>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Color&nbsp;<span class="text-danger">*</span></label>
                                <select class="col-md-7 custom-select custom-select-sm" v-model="Producto.color">
                                    <option value="" disabled>seleccione un color</option>
                                    <option class="text-gray-900" v-for="color in SelectColor" :key="color.nombre" :value="color.nombre" v-text="color.nombre"></option>
                                </select>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Codigo</label>
                                <input type="text" class="col-md-7 form-control form-control-sm" v-model="Producto.codigo" maxlength="13" placeholder="Ingrese el codigo">
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Precio al por menor&nbsp;<span class="text-danger">*</span></label>
                                <label class="col-md-3 text-right">S/.</label>
                                <input type="number" class="col-md-4 text-right form-control form-control-sm" v-model="Producto.precio_menor" placeholder="Ingrese el precio normal">
                            </div>
                            <div class="row">
                                <label class="col-md-5 font-weight-bold">Precio al por mayor&nbsp;<span class="text-danger">*</span></label>
                                <label class="col-md-3 text-right">S/.</label>
                                <input type="number" class="col-md-4 text-right form-control form-control-sm" v-model="Producto.precio_mayor" placeholder="Ingrese el precio normal">
                            </div>
                        </div>
                        <!-- Modal Numero 2 de VER-->
                        <div v-if="Modal.numero==2" class="container">
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Nombre</label>
                                <label class="col-md-9 text-info" v-text="Producto.nombre"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Codigo</label>
                                <label class="col-md-6 text-info text-right" v-text="Producto.codigo?Producto.codigo:'-'"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Costo de produccion</label>
                                <label class="col-md-3 text-right">S/.</label>
                                <label class="col-md-3 text-right text-info" v-text="Producto.costo_produccion"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Precio al por menor</label>
                                <label class="col-md-3 text-right">S/.</label>
                                <label class="col-md-3 text-right text-info" v-text="Producto.precio_menor"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Precio al por mayor</label>
                                <label class="col-md-3 text-right">S/.</label>
                                <label class="col-md-3 text-right text-info" v-text="Producto.precio_mayor"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Stock</label>
                                <label class="col-md-6 text-right text-info" v-text="Producto.stock"></label>
                            </div>
                            <div class="row">
                                <label class="col-md-5 font-weight-bold">Fecha de creacion</label>
                                <label class="col-md-6 text-right text-info" v-text="Producto.created_at"></label>
                            </div>
                        </div>
                        <!-- Modal Numero 3 de EDITAR-->
                        <div v-if="Modal.numero==3" class="container">
                            <div v-if="Error.estado && (Error.numero==1 || Error.numero==2 || Error.numero==5)" class="row d-flex justify-content-center">
                                <div class="alert alert-danger">
                                    <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold">Nombre</label>
                                <label class="col-md-9 text-info" v-text="updateNombre"></label>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Super Producto&nbsp;<span class="text-danger">*</span></label>
                                <select class="col-md-7 custom-select custom-select-sm" v-model="Producto.superproducto_id">
                                    <option value="0" disabled>Seleccione un superproducto</option>
                                    <option class="text-gray-900" v-for="superproducto in SelectSuperProducto" :key="superproducto.id" :value="superproducto.id" v-text="superproducto.nombre"></option>
                                </select>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Tamaño&nbsp;<span class="text-danger">*</span></label>
                                <select class="col-md-7 custom-select custom-select-sm" v-model="Producto.size">
                                    <option value="" disabled>Seleccione un tamaño</option>
                                    <option class="text-gray-900" v-for="size in SelectSize" :key="size.nombre" :value="size.nombre" v-text="size.nombre"></option>
                                </select>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Color&nbsp;<span class="text-danger">*</span></label>
                                <select class="col-md-7 custom-select custom-select-sm" v-model="Producto.color">
                                    <option value="" disabled>seleccione un color</option>
                                    <option class="text-gray-900" v-for="color in SelectColor" :key="color.nombre" :value="color.nombre" v-text="color.nombre"></option>
                                </select>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Codigo</label>
                                <input type="text" class="col-md-7 form-control form-control-sm" v-model="Producto.codigo" maxlength="13" placeholder="Ingrese el codigo">
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 font-weight-bold">Precio al por menor&nbsp;<span class="text-danger">*</span></label>
                                <label class="col-md-3 text-right">S/.</label>
                                <input type="number" class="col-md-4 text-right form-control form-control-sm" v-model="Producto.precio_menor" placeholder="Ingrese el precio normal">
                            </div>
                            <div class="row">
                                <label class="col-md-5 font-weight-bold">Precio al por mayor&nbsp;<span class="text-danger">*</span></label>
                                <label class="col-md-3 text-right">S/.</label>
                                <input type="number" class="col-md-4 text-right form-control form-control-sm" v-model="Producto.precio_mayor" placeholder="Ingrese el precio normal">
                            </div>
                        </div>
                        <!-- Modal Numero 4 de MATERIALES-->
                        <div v-if="Modal.numero==4" class="row">
                            <div class="col-md-4 container">
                                <div v-if="Error.estado && Error.numero==3" class="row d-flex justify-content-center">
                                    <div class="alert alert-danger">
                                        <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                                        <strong>Corregir los siguentes errores:</strong>
                                        <ul> 
                                            <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                        </ul>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-12 font-weight-bold">AGREGAR MATERIAL</label>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-4 font-weight-bold">Material&nbsp;<span class="text-danger">*</span></label>
                                    <select class="col-md-8 custom-select custom-select-sm" v-model="ProductoMaterial.material_id" @click="update(0)">
                                        <option value="0" disabled>Seleccione</option>
                                        <option class="text-gray-900" v-for="material in SelectMaterial" :key="material.id" :value="material.id" v-text="material.nombre"></option>
                                    </select>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-4 font-weight-bold">P/U</label>
                                    <label class="col-md-8 text-info" v-text="'S/. '+ProductoMaterial.costo_unitario"></label>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-4 font-weight-bold">Cantidad&nbsp;<span class="text-danger">*</span></label>
                                    <input type="number" class="col-md-3 text-right form-control form-control-sm" v-model="ProductoMaterial.cantidad" @keyup="update(0)" @click="update(0)" placeholder="Ingrese la cantidad">
                                    <label class="col-md-5 text-info" v-text="ProductoMaterial.unidad"></label>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-4 font-weight-bold">Subtotal</label>
                                    <label class="col-md-8 text-info" v-text="'S/. '+ProductoMaterial.subtotal"></label>
                                </div>
                                <div class="row form-group col-md-12 d-flex justify-content-around">
                                    <button type="button" class="btn btn-sm btn-info btn-icon-split" @click="add()">
                                        <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                                        <span class="text">Agregar Material</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-8 container">
                                <div v-if="Error.estado && Error.numero==6" class="row d-flex justify-content-center">
                                    <div class="alert alert-danger">
                                        <button type="button" @click="closeError()" class="close text-primary" data-dismiss="alert">×</button>
                                        <strong>Corregir los siguentes errores:</strong>
                                        <ul> 
                                            <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-12 font-weight-bold">LISTA DE MATERIALES</label>
                                </div>
                                <div class="row form-group table-scroll-15" v-if="ListaProductoMaterial.length">
                                    <table class="table table-bordered table-striped table-sm text-gray-900">
                                        <thead>
                                            <tr class="bg-info">
                                                <th class="text-center">Quitar</th>
                                                <th class="text-center">Material</th>
                                                <th class="text-center">Unidad</th>
                                                <th class="text-center">Costo unitario</th>
                                                <th class="text-center w6rem">Cantidad</th>
                                                <th class="text-center">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(material, indice) in listaProductoMaterialFiltrada" :key="indice">
                                                <td class="text-center w2rem">
                                                    <button type="button" class="btn btn-circle btn-outline-danger btn-sm" @click="remove(indice)" title="QUITAR">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </td>
                                                <td v-text="material.nombre"></td>
                                                <td v-text="material.unidad"></td>
                                                <td class="text-right" v-text="material.costo_unitario"></td>
                                                <td>
                                                    <input type="number" class="text-right form-control form-control-sm text-gray-900" min="1" step="1" v-model="material.cantidad" @keyup="update(1, material)" @click="update(1, material)">
                                                </td>
                                                <td class="text-right" v-text="material.subtotal"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row form-group table-scroll-15" v-else>
                                    <label class="col-md-12 text-danger">No existen materiales</label>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-7"></div>
                                    <label class="col-md-3 font-weight-bold">Costo de produccion: </label>
                                    <label class="col-md-2 text-info text-right pr-2" v-text="'S/. '+updateCostoProduccion"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row form-group col-md-12 d-flex justify-content-around">
                            <div v-if="Modal.btnA">
                                <button type="button" @click="accionar()" class="btn btn-success" v-text="Modal.btnA"></button>
                            </div>
                            <div v-if="Modal.btnC">
                                <button type="button" @click="cerrarModal()" class="btn btn-secondary" v-text="Modal.btnC"></button>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>

    </main>
</template>

<script>
    export default {
        data(){
            return {
                //datos generales
                ListaProducto: [],
                Producto: {
                    id: 0,
                    superproducto_id: 0,
                    nombre: '',
                    codigo: '',
                    size: '',
                    color: '',
                    costo_produccion: 0,
                    precio_menor: 0,
                    precio_mayor: 0,
                    stock: 0,
                    created_at: ''
                },
                SelectSuperProducto: [],
                SelectSize: [],
                SelectColor:[],

                //datos del producto_material
                ListaProductoMaterial: [],
                ProductoMaterial: {
                    id: 0,
                    producto_id: 0,
                    material_id: 0,
                    nombre: '',
                    unidad: '',
                    costo_unitario: 0,
                    cantidad: 0,
                    subtotal: 0
                },
                SelectMaterial: [],

                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 2,
                    filas: 5
                },

                //datos de modales
                Modal: {
                    numero: 0,
                    estado: 0,
                    titulo: '',
                    tamaño: '',
                    btnA: '',
                    btnC: ''
                },

                //datos de paginacion
                Paginacion: {
                    total: 0,
                    currentPage: 0,
                    lastPage: 0,
                    perPage: 0,
                    firstItem: 0,
                    lastItem: 0
                },
                Navegacion:{
                    offset: 3,
                    ordenarPor: 'id',
                    orden: 'desc' 
                },

                //datos de errores
                Error: {
                    estado: 0,
                    numero: 0,
                    mensaje: []
                },

                //datos de ruta de consultas
                Ruta: {
                    superproducto: '/superproducto',
                    producto: '/producto',
                    productomaterial: '/productomaterial',
                    material: '/material',
                    data: '/data'
                }
            }
        },
        computed: {
            Paginas: function(){
                if ( !this.Paginacion.lastItem ) {
                    return [];
                }

                var from = this.Paginacion.currentPage - this.Navegacion.offset;
                if ( from < 1) {
                    from = 1;
                }

                var to = this.Paginacion.currentPage + this.Navegacion.offset*2;
                if ( to > this.Paginacion.lastPage ) {
                    to = this.Paginacion.lastPage;
                }

                var pagesArray = [];
                while ( from <= to ) {
                    pagesArray.push(from);
                    from++;
                }
                
                return pagesArray;
            },
            Filas: function(){
                var min = 3;
                var max = 20;
                var filas = [];

                while ( min <= max) {
                    filas.push(min);
                    min++;
                }

                return filas;
            },
            updateNombre: function () {
                var name = '';
                for (let i = 0; i < this.SelectSuperProducto.length; i++) {
                    if ( this.SelectSuperProducto[i].id == this.Producto.superproducto_id ) {
                        name = this.SelectSuperProducto[i].nombre; break;
                    }
                }

                this.Producto.nombre = name + ' ' + this.Producto.size + ' ' + this.Producto.color;

                return this.Producto.nombre;
            },
            updateCostoProduccion: function() {
                var costo_produccion = 0.00;

                for (var i = 0; i < this.ListaProductoMaterial.length; i++) {
                    if ( this.ListaProductoMaterial[i].estado == 1 ) {
                        costo_produccion = Number.parseFloat(costo_produccion) + Number.parseFloat(this.ListaProductoMaterial[i].subtotal);
                    } 
                }
                this.Producto.costo_produccion = costo_produccion;

                return Number.parseFloat(costo_produccion).toFixed(2);
            },
            listaProductoMaterialFiltrada: function(){
                var lista = [];

                for (let i = 0; i < this.ListaProductoMaterial.length; i++) {
                    if ( this.ListaProductoMaterial[i].estado == 1 ) {
                        lista.push(this.ListaProductoMaterial[i]);
                    }
                }

                return lista;
            }
        },
        methods: {
            listar(page = 1){
                this.Paginacion.currentPage = page;

                var me = this;
                var url = this.Ruta.producto+'?'
                        +'page='+this.Paginacion.currentPage
                        +'&texto='+this.Busqueda.texto
                        +'&filas='+this.Busqueda.filas;
                
                axios.get(url).then(function (response) {
                    me.ListaProducto = response.data.productos.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            agregar(){
                if ( this.validar(1) ) return;
                
                var me = this;
                var url = this.Ruta.producto+'/agregar';
                axios.post(url , {
                    'superproducto_id' : this.Producto.superproducto_id,
                    'nombre' : this.Producto.nombre,
                    'codigo' : this.Producto.codigo,
                    'size': this.Producto.size,
                    'color' : this.Producto.color,
                    'precio_menor' : this.Producto.precio_menor,
                    'precio_mayor' : this.Producto.precio_mayor
                }).then(function(response){
                    var estado = response.data.estado;
                    if ( estado == 1 ) {
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'El producto se ha AGREGADO correctamente',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    } else {
                        console.log(response.data.error);
                        me.validar(2);
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            editar(){
                if ( this.validar(1) ) return;
                if ( this.validar(5) ) return;
                
                var me = this;
                var url = this.Ruta.producto+'/editar';
                axios.put(url , {
                    'id': this.Producto.id,
                    'superproducto_id' : this.Producto.superproducto_id,
                    'nombre' : this.Producto.nombre,
                    'size': this.Producto.size,
                    'color' : this.Producto.color,
                    'precio_menor' : this.Producto.precio_menor,
                    'precio_mayor' : this.Producto.precio_mayor
                }).then(function(response){
                    var estado = response.data.estado;
                    if ( estado == 1 ) {
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'El producto se ha EDITADO correctamente',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    } else {
                        console.log(response.data.error);
                        me.validar(2);
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            editarMateriales(){
                if ( this.validar(6) ) return;

                var me = this;
                var url = this.Ruta.productomaterial+'/agregar';

                axios.post(url, {
                    'producto_id': this.Producto.id,
                    'listaproductomaterial': this.ListaProductoMaterial,
                    'costo_produccion': this.Producto.costo_produccion
                }).then(function (response) {
                    var estado = response.data.estado;
                    if ( estado == 1 ) {
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'La lista de materiales ha sido editada correctamente',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    } else {
                        console.log(response.data.error);
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            abrirModalAgregar(){
                this.abrirModal(1, 'Nuevo Producto', '', 'Agregar', 'Cancelar');

                this.Producto.superproducto_id = 0;
                this.Producto.nombre = '';
                this.Producto.codigo = '';
                this.Producto.size = '';
                this.Producto.color = '';
                this.Producto.precio_menor = 0;
                this.Producto.precio_mayor = 0;

                this.select(0);
                this.select(1);
                this.select(2);
            },
            abrirModalVer(data = []){
                this.abrirModal(2, 'Ver Producto', '', '', 'Cerrar');
                
                this.Producto.nombre = data['nombre'];
                this.Producto.codigo = data['codigo'];
                this.Producto.costo_produccion = data['costo_produccion'];
                this.Producto.precio_menor = data['precio_menor'];
                this.Producto.precio_mayor = data['precio_mayor'];
                this.Producto.stock = data['stock'];
                this.Producto.created_at = data['created_at'];
            },
            abrirModalEditar(data = []){
                this.abrirModal(3, 'Editar Producto', '', 'Editar', 'Cancelar');
                
                this.Producto.id = data['id'];
                this.Producto.superproducto_id = data['superproducto_id'];
                this.Producto.nombre = data['nombre'];
                this.Producto.codigo = data['codigo'];
                this.Producto.size = data['size'];
                this.Producto.color = data['color'];
                this.Producto.precio_menor = data['precio_menor'];
                this.Producto.precio_mayor = data['precio_mayor'];

                this.select(0);
                this.select(1);
                this.select(2);
            },
            abrirModalMaterial(data = []){
                this.abrirModal(4, 'Materiales del Producto', 'modal-xl', 'Guardar Materiales', 'Cancelar');
                
                this.Producto.id = data['id'];
                this.Producto.costo_produccion = data['costo_produccion'];

                this.ProductoMaterial.producto_id = this.Producto.id;
                this.ProductoMaterial.material_id = 0;
                this.ProductoMaterial.nombre = '';
                this.ProductoMaterial.unidad = '-';
                this.ProductoMaterial.costo_unitario = 0;
                this.ProductoMaterial.cantidad = 0;
                this.ProductoMaterial.subtotal = 0;

                this.select(3);
                this.list(0);
            },
            abrirModal(numero, titulo, tamaño, btnA, btnC){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.tamaño = tamaño;
                this.Modal.btnA = btnA;
                this.Modal.btnC = btnC;
            },
            cerrarModal(){
                this.Modal.numero = 0;
                this.Modal.estado = 0;
                this.Modal.titulo = '';
                this.Modal.tamaño = '';
                this.Modal.btnA = '';
                this.Modal.btnC = '';

                this.Error.estado = 0;
                this.Error.mensaje = [];

                this.Producto.id = 0;
                this.Producto.superproducto_id = 0;
                this.Producto.nombre = '';
                this.Producto.codigo = '';
                this.Producto.size = '';
                this.Producto.color = '';
                this.Producto.costo_produccion = 0;
                this.Producto.precio_menor = 0;
                this.Producto.precio_mayor = 0;
                this.Producto.stock = 0;
                this.Producto.created_at = '';

                this.ListaProductoMaterial = [];

                this.ProductoMaterial.producto_id = 0;
                this.ProductoMaterial.material_id = 0;
                this.ProductoMaterial.nombre = '';
                this.ProductoMaterial.unidad = '';
                this.ProductoMaterial.costo_unitario = 0;
                this.ProductoMaterial.cantidad = 0;
                this.ProductoMaterial.subtotal = 0;
            },
            accionar(){
                switch( this.Modal.numero ){
                    case 1: this.agregar(); break;
                    case 3: this.editar(); break;
                    case 4: this.editarMateriales(); break;
                }
            },
            select(numero){
                var me = this;
                switch ( numero ) {
                    case 0: 
                        if ( this.SelectSuperProducto.length ) break;
                        var url = this.Ruta.superproducto+'/selectSuperProducto';
                        axios.get(url).then(function(response){
                            me.SelectSuperProducto = response.data;
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                    case 1:
                        if ( this.SelectSize.length ) break;
                        var url = this.Ruta.data+'/selectSize';
                        axios.get(url).then(function(response){
                            me.SelectSize = response.data;
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                    case 2:
                        if ( this.SelectColor.length ) break;
                        var url = this.Ruta.data+'/selectColor';
                        axios.get(url).then(function(response){
                            me.SelectColor = response.data;
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                    case 3:
                        if ( this.SelectMaterial.length ) break;
                        var url = this.Ruta.material+'/selectMaterial';
                        axios.get(url).then(function(response){
                            me.SelectMaterial = response.data;
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                }
            },
            list(numero) {
                var me = this;
                switch ( numero ) {
                    case 0:
                        var url = this.Ruta.productomaterial+'/listaProductoMaterial?producto_id='+this.Producto.id;
                        axios.get(url).then(function (response) {
                            me.ListaProductoMaterial = response.data;
                        }).catch(function (error) {
                            console.log(error);
                        });
                        break;
                }
            },
            add(){
                if ( this.validar(3) ) return;

                let producto_material = {
                    id: 0,
                    material_id: this.ProductoMaterial.material_id,
                    nombre: this.ProductoMaterial.nombre,
                    unidad: this.ProductoMaterial.unidad,
                    costo_unitario: this.ProductoMaterial.costo_unitario,
                    cantidad: this.ProductoMaterial.cantidad,
                    subtotal: this.ProductoMaterial.subtotal,
                    estado: 1
                };
                
                var found = 0;
                for (let i = 0; i < this.ListaProductoMaterial.length; i++) {
                    if ( this.ListaProductoMaterial[i].material_id == producto_material.material_id ) {
                        this.ListaProductoMaterial[i].cantidad = producto_material.cantidad;
                        this.ListaProductoMaterial[i].subtotal = producto_material.subtotal;
                        this.ListaProductoMaterial[i].estado = 1;
                        found = 1; break;
                    }
                }
                
                if ( found == 0 ) this.ListaProductoMaterial.push(producto_material);

                this.ProductoMaterial.material_id = 0;
                this.ProductoMaterial.nombre = '';
                this.ProductoMaterial.unidad = '-';
                this.ProductoMaterial.costo_unitario = 0;
                this.ProductoMaterial.cantidad = 0;
                this.ProductoMaterial.subtotal = 0;
            },
            remove(indice){
                if ( this.ListaProductoMaterial[indice].id != 0 ) {
                    this.ListaProductoMaterial[indice].estado = 0;
                } else {
                    this.ListaProductoMaterial.splice(indice,1);
                }
            },
            update(numero, data = null){
                switch ( numero ) {
                    case 0:
                        for (let i = 0; i < this.SelectMaterial.length; i++) {
                            if ( this.SelectMaterial[i].id == this.ProductoMaterial.material_id ) {
                                this.ProductoMaterial.nombre = this.SelectMaterial[i].nombre;
                                this.ProductoMaterial.unidad = this.SelectMaterial[i].unidad;
                                this.ProductoMaterial.costo_unitario = this.SelectMaterial[i].costo;
                                this.ProductoMaterial.subtotal = (this.ProductoMaterial.costo_unitario * this.ProductoMaterial.cantidad).toFixed(2);
                                break;
                            }
                        }
                        break;
                    case 1:
                        if ( data == null ) break;
                        data.subtotal = (data.cantidad * data.costo_unitario).toFixed(2);
                        break;
                }
                
            },
            validar(numero){
                this.Error.estado = 0;
                this.Error.numero = numero;
                this.Error.mensaje = [];

                switch (numero) {
                    case 1:
                        if ( this.Producto.superproducto_id == 0 ) this.Error.mensaje.push("Debe seleccionar un superproducto");    //superproducto
                        if ( this.Producto.size == '' ) this.Error.mensaje.push("Debe seleccionar un tamaño");                      //size
                        if ( this.Producto.color == '' ) this.Error.mensaje.push("Debe seleccionar un color");                      //color
                        if ( this.Producto.precio_menor == 0) this.Error.mensaje.push("Debe ingresar un precio al por menor");      //precio_menor
                        if ( this.Producto.precio_menor < 0) this.Error.mensaje.push("El precio al por menor debe ser positivo");   //precio_menor
                        if ( this.Producto.precio_mayor == 0) this.Error.mensaje.push("Debe ingresar un precio al por mayor");      //precio_mayor
                        if ( this.Producto.precio_mayor < 0) this.Error.mensaje.push("El precio al por mayor debe ser positivo");   //precio_mayor
                        break;
                    case 2:
                        this.Error.mensaje.push("Este producto ya esta registrado");    //producto existente
                        break;
                    case 3:
                        var found = 0;
                        for (var i = 0; i < this.ListaProductoMaterial.length; i++) {
                            if ( this.ProductoMaterial.material_id == this.ListaProductoMaterial[i].material_id && this.ListaProductoMaterial[i].estado == 1 ) {
                                found = 1; break;
                            }
                        }

                        if ( found == 0 ) {
                            if ( this.ProductoMaterial.material_id == 0) this.Error.mensaje.push("Debe seleccionar un material");   //material_id
                            if ( this.ProductoMaterial.cantidad == 0) this.Error.mensaje.push("Debe ingresar una cantidad");        //cantidad
                            if ( this.ProductoMaterial.cantidad < 0) this.Error.mensaje.push("La cantidad debe positiva");          //cantidad
                        } else {
                            this.Error.mensaje.push("Este material ya esta en lista");    //material existente
                        }
                        break;
                    case 4: 
                        if ( !this.ListaProductoMaterial.length ) this.Error.mensaje.push("La lista de materiales esta vacia");   //precio_mayor
                        break;
                    case 5:
                        for (let i = 0; i < this.ListaProducto.length; i++) {
                            console.log("entro al for");
                            if ( this.Producto.id == this.ListaProducto[i].id ) {
                                console.log("encontro el producto");
                                if ( this.Producto.size == this.ListaProducto[i].size && this.Producto.color == this.ListaProducto[i].color && this.Producto.precio_menor == this.ListaProducto[i].precio_menor && this.Producto.precio_mayor == this.ListaProducto[i].precio_mayor ) {
                                    this.Error.mensaje.push("Ningun cambio realizado");    //sin cambios
                                    console.log("deberia salir el error");
                                }
                                break;
                            }
                        }
                        break;
                    case 6:
                        for (let i = 0; i < this.ListaProductoMaterial.length; i++) {
                            if ( this.ListaProductoMaterial[i].cantidad <= 0 ) {
                                this.Error.mensaje.push("No se aceptan 0s o negativos en las cantidades de los materiales"); break; //cantidades de materiales
                            } 
                        }
                        break;
                }

                if ( this.Error.mensaje.length ) this.Error.estado = 1;

                return this.Error.estado;
            },
            closeError(){
                this.Error.estado = 0;
                this.Error.numero = 0;
                this.Error.mensaje = [];
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
            }
        },
        mounted() {
            this.listar();
        }
    }
</script>

<style>
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .ec-cursor{
        cursor: pointer;
    }
    .table-scroll-20{
        overflow: auto;
        height: 20rem;
    }
    .table-scroll-15{
        overflow: auto;
        height: 15rem;
    }
    .ec-th{
        background-color: skyblue;
    }
    .w2rem{
        width: 2rem;
    }
    .w4rem{
        width: 4rem;
    }
    .w6rem{
        width: 6rem;
    }
</style>