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
                                <button v-if="Caja.state == 1" class="btn btn-sm btn-success text-gray-900">
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
                        <div class="col-md-12 input-group" v-if="Caja.exist==true">
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
                                <button v-if="Caja.state == 1" class="btn btn-sm btn-warning text-gray-900">
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
                        <div class="col-md-12 input-group" v-if="Caja.exist==true">
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
                    <div class="col-md-12 input-group" v-if="Caja.exist == true || Caja.state == 1">
                        <label class="col-md-7 text-right h5 text-gray-900">Efectivo final</label>
                        <label class="col-md-2 text-right h5 text-gray-900">S/.</label>
                        <label class="col-md-3 text-right h5 text-gray-900" v-text="Number.parseFloat(Caja.total_end).toFixed(2)"></label>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-4 ml-6 align-content-center" v-if="Caja.state == 0">
                    <button class="btn btn-lg btn-success">
                        <i class="fas fa-power-off"></i>&nbsp;&nbsp;&nbsp;Abrir caja
                    </button>
                </div>
                <div class="col-md-4 ml-6 align-content-center" v-if="Caja.state == 1">
                    <button class="btn btn-lg btn-danger">
                        <i class="fas fa-power-off"></i>&nbsp;&nbsp;&nbsp;Cerrar caja
                    </button>
                </div>
            </div>

        </div>


        <!-- Modales de Ver -->
        <div class="modal text-gray-900" :class="{'mostrar': Modal.state}">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animated bounceIn fast" :class="Modal.size">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Modal de VER-->
                        <div v-if="Modal.option=='ver'" class="input-group">
                            <div v-if="Error.estado" class="col-md-12 d-flex justify-content-center">
                                <div class="col-md-6 alert alert-danger">
                                    <button type="button" @click="close(0)" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul class="m-0"> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="container-small col-md-12 input-group form-group">
                                <button type="button" class="col-md-6 btn btn-warning btn" @click="Step.number=0" style="border: 1px solid; border-color: black;">
                                    <label class="font-weight-bold m-0" style="color: black;">DETALLES DE VENTA</label>
                                </button>
                                <button type="button" class="col-md-6 btn btn-success" @click="Step.number=1" style="border: 1px solid; border-color: black;">
                                    <label class="font-weight-bold m-0" style="color: black;">CLIENTE Y PAGOS</label>
                                </button>
                            </div>
                            <div class="col-md-12 p-0 m-0 input-group" v-if="Step.number==0" style="height: 26rem;">
                                <div class="container-small col-md-4">
                                    <div v-if="Modal.step==0" class="shadow rounded bg-warning" style="border: 1px solid; height: 26rem;">
                                        <div class="container-small col-md-12 input-group form-group p-0">
                                            <button type="button" class="col-md-6 btn btn-warning btn" @click="Modal.step=0" style="border: 1px solid; border-color: black;">
                                                <label class="font-weight-bold m-0" style="color: black;">Productos</label>
                                            </button>
                                            <button type="button" class="col-md-6 btn btn-info" @click="Modal.step=1" style="border: 1px solid; border-color: black;">
                                                <label class="font-weight-bold m-0" style="color: black;">Adquiridos</label>
                                            </button>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="h5 mb-0 font-weight-bold">LISTA DE PRODUCTOS</label>
                                        </div>
                                        <div class="col-md-12 input-group form-group">
                                            <input type="search" class="form-control form-control-sm" v-model="Service.text" @keyup="list('detalle_producto')" id="filtroProducto" placeholder="Producto - marca - modelo - tamaño - color">
                                            <!-- <button type="button" class="btn btn-sm btn-primary" @click="list('detalle_producto')">
                                                <i class="fa fa-search"></i>&nbsp; Buscar
                                            </button> -->
                                        </div>
                                        <div v-if="ListaProducto.length" class="col-md-12 overflow-auto" style="height: 16rem;">
                                            <table class="table table-bordered table-striped table-sm text-gray-900 p-0 m-0 bg-white">
                                                <thead>
                                                    <tr class="table-danger">
                                                        <th class="text-center" style="width: 10%;">Agregar</th>
                                                        <th class="text-center">Nombre</th>
                                                        <th class="text-center">Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="producto in ListaProducto" :key="producto.id">
                                                        <td class="text-center">
                                                            <button type="button" v-if="producto.detalle.substock>0" title="AGREGAR" class="btn btn-circle btn-sm btn-outline-success" @click="add('detalle_venta', producto)">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </td>
                                                        <td v-text="producto.nombre"></td>
                                                        <td v-text="producto.detalle.substock" class="text-right"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div v-else class="col-md-12">
                                            <label class="h5 text-danger">No se han encontrado resultados</label>
                                        </div>
                                    </div>
                                    <div v-if="Modal.step==1" class="shadow rounded bg-info" style="border: 1px solid; height: 26rem;">
                                        <div class="container-small col-md-12 input-group form-group p-0">
                                            <button type="button" class="col-md-6 btn btn-warning btn" @click="Modal.step=0" style="border: 1px solid; border-color: black;">
                                                <label class="font-weight-bold m-0" style="color: black;">Productos</label>
                                            </button>
                                            <button type="button" class="col-md-6 btn btn-info" @click="Modal.step=1" style="border: 1px solid; border-color: black;">
                                                <label class="font-weight-bold m-0" style="color: black;">Adquiridos</label>
                                            </button>
                                        </div>
                                        <div class="col-md-12 input-group form-group">
                                            <label class="h5 font-weight-bold">PRODUCTO NUEVO</label>
                                        </div>
                                        <div class="col-md-12 input-group form-group">
                                            <label class="col-md-4 font-weight-bold">Proveedor</label>
                                            <input type="text" class="col-md-8 text-gray-900" v-model="Proveedor.nombres">
                                        </div>
                                        <div class="col-md-12 input-group form-group">
                                            <label class="col-md-4 font-weight-bold">Nombre</label>
                                            <input type="text" class="col-md-8 text-gray-900" v-model="DetalleVenta.nombre_producto">
                                        </div>
                                        <div class="col-md-12 input-group form-group">
                                            <label class="col-md-6 font-weight-bold">Precio de venta</label>
                                            <label class="col-md-2 text-right font-weight-bold">S/.</label>
                                            <input type="number" class="col-md-4 text-right text-gray-900" v-model="DetalleVenta.precio" @keyup="update('form_detalle_venta.subtotal')" @click="update('form_detalle_venta.subtotal')">
                                        </div>
                                        <div class="col-md-12 input-group form-group">
                                            <label class="col-md-7 font-weight-bold">Cantidad de items</label>
                                            <input type="number" class="col-md-5 text-right text-gray-900" v-model="DetalleVenta.cantidad"  @keyup="update('form_detalle_venta.subtotal')" @click="update('form_detalle_venta.subtotal')">
                                        </div>
                                        <div class="col-md-12 input-group form-group">
                                            <label class="col-md-7 font-weight-bold">Subtotal de venta</label>
                                            <label class="col-md-5 text-right font-weight-bold" v-text="'S/. '+Number.parseFloat(DetalleVenta.subtotal).toFixed(2)"></label>
                                        </div>
                                        <div class="col-md-12 d-flex justify-content-center">
                                            <button type="button" class="btn btn-sm btn-primary btn-icon-split" @click="add('detalle_venta_especial')">
                                                <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                                                <span class="text font-weight-bold">Agregar</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-small col-md-8">
                                    <div class="shadow rounded pt-2 bg-warning" style="border: 1px solid; height: 26rem;">
                                        <div class="col-md-12 form-group input-group">
                                            <div class="col-md-7 p-0">
                                                <label class="p-0 h5 mb-0 font-weight-bold">LISTA DE ITEMS</label>
                                            </div>
                                            <div class="col-md-5 input-group">
                                                <label class="col-md-6 font-weight-bold p-0">Tipo de precio&nbsp;<span class="text-danger">*</span></label>
                                                <select class="col-md-6 custom-select custom-select-sm text-gray-900" v-model="Venta.tipo_precio" @click="update('venta.tipo_precio')">
                                                    <option value="1">Al por menor</option>
                                                    <option value="2">Al por mayor</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div v-if="ListaDetalle.length" class="col-md-12 overflow-auto" style="height: 19rem;">
                                            <table class="table table-bordered table-striped table-sm text-gray-900 bg-white">
                                                <thead>
                                                    <tr class="table-success">
                                                        <th class="text-center">Quitar</th>
                                                        <th class="text-center">Nombre</th>
                                                        <th class="text-center" style="width: 5rem;">Cantidad</th>
                                                        <th class="text-center">Precio</th>
                                                        <th class="text-center">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(detalle, indice) in ListaDetalle" :key="indice">
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-circle btn-outline-danger btn-sm" title="QUITAR" @click="remove('detalle_venta', indice)">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </td>
                                                        <td v-text="detalle.nombre_producto"></td>
                                                        <td v-if="detalle.substock != null">
                                                            <input type="number" v-model="detalle.cantidad" class="form-control form-control-sm text-right" min="1" :max="detalle.substock" @click="update('detalle_venta.cantidad')" @keyup="update('detalle_venta.cantidad')">
                                                        </td>
                                                        <td v-else class="text-right" v-text="detalle.cantidad"></td>
                                                        <td class="text-right" v-text="Number.parseFloat(Venta.tipo_precio=='1'?detalle.precio_menor:detalle.precio_mayor).toFixed(2)"></td>
                                                        <td class="text-right" v-text="Number.parseFloat(detalle.subtotal).toFixed(2)">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div v-else class="col-md-12" style="height: 19rem;">
                                            <label class="h5 text-danger ">Sin detalles de venta</label>
                                        </div>
                                        <div class="col-md-12 input-group mt-2">
                                            <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6 input-group">
                                                <label class="col-md-6 text-right font-weight-bold h5 p-0">Monto de venta:</label>
                                                <label class="col-md-6 text-right text-info h5 p-0" v-text="'S/. '+Number.parseFloat(Venta.total_venta).toFixed(2)"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 p-0 m-0 input-group" v-if="Step.number==1" style="height: 26rem;">
                                <div class="container-small col-md-12 form-group" style="height: 7rem;">
                                    <div class="shadow rounded pt-2 bg-success input-group" style="border: 1px solid; height: 7rem;">
                                        <div class="col-md-2 form-group">
                                            <label class="h5 font-weight-bold">CLIENTE</label>
                                        </div>
                                        <div class="col-md-10 input-group form-group">
                                            <label class="col-md-1 p-0 font-weight-bold">RUC/DNI&nbsp;<span class="text-danger" v-if="Venta.tipo_pago=='2'">*</span></label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm" v-model="Service.document" @keyup.enter="consultar()" maxlength="11">
                                                    <button type="button" class="btn btn-sm btn-primary" @click="consultar()">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>
                                                    <span role="status" :class="Service.loadclass"></span>&nbsp;
                                                    <span v-text="Service.msm" :class="Service.msmclass"></span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-md-11 form-group input-group" v-if="Cliente.tipo=='P'">
                                            <div class="col-md-4 input-group">
                                                <label class="col-md-3">DNI</label>
                                                <input type="text" class="col-md-6 form-control form-control-sm" readonly v-model="Cliente.dni">
                                            </div>
                                            <div class="col-md-4 input-group">
                                                <label class="col-md-4 p-0">Nombres</label>
                                                <input type="text" class="col-md-8 form-control form-control-sm" readonly v-model="Cliente.nombres">
                                            </div>
                                            <div class="col-md-4 input-group">
                                                <label class="col-md-4 p-0">Apellidos</label>
                                                <input type="text" class="col-md-8 form-control form-control-sm" readonly v-model="Cliente.apellidos">
                                            </div>
                                        </div>
                                        <div class="col-md-11 form-group input-group" v-else-if="Cliente.tipo=='E'">
                                            <div class="col-md-4 input-group">
                                                <label class="col-md-3">RUC</label>
                                                <input type="text" class="col-md-6 form-control form-control-sm" readonly v-model="Cliente.ruc">
                                            </div>
                                            <div class="col-md-8 input-group">
                                                <label class="col-md-3">Razón social</label>&nbsp;
                                                <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.razon_social">
                                            </div>
                                        </div>
                                        <div class="col-md-1 form-group d-flex justify-content-center" v-if="Cliente.removable==true">
                                            <button type="button" class="btn btn-circle btn-sm btn-outline-danger" @click="remove('cliente')" title="ELIMINAR"> 
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-small col-md-4" style="height: 18rem;">
                                    <div class="shadow rounded pt-3 bg-success" style="border: 1px solid; height: 18rem;">
                                        <div class="col-md-12 form-group">
                                            <label class="font-weight-bold h5">TIPO DE VENTA</label>
                                        </div>
                                        <div class="col-md-12 input-group form-group">
                                            <label class="col-md-6 font-weight-bold">Tipo de pago&nbsp;<span class="text-danger">*</span></label>
                                            <select class="col-md-6 custom-select custom-select-sm" v-model="Venta.tipo_pago" @click="update('venta.tipo_pago')">
                                                <option class="text-gray-900" value="1">Contado</option>
                                                <option class="text-gray-900" value="2">Credito</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 input-group m-0 p-0" v-if="Venta.tipo_pago=='2'">
                                            <div class="col-md-12 input-group form-group">
                                                <label class="col-md-6 font-weight-bold">Tipo de entrega&nbsp;<span class="text-danger">*</span></label>
                                                <select class="col-md-6 custom-select custom-select-sm" v-model="Venta.tipo_entrega">
                                                    <option class="text-gray-900" value="1">Pre pago</option>
                                                    <option class="text-gray-900" value="2">Post pago</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12 input-group form-group"> 
                                                <label class="col-md-6 font-weight-bold" for="pago_inicial">Pago inicial&nbsp;<span class="text-danger">*</span></label>
                                                <input type="number" class="col-md-6 form-control form-control-sm text-right" v-model="Pago.monto" min="0" :max="Venta.total" id="pago_inicial" @click="update('pago.monto')" @keyup="update('pago.monto')">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-12 input-group" style="border: 1px solid; border-color: white;">
                                                <div class="col-md-6 p-0 mb-1 input-group d-flex justify-content-center">
                                                    <label class="col-md-12 p-0 text-center font-weight-bold" for="total_recibido">EFECTIVO</label>
                                                    <input type="number" class="col-md-9 form-control form-control-sm text-right" v-model="Venta.total_recibido" min="0" id="total_recibido" @click="update('venta.total_recibido')" @keyup="update('venta.total_recibido')">
                                                </div>
                                                <div class="col-md-6 p-0 input-group d-flex justify-content-center">
                                                    <label class="col-md-12 p-0 text-center font-weight-bold">CAMBIO</label>
                                                    <label class="col-md-12 h5 text-center text-white" v-text="'S/. '+Number.parseFloat(Venta.total_vuelto).toFixed(2)"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-small col-md-4">
                                    <div class="shadow rounded pt-3 bg-success" style="border: 1px solid; height: 18rem;" v-if="Vale.usado.id!=null">
                                        <div class="col-md-12 form-group">
                                            <label class="font-weight-bold h5">DESCUENTO</label>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <label class="font-weight-bold">-- Vale de descuento --</label>
                                        </div>
                                        <div class="col-md-12 input-group">
                                            <label class="col-md-6 font-weight-bold">Monto</label>
                                            <label class="col-md-6 text-white" v-text="'S/. '+Vale.usado.monto"></label>
                                        </div>
                                        <div class="col-md-12 input-group">
                                            <label class="col-md-6 font-weight-bold">Fecha</label>
                                            <label class="col-md-6 text-white" v-text="fix('fecha', Vale.usado.created_at)"></label>
                                        </div>
                                        <div class="col-md-12 input-group">
                                            <label class="col-md-6 font-weight-bold">Hora</label>
                                            <label class="col-md-6 text-white" v-text="fix('hora', Vale.usado.created_at)"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-small col-md-4">
                                    <div class="shadow rounded pt-3 bg-success" style="border: 1px solid; height: 18rem;">
                                        <div class="col-md-12 form-group">
                                            <label class="font-weight-bold h5">TOTALES</label>
                                        </div>
                                        <div class="col-md-12 input-group">
                                            <label class="col-md-7 font-weight-bold">Monto de venta</label>
                                            <label class="col-md-1 text-white text-right p-0">S/.</label>
                                            <label class="col-md-4 text-white text-right" v-text="Number.parseFloat(Venta.total_venta).toFixed(2)"></label>
                                        </div>
                                        <div class="col-md-12 input-group" v-if="Vale.usado.id!=null">
                                            <label class="col-md-7 font-weight-bold">Monto de descuento</label>
                                            <label class="col-md-1 text-white text-right p-0">S/.</label>
                                            <label class="col-md-4 text-white text-right" v-text="'- '+Number.parseFloat(Venta.total_descuento).toFixed(2)"></label>
                                        </div>
                                        <div class="col-md-10 bg-white m-4"><hr></div>
                                        <div class="col-md-12 input-group">
                                            <label class="col-md-6 font-weight-bold h5">Total</label>
                                            <label class="col-md-5 text-white text-right h5" v-text="'S/. '+Number.parseFloat(Venta.total).toFixed(2)"></label>
                                        </div>
                                        <div class="col-md-12 input-group" v-if="Venta.total_faltante!=null && Venta.tipo_pago=='2'">
                                            <label class="col-md-6 font-weight-bold h5">Faltante</label>
                                            <label class="col-md-5 text-white text-right h5" v-text="'S/. '+(Number.parseFloat(Venta.total_faltante)-Number.parseFloat(Pago.monto!=''?(Pago.monto>=0&&Pago.monto<=Venta.total_faltante?Pago.monto:0):0)).toFixed(2)"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row col-md-12 d-flex justify-content-around">
                            <button type="button" v-if="Modal.btnA" @click="accionar()" :class="[Modal.numero == 2 ? 'btn btn-danger' : 'btn btn-success']" :disabled="Button.press">
                                <div v-if="!Button.press">
                                    <div v-if="Modal.numero == 2">
                                        <i class="far fa-file-pdf"></i>&nbsp; {{Modal.btnA}}
                                    </div>
                                    <div v-else>{{Modal.btnA}}</div>
                                </div>
                                <span v-else class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                            <button type="button" v-if="Modal.btnC" @click="cerrarModal()" class="btn btn-secondary" v-text="Modal.btnC"></button>
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
                    end: null
                },

                ListaIngreso: [],
                ListaEgreso: [],
                
                Centro: {
                    id: $('meta[name="idCentro"]').attr('content')
                },

                //datos de modales
                Modal: {
                    visible: false,
                    option: null,
                    title: null,
                    size: null,
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
                    venta: '/venta',
                    abasto: '/abasto',
                    persona: '/persona',
                    producto: '/producto',
                    detalle_producto: '/detalle_producto',
                    detalle_venta: '/detalle_venta',
                    pago: '/pago',
                    vale: '/vale',
                    caja: '/caja',
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
            abrirModalAbastecer(){
                this.prepare('modal', {
                    option: 'abastecer',
                    titulo: 'COMPRA DE PRODUCTOS EXTERNOS',
                    size: 'modal-xl',
                    btnA: 'Confirmar Cambios',
                    btnC: 'Cerrar'
                });
                
                this.ListaAbasto = [];
                this.ListaDetalleAbasto = [];

                this.Abasto.id = null;
                this.Abasto.total = null;

                this.list('abasto');

                this.open('modal');
            },
            abrirModal(numero, titulo, size, btnA, btnC){
                this.Step.number = 0;

                this.Error.numero = 0;
                this.Error.estado = 0;
                this.Error.mensaje = [];
                
                this.Modal.titulo = titulo;
                this.Modal.size = size;
                this.Modal.btnA = btnA;
                this.Modal.btnC = btnC;
                this.Modal.numero = numero;
                this.Modal.step = 0;
                this.Modal.estado = 1;
            },
            cerrarModal(){
                this.Modal.estado = 0;
                this.Modal.step = null;
                this.Modal.numero = null;
                this.Modal.titulo = null;
                this.Modal.size = null;
                this.Modal.btnA = null;
                this.Modal.btnC = null;
                
                this.Step.number = null;

                this.Error.numero = null;
                this.Error.estado = null;
                this.Error.mensaje = null;

                this.Service.document = '';
                this.Service.msm = '';
                this.Service.msmclass = '';
                this.Service.loadclass = '';
                this.Service.text = '';

                this.Venta.id = null;
                this.Venta.total_venta = null;
                this.Venta.total_venta_start = null;
                this.Venta.total_descuento = null;
                this.Venta.total = null;
                this.Venta.total_start = null;
                this.Venta.total_faltante = null;
                this.Venta.total_faltante_start = null;
                this.Venta.centro_id = null;
                this.Venta.tipo = null;
                this.Venta.tipo_pago = null;
                this.Venta.tipo_entrega = null;
                this.Venta.tipo_precio = null;
                this.Venta.created_at = null;

                this.Cliente.id = null;
                this.Cliente.documento = null;
                this.Cliente.dni = null;
                this.Cliente.nombres = null;
                this.Cliente.apellidos = null;
                this.Cliente.ruc = null;
                this.Cliente.razon_social = null;
                this.Cliente.tipo = null;
                this.Cliente.searchable = null;
                this.Cliente.removable = null;

                this.ListaPago = null;
                this.Pago.monto = null;

                this.Vale.usado.id = null;
                this.Vale.usado.monto = null;
                this.Vale.usado.created_at = null;

                this.Vale.generado.id = null;
                this.Vale.generado.monto = null;
                this.Vale.generado.created_at = null;

                this.Button.press = false;

                this.ListaProducto = null;

                this.ListaDetalle = null;
            },
            play(){
                this.Modal.loading = true;

                switch ( this.Modal.option ){
                    case 'agregar': this.agregar(); break;
                }
            },
            validar(data = []) {
                this.Error.estado = 0;
                this.Error.mensaje = [];
                this.Error.place = [];

                for (let i = 0; i < data.length; i++) {
                    switch ( data[i] ) {
                        case 0: // cliente
                            if ( this.Modal.numero == 1 ) {
                                if ( this.Venta.tipo_pago == 2 && this.Cliente.dni == null && this.Cliente.ruc == null ) {
                                    this.Error.mensaje.push('Debe ingresar datos del cliente');
                                }
                            }
                            if ( this.Modal.numero == 3 ) {
                                if ( this.Cliente.dni == null && this.Cliente.ruc == null ) {
                                    if ( this.Venta.tipo_pago == 2 || (this.Venta.tipo_pago == 1 && this.Vale.generado.monto != null ) ) {
                                        this.Error.mensaje.push('Debe ingresar datos del cliente');
                                    }
                                }
                            }
                            break;
                        case 1: // pago
                            if ( this.Modal.numero == 1 ) {
                                if( this.Venta.tipo_pago == '2' ){
                                    if ( this.Pago.monto == '' ) {
                                        this.Error.mensaje.push('Debe ingresar un pago inicial')
                                    } else {
                                        if ( Number.parseFloat(this.Pago.monto) < 0 ){
                                            this.Error.mensaje.push('El pago inicial debe ser mayor o igual a 0')
                                        } else if ( Number.parseFloat(this.Pago.monto) > Number.parseFloat(this.Venta.total) ){
                                            this.Error.mensaje.push('El pago inicial no debe ser mayor al monto total')
                                        } else if ( Number.parseFloat(this.Pago.monto) == Number.parseFloat(this.Venta.total) ){
                                            this.Error.mensaje.push('El pago inicial es igual al monto total, se recomienda cambiarlo a una venta al contado')
                                        }
                                    }
                                }
                            }
                            break;
                        case 2: // cantidades de detalle de venta
                            if ( !this.ListaDetalle.length ) {
                                this.Error.mensaje.push("No existe ningun detalle de venta");
                            } else {
                                let found_a = false, found_b = false;
                                for (let i = 0; i < this.ListaDetalle.length; i++) {
                                    if ( (Number.parseFloat(this.ListaDetalle[i].cantidad) < 0 || (Number.parseFloat(this.ListaDetalle[i].cantidad) == 0 && this.Modal.numero == 1)) && !found_a ){
                                        this.Error.mensaje.push('Las cantidades no pueden 0 o negativos'); found_a = true;
                                    }
                                    if ( this.ListaDetalle[i].substock != null ) {
                                        if ( this.ListaDetalle[i].cantidad > this.ListaDetalle[i].substock && !found_b ){
                                            this.Error.mensaje.push('Las cantidades no pueden superar al stock'); found_b = true;
                                        }
                                    }
                                    if ( found_a && found_b ) break;
                                }
                            }
                            break;
                        case 3: // cantidad_fallido de detalle de venta
                            let found_a = false, found_b = false, found_c = false, found_d = false, found_e = false, found_f = false, found_g = false;
                            let total = 0;
                            for (let i = 0; i < this.ListaDetalle.length; i++) {
                                if ( this.ListaDetalle[i].cantidad_fallido == '' && !found_a) {
                                    this.Error.mensaje.push('Los fallidos no pueden estar vacios'); found_a = true;
                                } else {
                                    if ( Number.parseInt(this.ListaDetalle[i].cantidad_fallido) < 0 && !found_b ){
                                        this.Error.mensaje.push('Los fallidos no pueden ser negativos'); found_b = true;
                                    } 
                                    if ( Number.parseInt(this.ListaDetalle[i].cantidad_fallido) > this.ListaDetalle[i].cantidad_start && !found_c ){
                                        this.Error.mensaje.push('Los fallidos no pueden superar la cantidad inicial'); found_c = true;
                                    } 
                                }
                                if ( this.ListaDetalle[i].devuelto == '' && !found_d) {
                                    this.Error.mensaje.push('Los devueltos no pueden estar vacios'); found_d = true;
                                } else {
                                    if ( Number.parseInt(this.ListaDetalle[i].devuelto) < 0 && !found_e ){
                                        this.Error.mensaje.push('Los devueltos no pueden ser negativos'); found_e = true;
                                    } 
                                    if ( Number.parseInt(this.ListaDetalle[i].devuelto) > this.ListaDetalle[i].cantidad_start && !found_f ){
                                        this.Error.mensaje.push('Los devueltos no pueden superar la cantidad inicial'); found_f = true;
                                    } 
                                }
                                if ( this.ListaDetalle[i].cantidad_fallido != '' && this.ListaDetalle[i].devuelto != '' ) {
                                    let suma = Number.parseInt(this.ListaDetalle[i].cantidad_fallido) + Number.parseInt(this.ListaDetalle[i].devuelto);
                                    if ( suma > this.ListaDetalle[i].cantidad_start && !found_g) {
                                        this.Error.mensaje.push('La suma de devueltos y fallidos nos puede ser mayor a la cantidad inicial'); found_g = true;
                                    }
                                    total += suma;
                                }
                                if ( found_a && found_b && found_c && found_d && found_e && found_f && found_g ) break;
                            }
                            if ( total == 0 ) {
                                this.Error.mensaje.push('Debe indicar almenos un devuelto o fallido en los detalle de venta');
                            }
                            break;
                        case 4: // monto de pago
                            if ( this.Pago.monto == '' || Number.parseFloat(this.Pago.monto) <= 0 || Number.parseFloat(this.Pago.monto) > Number.parseFloat(this.Venta.total_faltante) ) {
                                this.Error.mensaje.push('Debe ingresar un monto válido');
                            }
                            break;
                        case 5: // cambio de valores
                            let change1 = false, change2 = false, change3 = false;
                            //Verificar cliente
                            for (let i = 0; i < this.ListaVenta.length; i++) {
                                if ( this.ListaVenta[i].id == this.Venta.id ) {
                                    if ( this.ListaVenta[i].cliente_id != this.Cliente.id ) change1 = true; 
                                    break;
                                }
                            }
                            //verificar tipos
                            if ( (this.Venta.tipo_pago+this.Venta.tipo_precio) != this.Venta.tipo ) change2 = true;
                            //verificar detalles
                            for (let i = 0; i < this.ListaDetalle.length; i++) {
                                if ( this.ListaDetalle[i].cantidad_fallido != this.ListaDetalle[i].cantidad_fallido_start || this.ListaDetalle[i].cantidad != this.ListaDetalle[i].cantidad_inicial ) {
                                    change3 = true; break;
                                }
                            }
                            if ( !change1 && !change2 && !change3 ) this.Error.mensaje.push('Ningun cambio realizado');
                            break;
                        case 'detalle_venta_especial':
                            //unitarias
                            if ( this.Proveedor.nombres == '' ) this.Error.mensaje.push('Debe ingresar el nombre del proveedor');
                            if ( this.DetalleVenta.nombre_producto == '' ) {
                                this.Error.mensaje.push('Debe ingresar el nombre del producto');
                            } else {
                                for (let i = 0; i < this.ListaDetalle.length; i++) {
                                    if ( this.ListaDetalle[i].nombre_producto == this.DetalleVenta.nombre_producto ) {
                                        this.Error.mensaje.push('El nombre de producto ingresado ya existe en los detalles de venta');
                                    }
                                }
                            }
                            if ( this.DetalleVenta.precio == '' ){
                                this.Error.mensaje.push('Debe ingresar el precio al que vendera el producto');
                            } else {
                                if ( Number.parseFloat(this.DetalleVenta.precio) <= 0) this.Error.mensaje.push('El precio de venta no puede ser 0 o negativo');
                            }
                            if ( this.DetalleVenta.cantidad == '' ) {
                                this.Error.mensaje.push('Debe ingresar la cantidad de items');
                            } else {
                                if ( Number.parseFloat(this.DetalleVenta.cantidad) <= 0) this.Error.mensaje.push('La cantidad no puede ser 0 o negativo');
                            }
                            break;
                        case 'detalle_abasto.costo_abasto':
                            let found1 = false, found2 = false;
                            for (let i = 0; i < this.ListaDetalleAbasto.length; i++) {
                                if ( this.ListaDetalleAbasto[i].costo_abasto == '' && !found1 ) {
                                    this.Error.mensaje.push('Debe ingresar el costo de todos los items');
                                    found1 = true;
                                } else {
                                    if ( Number.parseFloat(this.ListaDetalleAbasto[i].costo_abasto) <= 0 && !found2 ) {
                                        this.Error.mensaje.push('Los costos no pueden ser 0 o negativos');
                                        found2 = true;
                                    }
                                }
                                if ( found1 && found2 ) break;
                            }
                            break;
                        case 'abasto.total':
                            let found = false;
                            for (let i = 0; i < this.ListaAbasto.length; i++) {
                                if ( this.ListaAbasto[i].total != null ) {
                                    found = true; break;
                                } 
                            }
                            if ( !found ) this.Error.mensaje.push('Debe ingresar los precios de almenos una compra');
                            break;
                    }
                }

                if ( this.Error.mensaje.length ) {this.Error.estado = 1; this.Button.press = false;}
                return this.Error.estado;
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

                        axios.get(url, {
                            params: {
                                'centro_id': this.Centro.id,
                                'date': this.get('date-now')
                            }
                        }).then(function (response) {
                            console.log(response.data)
                            if ( response.data.exist == false ){
                                me.Caja.exist = false;
                                me.Caja.state = 0;
                                me.Caja.id = null;
                                me.Caja.total_start = 0;
                                me.Caja.total_end = 0;
                                me.Caja.total_ingreso = 0;
                                me.Caja.total_egreso = 0;
                                me.Caja.start = null;
                                me.Caja.end = null;
                                return;
                            }

                            me.fix('caja', response.data.caja);
                            me.fix('lista_concepto', response.data.caja.get_conceptos);
                        }).catch(function (error) {
                            console.log(error);
                        });
                        break;
                }
                
                return got;
            },
            open(option){
                switch (option) {
                    case 'modal':
                        this.Modal.estado = 1;
                        break;
                    default:
                        this.error();
                        break;
                }
            },
            close(numero){
                switch (numero) {
                    case 'error':
                        this.Error.estado = 0;
                        this.Error.mensaje = [];
                        this.Error.numero = 0;
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
                        this.Modal.btn_a = data.btn_a;
                        this.Modal.btn_b = data.btn_b;
                        this.Modal.title = data.title;
                        this.Modal.size = data.size;
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

