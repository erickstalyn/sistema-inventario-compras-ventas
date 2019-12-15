<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="row form-group">
                <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
                <span class="h3 mb-0 text-gray-900">Ventas&nbsp;</span>
                <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
                    <i class="fas fa-hammer"></i>&nbsp;Nuevo
                </button>&nbsp;
            </div>

            <!-- Inputs de busqueda -->
            <div class="row form-group">
                <div class="col-md-6 input-group"> 
                    <select class="custom-select text-gray-900 w4rem" v-model="Busqueda.type">
                        <option value="2">Todos</option>
                        <option value="0">Contado</option>
                        <option value="1">Credito</option>
                    </select>
                    <input type="search" class="form-control" v-model="Busqueda.text">
                    <button type="button" class="btn btn-primary" @click="listar()">
                        <i class="fa fa-search"></i>&nbsp;Buscar
                    </button>
                </div>
                <div class="col-md-3"></div>
                <label class="col-md-2 text-right">N° filas:</label>
                <select class="col-md-1 text-right custom-select custom-select-sm text-gray-900" v-model="Busqueda.rows">
                    <option v-for="item in Filas" :key="item" :value="item" v-text="item"></option>
                </select>
            </div>

            <!-- Listado -->
            <div v-if="ListaVenta.length">
                <!-- Tabla -->
                <div class="ec-table overflow-auto">
                    <table class="table table-striped table-condensed table-bordered table-sm text-gray-900">
                        <thead>
                            <tr class="table-danger">
                                <th class="text-center">Codigo</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Cliente</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Realizado</th>
                                <th class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="venta in ListaVenta" :key="venta.id" >
                                <td class="text-center" v-text="venta.codigo"></td>
                                <td>
                                    <label v-if="venta.tipo==0">Contado</label>
                                    <label v-if="venta.tipo==1">Credito</label>
                                </td>
                                <td v-text="venta.razon_social!=null?venta.razon_social:(venta.nombres!=null?(venta.nombres+' '+venta.apellidos):'---')"></td>
                                <td class="text-right" v-text="venta.total"></td>
                                <td class="text-center" v-text="venta.created_at"></td>
                                <td class="text-center">
                                    <template>
                                        <button type="button" title="VER" class="btn btn-primary btn-sm" @click="abrirModalVer(venta)">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </template>
                                    <template>
                                        <button type="button" title="EDITAR" class="btn btn-warning btn-sm" @click="abrirModalEditar(venta)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </template>
                                    <template v-if="venta.tipo==1">
                                        <button type="button"  title="PAGAR" class="btn btn-info btn-sm" @click="abrirModalPagar(venta)">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </button>
                                    </template>
                                    <template>
                                        <button type="button" title="ANULAR" class="btn btn-danger btn-sm" @click="abrirModalAnular(venta.id)">
                                            <i class="fas fa-trash-alt"></i>
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








    </main>
</template>
<script>
    export default {
        data(){
            return {
                //datos generales
                ListaVenta: [],
                Venta: {
                    id: 0,
                    codigo: '',
                    total: 0.00,
                    total_faltante: 0.00,
                    tipo: 1, // 0: Contado, 1: Credito
                    created_at : '',
                },
                ListaDetalle: [],
                Detalle: {

                },
                
                Cliente:{
                    id: 0,
                    documento: '',
                    nombres: '',
                    apellidos: '',
                    razon_social: ''
                },

                //datos de busqueda y filtracion general
                Busqueda: {
                    text: '',
                    type: 2,
                    rows: 5
                },

                //datos de modales
                Modal: {
                    numero: 0, // 1 Agregar, 2 Ver, 3 Pagar
                    estado: 0,
                    titulo: '',
                    size: '',
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
                    offset: 3
                },

                //datos de errores
                Error: {
                    estado: 0,
                    numero: 0,
                    mensaje: []
                },

                Carga: {
                    clase: ''
                },
                //DATOS PARA AGREGAR UNA PRODUCCION
                BusquedaFiltro:{
                    texto: ''
                },
                ListaProducto:[
                ],
                ListaDetalleAbasto:[
                ],
                //DATOS PARA ENVIAR UNA PRODUCCION
                SelectAlmacen: [],
                //DATOS PARA CONSULTA SUNAT Y RENIEC
                DatosServicio: {
                    documento: '',
                    tipo: 0, //1->PERSONA, 2-> EMPRESA
                    mensaje: '',
                    alert: '',
                    readonly: false
                },
                ListaPago: [],
                Pago:{
                    monto: '',
                    sumaPagos: 0.00,
                },

                //datos de Rutas
                Ruta: {
                    venta: '/venta'
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
            getDesembolso: function(){
                this.Abasto.total = 0.00;
                this.ListaDetalleAbasto.forEach( detalle => {
                    this.Abasto.total = this.Abasto.total + detalle.costo_abasto * detalle.cantidad;
                });
                return (this.Abasto.total).toFixed(2);
            },
            getSumaPagos: function(){
                let sumaPagos = 0.00;
                this.ListaPago.forEach( detalle => {
                    sumaPagos = sumaPagos + Number.parseFloat(detalle.monto);
                });
                return sumaPagos;
            }
        },
        methods: {
            listar(page = 1){
                this.Paginacion.currentPage = page;

                var me = this;
                var url = this.Ruta.venta+'?'
                        +'page='+this.Paginacion.currentPage
                        +'&type='+this.Busqueda.type
                        +'&text='+this.Busqueda.text
                        +'&rows='+this.Busqueda.rows;
                
                axios.get(url).then(function (response) {
                    me.ListaVenta = response.data.ventas.data;
                    me.Paginacion = response.data.pagination;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            agregar(){
                if ( this.validar() ) return;
                
                var me = this;
                axios.post('/abasto/agregar', {
                    //Datos del abasto
                    'id' : this.Abasto.id,
                    'total': this.Abasto.total,
                    'tipo': this.Abasto.tipo, 
                    'centro_to_id': this.Abasto.centro_to_id,
                    'pagoInicial': this.Abasto.pagoInicial,
                    'proveedor': this.DatosProveedor,
                    //Datos del detalle de abasto
                    'listaDetalleAbasto': this.ListaDetalleAbasto
                    
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: 'success',
                        title: 'El abasto se ha REGISTRADO correctamente',
                        showConfirmButton: false,
                        timer: 4500,
                        animation:false,
                        customClass:{
                            popup: 'animated bounceIn fast'
                        }
                    });
                }).catch(function(error){
                    console.log(error);
                });
            },
            abrirModalAgregar(){
                this.abrirModal(1, 'Registrar Venta', 'modal-xl', 'Agregar', 'Cancelar');

                if(!this.SelectAlmacen.length) this.selectAlmacen();
            },
            abrirModalPagar(abasto = []){
                this.Abasto.id = abasto['id'];
                this.Abasto.total = abasto['total'];
                this.abrirModal(3, 'Realizar Pago', 'Guardar', '');
                this.listarPagos(abasto['id'], abasto['total']);
            },
            anularAbasto(id){
                Swal.fire({
                    title: '¿Está seguro que desea ANULAR el abasto?',
                    type: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Si, anular',
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
                
                        axios.put('/abasto/anular', {
                            'id' : id
                        }).then(function (response) {
                            me.listar();
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                type: 'success',
                                title: 'El abasto se anuló correctamente',
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
            abrirModal(numero, titulo, accion, size){
                this.Modal.estado = 1;
                this.Modal.numero = numero;
                this.Modal.titulo = titulo;
                this.Modal.size = size;
                this.Modal.btnA = btnA;
                this.Modal.btnC = btnC;
            },
            cerrarModal(){
                this.Modal.numero = 0;
                this.Modal.estado = 0;
                this.Modal.mensaje = [];

                this.Error.estado = 0;
                this.Error.mensaje = [];

                this.DatosServicio.documento = '';
                this.DatosServicio.alert = '';
                this.DatosServicio.mensaje = '';

                this.Abasto.id = 0;
                this.Abasto.total = 0.00;
                this.Abasto.pagoInicial = '';
                this.Abasto.centro_to_id = 0;
                this.Abasto.tipo = 1;

                this.DatosServicio.tipo = 0;
                this.DatosProveedor.id = 0;
                this.DatosProveedor.documento = '';
                this.DatosProveedor.nombres = '';
                this.DatosProveedor.apellidos = '';
                this.DatosProveedor.razon_social = '';

                this.ListaPago = [];
                this.Pago.monto = '';

                this.ListaDetalleAbasto = [];
                this.BusquedaFiltro.texto = '';
            },
            accionar(){
                switch( this.Modal.numero ){
                    case 1: this.agregar(); break;
                    case 'Guardar': this.agregarPago(); break;
                }
            },
            validar(){
                this.Error.estado = 0;
                this.Error.mensaje = [];

                //Recorrere la lista de Material
                if(this.Modal.numero == 1){ //Modal agregar
                    if (this.DatosProveedor.documento == '') this.Error.mensaje.push('Debe ingresar datos del proveedor');
                    if (!this.ListaDetalleAbasto.length ) {
                        this.Error.mensaje.push("No existe ningun detalle de abasto");
                    }else if(this.Abasto.centro_to_id == 0){
                        this.Error.mensaje.push('Debe seleccionar el almacén receptor');
                    }else{
                        this.validarNegativos();
                    }

                    if(this.Abasto.tipo == 1){
                        if(this.Abasto.pagoInicial<0 || this.Abasto.pagoInicial == ''){
                            this.Error.mensaje.push('El pago inicial debe ser mayor o igual a 0')
                        }else if(this.Abasto.pagoInicial > this.Abasto.total){
                            this.Error.mensaje.push('El pago inicial no debe ser mayor al desembolso total')
                        }else if(this.Abasto.pagoInicial == this.Abasto.total){
                            this.Error.mensaje.push('El pago inicial es igual al desembolso total, se recomienda cambiarlo a una venta al contado')
                        }
                    }

                }else{ //Modal editar
                    if (this.Pago.monto == '' || this.Pago.monto <= 0 || this.Pago.monto > this.Abasto.total_faltante) this.Error.mensaje.push('Debe ingresar un monto válido');
                }
                if ( this.Error.mensaje.length ) this.Error.estado = 1;
                return this.Error.estado;
            },
            formatearFecha(fecha){
                let arrayFecha = fecha.split('-');
                let newFecha = arrayFecha[2] + '-' + arrayFecha[1] + '-' + arrayFecha[0];
                return newFecha;
            },
            listarFiltro(){
                if(this.BusquedaFiltro.texto != ''){
                    let me = this;
                    let url = '/producto/getProductoFiltrado?texto=' + this.BusquedaFiltro.texto;
                    axios.get(url).then(function(response){
                        if(response.data.productos.length == 1 && me.BusquedaFiltro.texto == response.data.productos[0].codigo){
                            me.agregarDetalle(response.data.productos[0]);
                            me.BusquedaFiltro.texto = '';
                        }else{
                            me.ListaProducto = response.data.productos;
                        }
                        let inputFiltro = document.getElementById('filtroProducto');
                        inputFiltro.focus();
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            },
            agregarDetalle(producto){
                //Verifico si el producto ya esta en la lista de detalle
                let incluido = false;
                for (let i = 0; i < this.ListaDetalleAbasto.length; i++) {
                    if(this.ListaDetalleAbasto[i].id == producto.id){
                        incluido = true;
                        this.ListaDetalleAbasto[i].cantidad ++;
                        break;
                    }
                }

                if(!incluido){
                    let elProducto = {
                        id: producto.id,
                        nombre: producto.nombre,
                        cantidad: 1,
                        costo_abasto: 0.00,
                        subtotal: 0.00
                    }
                    this.ListaDetalleAbasto.push(elProducto);
                }
            },
            quitarDetalle(indice){
                this.ListaDetalleAbasto.splice(indice,1);
            },
            consultar(){
                this.DatosServicio.alert = '';
                this.DatosServicio.mensaje = '';
                switch (this.DatosServicio.documento.length) {
                    case 0:
                        this.DatosServicio.alert = 'badge badge-warning';
                        this.DatosServicio.mensaje = 'Ingrese un DNI o RUC';
                        break;
                    case 8: case 11:
                        this.consultarDB();
                        break;
                    default:
                        // this.DatosServicio.alert = 'alert alert-danger';
                        this.DatosServicio.alert = 'badge badge-primary';
                        this.DatosServicio.mensaje = 'Documento inválido'
                        break;
                }
            },
            consultarDB(){
                var me = this;
                var url = '/persona/getPersona';

                me.DatosServicio.alert = 'badge badge-info';
                me.DatosServicio.mensaje = 'Consultado...';
                me.Carga.clase = 'spinner-border spinner-border-sm text-success';
                axios.get(url,{
                    params: {
                        'documento': me.DatosServicio.documento
                    }
                }).then(function(response){
                    if(response.data.persona.length){//Si existe la persona en la db
                        me.DatosServicio.alert = '';
                        me.DatosServicio.mensaje = '';
                        me.Carga.clase = '';

                        const persona = response.data.persona[0];
                        me.DatosProveedor.id = persona.id;
                        if(persona.razon_social){//Es una EMPRESA
                            me.DatosServicio.tipo = 2;
                            me.DatosServicio.readonly = true;
                            me.DatosProveedor.documento = me.DatosServicio.documento;
                            me.DatosProveedor.razon_social = persona.razon_social;

                            me.DatosServicio.documento = '';
                        }else{//Es una PERSONA
                            me.DatosServicio.tipo = 1;
                            me.DatosProveedor.documento = me.DatosServicio.documento;
                            me.DatosProveedor.nombres = persona.nombres;
                            me.DatosProveedor.apellidos = persona.apellidos;
                            
                            me.DatosServicio.documento = '';
                        }
                    }else{//No esxiste la persona en la db
                        me.DatosProveedor.id = 0;
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
                    url: "http://localhost:80/SunatPHP/demo.php",
                    data: "ruc="+ruc,
                    beforeSend(){
                        me.Carga.clase = 'spinner-border spinner-border-sm text-primary';
                        me.DatosServicio.alert = 'badge badge-info';
                        me.DatosServicio.mensaje = 'Consultado...';
                    },
                    success: function (data, textStatus, jqXHR) {
                        if(data.RazonSocial){
                            me.DatosServicio.documento = '';
                            me.DatosServicio.alert = '';
                            me.DatosServicio.mensaje = '';
                            me.DatosServicio.tipo = 2;
                            me.DatosServicio.readonly = false;
                            me.DatosProveedor.documento = data.RUC;
                            me.DatosProveedor.razon_social = data.RazonSocial;
                        }else{
                            me.DatosServicio.alert = 'badge badge-primary';
                            me.DatosServicio.mensaje = 'El RUC no existe';
                        }
                        me.Carga.clase = '';
                    }
                }).fail(function(){
                });
            },
            consultarDNI(){
                let me = this;
                let dni = me.DatosServicio.documento;
                $.ajax({
                    type: 'GET',
                    url: "http://localhost:80/Reniec/demo.php",
                    data: "dni="+dni,
                    beforeSend(){
                        me.Carga.clase = 'spinner-border spinner-border-sm text-primary';
                        me.DatosServicio.alert = 'badge badge-info';
                        me.DatosServicio.mensaje = 'Consultado...';
                    },
                    success: function (data, textStatus, jqXHR) {
                        let persona = JSON.parse(data);
                        if(persona.estado == true){
                            me.DatosServicio.documento = '';
                            me.DatosServicio.alert = '';
                            me.DatosServicio.mensaje = '';
                            me.DatosServicio.tipo = 1;
                            me.DatosProveedor.documento = persona.dni;
                            me.DatosProveedor.nombres = persona.nombres;
                            me.DatosProveedor.apellidos = persona.apellidos;
                        }else{
                            me.DatosServicio.alert = 'badge badge-primary';
                            me.DatosServicio.mensaje = 'El DNI no existe';
                        }
                        me.Carga.clase = '';
                    }
                }).fail(function(){
                });
            },
            validarNegativos(){
                for (let i = 0; i < this.ListaDetalleAbasto.length; i++) {
                    const detalle = this.ListaDetalleAbasto[i];
                    if(detalle.cantidad <1){
                        this.Error.mensaje.push('Las cantidades de los detalles deben ser mayores o iguales a 1');
                        break;
                    }else if(detalle.costo_abasto <= 0){
                        this.Error.mensaje.push('Los precios de los detalles debe ser mayores a 0');
                        break;
                    }
                }
            },
            listarPagos(id,total){
                let me = this;
                let url = '/abasto/getPagos';

                axios.get(url,{
                    params: {
                        'id': id
                    }
                }).then(function(response){
                    // me.Abasto.total_faltante = total;
                    me.ListaPago = response.data;
                }).catch(function(error){
                    console.log(error);
                });
            },
            agregarListaPago(){//Agrega pagos a la lista de pagos
                if ( this.validar() ) return;
                let pago = {
                    monto: Number.parseFloat(this.Pago.monto).toFixed(2),
                    created_at: this.getFechaHoraHoy(),
                    color: 'table-success',
                    estado: 1 // 1: nuevo
                }
                this.ListaPago.push(pago);
                this.Pago.monto = '';
            },
            agregarPago(){
                let me = this;
                let pagosNuevos = me.filtrarPagosNuevos();
                axios.post('/pago/agregar', {
                    'idAbasto': this.Abasto.id,
                    'listaPagos': pagosNuevos
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    Swal.fire({
                        position: 'top-end',
                        toast: true,
                        type: 'success',
                        title: 'El pago se ha REGISTRADO correctamente',
                        showConfirmButton: false,
                        timer: 4500,
                        animation:false,
                        customClass:{
                            popup: 'animated bounceIn fast'
                        }
                    });
                }).catch(function(error){
                    console.log(error);
                });
            },
            filtrarPagosNuevos(){
                let pagosNuevos = [];
                this.ListaPago.forEach(pago => {
                    if(pago.estado) pagosNuevos.push(pago);
                });
                return pagosNuevos;
            },
            cambiarPagina(page){
                if ( page >= 1 && page <= this.Paginacion.lastPage) {
                    this.listar(page);
                }
            },
            getFechaHoraHoy(){
                let n =  new Date();
                let y = n.getFullYear();
                let m = this.addCero(n.getMonth() + 1);
                let d = this.addCero(n.getDate());
                let h = n.getHours();
                let minu = n.getMinutes();
                let seg = n.getSeconds();
                let hoy =  y + '-' + m + '-' + d + ' ' + h + ':' + this.addCero(minu) + ':' + this.addCero(seg);
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
            },
            getMesActual(){
                let n =  new Date();
                let mes = this.addCero(n.getMonth() + 1);
                return mes;
            },
            getYearActual(){
                let n =  new Date();
                let year = n.getFullYear();
                return year;
            },
            //Metodos de envios
            selectAlmacen(){
                var me = this;
                var url = '/centro/selectAlmacen';

                axios.get(url).then(function(response){
                    me.SelectAlmacen = response.data;
                }).catch(function(error){
                    console.log(error);
                });
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
    .ec-table-modal{
        overflow: scroll;
        height: 21rem;
    }
    .ec-th{
        background-color: skyblue;
    }
    .w10rem{
        width: 4rem;
    }
</style>

