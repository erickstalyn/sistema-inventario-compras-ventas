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
                <div class="col-md-7 input-group"> 
                    <select class="col-md-2 custom-select text-gray-900 w4rem" v-model="Busqueda.type">
                        <option value="0">Todos</option>
                        <option value="1">Contado</option>
                        <option value="2">Credito</option>
                    </select>
                    <input type="search" class="col-md-8 form-control" v-model="Busqueda.text" placeholder="Busca por codigo, dni, ruc, razon social, nombres, apellidos" @keyup.enter="listar()">
                    <button type="button" class="col-md-2 btn btn-primary" @click="listar()">
                        <i class="fa fa-search"></i>&nbsp;Buscar
                    </button>
                </div>
                <div class="col-md-2"></div>
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
                                    <label v-if="venta.tipo.charAt(0)==1">Contado</label>
                                    <label v-if="venta.tipo.charAt(0)==2 || venta.tipo.charAt(0)==3">Credito</label>
                                </td>
                                <td v-text="venta.razon_social!=null?venta.razon_social:(venta.nombres!=null?(venta.nombres+' '+venta.apellidos):'---')"></td>
                                <td class="text-right" v-text="venta.total"></td>
                                <td class="text-center" v-text="fix(0, venta.created_at)"></td>
                                <td class="text-center">
                                    <template>
                                        <button type="button" title="VER" class="btn btn-primary btn-sm" @click="abrirModalVer(venta)">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </template>
                                    <template v-if="Number.parseFloat(venta.total)!=0">
                                        <button type="button" title="EDITAR" class="btn btn-warning btn-sm" @click="abrirModalEditar(venta)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </template>
                                    <template v-if="(venta.tipo.charAt(0)==2 || venta.tipo.charAt(0)==3)&&(venta.total_faltante!=0&&venta.total_faltante!=null)">
                                        <button type="button"  title="PAGAR" class="btn btn-success btn-sm" @click="abrirModalPagar(venta)">
                                            <i class="fas fa-hand-holding-usd"></i>
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


        <!-- Modales de Agregar/Editar/Pagar -->
        <div class="modal text-gray-900" :class="{'mostrar': Modal.estado}">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable animated bounceIn fast" :class="Modal.size">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 v-text="Modal.titulo" class="modal-title" ></h3>
                        <button type="button" @click="cerrarModal()" class="close">X</button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Modal Numero 1 de AGREGAR-->
                        <div v-if="Modal.numero==1" class="container-small input-group">
                            <div v-if="Error.estado" class="col-md-12 d-flex justify-content-center">
                                <div class="col-md-6 alert alert-danger">
                                    <button type="button" @click="Error.estado=0" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="container-small col-md-12">
                                <div class="shadow bg-white rounded pt-2 form-group" style="border: 1px solid;">
                                    <div class="col-md-12 form-group input-group">
                                        <label class="col-md-2 font-weight-bold">CLIENTE</label>
                                        <label class="col-md-1 font-weight-bold">RUC/DNI&nbsp;<span class="text-danger" v-if="Venta.tipo_pago=='2'||Venta.tipo_pago=='3'">*</span></label>
                                        <div class="col-md-2 input-group">
                                            <input type="text" class="form-control form-control-sm" v-model="Service.document" @keyup.enter="consultar()" maxlength="11">
                                            <button type="button" class="btn btn-sm btn-primary" @click="consultar()">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <h5>
                                                <span role="status" :class="Service.loadclass"></span>&nbsp;
                                                <span v-text="Service.msm" :class="Service.msmclass"></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-group input-group" v-if="Cliente.tipo=='P'">
                                        <div class="col-md-3 input-group">
                                            <label class="col-md-3">DNI</label>
                                            <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.documento">
                                        </div>
                                        <div class="col-md-4 input-group">
                                            <label class="col-md-4">Nombres</label>
                                            <input type="text" class="col-md-8 form-control form-control-sm" readonly v-model="Cliente.nombres">
                                        </div>
                                        <div class="col-md-4 input-group">
                                            <label class="col-md-4">Apellidos</label>
                                            <input type="text" class="col-md-8 form-control form-control-sm" readonly v-model="Cliente.apellidos">
                                        </div>
                                        <div class="col-md-1 d-flex justify-content-center" v-if="Venta.tipo_pago=='1'">
                                            <button type="button" class="btn btn-circle btn-sm btn-outline-danger" @click="remove(1)" title="ELIMINAR"> 
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-group input-group" v-else-if="Cliente.tipo=='E'">
                                        <div class="col-md-3 input-group">
                                            <label class="col-md-3">RUC</label>
                                            <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.documento">
                                        </div>
                                        <div class="col-md-8 input-group">
                                            <label class="col-md-3">Razón social</label>&nbsp;
                                            <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.razon_social">
                                        </div>
                                        <div class="col-md-1 d-flex justify-content-center" v-if="Venta.tipo_pago=='1'">
                                            <button type="button" class="btn btn-circle btn-sm btn-outline-danger" @click="remove(1)" title="ELIMINAR"> 
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-small col-md-4">
                                <div class="shadow bg-white rounded pt-2" style="border: 1px solid; height: 23rem;">
                                    <label class="col-md-12 form-group font-weight-bold">PRODUCTOS</label>
                                    <div class="col-md-12 input-group form-group">
                                        <input type="search" class="form-control form-control-sm" v-model="Service.text" @keyup.enter="list(0)" id="filtroProducto" placeholder="Producto,marca,modelo,tamaño,color">
                                        <button type="button" class="btn btn-sm btn-primary" @click="list(0)">
                                            <i class="fa fa-search"></i>&nbsp; Buscar
                                        </button>
                                    </div>
                                    <div v-if="ListaProducto.length" class="col-md-12 overflow-auto" style="height: 16rem;">
                                        <table class="table table-bordered table-striped table-sm text-gray-900">
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
                                                        <button type="button" v-if="producto.detalle.substock>0" title="AGREGAR" class="btn btn-circle btn-sm btn-outline-success" @click="add(0, producto)">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </td>
                                                    <td v-text="producto.nombre"></td>
                                                    <td v-text="producto.detalle.substock" class="text-right"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-else>
                                        <label class="col-md-12 text-danger">No se han encontrado resultados</label>
                                    </div>
                                </div>
                            </div>
                            <div class="container-small col-md-8">
                                <div class="shadow bg-white rounded pt-2" style="border: 1px solid; height: 23rem;">
                                    <div class="col-md-12 form-group input-group">
                                        <label class="col-md-3 font-weight-bold">LISTA DE ITEMS</label>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-4 input-group">
                                            <label class="col-md-4 font-weight-bold">Pago&nbsp;<span class="text-danger">*</span></label>
                                            <select class="col-md-8 custom-select custom-select-sm text-gray-900" v-model="Venta.tipo_pago">
                                                <option value="1">Contado</option>
                                                <option value="2">Credito prepago</option>
                                                <option value="3">Credito postpago</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 input-group">
                                            <label class="col-md-5 font-weight-bold">Precio&nbsp;<span class="text-danger">*</span></label>
                                            <select class="col-md-7 custom-select custom-select-sm text-gray-900" v-model="Venta.tipo_precio" @click="update(0)">
                                                <option value="1">Al por menor</option>
                                                <option value="2">Al por mayor</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div v-if="ListaDetalle.length" class="col-md-12 overflow-auto form-group" style="height: 16rem;">
                                        <table class="table table-bordered table-striped table-sm text-gray-900">
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
                                                        <button type="button" class="btn btn-circle btn-outline-danger btn-sm" title="QUITAR" @click="remove(0, indice)">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </td>
                                                    <td v-text="detalle.nombre_producto"></td>
                                                    <td>
                                                        <input type="number" v-model="detalle.cantidad" class="form-control form-control-sm text-right" min="1" :max="detalle.substock" @click="update(0)" @keyup="update(0)">
                                                    </td>
                                                    <td class="text-right" v-text="Venta.tipo_precio=='1'?detalle.precio_menor:detalle.precio_mayor"></td>
                                                    <td class="text-right" v-text="Number.parseFloat(detalle.subtotal).toFixed(2)">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-else class="col-md-12 form-group" style="height: 16rem;">
                                        <label class="text-danger">Sin detalles de venta</label>
                                    </div>
                                    <div class="col-md-12 input-group">
                                        <div class="col-md-5 input-group" v-if="Venta.tipo_pago=='2'||Venta.tipo_pago=='3'"> 
                                            <label class="col-md-6 font-weight-bold" for="pago_inicial">Pago inicial&nbsp;<span class="text-danger">*</span></label>
                                            <input type="number" class="col-md-6 form-control form-control-sm text-right" v-model="Pago.monto" min="0" :max="Venta.total" id="pago_inicial">
                                        </div>
                                        <div class="col-md-5" v-else></div>
                                        <div class="col-md-2">
                                        </div>
                                        <div class="col-md-5 input-group">
                                            <label class="col-md-6 text-right font-weight-bold">Monto total:</label>
                                            <label class="col-md-6 text-right text-info" v-text="'S/. '+(Venta.total).toFixed(2)"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Numero 2 de VER-->
                        <div v-if="Modal.numero==2" class="input-group">
                            <div class="container-small col-md-12">
                                <div class="shadow bg-white rounded pt-2 form-group"  style="border: 1px solid">
                                    <div class="col-md-12">
                                        <label class="font-weight-bold">CLIENTE</label>
                                    </div>
                                    <div v-if="Cliente.tipo=='P'" class="input-group form-group">
                                        <div class="col-md-3 input-group">
                                            <label class="col-md-3">DNI</label>
                                            <input type="text" class="col-md-7 form-control form-control-sm" readonly v-model="Cliente.dni">
                                        </div>
                                        <div class="col-md-4 input-group">
                                            <label class="col-md-4">Nombres</label>
                                            <input type="text" class="col-md-8 form-control form-control-sm" readonly v-model="Cliente.nombres">
                                        </div>
                                        <div class="col-md-4 input-group">
                                            <label class="col-md-4">Apellidos</label>
                                            <input type="text" class="col-md-8 form-control form-control-sm" readonly v-model="Cliente.apellidos">
                                        </div>
                                    </div>
                                    <div v-else-if="Cliente.tipo=='E'" class="input-group form-group">
                                        <div class="col-md-3 input-group">
                                            <label class="col-md-3">RUC</label>
                                            <input type="text" class="col-md-7 form-control form-control-sm" readonly v-model="Cliente.ruc">
                                        </div>
                                        <div class="col-md-8 input-group">
                                            <label class="col-md-2">Razón social</label>&nbsp;
                                            <input type="text" class="col-md-10 form-control form-control-sm" readonly v-model="Cliente.razon_social">
                                        </div>
                                    </div>
                                    <div v-else class="col-md-12">
                                        <label class="text-info">No se ha registrado cliente</label>
                                    </div>
                                </div>
                            </div>
                            <div class="container-small col-md-4" v-if="Venta.tipo_pago.charAt(0)==2||Venta.tipo_pago.charAt(0)==3">
                                <div class="shadow bg-white rounded pt-2" style="height: 24rem; border: 1px solid">
                                    <div class="col-md-12 input-group">
                                        <label class="font-weight-bold">LISTA DE PAGOS</label>
                                    </div>
                                    <div class="col-md-12 overflow-auto" style="height: 16rem" v-if="ListaPago.length">
                                        <table class="table table-bordered table-striped table-sm text-gray-900">
                                            <thead>
                                                <tr class="table-info">
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Fecha</th>
                                                    <th class="text-center">Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(pago, index) in ListaPago" :key="index" :class="pago.color">
                                                    <td class="text-right pr-1">{{index+1}}</td>
                                                    <td class="text-center" v-text="fix(0, pago.created_at)"></td>
                                                    <td class="text-right" v-text="pago.monto"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 overflow-auto" style="height: 16rem" v-else>
                                        <label class="text-info">Ningun pago registrado</label>
                                    </div>
                                    <div class="col-md-12 input-group d-flex justify-content-end">
                                        <!-- Monto pagado -->
                                        <label class="col-md-6 font-weight-bold p-0" style="margin-bottom: 0px;">Monto pagado:</label>
                                        <label class="col-md-1 text-right text-success p-0" style="margin-bottom: 0px;">S/.</label>
                                        <label class="col-md-4 text-right text-success" style="margin-bottom: 0px;" v-text="(Venta.total-Venta.total_faltante).toFixed(2)"></label>
                                        <!-- Monto faltante -->
                                        <label class="col-md-6 font-weight-bold p-0" style="margin-bottom: 0px;">Monto faltante:</label>
                                        <label class="col-md-1 text-right text-danger p-0" style="margin-bottom: 0px;">S/.</label>
                                        <label class="col-md-4 text-right text-danger" style="margin-bottom: 0px;" v-text="Number.parseFloat(Venta.total_faltante).toFixed(2)"></label>
                                        <!-- Monto total -->
                                        <label class="col-md-6 font-weight-bold p-0" style="margin-bottom: 0px;">Monto total: </label>
                                        <label class="col-md-1 text-right p-0" style="margin-bottom: 0px;">S/.</label>
                                        <label class="col-md-4 text-right" style="margin-bottom: 0px;" v-text="Number.parseFloat(Venta.total).toFixed(2)"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="container-small" :class="Venta.tipo_pago.charAt(0)==2||Venta.tipo_pago.charAt(0)==3?'col-md-8':'col-md-12'">
                                <div class="shadow bg-white rounded pt-2"  style="height: 24rem; border: 1px solid">
                                    <div class="col-md-12 input-group">
                                        <label class="col-md-3 font-weight-bold">LISTA DE ITEMS</label>
                                        <div class="col-md-1"></div>
                                        <label class="col-md-4 font-weight-bold">Pago:&nbsp;<label class="text-info font-weight-normal" v-text="fix(4, Venta.tipo_pago)"></label></label>
                                        <label class="col-md-4 font-weight-bold">Precio:&nbsp;<label class="text-info font-weight-normal" v-text="fix(5, Venta.tipo_precio)"></label></label>
                                    </div>
                                    <div class="col-md-12 form-group overflow-auto" style="height: 17rem;">
                                        <table class="table table-bordered table-striped table-sm text-gray-900">
                                            <thead>
                                                <tr class="table-success">
                                                    <th class="text-center">Nombre</th>
                                                    <th class="text-center">Cantidad</th>
                                                    <th class="text-center">Precio</th>
                                                    <th class="text-center">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(detalle, indice) in ListaDetalle" :key="indice">
                                                    <td v-text="detalle.nombre_producto"></td>
                                                    <td class="text-right" v-text="detalle.cantidad"></td>
                                                    <td class="text-right" v-text="detalle.precio"></td>
                                                    <td class="text-right" v-text="detalle.subtotal">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 input-group">
                                        <div class="col-md-6 input-group">
                                            <label class="col-md-12 font-weight-bold pl-0">Registrado:&nbsp;<label class="text-info font-weight-normal" v-text="fix(0, Venta.created_at)"></label></label>
                                        </div>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-5 input-group">
                                            <label class="col-md-7 font-weight-bold text-right">Monto total:</label>
                                            <label class="col-md-5 text-right text-info pr-0 pl-0" v-text="'S/. '+Number.parseFloat(Venta.total).toFixed(2)"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Numero 3 de EDITAR -->
                        <div v-if="Modal.numero==3" class="container-small input-group">
                            <div v-if="Error.estado" class="col-md-12 d-flex justify-content-center">
                                <div class="col-md-6 alert alert-danger pb-0">
                                    <button type="button" @click="Error.estado=0" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="container-small col-md-10 mt-1">
                                <div class="shadow bg-white rounded pt-2 input-group form-group" style="border: 1px solid; height: 6.5rem;">
                                    <div class="col-md-2 form-group">
                                        <label class="font-weight-bold">CLIENTE</label>
                                    </div>
                                    <div class="col-md-10 input-group form-group" v-if="Vale.id==null&&Venta.tipo_pago=='1'">
                                        <label class="col-md-2 font-weight-bold">RUC/DNI&nbsp;
                                            <span class="text-danger" v-if="Venta.tipo_pago=='2'||Venta.tipo_pago=='3'||(Venta.total<Number.parseFloat(Venta.total_minimo))">*</span>
                                        </label>
                                        <div class="col-md-3 input-group">
                                            <input type="text" class="form-control form-control-sm" v-model="Service.document" @keyup.enter="consultar()" maxlength="11">
                                            <button type="button" class="btn btn-sm btn-primary" @click="consultar()">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <h5>
                                                <span role="status" :class="Service.loadclass"></span>&nbsp;
                                                <span v-text="Service.msm" :class="Service.msmclass"></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-12 input-group form-group" v-if="Cliente.tipo=='P'">
                                        <div class="col-md-3 input-group">
                                            <label class="col-md-3">DNI</label>
                                            <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.dni">
                                        </div>
                                        <div class="col-md-4 input-group">
                                            <label class="col-md-4">Nombres</label>
                                            <input type="text" class="col-md-8 form-control form-control-sm" readonly v-model="Cliente.nombres">
                                        </div>
                                        <div class="col-md-4 input-group">
                                            <label class="col-md-4">Apellidos</label>
                                            <input type="text" class="col-md-8 form-control form-control-sm" readonly v-model="Cliente.apellidos">
                                        </div>
                                        <div class="col-md-1 d-flex justify-content-center" v-if="Venta.tipo_pago=='1'&&Vale.id==null&&Venta.total==Venta.total_minimo">
                                            <button type="button" class="btn btn-circle btn-sm btn-outline-danger" @click="remove(1)" title="ELIMINAR"> 
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 input-group form-group" v-if="Cliente.tipo=='E'">
                                        <div class="col-md-3 input-group">
                                            <label class="col-md-3">RUC</label>
                                            <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.ruc">
                                        </div>
                                        <div class="col-md-8 input-group">
                                            <label class="col-md-3">Razón social</label>&nbsp;
                                            <input type="text" class="col-md-9 form-control form-control-sm" readonly v-model="Cliente.razon_social">
                                        </div>
                                        <div class="col-md-1 d-flex justify-content-center" v-if="Venta.tipo_pago=='1'&&Vale.id==null&&Venta.total==Venta.total_minimo">
                                            <button type="button" class="btn btn-circle btn-sm btn-outline-danger" @click="remove(1)" title="ELIMINAR"> 
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-small col-md-2 mt-1 pl-0" v-if="Vale.id!=null">
                                <div class="shadow bg-white rounded pt-2 form-group" style="border: 1px solid; height: 6.5rem;">
                                    <div class="col-md-12 form-group input-group">
                                        <label class="col-md-12 font-weight-bold pl-0 mb-0">VALE</label>
                                        <ul class="col-md-12 pr-0 pl-0">
                                            <li class="input-group">
                                                <label class="col-md-4 p-0 mb-0">Monto:</label>
                                                <label class="col-md-8 p-0 mb-0 text-right text-primary" v-text="'S/.  '+Vale.monto"></label>
                                            </li>
                                            <li class="input-group">
                                                <label class="col-md-4 p-0 mb-0">Fecha:</label>
                                                <label class="col-md-8 p-0 mb-0 text-right text-primary" v-text="fix(7, Vale.created_at)"></label>
                                            </li>
                                            <li class="input-group">
                                                <label class="col-md-4 p-0 mb-0">Hora:</label>
                                                <label class="col-md-8 p-0 mb-0 text-right text-primary" v-text="fix(8, Vale.created_at)"></label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="container-small col-md-4">
                                <div class="shadow bg-white rounded pt-2" style="border: 1px solid; height: 23rem;">
                                    <label class="col-md-12 form-group font-weight-bold">PRODUCTOS</label>
                                    <div class="col-md-12 input-group form-group">
                                        <input type="search" class="form-control form-control-sm" v-model="Service.text" @keyup.enter="list(0)" id="filtroProducto" placeholder="Producto,marca,modelo,tamaño,color">
                                        <button type="button" class="btn btn-sm btn-primary" @click="list(0)">
                                            <i class="fa fa-search"></i>&nbsp; Buscar
                                        </button>
                                    </div>
                                    <div v-if="ListaProducto.length" class="col-md-12 overflow-auto" style="height: 16rem;">
                                        <table class="table table-bordered table-striped table-sm text-gray-900">
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
                                                        <button type="button" v-if="producto.detalle.substock>0" title="AGREGAR" class="btn btn-circle btn-sm btn-outline-success" @click="add(0, producto)">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </td>
                                                    <td v-text="producto.nombre"></td>
                                                    <td v-text="producto.detalle.substock" class="text-right"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-else>
                                        <label class="col-md-12 text-danger">No se han encontrado resultados</label>
                                    </div>
                                </div>
                            </div>
                            <div class="container-small col-md-8">
                                <div class="shadow bg-white rounded pt-2" style="border: 1px solid; height: 23rem;">
                                    <div class="col-md-12 input-group">
                                        <label class="col-md-4 font-weight-bold">LISTA DE ITEMS</label>
                                        <label class="col-md-4 font-weight-bold">Pago:&nbsp;<label class="text-info font-weight-normal" v-text="fix(4, Venta.tipo_pago)"></label></label>
                                        <label class="col-md-4 font-weight-bold">Precio:&nbsp;<label class="text-info font-weight-normal" v-text="fix(5, Venta.tipo_precio)"></label></label>
                                    </div>
                                    <div v-if="ListaDetalle.length" class="col-md-12 overflow-auto" style="height: 15rem;">
                                        <table class="table table-bordered table-striped table-sm text-gray-900">
                                            <thead>
                                                <tr class="table-success">
                                                    <th class="text-center">Quitar</th>
                                                    <th class="text-center">Nombre</th>
                                                    <th class="text-center" style="width: 5rem;">Fallidos</th>
                                                    <th class="text-center" style="width: 5rem;">Cantidad</th>
                                                    <th class="text-center">Precio</th>
                                                    <th class="text-center">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(detalle, indice) in ListaDetalle" :key="indice">
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-circle btn-outline-danger btn-sm" title="QUITAR" @click="remove(0, indice)" v-if="detalle.id==0">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </td>
                                                    <td v-text="detalle.nombre_producto"></td>
                                                    <td v-if="detalle.id!=0">
                                                        <input type="number" v-model="detalle.fallidos" class="form-control form-control-sm text-right" :min="detalle.fallidos_inicial" :max="detalle.cantidad_inicial" @click="update(3)" @keyup="update(3)">
                                                    </td>
                                                    <td v-else class="text-center">-</td>
                                                    <td>
                                                        <input type="number" v-model="detalle.cantidad" class="form-control form-control-sm text-right p-0" :min="detalle.cantidad_inicial" :max="detalle.substock" @click="update(3)" @keyup="update(3)">
                                                    </td>
                                                    <td class="text-right" v-text="Venta.tipo_precio=='1'?detalle.precio_menor:detalle.precio_mayor"></td>
                                                    <td class="text-right" v-text="Number.parseFloat(detalle.subtotal).toFixed(2)">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-else class="col-md-12" style="height: 15rem;">
                                        <label class="col-md-12 text-danger">Sin detalles de venta</label>
                                    </div>
                                    <div class="col-md-12 input-group pt-2">
                                        <div class="col-md-7">
                                            <div class="col-md-8 input-group p-0" v-if="Venta.total > Venta.total_minimo"> 
                                                <label class="col-md-6 font-weight-bold p-0" for="pago_inicial">Pago extra&nbsp;<span class="text-danger">*</span></label>
                                                <input type="number" class="col-md-6 form-control form-control-sm text-right" v-model="Pago.monto" min="0" :max="Venta.total-Venta.total_minimo" id="pago_inicial">
                                            </div>
                                            <div class="col-md-8 input-group p-0" v-if="Venta.total < Venta.total_minimo"> 
                                                <label class="col-md-12 font-weight-bold">
                                                    <span v-if="Vale.id==null">Se generara un vale de venta por:&nbsp;&nbsp;&nbsp;</span>
                                                    <span v-else>El vale de venta se incrementara en:&nbsp;&nbsp;&nbsp;</span>
                                                    <span class="font-weight-normal text-success" v-text="'S/. '+Number.parseFloat(Venta.total_minimo-Venta.total).toFixed(2)"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-5 input-group">
                                            <label class="col-md-6 font-weight-bold">Monto total:</label>
                                            <label class="col-md-1 text-info p-0" v-text="'S/.'"></label>
                                            <label class="col-md-5 text-right text-info" v-text="Number.parseFloat(Venta.total).toFixed(2)"></label>
                                            <label class="col-md-6 font-weight-bold">Minimo:</label>
                                            <label class="col-md-1 text-danger p-0" v-text="'S/.'"></label>
                                            <label class="col-md-5 text-right text-danger" v-text="Number.parseFloat(Venta.total_minimo).toFixed(2)"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Numero 4 de PAGAR-->
                        <div v-if="Modal.numero==4" class="container-small input-group d-flex justify-content-center">
                            <div v-if="Error.estado" class="row d-flex justify-content-center">
                                <div class="alert alert-danger" style="height: 4.5rem;">
                                    <button type="button" @click="Error.estado=0" class="close text-primary" data-dismiss="alert">×</button>
                                    <strong>Corregir los siguentes errores:</strong>
                                    <ul> 
                                        <li v-for="error in Error.mensaje" :key="error" v-text="error"></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-12 form-group input-group">
                                <label class="col-md-3 font-weight-bold p-0">Registrar</label>
                                <div class="col-md-2"></div>
                                <div class="col-md-7 input-group">
                                    <label>Monto&nbsp;&nbsp;</label>
                                    <input type="number" class="col-md-7 form-control form-control-sm text-right" v-model="Pago.monto" @keyup.enter="add(1)" min="0" :max="Venta.total_faltante" :disabled="Venta.total_faltante==0" id="PagoMonto">
                                    <button type="button" class="btn btn-sm btn-primary" @click="add(1)" :disabled="Venta.total_faltante==0">Registrar</button>
                                </div>
                            </div>
                            <div class="col-md-10 form-group overflow-auto pr-0 pl-0" style="height: 22rem;" v-if="ListaPago.length">
                                <table class="table table-bordered table-striped table-sm text-gray-900">
                                    <thead>
                                        <tr class="table-info">
                                            <th class="text-center">#</th>
                                            <th class="text-center">Fecha de pago</th>
                                            <th class="text-center">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(pago, index) in ListaPago" :key="index" :class="pago.color">
                                            <td class="text-right">{{index+1}}</td>
                                            <td class="text-center" v-text="fix(0, pago.created_at)"></td>
                                            <td class="text-right" v-text="pago.monto"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12 form-group" style="height: 22rem;" v-else>
                                <label class="text-primary">Ningun pago registrado</label>
                            </div>
                            <div class="col-md-12 input-group">
                                <!-- Monto pagado -->
                                <label class="col-md-4" style="margin-bottom: 0px;"></label>
                                <label class="col-md-4 font-weight-bold" style="margin-bottom: 0px;">Monto pagado:</label>
                                <label class="col-md-1 text-right text-success" style="margin-bottom: 0px;">S/.</label>
                                <label class="col-md-3 text-right text-success" style="margin-bottom: 0px;" v-text="(Venta.total-Venta.total_faltante).toFixed(2)"></label>
                                <!-- Monto faltante -->
                                <label class="col-md-4" style="margin-bottom: 0px;"></label>
                                <label class="col-md-4 font-weight-bold" style="margin-bottom: 0px;">Monto faltante:</label>
                                <label class="col-md-1 text-right text-danger" style="margin-bottom: 0px;">S/.</label>
                                <label class="col-md-3 text-right text-danger" style="margin-bottom: 0px;" v-text="Venta.total_faltante"></label>
                                <!-- Monto total -->
                                <label class="col-md-4" style="margin-bottom: 0px;"></label>
                                <label class="col-md-4 font-weight-bold" style="margin-bottom: 0px;">Monto total: </label>
                                <label class="col-md-1 text-right" style="margin-bottom: 0px;">S/.</label>
                                <label class="col-md-3 text-right" style="margin-bottom: 0px;" v-text="Venta.total"></label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row col-md-12 d-flex justify-content-around">
                            <button type="button" v-if="Modal.btnA" @click="accionar()" class="btn btn-success" v-text="Modal.btnA"></button>
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
                ListaVenta: [],
                Venta: {
                    id: 0,
                    codigo: '',
                    centro_id: '',
                    total: 0.00,
                    total_faltante: 0.00,
                    total_minimo: 0,
                    tipo_pago: '0', // 1: contado, 2: credito prepago, 3: credito postpago
                    tipo_precio: '0', // 1: al por menor, 2: al por mayor
                    created_at : '',
                },
                ListaProducto:[],
                ListaDetalle: [],
                
                Cliente:{
                    id: null,
                    documento: null,
                    dni: null,
                    ruc: null,
                    nombres: null,
                    apellidos: null,
                    razon_social: null,
                    tipo: null
                },

                //datos de busqueda y filtracion general
                Busqueda: {
                    text: '',
                    type: 0,
                    rows: 7
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

                //datos de servicios necesarios
                Service: {
                    document: '',
                    msm: '',
                    msmclass: '',
                    loadclass: '',
                    text: ''
                },

                //datos de pagos
                ListaPago: [],
                Pago:{
                    monto: 0.00
                },
                Vale: {
                    id: null,
                    monto: null,
                    created_at: null
                },

                //datos de Rutas
                Ruta: {
                    venta: '/venta',
                    persona: '/persona',
                    producto: '/producto',
                    detalle_producto: '/detalle_producto',
                    detalle_venta: '/detalle_venta',
                    pago: '/pago'
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
        },
        methods: {
            listar(page = 1){
                this.Paginacion.currentPage = page;

                var me = this;
                var url = this.Ruta.venta+'?'
                        +'page='+this.Paginacion.currentPage
                        +'&type='+this.Busqueda.type
                        +'&text='+this.Busqueda.text
                        +'&rows='+this.Busqueda.rows
                        +'&centro_id='+$('meta[name="idCentro"]').attr('content')
                        +'&dia='+this.fix(1)
                        +'&mes='+this.fix(2)
                        +'&year='+this.fix(3);

                axios.get(url).then(function (response) {
                    me.ListaVenta = response.data.ventas.data;
                    me.Paginacion = response.data.pagination;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            agregar(){
                if ( this.validar([0, 1, 2, 3]) ) return;
                
                var me = this;
                var url = this.Ruta.venta+'/agregar';

                axios.post(url, {
                    'centro_id': $('meta[name="idCentro"]').attr('content'),
                    'dataVenta': this.Venta,
                    'dataPago': this.Pago,
                    'dataCliente': this.Cliente,
                    'listDetalle': this.ListaDetalle
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    var estado = response.data.estado;
                    if ( estado == 0 ) {
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'La venta se ha REGISTRADO correctamente',
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
                }).catch(function(error){
                    console.log(error);
                });
            },
            editar(){
                if ( this.validar([5]) ) return;
                if ( this.validar([0, 1, 2, 3]) ) return;

                var me = this;
                var url = this.Ruta.venta+'/editar';

                axios.put(url, {
                    'centro_id': $('meta[name="idCentro"]').attr('content'),
                    'dataVenta': this.Venta,
                    'dataCliente': this.Cliente,
                    'dataPago': this.Pago,
                    'dataVale': this.Vale,
                    'listDetalle': this.ListaDetalle
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    var vale = response.data.vale;
                    if ( vale != null ) {
                        Swal.fire({
                            title: 'Se ha generado un vale',
                            text: 'Monto del vale (S/. '+Number.parseFloat(vale.monto).toFixed(2)+'), este vale lo puede utilizar en la siguiente compra que realize',
                            type: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Imprimir vale',
                            cancelButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-primary',
                                cancelButton: 'btn btn-success'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.value) {
                                // ZONA PARA EL CODIGO DE IMPRESION DE VALE
                                console.log('Se imprimio el vale');
                            }
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                type: 'success',
                                title: 'La venta se ha EDITADO correctamente',
                                showConfirmButton: false,
                                timer: 4500,
                                animation:false,
                                customClass:{
                                    popup: 'animated bounceIn fast'
                                }
                            });
                        });
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            type: 'success',
                            title: 'La venta se ha EDITADO correctamente',
                            showConfirmButton: false,
                            timer: 4500,
                            animation:false,
                            customClass:{
                                popup: 'animated bounceIn fast'
                            }
                        });
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            pagar(){
                let pagos = [];
                this.ListaPago.forEach(pago => {
                    if(pago.estado) pagos.push(pago);
                });
                var venta_tipo_pago = this.Venta.tipo_pago;
                var venta_total_faltante = Number.parseFloat(this.Venta.total_faltante);
                var venta_id = this.Venta.id;

                let me = this;
                let url = this.Ruta.pago+'/agregar';

                axios.post(url, {
                    'idVenta': this.Venta.id,
                    'listaPagos': pagos
                }).then(function(response){
                    me.cerrarModal();
                    me.listar();
                    if ( venta_tipo_pago != 3 || venta_total_faltante != 0 ) {
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
                    } else {
                        Swal.fire({
                            title: 'Pagos Finalizados',
                            text: "Ya que se ha pagado por completo la venta, ahora tiene que entregar los siguientes productos",
                            type: 'success',
                        }).then((result) => {
                            if (result.value) {
                                var data;
                                for (let i = 0; i < me.ListaVenta.length; i++) {
                                    if ( me.ListaVenta[i].id == venta_id ) {
                                        data = me.ListaVenta[i]; break;
                                    }
                                }
                                me.abrirModalVer(data);
                            } else if ( result.dismiss === Swal.DismissReason.cancel ) {
                            }
                        })
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            abrirModalAgregar(){
                this.abrirModal(1, 'Registrar Venta', 'modal-xl', 'Registrar', 'Cancelar');

                this.Error.numero = 0;
                this.Error.estado = 0;
                this.Error.mensaje = [];

                this.Venta.total = 0;
                this.Venta.total_faltante = 0;
                this.Venta.tipo_pago = '1';
                this.Venta.tipo_precio = '1';
                
                this.ListaProducto = [];
                this.Pago.monto = '';
            
                this.Cliente.id = null;
                this.Cliente.documento = '';
                this.Cliente.nombres = null;
                this.Cliente.apellidos = null;
                this.Cliente.dni = null;
                this.Cliente.razon_social = null;
                this.Cliente.ruc = null;
                this.Cliente.tipo = null;
                
                this.Service.document = '';
                this.Service.msm = '';
                this.Service.msmclass = '';
                this.Service.loadclass = '';
                this.Service.text = '';
            },
            abrirModalVer(data){
                this.abrirModal(2, 'Ver Venta', 'modal-xl', '', 'Cerrar');

                this.Venta.id = data.id;
                this.Venta.total = data.total;
                this.Venta.total_faltante = data.total_faltante==null?0:data.total_faltante;
                this.Venta.tipo_pago = data.tipo.charAt(0);
                this.Venta.tipo_precio = data.tipo.charAt(1);
                this.Venta.created_at = data.created_at;
                
                this.Cliente.dni = data.dni;
                this.Cliente.nombres = data.nombres;
                this.Cliente.apellidos = data.apellidos;
                this.Cliente.ruc = data.ruc;
                this.Cliente.razon_social = data.razon_social;
                this.Cliente.tipo = data.cliente_tipo;
                
                this.list(1, 'Ver');
                this.list(2);
            },
            abrirModalEditar(data){
                if ( this.fix(10, data.created_at) != this.fix(3) || this.fix(9, data.created_at) != this.fix(2) ) {
                    Swal.fire({
                        title: 'Esta venta ya no se puede editar',
                        text: 'Esta venta fue realizada el mes pasado y por eso ya no se puede editar',
                        type: 'info',
                        showCancelButton: false,
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                    return;
                }

                this.Venta.id = data.id;
                this.Venta.total = data.total;
                this.Venta.total_minimo = data.total;
                this.Venta.tipo = data.tipo;
                this.Venta.tipo_pago = data.tipo.charAt(0);
                this.Venta.tipo_precio = data.tipo.charAt(1);
                this.Venta.created_at = data.created_at;
                
                this.Cliente.id = data.cliente_id;
                this.Cliente.documento = data.dni!=null?data.dni:(data.ruc!=null?data.ruc:'');
                this.Cliente.dni = data.dni;
                this.Cliente.nombres = data.nombres;
                this.Cliente.apellidos = data.apellidos;
                this.Cliente.ruc = data.ruc;
                this.Cliente.razon_social = data.razon_social;
                this.Cliente.tipo = data.cliente_tipo;
                
                this.Vale.id = data.vale_id;
                this.Vale.monto = data.vale_monto;
                this.Vale.created_at = data.vale_created_at;

                this.list(1, 'Editar');

                this.abrirModal(3, 'Editar Venta', 'modal-xl', 'Editar', 'Cerrar');
            },
            abrirModalPagar(venta = []){
                this.abrirModal(4, 'Realizar Pago', '', 'Guardar', 'Cancelar');

                this.Venta.id = venta['id'];
                this.Venta.total = venta['total'];
                this.Venta.total_faltante = venta['total_faltante']==null?0:venta.total_faltante;
                this.Venta.tipo_pago = venta['tipo'].charAt(0);
                
                this.Pago.monto = '';

                this.list(2);
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
            abrirModal(numero, titulo, size, btnA, btnC){
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
                this.Modal.titulo = '';
                this.Modal.size = '';
                this.Modal.btnA = '';
                this.Modal.btnC = '';

                this.Error.numero = 0;
                this.Error.estado = 0;
                this.Error.mensaje = [];

                this.Service.document = '';
                this.Service.msm = '';
                this.Service.msmclass = '';
                this.Service.loadclass = '';
                this.Service.text = '';

                this.Venta.id = 0;
                this.Venta.total = 0.00;
                this.Venta.total_faltante = 0;
                this.Venta.total_minimo = 0;
                this.Venta.centro_id = 0;
                this.Venta.tipo = '00';
                this.Venta.tipo_pago = '0';
                this.Venta.tipo_precio = '0';

                this.Cliente.id = null;
                this.Cliente.documento = null;
                this.Cliente.dni = null;
                this.Cliente.nombres = null;
                this.Cliente.apellidos = null;
                this.Cliente.ruc = null;
                this.Cliente.razon_social = null;
                this.Cliente.tipo = null;

                this.ListaPago = [];
                this.Pago.monto = '';
                this.Vale.monto = null;

                this.ListaProducto = [];

                this.ListaDetalle = [];
            },
            accionar(){
                switch ( this.Modal.numero ){
                    case 1: this.agregar(); break;
                    case 3: this.editar(); break;
                    case 4: this.pagar(); break;
                }
            },
            validar(data = []){
                this.Error.estado = 0;
                this.Error.mensaje = [];

                for (let i = 0; i < data.length; i++) {
                    switch ( data[i] ) {
                        case 0: // cliente
                            if ( this.Cliente.documento == '' && (this.Venta.tipo_pago == 2 || this.Venta.tipo_pago == 3 || Number.parseFloat(this.Venta.total) < Number.parseFloat(this.Venta.total_minimo)) ) {
                                this.Error.mensaje.push('Debe ingresar datos del cliente');
                            }
                            break;
                        case 1: // tipo de venta
                            if( this.Venta.tipo_pago == 2 || this.Venta.tipo_pago == 3 ){
                                if ( this.Pago.monto < 0 || this.Pago.monto == '' ){
                                    this.Error.mensaje.push('El pago inicial debe ser mayor o igual a 0')
                                } else if (this.Pago.monto > this.Venta.total){
                                    this.Error.mensaje.push('El pago inicial no debe ser mayor al monto total')
                                } else if (this.Pago.monto == this.Venta.total){
                                    this.Error.mensaje.push('El pago inicial es igual al monto total, se recomienda cambiarlo a una venta al contado')
                                }
                            }
                            break;
                        case 2: // cantidades de detalle de venta
                            if ( !this.ListaDetalle.length ) {
                                this.Error.mensaje.push("No existe ningun detalle de venta");
                            } else {
                                let found_a = false, found_b = false;
                                for (let i = 0; i < this.ListaDetalle.length; i++) {
                                    if ( this.ListaDetalle[i].cantidad <= 0 && !found_a ){
                                        this.Error.mensaje.push('Las cantidades no pueden 0 o negativos'); found_a = true;
                                    } 
                                    if ( this.ListaDetalle[i].cantidad > this.ListaDetalle[i].substock && !found_b ){
                                        this.Error.mensaje.push('Las cantidades no pueden superar al stock'); found_b = true;
                                    } 
                                    if ( found_a && found_b ) break;
                                }
                            }
                            break;
                        case 3: // fallidos de detalle de venta
                            let found_a = false, found_b = false;
                            for (let i = 0; i < this.ListaDetalle.length; i++) {
                                if ( this.ListaDetalle[i].fallidos < this.ListaDetalle[i].fallidos_inicial && !found_a ){
                                    this.Error.mensaje.push('Los fallidos no pueden 0 o negativos'); found_a = true;
                                } 
                                if ( this.ListaDetalle[i].fallidos > this.ListaDetalle[i].cantidad_inicial && !found_b ){
                                    this.Error.mensaje.push('Los fallidos no pueden superar la cantidad inicial'); found_b = true;
                                } 
                                if ( found_a && found_b ) break;
                            }
                            break;
                        case 4: // monto de pago
                            if (this.Pago.monto == '' || this.Pago.monto <= 0 || this.Pago.monto > Number.parseFloat(this.Venta.total_faltante)) this.Error.mensaje.push('Debe ingresar un monto válido');
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
                                if ( this.ListaDetalle[i].fallidos != this.ListaDetalle[i].fallidos_inicial || this.ListaDetalle[i].cantidad != this.ListaDetalle[i].cantidad_inicial ) {
                                    change3 = true; break;
                                }
                            }
                            if ( !change1 && !change2 && !change3 ) this.Error.mensaje.push('Ningun cambio realizado');
                            break;
                    }
                }

                if ( this.Error.mensaje.length ) this.Error.estado = 1;
                return this.Error.estado;
            },
            consultar(){
                this.Service.msm = '';
                this.Service.msmclass = '';

                switch (this.Service.document.length) {
                    case 0:
                        this.Service.msm = 'Ingrese un DNI o RUC';
                        this.Service.msmclass = 'badge badge-warning';
                        break;
                    case 8: 
                    case 11:
                        this.Service.msm = 'Consultado...';
                        this.Service.msmclass = 'badge badge-info';
                        this.Service.loadclass = 'spinner-border spinner-border-sm text-success';
                        this.consultarDB();
                        break;
                    default:
                        this.Service.msm = 'Documento inválido'
                        this.Service.msmclass = 'badge badge-primary';
                        break;
                }
            },
            consultarDB(){
                var me = this;
                var url = this.Ruta.persona+'/getPersona';

                axios.get(url, {
                    params: {
                        'documento': me.Service.document
                    }
                }).then(function(response){
                    if (response.data.persona.length){ //Si existe la persona en la db
                        me.Service.msm = '';
                        me.Service.msmclass = '';
                        me.Service.loadclass = '';

                        const persona = response.data.persona[0];
                        
                        if (persona.razon_social != null){//Es una EMPRESA
                            me.Cliente.tipo = 'E';
                            me.Cliente.razon_social = persona.razon_social;
                            me.Cliente.ruc = me.Service.document;
                        } else {//Es una PERSONA
                            me.Cliente.tipo = 'P';
                            me.Cliente.nombres = persona.nombres;
                            me.Cliente.apellidos = persona.apellidos;
                            me.Cliente.dni = me.Service.document;
                        }

                        me.Cliente.id = persona.id;
                        me.Cliente.documento = me.Service.document;
                        me.Cliente.documento = me.Service.document;

                        me.Service.document = '';
                    } else {//No esxiste la persona en la db
                        if ( me.Service.document.length == 8 ){
                            me.consultarDNI();
                        }else{
                            me.consultarRUC();
                        }
                    }
                }).catch(function(error){
                    console.log(error);
                });
            },
            consultarDNI(){
                let me = this;
                let dni = me.Service.document;

                $.ajax({
                    type: 'GET',
                    url: "http://localhost:8080/Reniec/demo.php",
                    data: "dni="+dni,
                    beforeSend(){
                        me.Service.msm = 'Consultado...';
                        me.Service.msmclass = 'badge badge-info';
                        me.Service.loadclass = 'spinner-border spinner-border-sm text-primary';
                    },
                    success: function (data, textStatus, jqXHR) {
                        let persona = JSON.parse(data);
                        if (persona.estado == true){
                            me.Service.document = '';
                            me.Service.msm = '';
                            me.Service.msmclass = '';

                            me.Cliente.id = 0;
                            me.Cliente.tipo = 'P';
                            me.Cliente.documento = persona.dni;
                            me.Cliente.nombres = persona.nombres;
                            me.Cliente.apellidos = persona.apellidos;
                        } else {
                            me.Service.msm = 'El DNI no existe';
                            me.Service.msmclass = 'badge badge-primary';
                        }
                        me.Service.loadclass = '';
                    }
                }).fail(function(){
                });
            },
            consultarRUC(){
                let me = this;
                let ruc = me.Service.document;

                $.ajax({
                    type: 'GET',
                    url: "http://localhost:8080/SunatPHP/demo.php",
                    data: "ruc="+ruc,
                    beforeSend(){
                        me.Service.msm = 'Consultado...';
                        me.Service.msmclass = 'badge badge-info';
                        me.Service.loadclass = 'spinner-border spinner-border-sm text-primary';
                    },
                    success: function (data, textStatus, jqXHR) {
                        if (data.RazonSocial){
                            me.Service.document = '';
                            me.Service.msm = '';
                            me.Service.msmclass = '';

                            me.Cliente.id = 0;
                            me.Cliente.tipo = 'E';
                            me.Cliente.documento = data.RUC;
                            me.Cliente.razon_social = data.RazonSocial;
                        } else {
                            me.Service.msm = 'El RUC no existe';
                            me.Service.msmclass = 'badge badge-primary';
                        }
                        me.Service.loadclass = '';
                    }
                }).fail(function(){
                });
            },
            list(numero, data = ''){
                var me = this;
                var url;

                switch (numero) {
                    case 0: 
                        if ( this.Service.text == '' ) break;

                        url = this.Ruta.detalle_producto+'/listProductos?'
                                        +'text='+this.Service.text
                                        +'&centro_id='+$('meta[name="idCentro"]').attr('content');

                        axios.get(url).then(function(response){
                            if ( response.data.length == 1 && me.Service.text == response.data[0].codigo ){
                                if ( response.data[0].detalle.substock > 0 ) me.add(0, response.data[0]);
                                else me.ListaProducto = response.data;
                                me.Service.text = '';
                            } else {
                                me.ListaProducto = response.data;
                            }
                            document.getElementById('filtroProducto').focus();
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                    case 1:
                        url = this.Ruta.detalle_venta+'/list'+data+'?'
                                        +'venta_id='+this.Venta.id;

                        axios.get(url).then(function(response){
                            me.ListaDetalle = response.data;
                            if ( data == 'Editar' ) me.fix(6);
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                    case 2:
                        url = this.Ruta.pago+'/listVenta?'
                                        +'venta_id='+this.Venta.id;

                        axios.get(url).then(function(response){
                            me.ListaPago = response.data;
                        }).catch(function(error){
                            console.log(error);
                        });
                        break;
                }
            },
            add(numero, data = ''){
                switch (numero) {
                    case 0:
                        let found = false;
                        for (let i = 0; i < this.ListaDetalle.length; i++) {
                            if ( this.ListaDetalle[i].detalle_producto_id == data.detalle.id ){
                                if ( this.ListaDetalle[i].cantidad < this.ListaDetalle[i].substock ) this.ListaDetalle[i].cantidad++;
                                found = true; break;
                            }
                        }

                        if ( !found ){
                            this.ListaDetalle.push({
                                id: 0,
                                detalle_producto_id: data.detalle.id,
                                nombre_producto: data.nombre,
                                cantidad: 1,
                                fallidos: 0,
                                cantidad_inicial: 0,
                                substock: data.detalle.substock,
                                precio_menor: data.detalle.precio_menor,
                                precio_mayor: data.detalle.precio_mayor,
                                subtotal: Number.parseFloat(this.Venta.tipo_precio=='1'?data.detalle.precio_menor:data.detalle.precio_mayor).toFixed(2)
                            });
                        } else {
                            this.update(0);
                        }
                        this.update(1);
                        break;
                    case 1:
                        if ( this.validar([4]) ) return;

                        this.ListaPago.push({
                            monto: Number.parseFloat(this.Pago.monto).toFixed(2),
                            created_at: this.get(),
                            color: 'table-success',
                            estado: 1 // 1: nuevo
                        });
                        this.Pago.monto = '';
                        this.update(2);
                        break;
                }
            },
            update(numero){
                switch (numero) {
                    case 0:
                        this.ListaDetalle.forEach(detalle => {
                            detalle.subtotal = (this.Venta.tipo_precio=='1'?detalle.precio_menor:detalle.precio_mayor)*detalle.cantidad;
                        })
                        this.update(1);
                        break;
                    case 1:
                        this.Venta.total = 0;
                        this.ListaDetalle.forEach(detalle => {
                            this.Venta.total += Number.parseFloat(detalle.subtotal);
                        });
                        break;
                    case 2:
                        this.Venta.total_faltante = this.Venta.total;
                        this.ListaPago.forEach( pago => {
                            this.Venta.total_faltante = Number.parseFloat(this.Venta.total_faltante)-Number.parseFloat(pago.monto);
                        });
                        this.Venta.total_faltante = Number.parseFloat(this.Venta.total_faltante).toFixed(2);
                        break;
                    case 3:
                        this.ListaDetalle.forEach(detalle => {
                            detalle.subtotal = (this.Venta.tipo_precio=='1'?detalle.precio_menor:detalle.precio_mayor) * (detalle.cantidad-detalle.fallidos);
                        })
                        this.update(1);
                        this.update(4);
                        break;
                    case 4:
                        if ( this.Venta.total > this.Venta.total_minimo && this.Venta.tipo_pago == '1') {
                            this.Venta.tipo_pago = '2';
                        } 
                        if ( this.Venta.total <= this.Venta.total_minimo && this.Venta.tipo.charAt(0) == '1' ) {
                            this.Venta.tipo_pago = '1';
                        }
                        break;
                }
            },
            remove(numero, data){
                switch (numero) {
                    case 0:
                        this.ListaDetalle.splice(data, 1);
                        this.update(1);
                        break;
                    case 1:
                        this.Cliente.id = null;
                        this.Cliente.documento = '';
                        this.Cliente.nombres = null;
                        this.Cliente.apellidos = null;
                        this.Cliente.dni = null;
                        this.Cliente.razon_social = null;
                        this.Cliente.ruc = null;
                        this.Cliente.tipo = null;
                        break;
                }
            },
            fix(numero, data = ''){
                var fixed;

                let fecha, hora, fecha_fixed, hora_fixed;

                switch (numero) {
                    case 0:
                        fecha = data.split(' ')[0].split('-');
                        hora = data.split(' ')[1].split(':');
                        fecha_fixed = fecha[2]+'-'+fecha[1]+'-'+fecha[0];
                        hora_fixed = (hora[0]>12?(hora[0]-12).toString().padStart(2, '0'):hora[0])+':'+hora[1]+' '+(hora[0]>12?'pm':'am') ;
                        fixed = fecha_fixed+' '+hora_fixed;
                        break;
                    case 1:
                        fixed = (new Date()).getDate();
                        break;
                    case 2:
                        fixed = (new Date()).getMonth()+1;
                        break;
                    case 3:
                        fixed = (new Date()).getFullYear();
                        break;
                    case 4:
                        if ( data == '1' ) fixed = 'Contado'; 
                        if ( data == '2' ) fixed = 'Credito Prepago'; 
                        if ( data == '3' ) fixed = 'Credito Postpago'; 
                        break;
                    case 5:
                        if ( data == '1' ) fixed = 'Al por menor'; 
                        if ( data == '2' ) fixed = 'Al por mayor'; 
                        break;
                    case 6:
                        for (let i = 0; i < this.ListaDetalle.length; i++) {
                            if ( this.ListaDetalle[i].fallidos == null ) this.ListaDetalle[i].fallidos = 0;
                            if ( this.ListaDetalle[i].fallidos_inicial == null ) this.ListaDetalle[i].fallidos_inicial = 0;
                        }
                        break;
                    case 7:
                        fecha = data.split(' ')[0].split('-');
                        fecha_fixed = fecha[2]+'-'+fecha[1]+'-'+fecha[0];
                        fixed = fecha_fixed;
                        break;
                    case 8:
                        hora = data.split(' ')[1].split(':');
                        hora_fixed = (hora[0]>12?(hora[0]-12).toString().padStart(2, '0'):hora[0])+':'+hora[1]+' '+(hora[0]>12?'pm':'am') ;
                        fixed = hora_fixed;
                        break; 
                    case 9:
                        fixed = data.split(' ')[0].split('-')[1];
                        break; 
                    case 10:
                        fixed = data.split(' ')[0].split('-')[0];
                        break; 
                    default:
                        fixed = '';
                        break;
                }

                return fixed;
            },
            get(){
                let n =  new Date();
                let y = n.getFullYear();
                let m = (n.getMonth()+1).toString().padStart(2, 0);
                let d = n.getDate().toString().padStart(2, 0);
                let h = n.getHours();
                let minu = n.getMinutes().toString().padStart(2, 0);
                let seg = n.getSeconds().toString().padStart(2, 0);
                let hoy =  y + '-' + m + '-' + d + ' ' + h + ':' + minu + ':' + seg;
                return hoy;
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

