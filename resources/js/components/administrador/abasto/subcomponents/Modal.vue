<template>
  <div :class="{'show-modal': modal.estado}" class="modal text-gray-900">
    <div
      class="modal-dialog modal-dialog-centered"
      :class="{'animate__animated animate__zoomIn animate__faster': modal.estado, [modal.size]: modal.size}"
    >
      <div class="modal-content">
        <div class="modal-header">
          <h3 v-text="modal.titulo" class="modal-title"></h3>
          <button @click="cerrarModal()" class="close">X</button>
        </div>
        <div class="modal-body">
          <error-modal :error.sync="error"></error-modal>
          <div v-if="modal.tipo == 'agregar'">
            <div class="row">
              <div class="col-md-2 pl-2">
                <p class="font-weight-bold">PROVEEDOR</p>
              </div>
              <div class="col-md-3">
                <div class="input-group">
                  RUC/DNI
                  <span class="text-danger">*</span>
                  <input
                    type="text"
                    class="form-control form-control-sm"
                    v-model="api.documento"
                    @keyup.enter="consultar()"
                  />
                  <button type="button" class="btn btn-sm btn-primary" @click="consultar()">
                    <i class="fas fa-sync-alt"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-3">
                <h5>
                  <span role="status" :class="api.loader.clase"></span>
                  <span v-text="api.alert.mensaje" :class="api.alert.clase"></span>
                </h5>
              </div>
            </div>

            <div v-if="api.tipo == 'persona'">
              <div class="row form-group">
                <div class="col-md-2">
                  <div class="input-group">
                    DNI
                    <input
                      type="text"
                      class="form-control form-control-sm"
                      readonly
                      v-model="proveedor.documento"
                    />
                  </div>
                </div>
                <div class :class="modal.numero == 2 ? 'col-md-3': 'col-md-4'">
                  <div class="input-group">
                    <label>Nombres</label>
                    <input
                      type="text"
                      class="form-control form-control-sm"
                      readonly
                      v-model="proveedor.nombres"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <label>Apellidos</label>
                    <input
                      type="text"
                      class="form-control form-control-sm"
                      readonly
                      v-model="proveedor.apellidos"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div v-else-if="api.tipo == 'empresa'">
              <div class="row form-group">
                <div class="col-md-2">
                  <div class="input-group">
                    RUC
                    <input
                      type="text"
                      class="form-control form-control-sm"
                      readonly
                      v-model="proveedor.documento"
                    />
                  </div>
                </div>
                <div class="col-md-10">
                  <div class="input-group">
                    <label>Razón social</label>
                    <input
                      type="text"
                      class="form-control form-control-sm"
                      v-model="proveedor.razon_social"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div class="row shadow bg-white rounded p-2">
              <div class="col-md-4">
                <div class="row">
                  <p class="font-weight-bold">PRODUCTOS</p>
                </div>
                <div class="row">
                  <div class="input-group">
                    <input
                      type="search"
                      class="form-control form-control-sm"
                      v-model="BusquedaFiltro.texto"
                      @keyup.enter="listarFiltro()"
                      id="filtroProducto"
                      placeholder="Producto,marca,modelo,tamaño,color"
                    />
                    <!-- <button type="button" class="btn btn-sm btn-primary" @click="listarFiltro()">
                                                    <i class="fa fa-search"></i> Buscar
                    </button>-->
                  </div>
                </div>
                <br />
                <div class="row form-group overflow-auto" style="height: 17.5rem;">
                  <div v-if="ListaProducto.length">
                    <table class="table table-borderless table-striped table-sm text-gray-900">
                      <thead>
                        <tr class="table-danger">
                          <th class="text-center" style="width: 3rem;">Agregar</th>
                          <th>Nombre</th>
                          <th>Stock</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="producto in ListaProducto" :key="producto.id">
                          <td class="text-center">
                            <button
                              type="button"
                              title="Editar"
                              class="btn btn-circle btn-sm btn-outline-success"
                              @click="agregarDetalle(producto)"
                            >
                              <i class="fas fa-plus"></i>
                            </button>
                          </td>
                          <td v-text="producto.nombre"></td>
                          <td v-text="producto.stock"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div v-else>
                    <p>No se han encontrado resultados</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="input-group">
                      <label for>Enviar a</label>
                      <span class="text-danger">*</span>
                      <select
                        v-model="abasto.centro_to_id"
                        class="custom-select custom-select-sm text-gray-900"
                      >
                        <option value="0">Seleccione</option>
                        <option
                          v-for="item in SelectAlmacen"
                          :key="item.id"
                          :value="item.id"
                          v-text="item.nombre"
                        ></option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8 ml-auto container">
                <div class="row">
                  <div class="col-md-3 p-0">
                    <p class="font-weight-bold">LISTA DE ITEMS</p>
                  </div>
                  <div class="col-md-6"></div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <label for="tipo" class="font-weight-bold">Tipo</label>
                      <span class="text-danger">*</span>
                      <select
                        v-model="abasto.tipo_abasto"
                        class="custom-select custom-select-sm"
                        id="tipo"
                      >
                        <option value="0">Contado</option>
                        <option value="1">Credito</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row form-group ec-table-modal overflow-auto">
                  <div v-if="ListaDetalleAbasto.length">
                    <table class="table tableless table-striped table-sm text-gray-900">
                      <thead>
                        <tr class="table-success">
                          <th class="text-center" style="width: 3rem;">Quitar</th>
                          <th>Nombre</th>
                          <th style="width: 5rem;">Cant.</th>
                          <th style="width: 5rem;">P. Unit.</th>
                          <th class="text-center">Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(detalle, indice) in ListaDetalleAbasto" :key="detalle.id">
                          <td class="text-center">
                            <button
                              type="button"
                              title="Editar"
                              class="btn btn-circle btn-outline-danger btn-sm"
                              @click="quitarDetalle(indice)"
                            >
                              <i class="fas fa-minus"></i>
                            </button>
                          </td>
                          <td v-text="detalle.nombre"></td>
                          <td>
                            <input
                              type="number"
                              v-model="detalle.cantidad"
                              class="form-control form-control-sm"
                              min="1"
                            />
                          </td>
                          <td>
                            <input
                              type="number"
                              v-model="detalle.costo_abasto"
                              class="form-control form-control-sm"
                              min="0"
                            />
                          </td>
                          <td
                            class="text-right pr-3"
                          >{{detalle.subtotal = (detalle.costo_abasto * detalle.cantidad).toFixed(2)}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div v-else>
                    <br />
                    <p>Sin detalles de abasto</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div
                      class="input-group"
                      style="width: 12.4rem;"
                      v-if="abasto.tipo_abasto == '1'"
                    >
                      <label for>Pago inicial</label>
                      <span class="text-danger">*</span>
                      <input
                        type="number"
                        class="form-control form-control-sm"
                        v-model="abasto.pagoInicial"
                        min="0"
                      />
                    </div>
                  </div>
                  <div class="col-md-3"></div>
                  <div class="col-md-5">
                    <p class="text-right pr-1">Desembolso total: s/ {{getDesembolso}}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>

        <footer-modal
          :modalLoading.sync="modal.loading"
          :btnSuccess.sync="modal.btnSuccess"
          :btnCancel.sync="modal.btnCancel"
          @cerrarModal="cerrarModal"
          @accionar="accionar"
        ></footer-modal>
      </div>
    </div>
  </div>
</template>

<script>
/** import: sweetalert*/
import mainAlert from "../../../globals/Main-alert";

// Components
import errorModal from "../../../globals/Error-modal";
import footerModal from "../../../globals/Footer-modal";

export default {
  components: {
    errorModal,
    footerModal,
  },
  props: {},
  data() {
    return {
      modal: {
        estado: false,
        tipo: "",
        titulo: "",
        size: "",
        loading: false,
        btnSuccess: "",
        btnCancel: "",
      },
      abasto: {
        id: 0,
        nombre: "",
        subtipo: "",
        unidad: "",
        costo: "",
      },
      proveedor: {
        id: 0,
        documento: "",
        nombres: "",
        apellidos: "",
        razon_social: "",
        direccion: "",
      },
      api: {
        documento: "",
        tipo: '', // persona | empresa
        loader: {
          clase:''
        },
        alert: {
          clase: '',
          mensaje: ''
        }
      },
      BusquedaFiltro: {
        texto: "",
      },
      ListaProducto: [],
      ListaDetalleAbasto: [],
      ListaDetalleAbastoVer: [],

      SelectAlmacen: [],

      //datos de errores
      error: {
        estado: 0,
        mensaje: [],
      },
      ruta: {
        abasto: "/abasto",
        persona: '/persona',
        data: "/data",
      },
    };
  },
  computed: {
    getDesembolso: function () {
      this.abasto.total = 0.0;
      this.ListaDetalleAbasto.forEach((detalle) => {
        this.abasto.total =
          this.abasto.total + detalle.costo_abasto * detalle.cantidad;
      });
      return this.abasto.total.toFixed(2);
    },
  },
  watch: {},
  methods: {
    prepararModal({ titulo, size = "", btnSuccess = null, btnCancel = null }) {
      this.modal.titulo = titulo;
      this.modal.size = size;
      this.modal.loading = false;
      this.modal.btnSuccess = btnSuccess;
      this.modal.btnCancel = btnCancel;
    },
    abrirModalAgregar() {
      this.modal.estado = true;
      this.modal.tipo = "agregar";

      this.prepararModal({
        titulo: "Nuevo abasto",
        size: "modal-xl",
        btnSuccess: "Agregar",
        btnCancel: "Cancelar",
      });
    },
    abrirModalEditar({ abasto }) {
      this.modal.estado = true;
      this.modal.tipo = "editar";

      this.prepararModal({
        titulo: "Editar abasto",
        size: "modal-lg",
        btnSuccess: "Editar",
        btnCancel: "Cancelar",
      });

      this.abasto.id = abasto.id;
      this.abasto.nombre = abasto.nombre;
      this.abasto.unidad = abasto.unidad;
      this.abasto.subtipo = abasto.subtipo;
      this.abasto.costo = abasto.costo;
    },
    cerrarModal() {
      this.modal.estado = false;
      this.modal.tipo = "";
      this.modal.titulo = ""
      this.modal.size = "";
      this.modal.loading = false;
      this.modal.btnSuccess = "";
      this.modal.btnCancel = "";

      this.abasto.id = 0;
      this.abasto.nombre = "";
      this.abasto.unidad = "";
      this.abasto.subtipo = "";
      this.abasto.costo = "";

      this.error.estado = 0;
      this.error.mensaje = [];
    },
    accionar() {
      switch (this.modal.tipo) {
        case "agregar": {
          this.agregar();
          break;
        }
        case "editar": {
          this.editar();
          break;
        }
      }
    },
    validar() {
      this.error.estado = 0;
      this.error.mensaje = [];

      if (!this.abasto.nombre)
        this.error.mensaje.push("Debe ingresar un nombre");
      if (!this.abasto.unidad)
        this.error.mensaje.push("Debe seleccionar una Unid. Medida");
      if (this.abasto.costo == 0 || this.abasto.costo < 0)
        this.error.mensaje.push("Debe ingresar un costo válido");

      if (this.error.mensaje.length) {
        this.error.estado = 1;
        this.modal.loading = false;
      }
      return this.error.estado;
    },
    agregar() {
      if (this.validar()) return;
      const abasto = {
        nombre: this.abasto.nombre,
        subtipo: this.abasto.subtipo,
        unidad: this.abasto.unidad,
        costo: this.abasto.costo,
      };
      axios
        .post(this.ruta.abasto + "/agregar", abasto)
        .then((res) => {
          if (res.data.success) {
            this.cerrarModal();
            this.$emit("runParentMethod", "listar");
            mainAlert.fire({
              icon: "success",
              title: "El abasto se ha AGREGADO correctamente",
            });
          } else {
            this.error.mensaje.push(
              `${abasto.nombre} ya se encuentra registrado`
            );
            this.error.estado = 1;
          }
        })
        .catch((error) =>
          console.log(`Error in method agregar() - Modal.vue: "${error}`)
        )
        .then((res) => (this.modal.loading = false));
    },
    editar() {
      if (this.validar()) return;
      const abasto = {
        id: this.abasto.id,
        nombre: this.abasto.nombre,
        subtipo: this.abasto.subtipo,
        unidad: this.abasto.unidad,
        costo: this.abasto.costo,
      };
      axios
        .put(this.ruta.abasto + "/editar", abasto)
        .then((res) => {
          if (res.data.success) {
            this.cerrarModal();
            this.$emit("runParentMethod", "listar");
            mainAlert.fire({
              icon: "success",
              title: "El Material se ha EDITADO correctamente",
            });
          } else {
            this.error.mensaje.push(`${abasto.nombre} ya está registrado`);
            this.error.estado = 1;
          }
        })
        .catch((error) => {
          console.log("Error in method editar() - Modal.vue" + error);
        })
        .then((res) => (this.modal.loading = false));
    },

    consultar() {
      this.api.alert.clase = "";
      this.api.alert.mensaje = "";
      switch (this.api.documento.length) {
        case 0:
          this.api.alert.clase = "badge badge-warning";
          this.api.alert.mensaje = "Ingrese un DNI o RUC";
          break;
        case 8:
        case 11:
          this.consultarDB();
          break;
        default:
          // this.api.alert.clase = 'alert.clase alert.clase-danger';
          this.api.alert.clase = "badge badge-primary";
          this.api.alert.mensaje = "Documento inválido";
          break;
      }
    },
    consultarDB() {
      this.api.alert.clase = "badge badge-info";
      this.api.alert.mensaje = "Consultado...";
      this.api.loader.clase = "spinner-border spinner-border-sm text-success";
      axios
        .get(`${this.ruta.persona}/getPersona?documento=${this.api.documento}`)
        .then((res) => {
          const persona = res.data.persona;
          if (persona != null) {
            //Si existe la persona en la db
            this.api.alert.clase = "";
            this.api.alert.mensaje = "";
            this.api.loader.clase = "";

            this.proveedor.id = persona.id;
            if (persona.razon_social) {//Es una EMPRESA
              this.api.tipo = 'empresa';
              this.proveedor.documento = this.api.documento;
              this.proveedor.razon_social = persona.razon_social;

              this.api.documento = "";
            } else {//Es una PERSONA
              this.api.tipo = 'persona';
              this.proveedor.documento = this.api.documento;
              this.proveedor.nombres = persona.nombres;
              this.proveedor.apellidos = persona.apellidos;

              this.api.documento = "";
            }
          } else {
            //No esxiste la persona en la db
            this.proveedor.id = 0;
            if (this.api.documento.length == 8) {
              // this.consultarDNI();
            } else {
              // this.consultarRUC();
            }
          }
        })
        .catch((error) => console.log(error));
    },
    consultarRUC() {
      let me = this;
      let ruc = me.api.documento;
      $.ajax({
        type: "GET",
        url: me.ruta.serverApache + "/SunatPHP/demo.php",
        data: "ruc=" + ruc,
        beforeSend() {
          me.api.loader.clase = "spinner-border spinner-border-sm text-primary";
          me.api.alert.clase = "badge badge-info";
          me.api.alert.mensaje = "Consultado...";
        },
        success: function (data, textStatus, jqXHR) {
          let empresa = JSON.parse(data);
          if (empresa.RazonSocial) {
            me.api.documento = "";
            me.api.alert.clase = "";
            me.api.alert.mensaje = "";
            me.api.tipo = 'empresa';
            me.proveedor.documento = empresa.RUC;
            me.proveedor.razon_social = empresa.RazonSocial;
            me.proveedor.direccion = empresa.Direccion;
          } else {
            me.api.alert.clase = "badge badge-primary";
            me.api.alert.mensaje = "El RUC no existe";
          }
          me.api.loader.clase = "";
        },
      }).fail(function () {});
    },
    consultarDNI() {
      let me = this;
      let dni = me.api.documento;
      $.ajax({
        type: "POST",
        url: me.ruta.serverApache + "/Reniec/consulta_reniec.php",
        data: "dni=" + dni,
        beforeSend() {
          me.api.loader.clase = "spinner-border spinner-border-sm text-primary";
          me.api.alert.clase = "badge badge-info";
          me.api.alert.mensaje = "Consultado...";
        },
        success: function (data, textStatus, jqXHR) {
          // console.log(JSON.parse(data));
          try {
            let persona = JSON.parse(data);
            if (persona[2] != null) {
              me.api.documento = "";
              me.api.alert.clase = "";
              me.api.alert.mensaje = "";
              me.api.tipo = 'persona';
              me.proveedor.documento = persona[0];
              me.proveedor.nombres = persona[1];
              me.proveedor.apellidos = persona[2] + " " + persona[3];
            } else {
              me.api.alert.clase = "badge badge-primary";
              me.api.alert.mensaje = "El DNI no existe";
            }
            me.api.loader.clase = "";
          } catch (e) {
            me.api.alert.clase = "badge badge-primary";
            me.api.alert.mensaje = "Vuelva a intentarlo porfavor =D";
          }
        },
      }).fail(function () {});
    },

    runChildMethod(method = "", data = {}) {
      switch (method) {
        case "abrirModalAgregar":
          this.abrirModalAgregar();
          break;
        case "abrirModalEditar":
          this.abrirModalEditar(data);
          break;
        default:
          `Option "${method}" don't found in runParentMethod() function in Modal.vue`;
          break;
      }
    },
  },
  mounted() {
    this.$parent.$on("runChildMethod", this.runChildMethod);
  },
};
</script>

<style>
</style>