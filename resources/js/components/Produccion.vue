<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Mis Producciones&nbsp;</span>
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="fas fa-hammer"></i>&nbsp; Nuevo
                </button>&nbsp;
                <button type="button" class="btn btn-danger">
                    <i class="far fa-file-pdf"></i>&nbsp; PDF
                </button>
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-2">
                    <div class="input-group"> 
                        <select class="custom-select text-gray-900" v-model="Busqueda.estado">
                            <option value="3">Todos</option>
                            <option value="2">Sin iniciar</option>
                            <option value="1">En Proceso</option>
                            <option value="0">Finalizado</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <label for="">Fecha de inicio</label>
                </div>
                <div class="col-md-1">
                    Dia
                    <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.dia">
                        <option value="">Día</option>
                        <option v-for="item in getDia()" :key="item" :value="item" v-text="item"></option>
                    </select>
                </div>
                <div class="col-md-2">
                    Mes
                    <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.mes">
                        <option value="">Mes</option>
                        <option v-for="item in getMes()" :key="item.valor" :value="item.valor" v-text="item.nombre"></option>
                    </select>
                </div>
                <div class="col-md-1">
                    Año
                    <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.year">
                        <option value="">Año</option>
                        <option v-for="item in getYear(2016)" :key="item" :value="item" v-text="item"></option>
                    </select>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-1" align="right">
                    <label>N° filas:</label>
                </div>
                <div class="col-md-1">
                    <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas">
                        <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                    </select>
                </div>
                <!-- <div class="col-md-1"></div> -->
                <button type="button" class="btn btn-primary" @click="listar()">
                    <i class="fa fa-search"></i>&nbsp; Buscar
                </button>
            </div>

            <!-- Listado -->
            <div v-if="ListaProduccion.length" class="table-responsive">
                <!-- Tabla -->
                <div class="ec-table overflow-auto">
                    <table class="table table-bordered table-striped table-sm text-gray-900">
                        <thead>
                            <tr class="ec-th">
                                <th>Fecha de inicio</th>
                                <th>Fecha programada</th>
                                <th>Fecha finalizada</th>
                                <th>Inversión en materiales</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="produccion in ListaProduccion" :key="produccion.id" >
                                <td v-text="produccion.fecha_inicio"></td>
                                <td v-text="produccion.fecha_programada"></td>
                                <td v-text="produccion.fecha_fin ? produccion.fecha_fin : '----------------'"></td>
                                <td v-text="produccion.total"></td>
                                <td>
                                    <div v-if="produccion.fecha_inicio > getFechaHoy()">
                                        <span class="badge badge-primary">Sin iniciar</span>
                                    </div>
                                    <div v-else-if="produccion.fecha_fin">
                                        <span class="badge badge-danger">Finalizado</span>
                                    </div>
                                    <div v-else="">
                                        <span class="badge badge-success">En proceso</span>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" @click="abrirModalEditar(produccion)" title="Editar" class="btn btn-warning btn-sm">
                                        <i class="fas fa-user-edit"></i>
                                    </button>
                                    <template v-if="produccion.estado">
                                        <button type="button" @click="desactivar(produccion)" title="Desactivar" class="btn btn-danger btn-sm">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="activar(produccion)" title="Activar" class="btn btn-success btn-sm">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    </template>
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

        <!-- Modales -->
        <div class="modal text-gray-900 " :class="{'mostrar': Modal.estado}">
            <div class="modal-dialog modal-dialog-centered animated bounceIn fast modal-xl">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="container-fluid">
                        <!-- Modal Numero 1 de AGREGAR-->
                            <div v-if="Modal.numero==1">
                                <!-- <div v-if="Error.estado" class="row d-flex justify-content-center">
                                    <div class="alert alert-danger">
                                        <button type="button" @click="Error.estado=0" class="close text-primary" data-dismiss="alert">×</button>
                                        <strong>Corregir los siguentes errores:</strong>
                                        <ul> 
                                            <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                        </ul>
                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <h5>Productos</h5>
                                        </div>
                                        <div class="row">
                                            <div class="input-group"> 
                                                <input type="search" class="form-control form-control-sm" v-model="Busqueda.texto" @keyup.enter="listar()">
                                                <button type="button" class="btn btn-sm btn-primary" @click="listar()">
                                                    <i class="fa fa-search"></i>&nbsp; Buscar
                                                </button>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row form-group ec-table overflow-auto">
                                            <table class="table table-borderless table-striped table-sm text-gray-900">
                                                <thead>
                                                    <tr class="table-danger">
                                                        <th>Agregar</th>
                                                        <th>Nombre</th>
                                                        <th>Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <button type="button" title="Editar" class="btn btn-sm btn-outline-success">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </td>
                                                        <td>Campera grande Silmar roja</td>
                                                        <td>120</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-8 ml-auto container">
                                        <div class="row">
                                            <h5>Lista de items</h5>
                                        </div>
                                        <div class="row form-group ec-table-modal overflow-auto">
                                            <table class="table tableless table-striped table-sm text-gray-900">
                                                <thead>
                                                    <tr class="table-success">
                                                        <th>Quitar</th>
                                                        <th>Nombre</th>
                                                        <th>Cant.</th>
                                                        <th>C. Unitario</th>
                                                        <th>Subtototal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <button type="button" title="Editar" class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </td>
                                                        <td>Campera grande Silmar roja</td>
                                                        <td>30</td>
                                                        <td>20</td>
                                                        <td>600</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <button type="button" title="Editar" class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </td>
                                                        <td>Mochila pequeña Cat verde</td>
                                                        <td>30</td>
                                                        <td>20</td>
                                                        <td>600</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <button type="button" title="Editar" class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </td>
                                                        <td>Mochila pequeña Cat verde</td>
                                                        <td>30</td>
                                                        <td>20</td>
                                                        <td>600</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                            </div>
                                            <div class="col-md-4">
                                                Inversión total: s/2450.60
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-4 form-inline">
                                        Fecha de inicio&nbsp;<span class="text-danger">*</span>
                                        <input type="date" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-5 form-inline">
                                        Fecha prog. finalización&nbsp;<span class="text-danger">*</span>
                                        <input type="date" class="form-control form-control-sm">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" v-if="permisoModalFooter">
                        <div class="row form-group col-md-12 d-flex justify-content-around">
                            <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cancelar</button>
                            <button type="button" @click="accionar(Modal.accion)" class="btn btn-primary" v-text="Modal.accion"></button>
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
                ListaProduccion: [],
                Produccion: {
                    id: 0,
                    fecha_inicio : '',
                    fecha_programada : '',
                    fecha_fin: '',
                    total : 0
                },
                SelectUnidad: [],
                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 3,
                    filas: 5,
                    dia: '',
                    mes: '',
                    year: '2019'
                },

                //datos de modales
                Modal: {
                    numero: 1,
                    estado: 0,
                    titulo: '',
                    accion: ''
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
                    mensaje: []
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
            // Contador: function(min, max){
            //     // let min = 1;
            //     // let max = 31;
            //     let dias = [];
            //     while(min <= max){
            //         dias.push(min);
            //         min++;
            //     }
            //     return dias;
            // },
            permisoModalFooter: function(){
                if ( this.Modal.numero == 1 ) return true;
                if ( this.Modal.numero == 2 ) return true;

                return false;
            },
            selectUnidadFiltrado: function(){
                let selectUnidadFiltrado = [];
                this.SelectUnidad.forEach(unidad => {
                    if(unidad.subtipo == this.Material.subtipo){
                        selectUnidadFiltrado.push(unidad);
                        // console.log('Ingrese al if');
                    }
                });
                return selectUnidadFiltrado;
            }
        },
        methods: {
            listar(page = 1, ordenarPor = ''){
                if ( ordenarPor == this.Navegacion.ordenarPor ) {
                    this.Navegacion.orden = (this.Navegacion.orden == 'asc' ? 'desc' : 'asc');
                } else {
                    this.Navegacion.ordenarPor = (ordenarPor != '' ? ordenarPor: this.Navegacion.ordenarPor);
                    // this.Navegacion.orden = 'asc';
                }
                this.Paginacion.currentPage = page==1?1:page;

                var url = '/produccion?page='+this.Paginacion.currentPage
                        +'&estado='+this.Busqueda.estado
                        +'&texto='+this.Busqueda.texto
                        +'&filas='+this.Busqueda.filas
                        +'&dia='+this.Busqueda.dia
                        +'&mes='+this.Busqueda.mes
                        +'&year='+this.Busqueda.year
                        +'&ordenarPor='+this.Navegacion.ordenarPor
                        +'&orden='+this.Navegacion.orden;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaProduccion = response.data.producciones.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            // agregar(){
            //     if ( this.validar() ) return;
                
            //     var me = this;
            //     axios.post('/material/agregar', {
            //         'nombre' : this.Material.nombre,
            //         'subtipo': this.Material.subtipo,
            //         'unidad' : this.Material.unidad,
            //         'costo' : this.Material.costo
            //     }).then(function(response){
            //         me.cerrarModal();
            //         me.listar();
            //         Swal.fire({
            //             position: 'top-end',
            //             toast: true,
            //             type: 'success',
            //             title: 'El material se ha AGREGADO correctamente',
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
            // editar(){
            //     if ( this.validar() ) return;

            //     var me = this;
            //     axios.put('/material/editar', {
            //         'id' : this.Material.id,
            //         'nombre' : this.Material.nombre,
            //         'subtipo' : this.Material.subtipo,
            //         'unidad' : this.Material.unidad,
            //         'costo' : this.Material.costo,
            //     }).then(function(response){
            //         me.cerrarModal();
            //         me.listar();
            //         Swal.fire({
            //             position: 'top-end',
            //             toast: true,
            //             type: 'success',
            //             title: 'El Material se ha EDITADO correctamente',
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
            // activar(material = []){
            //     this.Material.id = material['id'];
            //     this.Material.nombre = material['nombre'];

            //     Swal.fire({
            //         title: '¿Esta seguro de ACTIVAR el material "'+this.Material.nombre+'"?',
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonText: 'Aceptar',
            //         cancelButtonText: 'Cancelar',
            //         reverseButtons: true,
            //         customClass: {
            //             confirmButton: 'btn btn-success',
            //             cancelButton: 'btn btn-danger'
            //         },
            //         buttonsStyling: false
            //     }).then((result) => {
            //         if (result.value) {
            //             var me = this;
                
            //             axios.put('/material/activar', {
            //                 'id' : me.Material.id
            //             }).then(function (response) {
            //                 me.listar();
            //                 Swal.fire({
            //                     position: 'top-end',
            //                     toast: true,
            //                     type: 'success',
            //                     title: 'El material se ha ACTIVADO correctamente',
            //                     showConfirmButton: false,
            //                     timer: 4500,
            //                     animation:false,
            //                     customClass:{
            //                         popup: 'animated bounceIn fast'
            //                     }
            //                 });
            //             }).catch(function (error) {
            //                 console.log(error);
            //             });
            //         } else if ( result.dismiss === Swal.DismissReason.cancel ) {

            //         }
            //     });
            // },
            // desactivar(material = []){
            //     this.Material.id = material['id'];
            //     this.Material.nombre = material['nombre'];

            //     Swal.fire({
            //         title: '¿Esta seguro de DESACTIVAR el material "'+this.Material.nombre+'"?',
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonText: 'Aceptar',
            //         cancelButtonText: 'Cancelar',
            //         reverseButtons: true,
            //         customClass: {
            //             confirmButton: 'btn btn-success',
            //             cancelButton: 'btn btn-danger'
            //         },
            //         buttonsStyling: false
            //     }).then((result) => {
            //         if (result.value) {
            //             var me = this;
                
            //             axios.put('/material/desactivar', {
            //                 'id' : me.Material.id
            //             }).then(function (response) {
            //                 me.listar();
            //                 Swal.fire({
            //                     position: 'top-end',
            //                     toast: true,
            //                     type: 'success',
            //                     title: 'El material se ha DESACTIVADO correctamente',
            //                     showConfirmButton: false,
            //                     timer: 4500,
            //                     animation:false,
            //                     customClass:{
            //                         popup: 'animated bounceIn fast'
            //                     }
            //                 });
            //             }).catch(function (error) {
            //                 console.log(error);
            //             });
            //         } else if ( result.dismiss === Swal.DismissReason.cancel ) {

            //         }
            //     });
            // },
            abrirModalAgregar(){
                this.abrirModal(1, 'Registrar Produccion', 'Agregar');

            },
            // abrirModalEditar(data = []){
            //     this.abrirModal(2, 'Editar Material', 'Editar');
                
            //     this.Material.id = data['id'];
            //     this.Material.nombre = data['nombre'];
            //     this.Material.subtipo = data['subtipo'];
            //     this.Material.unidad = data['unidad'];
            //     this.Material.costo  = data['costo'];

            //     //Lleno los campos de mi Material Original
            //     this.MaterialOrigen.id = data['id'];
            //     this.MaterialOrigen.nombre = data['nombre'];
            //     this.MaterialOrigen.subtipo = data['subtipo'];
            //     this.MaterialOrigen.unidad = data['unidad'];
            //     this.MaterialOrigen.costo  = data['costo'];
                
            //     //Verifico si el arreglo SelectUnidad esta vacia
            //     if(!this.SelectUnidad.length) this.selectUnidad();
            // },
            abrirModal(numero, titulo, accion){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.accion = accion;
            },
            cerrarModal(){
                this.Modal.estado = 0;
                this.Modal.mensaje = [];

                this.Error.estado = 0;
                this.Error.mensaje = [];

                this.Produccion.id = 0;
                this.Produccion.fecha_fin = '';
            },
            // accionar(accion){
            //     switch( accion ){
            //         case 'Agregar': {
            //             this.agregar();
            //             break;
            //         }
            //         case 'Editar': {
            //             this.editar();
            //             break;
            //         }
            //         case 'Activar': {
            //             this.activar();
            //             break;
            //         }
            //         case 'Desactivar': {
            //             this.desactivar();
            //             break;
            //         }
            //     }
            // },
            // getTitulo(titulo){
            //     var seleccionada = 0;

            //     for (let i = 0; i < this.Headers.length; i++) {
            //         if ( titulo == this.Headers[i].titulo && this.Navegacion.ordenarPor == this.Headers[i].nombre ) {
            //             seleccionada = 1;
            //             break;
            //         }
            //     }

            //     if ( seleccionada == 1 ) {
            //         if ( this.Navegacion.orden == 'asc' ) {
            //             titulo = titulo + ' ^';
            //         } else {
            //             titulo = titulo + ' v';
            //         }
            //     }

            //     return titulo;
            // },
            // validar(){
            //     this.Error.estado = 0;
            //     this.Error.mensaje = [];

            //     //Recorrere la lista de Material
            //     if(this.Modal.numero == 1){
            //         //Modal agregar
            //         let registrado = false;
            //         for (let i = 0; i < this.ListaProduccion.length; i++) {
            //             if(this.ListaProduccion[i].nombre == this.Material.nombre) {
            //                 this.Error.mensaje.push("El material '" + this.Material.nombre + "' ya está registrado");
            //                 registrado = true;
            //                 break;
            //             }
            //         }
            //         if(!registrado){
            //             if ( !this.Material.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");
            //             if ( !this.Material.unidad ) this.Error.mensaje.push("Debe seleccionar una Unid. Medida");
            //             if ( this.Material.costo == 0 || this.Material.costo < 0) this.Error.mensaje.push("Debe ingresar un costo válido");
            //         }
            //     }else{
            //         //Modal editar
            //         if(this.Material.nombre != this.MaterialOrigen.nombre){
            //             for (let i = 0; i < this.ListaProduccion.length; i++) {
            //                 if(this.ListaProduccion[i].nombre == this.Material.nombre) {
            //                     this.Error.mensaje.push("El material '" + this.Material.nombre + "' ya está registrado");
            //                     break;
            //                 }
            //             }
            //         }else{
            //             if(this.Material.subtipo == this.MaterialOrigen.subtipo && this.Material.unidad == this.MaterialOrigen.unidad && this.Material.costo == this.MaterialOrigen.costo){
            //                 this.Error.mensaje.push("Ningun cambio registrado");
            //             }else{
            //                 if ( !this.Material.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");
            //                 if ( !this.Material.unidad ) this.Error.mensaje.push("Debe seleccionar una Unid. Medida");
            //                 if ( this.Material.costo == 0 || this.Material.costo < 0) this.Error.mensaje.push("Debe ingresar un costo válido");
            //             }
            //         }
            //     }

            //     // if ( !this.Material.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");
            //     // if ( !this.Material.unidad ) this.Error.mensaje.push("Debe seleccionar una Unid. Medida");
            //     // if ( this.Material.costo == 0 || this.Material.costo < 0) this.Error.mensaje.push("Debe ingresar un costo válido");

            //     if ( this.Error.mensaje.length ) this.Error.estado = 1;
            //     return this.Error.estado;
            // },
            // cambiarPagina(page){
            //     if ( page >= 1 && page <= this.Paginacion.lastPage) {
            //         this.listar(page);
            //     }
            // },
            // selectUnidad(){
            //     var me = this;
            //     var url = '/material/selectUnidad';

            //     axios.get(url).then(function(response){
            //         me.SelectUnidad = response.data;
            //     }).catch(function(error){
            //         console.log(error);
            //     });
            // },
            getFechaHoy(){
                let n =  new Date();
                //Año
                let y = n.getFullYear();
                //Mes
                let m = this.addCero(n.getMonth() + 1);
                //Día
                let d = this.addCero(n.getDate());
                let hoy = y + "-" + m + "-" + d;
                return hoy;
            },
            addCero(i) {
                if (i < 10) {
                    i = '0' + i;
                }
                return i;
            },
            getDia(){
                let min = 1;
                let max = 31;
                let lista = [];
                while(min <= max){
                    lista.push(this.addCero(min));
                    min++;
                }
                return lista;
            },
            getMes(){
                let nombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                let min = 1;
                let max = 12;
                let lista = [];
                while(min <= max){
                    lista.push({nombre: nombres[min-1], valor: this.addCero(min)});
                    min++;
                }
                return lista;
            },
            getYear(min){
                let n =  new Date();
                let max = n.getFullYear();
                let lista = [];
                while(min <= max){
                    lista.push(min);
                    min++;
                }
                return lista;
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
    .ec-table{
        overflow: scroll;
        height: 20rem;
    }
    .ec-table-modal{
        overflow: scroll;
        height: 21rem;
    }
    .ec-th{
        background-color: skyblue;
    }
</style>

