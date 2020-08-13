<template>
  <main>
    <!-- Interfaz Principal -->
    <div class="container-small">
      <!-- Encabezado principal -->
      <div class="row form-group">
        <div class="col-12 col-sm-6 col-md-5 mb-2">
          <i class="fas fa-map-signs mr-1"></i>
          <span class="h3 mb-0 text-gray-900">Abastecimientos</span>
        </div>
        <div class="col-12 col-sm-6 col-md-7">
          <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
            <i class="fas fa-hammer mr-2"></i>Nuevo
          </button>
          <button type="button" class="btn btn-danger" @click="generatePdf()">
            <i class="far fa-file-pdf mr-2"></i>PDF
          </button>
        </div>
      </div>

      <!-- Inputs de busqueda -->
      <div class="row mb-3">
        <div class="col-12 col-sm-4 mb-1">
          <div class="input-group">
            <select
              class="custom-select text-gray-900"
              v-model="busqueda.estado"
              @change="listar()"
            >
              <option value="3">Todos</option>
              <option value="0">Enviados</option>
              <option value="1">Recibidos</option>
            </select>
          </div>
        </div>
        <div class="col-12 col-sm-8">
          <div class="input-group">
            <input
              type="search"
              class="form-control"
              v-model="busqueda.texto"
              placeholder="Buscar por proveedor"
              @keyup.enter="listar()"
            />
          </div>
        </div>

        <div class="mb-2 col-4 col-md-2 col-lg-2 col-xl-2">
          <div class="form-inline">
            <label class="mr-2">Día</label>
            <select
              class="custom-select custom-select-sm text-gray-900"
              v-model="busqueda.dia"
              @change="listar()"
            >
              <option value>Todos</option>
              <option v-for="item in getDia()" :key="item" :value="item" v-text="item"></option>
            </select>
          </div>
        </div>
        <div class="mb-2 col-4 col-md-3 col-lg-3 col-xl-2">
          <div class="form-inline">
            <label class="mr-2">Mes</label>
            <select
              class="custom-select custom-select-sm text-gray-900"
              v-model="busqueda.mes"
              @change="listar()"
            >
              <option value>Todos</option>
              <option
                v-for="item in getMes()"
                :key="item.valor"
                :value="item.valor"
                v-text="item.nombre"
              ></option>
            </select>
          </div>
        </div>
        <div class="mb-2 col-4 col-md-2 col-lg-2 col-xl-2">
          <div class="form-inline">
            <label class="mr-2">Año</label>
            <select
              class="custom-select custom-select-sm text-gray-900"
              v-model="busqueda.year"
              @change="listar()"
            >
              <option value>Todos</option>
              <option v-for="item in getYear(2016)" :key="item" :value="item" v-text="item"></option>
            </select>
          </div>
        </div>

        <div class="col-4 col-sm-4 col-md-2 col-lg-3 col-xl-2" >
          <div class="form-inline">
            <label class="mr-2">N° filas</label>
            <select
              class="custom-select custom-select-sm mr-sm-2 text-gray-900"
              v-model="busqueda.filas"
              @change="listar()"
            >
              <option
                v-for="item in [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17]"
                :key="item"
                :value="item"
                v-text="item"
              ></option>
            </select>
          </div>
        </div>
        <div class="col-8 col-sm-4 col-md-3 col-lg-2 col-xl-2 d-flex justify-content-center align-items-center">
          <button type="button" class="btn btn-primary" @click="listar()">
            <i class="fa fa-search mr-1"></i>Buscar
          </button>
        </div>

      </div>
      
    </div>

    <my-table
      :listaAbasto="listaAbasto"
      :paginacion="paginacion"
    ></my-table>
		
    <my-modal ></my-modal>

  </main>
</template>
<script>
/** Components */
import myTable from "./subcomponents/Table";
import myModal from "./subcomponents/modal";

export default {
  components: {
    myTable,
    myModal
  },
  data() {
    return {
      //datos generales
      listaAbasto: [],
      abasto: {
        id: 0,
        total: 0.0,
        total_ver: 0.0,
        total_faltante: 0.0,
        tipo_abasto: 1, // 0: Contado, 1: Credito
        centro_to_id: 0, //Almacén donde se enviará el abasto
        created_at: "",
        pagoInicial: "",
        nombre_centro: "", //Nombre del centro al que se envia
        estado_envio: -1,
      },
      // DatosProveedor: {
      //   id: 0,
      //   documento: "",
      //   nombres: "",
      //   apellidos: "",
      //   razon_social: "",
      //   direccion: "",
      // },
      //datos de busqueda y filtracion general
      busqueda: {
        texto: "",
        estado: 3,
        filas: 5,
        dia: "",
        mes: this.getMesActual(),
        year: this.getYearActual(),
      },

      //datos de modales
      Modal: {
        numero: 0, // 1: Agregar, 2: Ver, 3: Pagar
        estado: 0,
        titulo: "",
        accion: "",
        cancelar: "",
        size: "",
      },

      //datos de paginacion
      paginacion: {
        total: 0,
        currentPage: 0,
        lastPage: 0,
        perPage: 0,
        firstItem: 0,
        lastItem: 0,
      },

      //datos de errores
      Error: {
        estado: 0,
        mensaje: [],
      },
      Carga: {
        clase: "",
      },
      //Boton de accion
      Button: {
        press: false,
      },
      //DATOS PARA AGREGAR UNA PRODUCCION
      // BusquedaFiltro: {
      //   texto: "",
      // },
      ListaProducto: [],
      ListaDetalleAbasto: [],
      ListaDetalleAbastoVer: [],
      //DATOS PARA ENVIAR UNA PRODUCCION
      SelectAlmacen: [],
      //DATOS PARA CONSULTA SUNAT Y RENIEC
      // DatosServicio: {
      //   documento: "",
      //   tipo: 0, //1->PERSONA, 2-> EMPRESA
      //   mensaje: "",
      //   alert: "",
      //   readonly: false,
      // },
      ListaPago: [],
      Pago: {
        monto: "",
        sumaPagos: 0.0,
      },
      ruta: {
				abasto: '/abasto',
        serverApache: "http://localhost:80",
        serverPhp: "http://127.0.0.1:8000",
      },
    };
  },
  computed: {
    // permisoModalFooter: function () {
    //   if (this.Modal.numero == 1) return true;
    //   if (this.Modal.numero == 2) return true;
    //   if (this.Modal.numero == 3) return true;

    //   return false;
    // },
    getDesembolso: function () {
      this.abasto.total = 0.0;
      this.ListaDetalleAbasto.forEach((detalle) => {
        this.abasto.total =
          this.abasto.total + detalle.costo_abasto * detalle.cantidad;
      });
      return this.abasto.total.toFixed(2);
    },
    getSumaPagos: function () {
      let sumaPagos = 0.0;
      this.ListaPago.forEach((detalle) => {
        sumaPagos = sumaPagos + Number.parseFloat(detalle.monto);
      });
      return sumaPagos.toFixed(2);
    },
  },
  methods: {
    listar({page = 1} = {}) {
      this.paginacion.currentPage = page;
      const url =
        this.ruta.abasto + "?page=" +
        this.paginacion.currentPage +
        "&estado=" +
        this.busqueda.estado +
        "&texto=" +
        this.busqueda.texto +
        "&dia=" +
        this.busqueda.dia +
        "&mes=" +
        this.busqueda.mes +
        "&year=" +
        this.busqueda.year;

      axios
        .get(url)
        .then((res) => {
          this.listaAbasto = res.data.abastos.data;
          this.paginacion = res.data.paginacion;
        })
        .catch(error => console.log(`Error: "${error}" in method listar() in abasto.vue`));
    },
    formatearFecha(fecha) {
      let arrayFecha = fecha.split(" ")[0].split("-");
      let fecha_fixed =
        arrayFecha[2] + "/" + arrayFecha[1] + "/" + arrayFecha[0];
      return fecha_fixed;
    },
    listarFiltro() {
      if (this.BusquedaFiltro.texto != "") {
        let me = this;
        let url =
          "/producto/getProductoFiltrado?texto=" + this.BusquedaFiltro.texto;
        axios
          .get(url)
          .then(function (response) {
            if (
              response.data.productos.length == 1 &&
              me.BusquedaFiltro.texto == response.data.productos[0].codigo
            ) {
              me.agregarDetalle(response.data.productos[0]);
              me.BusquedaFiltro.texto = "";
            } else {
              me.ListaProducto = response.data.productos;
            }
            let inputFiltro = document.getElementById("filtroProducto");
            inputFiltro.focus();
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    },
    agregarDetalle(producto) {
      //Verifico si el producto ya esta en la lista de detalle
      let incluido = false;
      for (let i = 0; i < this.ListaDetalleAbasto.length; i++) {
        if (this.ListaDetalleAbasto[i].id == producto.id) {
          incluido = true;
          this.ListaDetalleAbasto[i].cantidad++;
          break;
        }
      }

      if (!incluido) {
        let elProducto = {
          id: producto.id,
          nombre: producto.nombre,
          cantidad: 1,
          costo_abasto: 0.0,
          subtotal: 0.0,
        };
        this.ListaDetalleAbasto.push(elProducto);
      }
    },
    quitarDetalle(indice) {
      this.ListaDetalleAbasto.splice(indice, 1);
    },
    consultar() {
      this.DatosServicio.alert = "";
      this.DatosServicio.mensaje = "";
      switch (this.DatosServicio.documento.length) {
        case 0:
          this.DatosServicio.alert = "badge badge-warning";
          this.DatosServicio.mensaje = "Ingrese un DNI o RUC";
          break;
        case 8:
        case 11:
          this.consultarDB();
          break;
        default:
          // this.DatosServicio.alert = 'alert alert-danger';
          this.DatosServicio.alert = "badge badge-primary";
          this.DatosServicio.mensaje = "Documento inválido";
          break;
      }
    },
    consultarDB() {
      var me = this;
      var url = "/persona/getPersona";

      me.DatosServicio.alert = "badge badge-info";
      me.DatosServicio.mensaje = "Consultado...";
      me.Carga.clase = "spinner-border spinner-border-sm text-success";
      axios
        .get(url, {
          params: {
            documento: me.DatosServicio.documento,
          },
        })
        .then(function (response) {
          if (response.data.persona.length) {
            //Si existe la persona en la db
            me.DatosServicio.alert = "";
            me.DatosServicio.mensaje = "";
            me.Carga.clase = "";

            const persona = response.data.persona[0];
            me.DatosProveedor.id = persona.id;
            if (persona.razon_social) {
              //Es una EMPRESA
              me.DatosServicio.tipo = 2;
              me.DatosServicio.readonly = true;
              me.DatosProveedor.documento = me.DatosServicio.documento;
              me.DatosProveedor.razon_social = persona.razon_social;

              me.DatosServicio.documento = "";
            } else {
              //Es una PERSONA
              me.DatosServicio.tipo = 1;
              me.DatosProveedor.documento = me.DatosServicio.documento;
              me.DatosProveedor.nombres = persona.nombres;
              me.DatosProveedor.apellidos = persona.apellidos;

              me.DatosServicio.documento = "";
            }
          } else {
            //No esxiste la persona en la db
            me.DatosProveedor.id = 0;
            if (me.DatosServicio.documento.length == 8) {
              me.consultarDNI();
            } else {
              me.consultarRUC();
            }
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    consultarRUC() {
      let me = this;
      let ruc = me.DatosServicio.documento;
      $.ajax({
        type: "GET",
        url: me.ruta.serverApache + "/SunatPHP/demo.php",
        data: "ruc=" + ruc,
        beforeSend() {
          me.Carga.clase = "spinner-border spinner-border-sm text-primary";
          me.DatosServicio.alert = "badge badge-info";
          me.DatosServicio.mensaje = "Consultado...";
        },
        success: function (data, textStatus, jqXHR) {
          let empresa = JSON.parse(data);
          if (empresa.RazonSocial) {
            me.DatosServicio.documento = "";
            me.DatosServicio.alert = "";
            me.DatosServicio.mensaje = "";
            me.DatosServicio.tipo = 2;
            me.DatosServicio.readonly = false;
            me.DatosProveedor.documento = empresa.RUC;
            me.DatosProveedor.razon_social = empresa.RazonSocial;
            me.DatosProveedor.direccion = empresa.Direccion;
          } else {
            me.DatosServicio.alert = "badge badge-primary";
            me.DatosServicio.mensaje = "El RUC no existe";
          }
          me.Carga.clase = "";
        },
      }).fail(function () {});
    },
    consultarDNI() {
      let me = this;
      let dni = me.DatosServicio.documento;
      $.ajax({
        type: "POST",
        url: me.ruta.serverApache + "/Reniec/consulta_reniec.php",
        data: "dni=" + dni,
        beforeSend() {
          me.Carga.clase = "spinner-border spinner-border-sm text-primary";
          me.DatosServicio.alert = "badge badge-info";
          me.DatosServicio.mensaje = "Consultado...";
        },
        success: function (data, textStatus, jqXHR) {
          // console.log(JSON.parse(data));
          try {
            let persona = JSON.parse(data);
            if (persona[2] != null) {
              me.DatosServicio.documento = "";
              me.DatosServicio.alert = "";
              me.DatosServicio.mensaje = "";
              me.DatosServicio.tipo = 1;
              me.DatosProveedor.documento = persona[0];
              me.DatosProveedor.nombres = persona[1];
              me.DatosProveedor.apellidos = persona[2] + " " + persona[3];
            } else {
              me.DatosServicio.alert = "badge badge-primary";
              me.DatosServicio.mensaje = "El DNI no existe";
            }
            me.Carga.clase = "";
          } catch (e) {
            me.DatosServicio.alert = "badge badge-primary";
            me.DatosServicio.mensaje = "Vuelva a intentarlo porfavor =D";
          }
        },
      }).fail(function () {});
    },
    abrirModalAgregar() {
      // if (!this.SelectAlmacen.length) this.selectAlmacen();
      const data = {
        modal: {
          tipo: "agregar",
        },
        material: {},
      };
      this.$emit('runChildMethod', "abrirModalAgregar", data);
    },
    abrirModalVer(abasto = []) {
      if (abasto["dni"]) {
        this.DatosProveedor.documento = abasto["dni"];
        this.DatosProveedor.nombres = abasto["proveedor_persona"];
      } else {
        this.DatosProveedor.documento = abasto["ruc"];
        this.DatosProveedor.razon_social = abasto["proveedor_empresa"];
      }
      this.abasto.id = abasto["id"];
      this.abasto.nombre_centro = abasto["nombre_centro"];
      this.abasto.estado_envio = abasto["estado_envio"];
      this.abasto.tipo_abasto = abasto["tipo_abasto"];
      this.abasto.total_ver = abasto["total"];

      this.listarPagos(abasto["id"]);
      this.listarDetallesAbasto(abasto["id"]);
      this.abrirModal(
        2,
        "Ver abasto",
        "Generar comprobante",
        "Cerrar",
        "modal-xl modal-dialog-scrollable"
      );
    },
    abrirModal(numero, titulo, accion, cancelar, size) {
      this.Modal.estado = 1;
      this.Modal.numero = numero;
      this.Modal.titulo = titulo;
      this.Modal.accion = accion;
      this.Modal.cancelar = cancelar;
      this.Modal.size = size;
    },
    accionar(accion) {
      this.Button.press = true;
      switch (accion) {
        case "Agregar":
          this.agregar();
          break;
        case "Guardar":
          this.agregarPago();
          break;
        case "Generar comprobante":
          this.generatePdfSpecific();
          break;
      }
    },
    validar() {
      this.Error.estado = 0;
      this.Error.mensaje = [];

      //Recorrere la lista de Material
      if (this.Modal.numero == 1) {
        //Modal agregar
        if (this.DatosProveedor.documento == "")
          this.Error.mensaje.push("Debe ingresar datos del proveedor");
        if (!this.ListaDetalleAbasto.length) {
          this.Error.mensaje.push("No existe ningun detalle de abasto");
        } else if (this.abasto.centro_to_id == 0) {
          this.Error.mensaje.push("Debe seleccionar el almacén receptor");
        } else {
          this.validarNegativos();
        }

        if (this.abasto.tipo_abasto == 1) {
          if (this.abasto.pagoInicial < 0 || this.abasto.pagoInicial == "") {
            this.Error.mensaje.push(
              "El pago inicial debe ser mayor o igual a 0"
            );
          } else if (this.abasto.pagoInicial > this.abasto.total) {
            this.Error.mensaje.push(
              "El pago inicial no debe ser mayor al desembolso total"
            );
          } else if (this.abasto.pagoInicial == this.abasto.total) {
            this.Error.mensaje.push(
              "El pago inicial es igual al desembolso total, se recomienda cambiarlo a una venta al contado"
            );
          }
        }
      } else {
        //Modal editar
        if (
          this.Pago.monto == "" ||
          this.Pago.monto <= 0 ||
          this.Pago.monto > this.abasto.total_ver - this.getSumaPagos
        )
          this.Error.mensaje.push("Debe ingresar un monto válido");
      }
      if (this.Error.mensaje.length) {
        this.Error.estado = 1;
        this.Button.press = false;
      }
      return this.Error.estado;
    },
    validarNegativos() {
      for (let i = 0; i < this.ListaDetalleAbasto.length; i++) {
        const detalle = this.ListaDetalleAbasto[i];
        if (detalle.cantidad < 1) {
          this.Error.mensaje.push(
            "Las cantidades de los detalles deben ser mayores o iguales a 1"
          );
          break;
        } else if (detalle.costo_abasto <= 0) {
          this.Error.mensaje.push(
            "Los precios de los detalles debe ser mayores a 0"
          );
          break;
        }
      }
    },
    agregar() {
      if (this.validar()) return;

      var me = this;
      axios
        .post("/abasto/agregar", {
          //Datos del abasto
          id: this.abasto.id,
          total: this.abasto.total,
          tipo: this.abasto.tipo_abasto,
          centro_to_id: this.abasto.centro_to_id,
          pagoInicial: this.abasto.pagoInicial,
          proveedor: this.DatosProveedor,
          //Datos del detalle de abasto
          listaDetalleAbasto: this.ListaDetalleAbasto,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listar();
          Swal.fire({
            position: "top-end",
            toast: true,
            type: "success",
            title: "El abasto se ha REGISTRADO correctamente",
            showConfirmButton: false,
            timer: 4500,
            animation: false,
            customClass: {
              popup: "animated bounceIn fast",
            },
          });
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    listarPagos(id) {
      let me = this;
      let url = "/abasto/getPagos";

      axios
        .get(url, {
          params: {
            id: id,
          },
        })
        .then(function (response) {
          me.ListaPago = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    listarDetallesAbasto(id) {
      let me = this;
      let url = "/abasto/getDetalles";

      axios
        .get(url, {
          params: {
            id: id,
          },
        })
        .then(function (response) {
          me.ListaDetalleAbastoVer = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    abrirModalPagar(abasto = []) {
      this.abasto.id = abasto["id"];
      this.abasto.total_ver = abasto["total"];
      this.listarPagos(abasto["id"]);

      this.abrirModal(3, "Realizar Pago", "Guardar", "Cancelar", "");
    },
    agregarListaPago() {
      //Agrega pagos a la lista de pagos
      if (this.validar()) return;
      let pago = {
        monto: Number.parseFloat(this.Pago.monto).toFixed(2),
        created_at: this.getFechaHoraHoy(),
        color: "table-success",
        estado: 1, // 1: nuevo
      };
      this.ListaPago.push(pago);
      this.Pago.monto = "";
    },
    agregarPago() {
      let me = this;
      let pagosNuevos = me.filtrarPagosNuevos();
      axios
        .post("/pago/agregar", {
          idAbasto: this.abasto.id,
          listaPagos: pagosNuevos,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listar();
          Swal.fire({
            position: "top-end",
            toast: true,
            type: "success",
            title: "El pago se ha REGISTRADO correctamente",
            showConfirmButton: false,
            timer: 4500,
            animation: false,
            customClass: {
              popup: "animated bounceIn fast",
            },
          });
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filtrarPagosNuevos() {
      let pagosNuevos = [];
      this.ListaPago.forEach((pago) => {
        if (pago.estado) pagosNuevos.push(pago);
      });
      return pagosNuevos;
    },
    cerrarModal() {
      this.Modal.numero = 0;
      this.Modal.estado = 0;
      this.Modal.mensaje = [];

      this.Error.estado = 0;
      this.Error.mensaje = [];

      this.DatosServicio.documento = "";
      this.DatosServicio.alert = "";
      this.DatosServicio.mensaje = "";

      this.abasto.id = 0;
      this.abasto.total = 0.0;
      this.abasto.pagoInicial = "";
      this.abasto.centro_to_id = 0;
      this.abasto.tipo_abasto = 1;

      this.DatosServicio.tipo = 0;
      this.DatosProveedor.id = 0;
      this.DatosProveedor.documento = "";
      this.DatosProveedor.nombres = "";
      this.DatosProveedor.apellidos = "";
      this.DatosProveedor.razon_social = "";

      this.ListaPago = [];
      this.Pago.monto = "";

      this.ListaDetalleAbasto = [];
      this.ListaDetalleAbastoVer = [];
      this.BusquedaFiltro.texto = "";
      this.Button.press = false;
    },
    anularAbasto(id) {
      Swal.fire({
        title: "¿Está seguro que desea ANULAR el abasto?",
        type: "error",
        showCancelButton: true,
        confirmButtonText: "Si, anular",
        cancelButtonText: "Cancelar",
        // reverseButtons: true,
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-secondary",
        },
        buttonsStyling: false,
      }).then((result) => {
        if (result.value) {
          var me = this;

          axios
            .put("/abasto/anular", {
              id: id,
            })
            .then(function (response) {
              me.listar();
              Swal.fire({
                position: "top-end",
                toast: true,
                type: "success",
                title: "El abasto se ANULÓ correctamente",
                showConfirmButton: false,
                timer: 4500,
                animation: false,
                customClass: {
                  popup: "animated bounceIn fast",
                },
              });
            })
            .catch(function (error) {
              console.log(error);
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
        }
      });
    },
    cambiarPagina(page) {
      if (page >= 1 && page <= this.paginacion.lastPage) {
        this.listar(page);
      }
    },
    getFechaHoraHoy() {
      let n = new Date();
      let y = n.getFullYear();
      let m = this.addCero(n.getMonth() + 1);
      let d = this.addCero(n.getDate());
      let h = n.getHours();
      let minu = n.getMinutes();
      let seg = n.getSeconds();
      let hoy =
        y +
        "-" +
        m +
        "-" +
        d +
        " " +
        h +
        ":" +
        this.addCero(minu) +
        ":" +
        this.addCero(seg);
      return hoy;
    },
    addCero(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    },
    getDia() {
      let min = 1;
      let max = 31;
      let lista = [];
      while (min <= max) {
        lista.push(this.addCero(min));
        min++;
      }
      return lista;
    },
    getMes() {
      let nombres = [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Setiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
      ];
      let min = 1;
      let max = 12;
      let lista = [];
      while (min <= max) {
        lista.push({ nombre: nombres[min - 1], valor: this.addCero(min) });
        min++;
      }
      return lista;
    },
    getYear(min) {
      let n = new Date();
      let max = n.getFullYear();
      let lista = [];
      while (min <= max) {
        lista.push(min);
        min++;
      }
      return lista;
    },
    getMesActual() {
      let n = new Date();
      let mes = this.addCero(n.getMonth() + 1);
      return mes;
    },
    getYearActual() {
      let n = new Date();
      let year = n.getFullYear();
      return year;
    },
    //Metodos de envios
    selectAlmacen() {
      var me = this;
      var url = "/centro/selectCentro?tipo=A";

      axios
        .get(url)
        .then(function (response) {
          me.SelectAlmacen = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    fix(numero, data = "") {
      var fixed;

      switch (numero) {
        case 0:
          let fecha = data.split(" ")[0].split("-");
          let hora = data.split(" ")[1].split(":");
          let fecha_fixed = fecha[2] + "-" + fecha[1] + "-" + fecha[0];
          let hora_fixed =
            (hora[0] > 12
              ? (hora[0] - 12).toString().padStart(2, "0")
              : hora[0]) +
            ":" +
            hora[1] +
            ":" +
            hora[2] +
            " " +
            (hora[0] > 12 ? "pm" : "am");
          fixed = fecha_fixed + " " + hora_fixed;
          break;
      }
      return fixed;
    },
    generatePdf() {
      window.open(this.ruta.serverPhp + "/abasto/generatePdf", "_blank");
    },
    generatePdfSpecific() {
      window.open(
        this.ruta.serverPhp +
          "/abasto/generatePdfSpecific?code=" +
          this.abasto.id,
        "_blank"
      );
      this.Button.press = false;
    },
  },
  mounted() {
    this.listar();
  },
};
</script>

<style>
</style>

