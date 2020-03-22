<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Materiales&nbsp;</span>
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="fas fa-hammer"></i>&nbsp; Nuevo
                </button>&nbsp;
                <button type="button" class="btn btn-danger" @click="generatePdf()">
                    <i class="far fa-file-pdf"></i>&nbsp; PDF
                </button>
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-8">
                    <div class="input-group"> 
                        <!-- <select class="col-md-3 custom-select text-gray-900" v-model="Busqueda.estado" @change="listar()">
                            <option value="2">Todos</option>
                            <option value="1">Activados</option>
                            <option value="0">Desactivados</option>
                        </select> -->
                        <input type="search" class="form-control" v-model="Busqueda.texto" placeholder="Buscar por NOMBRE" @keyup="Busqueda.texto.length >=3 || Busqueda.texto.length == 0 ? listar() : ''">
                        <button type="button" class="btn btn-primary" @click="listar()">
                            <i class="fa fa-search"></i>&nbsp; Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-1" align="right">
                    <label>N° filas:</label>
                </div>
                <div class="col-md-1">
                    <select class="custom-select custom-select-sm text-gray-900" v-model="Busqueda.filas" @change="listar()">
                        <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                    </select>
                </div>
            </div>

            <!-- Listado -->
            <div v-if="ListaMaterial.length" class="table-responsive">
                <!-- Tabla -->
                <div class="ec-table overflow-auto">
                    <table class="table table-borderless table-sm text-gray-900">
                        <thead>
                            <tr class="table-info">
                                <th>Nombre</th>
                                <th>Unid. Medida</th>
                                <th >Costo Unit.</th>
                                <!-- <th>Estado</th> -->
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="material in ListaMaterial" :key="material.id" >
                                <td v-text="material.nombre"></td>
                                <td v-text="material.unidad"></td>
                                <td v-text="material.costo"></td>
                                <!-- <td>
                                    <div v-if="material.estado">
                                        <span class="badge badge-success">Activado</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>
                                </td> -->
                                <td>
                                    <button type="button" @click="abrirModalEditar(material)" title="Editar" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-user-edit"></i>
                                    </button>
                                    <!-- <template v-if="material.estado">
                                        <button type="button" @click="desactivar(material)" title="Desactivar" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="activar(material)" title="Activar" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    </template> -->
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

        <div v-if="Modal.estado" class="modal text-gray-900 mostrar">
            <div class="modal-dialog modal-dialog-centered animated bounceIn fast">
                <div class="modal-content modal-lg">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Modal Numero 1 de AGREGAR-->
                        <div v-if="Modal.numero==1">
                            <div v-if="Error.estado" class="row d-flex justify-content-center">
                                <div class="alert alert-danger">
                                    <button type="button" @click="Error.estado=0" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Nombre&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Material.nombre" class="form-control" placeholder="ingrese el nombre" autofocus>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="des">Unid.Medida&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <select v-model="Material.subtipo" class="custom-select">
                                        <option value="" disabled>tipo</option>
                                        <option v-for="item in SelectTipoFiltrado" :key="item" :value="item" v-text="item" class="text-gray-900"></option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select v-model="Material.unidad" class="custom-select" id="cat">
                                        <option value="" disabled>subtipo</option>
                                        <option v-for="unidad in selectUnidadFiltrado" :key="unidad.id" :value="unidad.nombre" v-text="unidad.nombre" class="text-gray-900"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Costo por Unidad&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <input type="number" v-model="Material.costo" min="0" class="form-control text-right">
                                </div>
                            </div>
                        </div>
                        <!-- Modal Numero 2 de EDITAR-->
                        <div v-if="Modal.numero==2">
                            <div v-if="Error.estado" class="row d-flex justify-content-center">
                                <div class="alert alert-danger">
                                    <button type="button" @click="Error.estado=0" class="close" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Nombre&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Material.nombre" class="form-control" placeholder="ingrese el nombre">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="des">Unid.Medida&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <select v-model="Material.subtipo" class="custom-select" id="cat">
                                        <option value="" disabled>tipo</option>
                                        <option v-for="item in SelectTipoFiltrado" :key="item" :value="item" v-text="item" class="text-gray-900"></option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select v-model="Material.unidad" class="custom-select" id="cat">
                                        <option value="" disabled>subtipo</option>
                                        <option v-for="unidad in selectUnidadFiltrado" :key="unidad.id" :value="unidad.nombre" v-text="unidad.nombre" class="text-gray-900"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Costo por Unidad&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <input type="number" v-model="Material.costo" min="0" class="form-control text-right">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row form-group col-md-12 d-flex justify-content-around">
                            <button type="button" @click="accionar(Modal.accion)" class="btn btn-success" :disabled="Button.press">
                                <div v-if="!Button.press">{{Modal.accion}}</div>
                                <span v-else class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                            <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cancelar</button>
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
                ListaMaterial: [],
                Material: {
                    id: 0,
                    nombre: '',
                    subtipo: '',
                    unidad: '',
                    costo: 0,
                    estado: ''
                },
                MaterialOrigen: {
                    id: 0,
                    nombre: '',
                    subtipo: '',
                    unidad: '',
                    costo: 0,
                },
                SelectUnidad: [],
                SelectTipoFiltrado: [],
                YaIngrese: 0,
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
                    accion: ''
                },
                Button:{
                    press: false
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
                },
                Ruta: {
                    serverPhp: 'http://127.0.0.1:8000'
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
            selectUnidadFiltrado: function(){
                // console.log('Soy el computado selectUnidadFiltrado');
                let selectUnidadFiltrado = [];
                // console.log('tamaño de SelectUnidad: ' + this.SelectUnidad.length);
                if(this.SelectUnidad.length){
                    this.SelectUnidad.forEach(unidad => {
                        if(unidad.subtipo == this.Material.subtipo){
                            selectUnidadFiltrado.push(unidad);
                        }
                    });

                    if(this.Modal.numero == 1  || this.YaIngrese) {//Es moda nuevo o ya ingrese
                        this.Material.unidad = '';
                    }else{
                        this.YaIngrese = 1;
                    }
                    return selectUnidadFiltrado;
                }
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

                var url = '/material?page='+this.Paginacion.currentPage
                        +'&estado='+this.Busqueda.estado
                        +'&texto='+this.Busqueda.texto
                        +'&filas='+this.Busqueda.filas
                        +'&ordenarPor='+this.Navegacion.ordenarPor
                        +'&orden='+this.Navegacion.orden;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaMaterial = response.data.materiales.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            agregar(){
                if ( this.validar() ) return;
                
                var me = this;
                axios.post('/material/agregar', {
                    'nombre' : this.Material.nombre,
                    'subtipo': this.Material.subtipo,
                    'unidad' : this.Material.unidad,
                    'costo' : this.Material.costo
                }).then(function(response){
                    if(response.data.estado){
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'El material se ha AGREGADO correctamente',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    }else{
                        me.Error.mensaje.push("El material '"+ me.Material.nombre + "' ya se encuentra registrado");
                        me.Error.estado = 1;
                    }
                    
                }).catch(function(error){
                    console.log('soy el error' + error);
                });
            },
            editar(){
                if ( this.validar() ) return;

                var me = this;
                axios.put('/material/editar', {
                    'id' : this.Material.id,
                    'nombre' : this.Material.nombre,
                    'subtipo' : this.Material.subtipo,
                    'unidad' : this.Material.unidad,
                    'costo' : this.Material.costo,
                }).then(function(response){
                    if(response.data.estado){
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'El Material se ha EDITADO correctamente',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    }else{
                        me.Error.mensaje.push('Este material ya está registrado');
                        me.Error.estado = 1;
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            activar(material = []){
                this.Material.id = material['id'];
                this.Material.nombre = material['nombre'];

                Swal.fire({
                    title: '¿Esta seguro de ACTIVAR el material "'+this.Material.nombre+'"?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    // reverseButtons: true,
                    
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.value) {
                        var me = this;
                
                        axios.put('/material/activar', {
                            'id' : me.Material.id
                        }).then(function (response) {
                            me.listar();
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                type: 'success',
                                title: 'El material se ha ACTIVADO correctamente',
                                showConfirmButton: false,
                                timer: 4500,
                                animation:false,
                                customClass:{
                                    popup: 'animated bounceIn fast'
                                }
                            });
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else if ( result.dismiss === Swal.DismissReason.cancel ) {

                    }
                });
            },
            desactivar(material = []){
                this.Material.id = material['id'];
                this.Material.nombre = material['nombre'];

                Swal.fire({
                    title: '¿Esta seguro de DESACTIVAR el material "'+this.Material.nombre+'"?',
                    type: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    // reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.value) {
                        var me = this;
                
                        axios.put('/material/desactivar', {
                            'id' : me.Material.id
                        }).then(function (response) {
                            me.listar();
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                type: 'success',
                                title: 'El material se ha DESACTIVADO correctamente',
                                showConfirmButton: false,
                                timer: 4500,
                                animation:false,
                                customClass:{
                                    popup: 'animated bounceIn fast'
                                }
                            });
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else if ( result.dismiss === Swal.DismissReason.cancel ) {

                    }
                });
            },
            abrirModalAgregar(){
                this.abrirModal(1, 'Nuevo Material', 'Agregar');

                //Verifico si el arreglo SelectUnidad esta vacia
                if(!this.SelectUnidad.length) this.selectUnidad();
                //Verifico si el arreglo SelectTipoFiltro esta vacia
                // if(!this.SelectTipoFiltrado.length) this.selectTipoFiltrado();
                // this.selectTipoFiltrado();
            },
            abrirModalEditar(data = []){
                this.abrirModal(2, 'Editar Material', 'Editar');
                
                this.Material.id = data['id'];
                this.Material.nombre = data['nombre'];
                this.Material.subtipo = data['subtipo'];
                this.Material.unidad = data['unidad'];
                this.Material.costo  = data['costo'];

                //Lleno los campos de mi Material Original
                this.MaterialOrigen.id = data['id'];
                this.MaterialOrigen.nombre = data['nombre'];
                this.MaterialOrigen.subtipo = data['subtipo'];
                this.MaterialOrigen.unidad = data['unidad'];
                this.MaterialOrigen.costo  = data['costo'];
                
                //Verifico si el arreglo SelectUnidad esta vacia
                if(!this.SelectUnidad.length) this.selectUnidad();
                //Verifico si el arreglo SelectTipoFiltro esta vacia
                // if(!this.SelectTipoFiltrado.length) this.selectTipoFiltrado();
            },
            abrirModal(numero, titulo, accion){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.accion = accion;
            },
            cerrarModal(){
                this.Modal.numero = 0;
                this.Modal.estado = 0;
                this.Modal.mensaje = [];

                this.Error.estado = 0;
                this.Error.mensaje = [];

                this.Material.id = 0;
                this.Material.nombre = '';
                this.Material.unidad = '';
                this.Material.subtipo = '';
                this.Material.costo = 0;

                this.Button.press = false;

                this.YaIngrese = 0;
            },
            accionar(accion){
                this.Button.press = true;
                switch( accion ){
                    case 'Agregar': {
                        this.agregar();
                        break;
                    }
                    case 'Editar': {
                        this.editar();
                        break;
                    }
                    case 'Activar': {
                        this.activar();
                        break;
                    }
                    case 'Desactivar': {
                        this.desactivar();
                        break;
                    }
                }
            },
            getTitulo(titulo){
                var seleccionada = 0;

                for (let i = 0; i < this.Headers.length; i++) {
                    if ( titulo == this.Headers[i].titulo && this.Navegacion.ordenarPor == this.Headers[i].nombre ) {
                        seleccionada = 1;
                        break;
                    }
                }

                if ( seleccionada == 1 ) {
                    if ( this.Navegacion.orden == 'asc' ) {
                        titulo = titulo + ' ^';
                    } else {
                        titulo = titulo + ' v';
                    }
                }

                return titulo;
            },
            validar(){
                this.Error.estado = 0;
                this.Error.mensaje = [];

                //Recorrere la lista de Material
                if(this.Modal.numero == 1){
                    //Modal agregar
                    if ( !this.Material.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");
                    if ( !this.Material.unidad ) this.Error.mensaje.push("Debe seleccionar una Unid. Medida");
                    if ( this.Material.costo == 0 || this.Material.costo < 0) this.Error.mensaje.push("Debe ingresar un costo válido");
                }else{
                    //Modal editar
                    if(this.Material.nombre == this.MaterialOrigen.nombre && this.Material.subtipo == this.MaterialOrigen.subtipo && this.Material.unidad == this.MaterialOrigen.unidad && this.Material.costo == this.MaterialOrigen.costo){
                        this.Error.mensaje.push("Ningun cambio realizado");
                    }else{ 
                        if ( !this.Material.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");
                        if ( !this.Material.unidad ) this.Error.mensaje.push("Debe seleccionar una Unid. Medida");
                        if ( this.Material.costo == 0 || this.Material.costo < 0) this.Error.mensaje.push("Debe ingresar un costo válido");
                    }
                }

                if ( this.Error.mensaje.length ) {this.Error.estado = 1; this.Button.press = false;}
                return this.Error.estado;
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
            },
            selectUnidad(){
                var me = this;
                var url = '/data/selectUnidad';
                axios.get(url).then(function(response){
                    me.SelectUnidad = response.data;
                }).then(function(){
                    me.selectTipoFiltrado();
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            selectTipoFiltrado(){
                // let selectTipoFiltrado = [];
                console.log('Ingrese al metodo selectTipoFiltrado');
                // console.log("tamaño del SelectUnidad " + this.SelectUnidad.length);
                this.SelectUnidad.forEach(unidad => {
                    // console.log('Ingrese el foreach');
                    if(!this.SelectTipoFiltrado.includes(unidad.subtipo)){
                        // console.log('ingrese al if del metodo selectTipoFiltrado');
                        this.SelectTipoFiltrado.push(unidad.subtipo);
                    }
                });
                // return selectTipoFiltrado;
            },
            generatePdf(){
                window.open(this.Ruta.serverPhp + '/material/generatePdf','_blank');
                this.Button.press = false;
            },
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
    .ec-th{
        background-color: skyblue;
    }
</style>

