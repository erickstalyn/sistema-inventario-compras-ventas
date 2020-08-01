<template>
    <main>

        <!-- Interfaz Principal -->
        <div class="container-small">
            
            <!-- Encabezado principal -->
            <div class="col-md-12 input-group">
                <div class="col-md-12 text-center form-group">
                    <label class="h3 text-gray-900">ESTADO DE LA CAJA CHICA</label>
                </div>
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold text-gray-900" v-text="'Fecha: '+get('date-now')"></label>
                </div>
                <div class="col-md-6 form-group d-flex justify-content-end">
                    <button class="btn btn-primary" @click="openModal('search')">
                        <i class="fas fa-eye"></i>&nbsp;&nbsp;Buscar cajas
                    </button>
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
        <div class="modal text-gray-900 show-modal" v-if="Modal.visible">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animated bounceIn fast" :class="Modal.class">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.title" class="modal-title" ></h3>
                        <button type="button" @click="close('modal')" class="close">X</button>
                    </div>
                    
                    <div class="modal-body" :class="Modal.body">
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
                        <!-- Modal de BUSCAR-->
                        <div v-if="Modal.option=='search'">
                            <div class="col-md-12 container-small"> <!-- Barra de busqueda -->
                                <div class="shadow rounded input-group pt-3 form-group" style="border: 1px solid;">
                                    <div class="col-md-2 input-group form-group">
                                        <label class="font-weight-bold">BUSCAR</label>
                                    </div>
                                    <div class="col-md-8 input-group form-group d-flex justify-content-center">
                                        <label class="col-md-4 mt-2 mb-0 font-weight-bold">Seleccione la fecha:</label>
                                        <input type="date" v-model="TheDate.date" :min="TheDate.min" :max="TheDate.max" class="col-md-5 form-control">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <button class="btn btn-primary btn-sm" :disabled="Modal.loading" @click="play()">
                                            <div v-if="!Modal.loading"  class="p-0 m-0">
                                                <label class="mt-1 mb-1">Buscar Caja</label>
                                            </div>
                                            <div v-else class="p-0 m-0">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="FoundBox.id==0" class="col-md-12 pt-3" style="height: 26rem;">
                                <label class="h5 text-primary">Indique la fecha de la caja que desea ver</label>
                            </div>
                            <div v-else-if="FoundBox.id!=null" class="col-md-12 p-0 m-0 input-group" style="height: 26rem;"> <!-- Si la caja encontrada es correcta entonces -->
                                <div class="col-md-6 container-small"> <!-- Lista de ingresos -->
                                    <div class="shadow rounded input-group form-group table-success" style="border: 1px solid; height: 20rem;">
                                        <div class="col-md-12 pt-3 form-group">
                                            <label class="h5 font-weight-bold text-gray-900">Lista de ingresos</label>
                                        </div>
                                        <div class="col-md-12 form-group overflow-auto" style="height: 13rem;">
                                            <table v-if="FoundBox.ListIngress.length" class="table table-condensed table-bordered table-sm bg-white text-gray-900">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">Hora</th>
                                                        <th class="text-center">Descripcion</th>
                                                        <th class="text-center">Monto</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(concepto, index) in FoundBox.ListIngress" :key="index">
                                                        <td class="text-center" v-text="index+1"></td>
                                                        <td v-text="fix('time', concepto.created_at)" class="text-center"></td>
                                                        <td v-text="concepto.description"></td>
                                                        <td v-text="Number.parseFloat(concepto.amount).toFixed(2)" class="text-right"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <h5 v-else>No se han registrado ingresos</h5>
                                        </div>
                                        <div class="col-md-12 input-group">
                                            <label class="col-md-7 h5 text-right font-weight-bold">Total de ingresos:</label>
                                            <label class="col-md-2 h5 text-right font-weight-bold">S/.</label>
                                            <label class="col-md-3 h5 text-right font-weight-bold" v-text="Number.parseFloat(FoundBox.total_ingress).toFixed(2)"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 container-small"> <!-- Lista de egresos -->
                                    <div class="shadow rounded input-group form-group table-warning" style="border: 1px solid; height: 20rem;">
                                        <div class="col-md-12 pt-3 form-group">
                                            <label class="h5 font-weight-bold text-gray-900">Lista de egresos</label>
                                        </div>
                                        <div class="col-md-12 form-group overflow-auto" style="height: 13rem;">
                                            <table v-if="FoundBox.ListEgress.length" class="table table-condensed table-bordered table-sm bg-white text-gray-900">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">Hora</th>
                                                        <th class="text-center">Descripcion</th>
                                                        <th class="text-center">Monto</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(concepto, index) in FoundBox.ListEgress" :key="index">
                                                        <td class="text-center" v-text="index+1"></td>
                                                        <td v-text="fix('time', concepto.created_at)" class="text-center"></td>
                                                        <td v-text="concepto.description"></td>
                                                        <td v-text="Number.parseFloat(concepto.amount).toFixed(2)" class="text-right"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <h5 v-else>No se han registrado egresos</h5>
                                        </div>
                                        <div class="col-md-12 input-group">
                                            <label class="col-md-7 h5 text-right font-weight-bold">Total de egresos:</label>
                                            <label class="col-md-2 h5 text-right font-weight-bold">S/.</label>
                                            <label class="col-md-3 h5 text-right font-weight-bold" v-text="Number.parseFloat(FoundBox.total_egress).toFixed(2)"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 container-small"> <!-- Datos de totales -->
                                    <div class="shadow rounded input-group pt-3" style="border: 1px solid;">
                                        <div class="col-md-6 input-group d-flex align-content-center">
                                            <div class="col-md-6">
                                                <label class="h5 font-weight-bold">Fecha:&nbsp;&nbsp;<span class="h5 text-primary" v-text="fix('date', FoundBox.date)"></span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Apertura:&nbsp;&nbsp;<span class="font-weight-normal text-primary" v-text="fix('time', FoundBox.start_at)"></span></label>
                                                <label class="font-weight-bold">Cierre:&nbsp;&nbsp;<span class="font-weight-normal text-primary" v-text="fix('time', FoundBox.finish_at)"></span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-10 input-group">
                                                <label class="col-md-7 text-right h5 text-gray-900">Efectivo inicial</label>
                                                <label class="col-md-2 text-right h5 text-gray-900">S/.</label>
                                                <label class="col-md-3 text-right h5 text-gray-900" v-text="Number.parseFloat(FoundBox.total_start).toFixed(2)"></label>
                                            </div>
                                            <div class="col-md-10 input-group">
                                                <label class="col-md-7 text-right h5 text-gray-900">Efectivo final</label>
                                                <label class="col-md-2 text-right h5 text-gray-900">S/.</label>
                                                <label class="col-md-3 text-right h5 text-gray-900" v-text="Number.parseFloat(FoundBox.total_finish).toFixed(2)"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="FoundBox.id==null" class="col-md-12 pt-3" style="height: 26rem;">
                                <label class="h5 text-danger">No se ha encontrado una caja con la fecha indicada</label>
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
import { join } from 'path';
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

                FoundBox: {
                    id: null,
                    total_start: null,
                    total_finish: null,
                    total_ingress: null,
                    total_egress: null,
                    ListIngress: [],
                    ListEgress: []
                },

                TheDate: {
                    date: null,
                    min: null,
                    max: null
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
                    head: null,
                    body: null,
                    foot: null,
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
                            body: {
                                'table-success': true
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
                            body: {
                                'table-warning': true
                            }
                        });
                        
                        this.Concepto.descripcion = '';
                        this.Concepto.monto = '';
                        
                        this.open('modal');
                        break;
                    case 'search':
                        this.prepare('modal', {
                            option: 'search',
                            title: 'Busqueda de las cajas de los 2 ultimos meses',
                            btn_b: {
                                text: 'Cerrar'
                            },
                            class: {
                                'modal-xl': true
                            }
                        });

                        this.FoundBox.id = 0;
                        this.FoundBox.total_start = null;
                        this.FoundBox.total_finish = null;
                        this.FoundBox.total_ingress = null;
                        this.FoundBox.total_egress = null;
                        this.FoundBox.start_at = null;
                        this.FoundBox.finish_at = null;

                        this.SelectDay = [];
                        this.SelectMonthYear = [];

                        this.select('date');

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
                    case 'search': 
                        this.search('box');
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
            fix(option, data = ''){
                this.log('on fix("'+option+'")');

                var fixed;
                let fecha, hora, fecha_fixed, hora_fixed;

                switch (option) {
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
                    case 'month.name':
                        switch (data) {
                            case 1: fixed = 'Enero'; break;
                            case 2: fixed = 'Febrero'; break;
                            case 3: fixed = 'Marzo'; break;
                            case 4: fixed = 'Abril'; break;
                            case 5: fixed = 'Mayo'; break;
                            case 6: fixed = 'Junio'; break;
                            case 7: fixed = 'Julio'; break;
                            case 8: fixed = 'Agosto'; break;
                            case 9: fixed = 'Septiembre'; break;
                            case 10: fixed = 'Octubre'; break;
                            case 11: fixed = 'Noviembre'; break;
                            case 12: fixed = 'Diciembre'; break;
                            default: fixed = 'ERROR'; break;
                        }
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
                    case 'found.box':
                        this.FoundBox.id = data.id;
                        this.FoundBox.total_ingress = data.total_ingress;
                        this.FoundBox.total_egress = data.total_egress;
                        this.FoundBox.total_start = data.total_start;
                        this.FoundBox.total_finish = data.total_finish;
                        this.FoundBox.date = data.start_at.substring(0, 10);
                        this.FoundBox.start_at = data.start_at;
                        this.FoundBox.finish_at = data.finish_at;
                        break;
                    case 'found.concepts':
                        let listIngress = [], listEgress = [];

                        data.forEach(concept => {
                            let item = {
                                created_at: concept.created_at,
                                description: concept.descripcion,
                                amount: concept.monto
                            }

                            if ( concept.type == 1 ) {
                                listIngress.push(item);
                            } else if ( concept.type == 0 ) {
                                listEgress.push(item);
                            } else {
                                this.error();
                            }
                        });

                        this.FoundBox.ListIngress = listIngress;
                        this.FoundBox.ListEgress = listEgress;
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
            search(option){
                switch (option) {
                    case 'box':
                        let me = this;
                        let url = this.Ruta.caja + '/search';
                        
                        axios.get(url, {
                            params: {
                                date: this.TheDate.date,
                                center_id: this.Centro.id
                            }
                        }).then(function (response) {
                            if ( response.data.state == 'transaction-success') {
                                me.fix('found.box', response.data.box);
                                me.fix('found.concepts', response.data.box.get_conceptos);
                                return;
                            }

                            me.FoundBox.id = null;
                            me.FoundBox.total_start = null;
                            me.FoundBox.total_finish = null;
                            me.FoundBox.total_ingress = null;
                            me.FoundBox.total_egress = null;
                            me.FoundBox.start_at = null;
                            me.FoundBox.finish_at = null;

                            console.log(response.data);
                        }).catch(function (error) {
                            console.log(error);
                        }).then(function (response) {
                            console.log(response);
                            me.Modal.loading = false;
                        });
                        break;
                    default:
                        this.error();
                        break;
                }
            },
            select(option){
                switch (option) {
                    case 'date':
                        let now = new Date();
                        
                        let min = new Date();
                        min.setMonth(now.getMonth()-1);
                        min.setDate(1);

                        let max = new Date();
                        max.setDate(now.getDate()-1);

                        this.TheDate.min = min.getFullYear()+'-'+(min.getMonth()+1).toString().padStart(2, '0')+'-'+(min.getDate()).toString().padStart(2, '0');
                        this.TheDate.max = max.getFullYear()+'-'+(max.getMonth()+1).toString().padStart(2, '0')+'-'+(max.getDate()).toString().padStart(2, '0');
                        this.TheDate.date = this.TheDate.max;
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
                        this.Modal.title = data.title;
                        
                        this.Modal.btn_a = data.btn_a==undefined?null:{
                            text: data.btn_a.text,
                            class: data.btn_a.class==undefined?'btn-success':data.btn_a.class
                        };
                        this.Modal.btn_b = data.btn_b==undefined?null:{
                            text: data.btn_b.text,
                            class: data.btn_b.class==undefined?'btn-secondary':data.btn_b.class
                        };
                        
                        this.Modal.class = data.class==undefined?'':data.class;
                        this.Modal.head = data.head==undefined?'':data.head;
                        this.Modal.body = data.body==undefined?'':data.body;
                        this.Modal.foot = data.foot==undefined?'':data.foot;
                        
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

