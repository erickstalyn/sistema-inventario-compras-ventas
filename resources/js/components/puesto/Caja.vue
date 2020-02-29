<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="col-md-12">
                <div class="col-md-12 text-center form-group">
                    <label class="h3 text-gray-900">ESTADO DE LA CAJA CHICA</label>
                </div>
                <div class="col-md-12 form-group">
                    <label class="font-weight-bold text-gray-900" v-text="'Fecha: '+get('date-now')"></label>
                </div>
            </div>

            <!-- Conceptos de ingresos y egresos -->
            <div class="col-md-12 p-0 input-group form-group">
                <div class="col-md-6 container-small form-group">
                    <div class="shadow rounded table-success" style="border: 1px solid; height: 22rem;">
                        <div class="col-md-12 pt-3 input-group form-group">
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-center">
                                <label class="h5 font-weight-bold text-gray-900">INGRESOS</label>
                            </div>
                            <div class="col-md-4">
                                <button v-if="Caja.state == 1" class="btn btn-sm btn-success text-gray-900" @click="openModal('agregar.ingreso')">
                                    <i class="fas fa-edit"></i>&nbsp;&nbsp;Agregar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12 form-group overflow-auto" style="height: 15rem;">
                            <table v-if="ListaIngreso.length" class="table table-condensed table-bordered table-sm bg-white text-gray-900">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Descripcion</th>
                                        <th class="text-center">Hora</th>
                                        <th class="text-center">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(concepto, index) in ListaIngreso" :key="concepto.id" >
                                        <td class="text-center" v-text="index+1"></td>
                                        <td v-text="concepto.descripcion"></td>
                                        <td v-text="concepto.hora" class="text-center"></td>
                                        <td v-text="Number.parseFloat(concepto.monto).toFixed(2)" class="text-right"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 v-else-if="Caja.state==1">No se han registrado ingresos</h5>
                        </div>
                        <div class="col-md-12 input-group">
                            <label class="col-md-7 h5 text-right font-weight-bold">Total de ingresos:</label>
                            <label class="col-md-2 h5 text-right font-weight-bold">S/.</label>
                            <label class="col-md-3 h5 text-right font-weight-bold" v-text="Number.parseFloat(Caja.total_ingreso).toFixed(2)"></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 container-small form-group">
                    <div class="shadow rounded table-warning" style="border: 1px solid; height: 22rem;">
                        <div class="col-md-12 pt-3 input-group form-group">
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-center">
                                <label class="h5 font-weight-bold text-gray-900">EGRESOS</label>
                            </div>
                            <div class="col-md-4">
                                <button v-if="Caja.state == 1" class="btn btn-sm btn-warning text-gray-900" @click="openModal('agregar.egreso')">
                                    <i class="fas fa-edit"></i>&nbsp;&nbsp;Agregar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12 form-group overflow-auto" style="height: 15rem;">
                            <table v-if="ListaEgreso.length" class="table table-condensed table-bordered table-sm bg-white text-gray-900">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Descripcion</th>
                                        <th class="text-center">Hora</th>
                                        <th class="text-center">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(concepto, index) in ListaEgreso" :key="concepto.id" >
                                        <td class="text-center" v-text="index+1"></td>
                                        <td v-text="concepto.descripcion"></td>
                                        <td v-text="concepto.hora" class="text-center"></td>
                                        <td v-text="Number.parseFloat(concepto.monto).toFixed(2)" class="text-right"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 v-else-if="Caja.state==1">No se han registrado egresos</h5>
                        </div>
                        <div class="col-md-12 input-group">
                            <label class="col-md-7 h5 text-right font-weight-bold">Total de egresos:</label>
                            <label class="col-md-2 h5 text-right font-weight-bold">S/.</label>
                            <label class="col-md-3 h5 text-right font-weight-bold" v-text="Number.parseFloat(Caja.total_egreso).toFixed(2)"></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 input-group form-group">
                <div class="col-md-7 input-group mr-6">
                    <div class="col-md-12 input-group">
                        <label class="col-md-7 text-right h5 text-gray-900">Efectivo inicial</label>
                        <label class="col-md-2 text-right h5 text-gray-900">S/.</label>
                        <label class="col-md-3 text-right h5 text-gray-900" v-text="Number.parseFloat(Caja.total_start).toFixed(2)"></label>
                    </div>
                    <div class="col-md-12 input-group" v-if="Caja.start != null">
                        <label class="col-md-7 text-right h5 text-gray-900">Efectivo final</label>
                        <label class="col-md-2 text-right h5 text-gray-900">S/.</label>
                        <label class="col-md-3 text-right h5 text-gray-900" v-text="Number.parseFloat(Caja.total_end).toFixed(2)"></label>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-4 ml-6 align-content-center" v-if="Caja.state == 0">
                    <button class="btn btn-lg btn-success" @click="open('caja')" :disabled="Caja.loading">
                        <div v-if="!Caja.loading" class="p-0 m-0">
                            <i class="fas fa-power-off"></i>&nbsp;&nbsp;&nbsp;Abrir caja
                        </div>
                        <div v-else class="p-0 m-0">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </div>
                    </button>
                </div>
                <div class="col-md-4 ml-6 align-content-center" v-if="Caja.state == 1">
                    <button class="btn btn-lg btn-danger" @click="close('caja')" :disabled="Caja.loading">
                        <div v-if="!Caja.loading" class="p-0 m-0">
                            <i class="fas fa-power-off"></i>&nbsp;&nbsp;&nbsp;Cerrar caja
                        </div>
                        <div v-else class="p-0 m-0">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </div>
                    </button>
                </div>
            </div>

        </div>


        <!-- Modales de Ver -->
        <div class="modal text-gray-900" :class="{'mostrar': Modal.visible}" v-if="Modal.visible">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animated bounceIn fast" :class="Modal.class.size">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.title" class="modal-title" ></h3>
                        <button type="button" @click="close('modal')" class="close">X</button>
                    </div>
                    
                    <div class="modal-body" :class="Modal.class.body_bgc">
                        <!-- Modal de AGREGAR.INGRESO-->
                        <div v-if="Modal.option=='agregar.ingreso'" class="input-group">
                            <div v-if="Error.state" class="col-md-12 d-flex justify-content-center">
                                <div class="col-md-12 alert alert-danger">
                                    <button type="button" @click="close('mistake')" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul class="m-0"> 
                                        <li v-for="error in Error.message" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-12 input-group form-group">
                                <label class="col-md-4 font-weight-bold">Descripcion</label>
                                <input type="text" class="col-md-8 text-gray-900" v-model="Concepto.descripcion">
                            </div>
                            <div class="col-md-12 input-group form-group">
                                <label class="col-md-6 font-weight-bold">Monto</label>
                                <label class="col-md-2 text-right font-weight-bold">S/.</label>
                                <input type="number" class="col-md-4 text-right text-gray-900" v-model="Concepto.monto">
                            </div>
                        </div>
                        <!-- Modal de AGREGAR.EGRESO-->
                        <div v-if="Modal.option=='agregar.egreso'" class="input-group">
                            <div v-if="Error.state" class="col-md-12 d-flex justify-content-center">
                                <div class="col-md-12 alert alert-danger">
                                    <button type="button" @click="close('mistake')" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul class="m-0"> 
                                        <li v-for="error in Error.message" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-12 input-group form-group">
                                <label class="col-md-4 font-weight-bold">Descripcion</label>
                                <input type="text" class="col-md-8 text-gray-900" v-model="Concepto.descripcion">
                            </div>
                            <div class="col-md-12 input-group form-group">
                                <label class="col-md-6 font-weight-bold">Monto</label>
                                <label class="col-md-2 text-right font-weight-bold">S/.</label>
                                <input type="number" class="col-md-4 text-right text-gray-900" v-model="Concepto.monto">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row col-md-12 d-flex justify-content-around">
                            <button type="button" v-if="Modal.btn_a!=null" @click="play()" class="btn" :class="Modal.btn_a.class" :disabled="Modal.loading">
                                <div v-if="!Modal.loading" v-text="Modal.btn_a.text"></div>
                                <div v-else><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>
                            </button>
                            <button type="button" v-if="Modal.btn_b!=null" @click="close('modal')" class="btn" :class="Modal.btn_b.class" v-text="Modal.btn_b.text"></button>
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
                Caja: {
                    id: null,
                    exist: false,
                    total_start: 0,
                    total_end: 0,
                    total_ingreso: 0,
                    total_egreso: 0,
                    state: 0,
                    start: null,
                    end: null,
                    loading: false
                },

                ListaIngreso: [],
                ListaEgreso: [],
                
                Concepto: {
                    descripcion: null,
                    monto: null
                },

                Centro: {
                    id: $('meta[name="idCentro"]').attr('content')
                },
                
                //datos de modales
                Modal: {
                    visible: false,
                    option: null,
                    title: null,
                    class: null,
                    btn_a: null,
                    btn_b: null,
                    superstep: null,
                    step: null,
                    substep: null,
                    loading: null
                },

                //datos de errores
                Error: {
                    state: null,
                    place: null,
                    message: null
                },

                //datos de Rutas
                Ruta: {
                    caja: '/caja',
                    concepto: '/concepto',
                    serverApache: 'http://127.0.0.1:80',
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
            }
        },
        methods: {
            openModal(option){
                switch (option) {
                    case 'agregar.ingreso':
                        this.prepare('modal', {
                            option: 'agregar.ingreso',
                            title: 'Registrar INGRESO de efectivo',
                            btn_a: {
                                text: 'Registrar'
                            },
                            btn_b: {
                                text: 'Cancelar'
                            },
                            class: {
                                body_bgc: 'table-success'
                            }
                        });
                        
                        this.Concepto.descripcion = '';
                        this.Concepto.monto = '';
                        
                        this.open('modal');
                        break;
                    case 'agregar.egreso':
                        this.prepare('modal', {
                            option: 'agregar.egreso',
                            title: 'Registrar EGRESO de efectivo',
                            btn_a: {
                                text: 'Registrar'
                            },
                            btn_b: {
                                text: 'Cancelar'
                            },
                            class: {
                                body_bgc: 'table-warning'
                            }
                        });
                        
                        this.Concepto.descripcion = '';
                        this.Concepto.monto = '';
                        
                        this.open('modal');
                        break;
                }
            },
            play(){
                this.Modal.loading = true;

                switch ( this.Modal.option ){
                    case 'agregar.ingreso': 
                        this.add('ingreso');
                        break;
                    case 'agregar.egreso': 
                        this.add('egreso');
                        break;
                }
            },
            validar(data = []) {
                this.Error.state = 0;
                this.Error.message = [];
                this.Error.place = 0;

                for (let i = 0; i < data.length; i++) {
                    switch ( data[i] ) {
                        case 'concepto':
                            //descripcion
                            if ( this.Concepto.descripcion == '' ) {
                                this.Error.message.push('Debe ingresar una descripcion');
                            }
                            //monto
                            if ( this.Concepto.monto == '' ) {
                                this.Error.message.push('Debe ingresar el monto');
                            } else if ( Number.parseFloat(this.Concepto.monto) <= 0 ) {
                                this.Error.message.push('El monto ingresado debe ser mayor que 0');
                            }
                            if ( this.Modal.option == 'agregar.egreso' ) {
                                if ( this.Concepto.monto != '' ) {
                                    if ( Number.parseFloat(this.Concepto.monto) > this.Caja.total_end ) {
                                        this.Error.message.push('El monto de este egreso no puede ser mayor al saldo actual de la caja');
                                    }
                                }
                            }
                            break;
                    }
                }

                if ( this.Error.message.length ) this.Error.state = 1;
                return this.Error.state;
            },
            add(option, data = '') {
                let me = this;
                let url = '';

                switch (option) {
                    case 'ingreso':
                        if ( this.validar(['concepto']) ) {
                            this.Modal.loading = false; return;
                        };

                        // console.log('Se registro el INGRESO con descripcion="'+this.Concepto.descripcion+'", monto="'+this.Concepto.monto+'"');
                        // this.close('modal');
                        // Swal.fire({
                        //     position: 'top-end',
                        //     toast: true,
                        //     type: 'success',
                        //     title: 'INGRESO registrado correctamente',
                        //     showConfirmButton: false,
                        //     timer: 4500,
                        //     animation: false,
                        //     customClass: {
                        //         popup: 'animated bounceIn fast'
                        //     }
                        // });
                        // return;

                        url = this.Ruta.concepto + '/addIngreso';

                        axios.post(url, {
                            caja_id: this.Caja.id,
                            descripcion: this.Concepto.descripcion,
                            monto: Number.parseFloat(this.Concepto.monto)
                        }).then(function (response) {
                            me.close('modal');

                            if ( response.data.state == 'transaction-success') {
                                me.get('caja_actual');
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    type: 'success',
                                    title: 'INGRESO registrado correctamente',
                                    showConfirmButton: false,
                                    timer: 4500,
                                    animation: false,
                                    customClass: {
                                        popup: 'animated bounceIn fast'
                                    }
                                });
                                return;
                            }

                            me.Modal.loading = false;
                            console.log(response.data);
                        }).catch(function (error) {
                            me.Modal.loading = false;
                            console.log(error);
                        });
                        break;
                    case 'egreso':
                        if ( this.validar(['concepto']) ) {
                            this.Modal.loading = false; return;
                        };
                        
                        // console.log('Se registro el EGRESO con descripcion="'+this.Concepto.descripcion+'", monto="'+this.Concepto.monto+'"');
                        // this.close('modal');
                        // Swal.fire({
                        //     position: 'top-end',
                        //     toast: true,
                        //     type: 'success',
                        //     title: 'EGRESO registrado correctamente',
                        //     showConfirmButton: false,
                        //     timer: 4500,
                        //     animation: false,
                        //     customClass: {
                        //         popup: 'animated bounceIn fast'
                        //     }
                        // });
                        // return;

                        url = this.Ruta.concepto + '/addEgreso';

                        axios.post(url, {
                            caja_id: this.Caja.id,
                            descripcion: this.Concepto.descripcion,
                            monto: Number.parseFloat(this.Concepto.monto)
                        }).then(function (response) {
                            me.close('modal');

                            if ( response.data.state == 'transaction-success' ) {
                                me.get('caja_actual');
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    type: 'success',
                                    title: 'EGRESO registrado correctamente',
                                    showConfirmButton: false,
                                    timer: 4500,
                                    animation: false,
                                    customClass: {
                                        popup: 'animated bounceIn fast'
                                    }
                                });
                                return;
                            }

                            me.Modal.loading = false;
                            console.log(response.data);
                        }).catch(function (error) {
                            me.Modal.loading = false;
                            console.log(error);
                        });
                        break;
                }
            },
            fix(numero, data = ''){
                this.log('on fix("'+numero+'")');

                var fixed;
                let fecha, hora, fecha_fixed, hora_fixed;

                switch (numero) {
                    case 'datetime':
                        fecha = data.split(' ')[0].split('-');
                        hora = data.split(' ')[1].split(':');
                        fecha_fixed = fecha[2]+'-'+fecha[1]+'-'+fecha[0];
                        hora_fixed = (hora[0]>12?(hora[0]-12).toString().padStart(2, '0'):hora[0])+':'+hora[1]+' '+(hora[0]>12?'pm':'am') ;
                        fixed = fecha_fixed+' '+hora_fixed;
                        break;
                    case 'date':
                        fecha = data.split(' ')[0].split('-');
                        fecha_fixed = fecha[2]+'-'+fecha[1]+'-'+fecha[0];
                        fixed = fecha_fixed;
                        break;
                    case 'time':
                        hora = data.split(' ')[1].split(':');
                        hora_fixed = (hora[0]>12?(hora[0]-12).toString().padStart(2, '0'):hora[0])+':'+hora[1]+' '+(hora[0]>12?'pm':'am') ;
                        fixed = hora_fixed;
                        break; 
                    case 'day':
                        fixed = data.split(' ')[0].split('-')[2];
                        break; 
                    case 'month':
                        fixed = data.split(' ')[0].split('-')[1];
                        break; 
                    case 'year':
                        fixed = data.split(' ')[0].split('-')[0];
                        break; 
                    case 'caja':
                        this.Caja.exist = true;
                        this.Caja.id = data.id;
                        this.Caja.total_start = data.total_start;
                        this.Caja.total_end = data.total_end;
                        this.Caja.total_ingreso = data.total_ingreso;
                        this.Caja.total_egreso = data.total_egreso;
                        this.Caja.state = data.state;
                        this.Caja.start = data.start;
                        this.Caja.end = data.end;
                        break; 
                    case 'lista_concepto':
                        this.ListaIngreso = [];
                        this.ListaEgreso = [];

                        data.forEach(concepto => {
                            let c = {
                                descripcion: concepto.descripcion,
                                monto: Number.parseFloat(concepto.monto),
                                hora: this.fix('time', concepto.created_at)
                            }
                            if ( concepto.type == 0 ) {
                                this.ListaEgreso.push(c);
                            } else if ( concepto.type == 1 ) {
                                this.ListaIngreso.push(c);
                            } else {
                                this.error();
                            }
                        });
                        break;
                    default:
                        fixed = '';
                        break;
                }

                return fixed;
            },
            get(option, data = ''){
                this.log('on get("'+option+'")');

                var me = this;
                var url = '';
                var got = '';

                switch ( option ) {
                    case 'date-now': {
                            let n =  new Date();
                            let y = n.getFullYear();
                            let m = (n.getMonth()+1).toString().padStart(2, 0);
                            let d = n.getDate().toString().padStart(2, 0);
                            got =  d + '-' + m + '-' + y;
                            break;
                        }
                    case 'datetime-now': {
                            let n =  new Date();
                            let y = n.getFullYear();
                            let m = (n.getMonth()+1).toString().padStart(2, 0);
                            let d = n.getDate().toString().padStart(2, 0);
                            let h = n.getHours();
                            let minu = n.getMinutes().toString().padStart(2, 0);
                            let seg = n.getSeconds().toString().padStart(2, 0);
                            got =  y + '-' + m + '-' + d + ' ' + h + ':' + minu + ':' + seg;
                            break;
                        }
                    case 'caja_actual':
                        url = this.Ruta.caja+'/get';
                        this.Caja.loading = true;

                        axios.get(url, {
                            params: {
                                'centro_id': this.Centro.id,
                                'date': this.get('date-now')
                            }
                        }).then(function (response) {
                            if ( response.data.state == 'transaction-success' ){
                                me.fix('caja', response.data.caja);
                                me.fix('lista_concepto', response.data.caja.get_conceptos);
                                me.Caja.loading = false;
                                return;
                            }

                            me.Caja.exist = false;
                            me.Caja.state = 0;
                            me.Caja.id = null;
                            me.Caja.total_start = 0;
                            me.Caja.total_end = 0;
                            me.Caja.total_ingreso = 0;
                            me.Caja.total_egreso = 0;
                            me.Caja.start = null;
                            me.Caja.end = null;
                            me.Caja.loading = false;
                        }).catch(function (error) {
                            me.Caja.loading = false;
                            console.log(error);
                        });
                        break;
                }
                
                return got;
            },
            open(option){
                switch (option) {
                    case 'modal':
                        this.Modal.visible = true;
                        break;
                    case 'caja':
                        this.Caja.loading = true;
                        let me = this;
                        let url = this.Ruta.caja+'/open';
                        
                        axios.put(url, {
                            caja_id: this.Caja.id
                        }).then(function (response) {
                            if ( response.data.state == 'transaction-success' ) {
                                let caja = response.data.caja;

                                me.Caja.id = caja.id;
                                me.Caja.state = caja.state;
                                me.Caja.total_end = caja.total_end;
                                me.Caja.start = caja.start;
                                me.Caja.end = caja.end;
                                me.Caja.loading = false;
                                return;
                            }

                            me.Caja.loading = false;
                            console.log(response.data);
                        }).catch(function (error) {
                            me.Caja.loading = false;
                            console.log(error);
                        });
                        break;
                    default:
                        this.error();
                        break;
                }
            },
            close(option){
                switch (option) {
                    case 'mistake':
                        this.Error.state = 0;
                        this.Error.message = [];
                        this.Error.place = 0;
                        break;
                    case 'caja':
                        this.Caja.loading = true;
                        let me = this;
                        let url = this.Ruta.caja + '/close';

                        axios.put(url, {
                            caja_id: this.Caja.id
                        }).then(function (response) {
                            if ( response.data.state == 'transaction-success' ) {
                                let caja = response.data.caja;

                                me.Caja.id = caja.id;
                                me.Caja.end = caja.end;
                                me.Caja.state = caja.state;
                                me.Caja.loading = false;
                                return;
                            }
                            
                            me.Caja.loading = false;
                            console.log(response.data);
                        }).catch(function (error) {
                            me.Caja.loading = false;
                            console.log(error);
                        });
                        break;
                    case 'modal':
                        this.Modal.visible = false;
                        this.Modal.superstep = null;
                        this.Modal.step = null;
                        this.Modal.substep = null;
                        this.Modal.option = null;
                        this.Modal.title = null;
                        this.Modal.class = null;
                        this.Modal.btn_a = null;
                        this.Modal.btn_b = null;
                        this.Modal.loading = null;
                        
                        this.Error.place = null;
                        this.Error.state = null;
                        this.Error.message = null;

                        this.Concepto.descripcion = null;
                        this.Concepto.monto = null;
                        break;
                }
            },
            prepare(option, data = {}){
                switch (option) {
                    case 'modal':
                        this.Error.place = 0;
                        this.Error.state = 0;
                        this.Error.message = [];
                        
                        this.Modal.option = data.option;
                        this.Modal.btn_a = data.btn_a==null?null:{
                            text: data.btn_a.text,
                            class: data.btn_a.class==undefined?'btn-success':data.btn_a.class
                        };
                        this.Modal.btn_b = data.btn_b==null?null:{
                            text: data.btn_b.text,
                            class: data.btn_b.class==undefined?'btn-secondary':data.btn_b.class
                        };
                        this.Modal.title = data.title;
                        this.Modal.class = {
                            size: data.class.size==undefined?'':data.class.size,
                            body_bgc: data.class.body_bgc==undefined?'':data.class.body_bgc
                        };
                        this.Modal.loading = false;

                        this.Modal.superstep = data.superstep==undefined?0:data.superstep;
                        this.Modal.step = data.step==undefined?0:data.step;
                        this.Modal.substep = data.substep==undefined?0:data.substep;
                        break;
                    default:
                        this.error();
                        break;
                }
            },
            log(message = ''){
                var data = {
                };
                // if ( message != '' ) console.log(message);
                // console.log(data);
            },
            error(){
                console.log('surgio un supererror');
            }
        },
        mounted() {
            this.get('caja_actual');
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
    .w4rem{
        width: 4rem !important;
    }
</style>

