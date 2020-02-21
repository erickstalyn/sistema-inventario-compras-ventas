<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Clientes&nbsp;</span>
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
                        <select class="col-md-3 custom-select text-gray-900" v-model="Busqueda.estado" @change="listar()">
                            <option value="2">Todos</option>
                            <option value="1">Activados</option>
                            <option value="0">Desactivados</option>
                        </select>
                        <input type="search" class="form-control" v-model="Busqueda.texto" placeholder="Buscar por NOMBRE" @keyup="listar()">
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
            <div v-if="ListaCliente.length" class="table-responsive">
                <!-- Tabla -->
                <div class="ec-table overflow-auto">
                    <table class="table table-borderless table-sm text-gray-900">
                        <thead>
                            <tr class="table-info">
                                <th>Nombre</th>
                                <th>DNI/RUC</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cliente in ListaCliente" :key="cliente.id" >
                                <td v-text="cliente.razon_social? cliente.razon_social: cliente.nombres+' '+cliente.apellidos"></td>
                                <td v-text="cliente.dni? cliente.dni:cliente.ruc"></td>
                                <td v-text="cliente.direccion?cliente.direccion:'---'"></td>
                                <td v-text="cliente.telefono?cliente.telefono:'---'"></td>
                                <td v-text="cliente.email?cliente.email:'---'"></td>
                                <td>
                                    <div v-if="cliente.estado">
                                        <span class="badge badge-success">Activado</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" @click="abrirModalEditar(cliente)" title="Editar" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-user-edit"></i>
                                    </button>
                                    <template v-if="cliente.estado">
                                        <button type="button" @click="desactivar(material)" title="Desactivar" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="activar(material)" title="Activar" class="btn btn-outline-success btn-sm">
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

        <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
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
                                <label class="col-md-2 font-weight-bold" for="nom">DNI/RUC</label>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" v-model="DatosServicio.documento" class="form-control" autofocus placeholder="Consultar..." @keyup.enter="consultar()" :readonly="DatosServicio.readonly">
                                        <button class="btn btn-primary" title="CONSULTAR" @click="consultar()"><i class="fas fa-sync-alt"></i></button>
                                        <!-- <h5>
                                            <span role="status" :class="Carga.clase"></span>&nbsp;
                                            <span v-text="DatosServicio.mensaje" :class="DatosServicio.alert"></span>
                                        </h5> -->
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <p style="font-size: 18px">
                                        <span role="status" :class="Carga.clase"></span>&nbsp;
                                        <span v-text="DatosServicio.mensaje" :class="DatosServicio.alert"></span>
                                    </p>
                                </div>
                            </div>
                            <div v-if="Cliente.tipo == 'P'">
                                <div class="row form-group">
                                    <label class="col-md-3 font-weight-bold" for="nom">DNI&nbsp;<span class="text-danger">*</span></label>
                                    <div class="col-md-4">
                                            <input type="text" v-model="Cliente.dni" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3 font-weight-bold" for="nom">Nombres&nbsp;<span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="Cliente.nombres" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3 font-weight-bold" for="nom">Apellidos&nbsp;<span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="Cliente.apellidos" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div v-if="Cliente.tipo == 'E'">
                                <div class="row form-group">
                                    <label class="col-md-3 font-weight-bold" for="nom">RUC&nbsp;<span class="text-danger">*</span></label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="Cliente.ruc" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3 font-weight-bold" for="nom">Razón social&nbsp;<span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="Cliente.razon_social" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Teléfono</label>
                                <div class="col-md-4">
                                        <input type="text" v-model="Cliente.telefono" class="form-control">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Cliente.direccion" class="form-control">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Email</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="Cliente.email" class="form-control">
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
                                    <input type="text" v-model="Cliente.nombre" class="form-control" placeholder="ingrese el nombre">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="des">Unid.Medida&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <select v-model="Cliente.subtipo" class="custom-select" id="cat">
                                        <option value="" disabled>Tipo</option>
                                        <option v-for="item in SelectTipoFiltrado" :key="item" :value="item" v-text="item"></option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select v-model="Cliente.unidad" class="custom-select" id="cat">
                                        <option value="" disabled>Subtipo</option>
                                        <option v-for="unidad in selectUnidadFiltrado" :key="unidad.id" :value="unidad.nombre" v-text="unidad.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-3 font-weight-bold" for="nom">Costo por Unidad&nbsp;<span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <input type="number" v-model="Cliente.costo" class="form-control" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" v-if="permisoModalFooter">
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
                ListaCliente: [],
                Cliente: {
                    id: 0,
                    nombre: '',
                    documento: '',
                    direccion: '',
                    telefono: '',
                    emai: '',
                    tipo: 'P',
                    estado: 2
                },
                //datos de busqueda y filtracion
                Busqueda: {
                    texto: '',
                    estado: 2,
                    filas: 5
                },

                //datos de modales
                Modal: {
                    numero: 0, // 1: Agregar, 2: Editar
                    estado: 0,
                    titulo: '',
                    accion: ''
                },
                Button:{
                    press: false
                },
                Carga: {
                    clase: ''
                },
                //DATOS PARA CONSULTA SUNAT Y RENIEC
                DatosServicio: {
                    documento: '',
                    mensaje: '',
                    alert: '',
                    readonly: false
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
                    persona: '/persona',
                    serverApache: 'http://localhost:80',
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
            permisoModalFooter: function(){
                if ( this.Modal.numero == 1 ) return true;
                if ( this.Modal.numero == 2 ) return true;

                return false;
            },
        },
        methods: {
            listar(page = 1){
                this.Paginacion.currentPage = page;
                var url = this.Ruta.persona + '?page='+this.Paginacion.currentPage +'&funcion=1'
                        +'&estado='+this.Busqueda.estado
                        +'&texto='+this.Busqueda.texto
                        +'&filas='+this.Busqueda.filas;
                
                var me = this;
                axios.get(url).then(function (response) {
                    me.ListaCliente = response.data.personas.data;
                    me.Paginacion = response.data.paginacion;
                }).catch(function (error) {
                    console.log(error)
                });
            },
            agregar(){
                if ( this.validar() ) return;
                
                var me = this;
                axios.post('/material/agregar', {
                    'nombre' : this.Cliente.nombre,
                    'subtipo': this.Cliente.subtipo,
                    'unidad' : this.Cliente.unidad,
                    'costo' : this.Cliente.costo
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
                        me.Error.mensaje.push("El material '"+ me.Cliente.nombre + "' ya se encuentra registrado");
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
                    'id' : this.Cliente.id,
                    'nombre' : this.Cliente.nombre,
                    'subtipo' : this.Cliente.subtipo,
                    'unidad' : this.Cliente.unidad,
                    'costo' : this.Cliente.costo,
                }).then(function(response){
                    if(response.data.estado){
                        me.cerrarModal();
                        me.listar();
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'El Cliente se ha EDITADO correctamente',
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
                this.Cliente.id = material['id'];
                this.Cliente.nombre = material['nombre'];

                Swal.fire({
                    title: '¿Esta seguro de ACTIVAR el material "'+this.Cliente.nombre+'"?',
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
                            'id' : me.Cliente.id
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
                this.Cliente.id = material['id'];
                this.Cliente.nombre = material['nombre'];

                Swal.fire({
                    title: '¿Esta seguro de DESACTIVAR el material "'+this.Cliente.nombre+'"?',
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
                            'id' : me.Cliente.id
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
                this.abrirModal(1, 'Nuevo Cliente', 'Agregar');
            },
            abrirModalEditar(data = []){
                this.abrirModal(2, 'Editar Cliente', 'Editar');
                
                this.Cliente.id = data['id'];
                this.Cliente.nombre = data['nombre'];
                this.Cliente.subtipo = data['subtipo'];
                this.Cliente.unidad = data['unidad'];
                this.Cliente.costo  = data['costo'];

                //Lleno los campos de mi Cliente Original
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

                this.Cliente.id = 0;
                this.Cliente.nombre = '';
                this.Cliente.unidad = '';
                this.Cliente.subtipo = '';
                this.Cliente.costo = 0;

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
            consultar(){
                this.DatosServicio.alert = '';
                this.DatosServicio.mensaje = '';
                this.DatosServicio.readonly = true;
                switch (this.DatosServicio.documento.length) {
                    case 0:
                        this.DatosServicio.alert = 'badge badge-warning';
                        this.DatosServicio.mensaje = 'Ingrese un DNI o RUC';
                        this.DatosServicio.readonly = false;
                        break;
                    case 8: case 11:
                        this.consultarDB();
                        break;
                    default:
                        // this.DatosServicio.alert = 'alert alert-danger';
                        this.DatosServicio.alert = 'badge badge-primary';
                        this.DatosServicio.mensaje = 'Documento inválido'
                        this.DatosServicio.readonly = false;
                        break;
                }
            },
            consultarDB(){
                var me = this;
                var url = this.Ruta.persona + '/getPersona';

                me.DatosServicio.alert = 'badge badge-info';
                me.DatosServicio.mensaje = 'Consultado...';
                me.Carga.clase = 'spinner-border spinner-border-sm text-success';
                axios.get(url,{
                    params: {
                        'documento': me.DatosServicio.documento
                    }
                }).then(function(response){
                    if(response.data.persona.length){//Si existe la persona en la db
                        me.DatosServicio.alert = 'badge badge-danger';
                        me.DatosServicio.mensaje = 'Documento ya registrado';
                        me.Carga.clase = '';
                        me.DatosServicio.readonly = false;
                    }else{//No esxiste la persona en la db
                        if(me.DatosServicio.documento.length == 8){
                            me.consultarDNI();
                        }else{
                            me.consultarRUC();
                        }
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            consultarRUC(){
                let me = this;
                let ruc = me.DatosServicio.documento;
                $.ajax({
                    type: 'GET',
                    url: me.Ruta.serverApache + '/SunatPHP/demo.php',
                    data: 'ruc=' + ruc,
                    beforeSend(){
                        me.Carga.clase = 'spinner-border spinner-border-sm text-primary';
                        me.DatosServicio.alert = 'badge badge-info';
                        me.DatosServicio.mensaje = 'Consultado...';
                    },
                    success: function (data, textStatus, jqXHR) {
                        let empresa = JSON.parse(data);
                        if( empresa.RazonSocial ){
                            me.DatosServicio.documento = '';
                            me.DatosServicio.alert = '';
                            me.DatosServicio.mensaje = '';

                            me.Cliente.tipo = 'E';
                            me.Cliente.ruc = empresa.RUC;
                            me.Cliente.razon_social = empresa.RazonSocial;
                        } else {
                            me.DatosServicio.alert = 'badge badge-primary';
                            me.DatosServicio.mensaje = 'El RUC no existe';
                        }
                        me.DatosServicio.readonly = false;
                        me.Carga.clase = '';
                    }
                }).fail(function(){
                });
            },
            consultarDNI(){
                let me = this;
                let dni = me.DatosServicio.documento;
                $.ajax({
                    type: 'POST',
                    url: me.Ruta.serverApache + '/Reniec/consulta_reniec.php',
                    data: 'dni=' + dni,
                    beforeSend(){
                        me.Carga.clase = 'spinner-border spinner-border-sm text-primary';
                        me.DatosServicio.alert = 'badge badge-info';
                        me.DatosServicio.mensaje = 'Consultado...';
                    },
                    success: function (data, textStatus, jqXHR) {
                        // console.log(JSON.parse(data));
                        try {
                            let persona = JSON.parse(data);
                            if(persona[2] != null ){
                                me.DatosServicio.documento = '';
                                me.DatosServicio.alert = '';
                                me.DatosServicio.mensaje = '';
                                me.Cliente.dni = persona[0];
                                me.Cliente.nombres = persona[1];
                                me.Cliente.apellidos = persona[2] + ' ' + persona[3];
                                me.Cliente.tipo = 'P';
                            }else{
                                me.DatosServicio.alert = 'badge badge-primary';
                                me.DatosServicio.mensaje = 'El DNI no existe';
                            }
                            me.Carga.clase = '';
                        } catch (e) {
                            me.DatosServicio.alert = 'badge badge-primary';
                            me.DatosServicio.mensaje = 'Vuelva a intentarlo =D';
                        }
                        me.DatosServicio.readonly = false;
                    }
                }).fail(function(){
                });
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

                //Recorrere la lista de Cliente
                if(this.Modal.numero == 1){
                    //Modal agregar
                    if ( !this.Cliente.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");
                    if ( !this.Cliente.unidad ) this.Error.mensaje.push("Debe seleccionar una Unid. Medida");
                    if ( this.Cliente.costo == 0 || this.Cliente.costo < 0) this.Error.mensaje.push("Debe ingresar un costo válido");
                }else{
                    //Modal editar
                    if(this.Cliente.nombre == this.MaterialOrigen.nombre && this.Cliente.subtipo == this.MaterialOrigen.subtipo && this.Cliente.unidad == this.MaterialOrigen.unidad && this.Cliente.costo == this.MaterialOrigen.costo){
                        this.Error.mensaje.push("Ningun cambio realizado");
                    }else{ 
                        if ( !this.Cliente.nombre ) this.Error.mensaje.push("Debe ingresar un nombre");
                        if ( !this.Cliente.unidad ) this.Error.mensaje.push("Debe seleccionar una Unid. Medida");
                        if ( this.Cliente.costo == 0 || this.Cliente.costo < 0) this.Error.mensaje.push("Debe ingresar un costo válido");
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

