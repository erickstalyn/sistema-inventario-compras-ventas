<template>
  <div :class="{'show-modal': modal.estado}" class="modal text-gray-900">
    <div :class="{'animate__animated animate__zoomIn animate__faster': modal.estado}" class="modal-dialog modal-dialog-centered">
      <div class="modal-content" :class="modal.size">
        <div class="modal-header">
          <h3 v-text="modal.titulo" class="modal-title"></h3>
          <button type="button" @click="cerrar()" class="close">X</button>
        </div>
        <div class="modal-body">
          <error-modal :error.sync="error"></error-modal>

          <div class="row form-group">
            <label class="col-md-3 font-weight-bold" for="nom">
              Nombre&nbsp;
              <span class="text-danger">*</span>
            </label>
            <div class="col-md-9">
              <input
                type="text"
                v-model="material.nombre"
                class="form-control"
                placeholder="ingrese el nombre"
              />
            </div>
          </div>

          <select-unit
            :initTipo.sync="material.subtipo"
            :initUnit.sync="material.unidad"
            :estadoModal="modal.estado"
          ></select-unit>

          <div class="row form-group">
            <label class="col-6 col-sm-3 font-weight-bold">
              Costo por Unidad
              <span class="text-danger">*</span>
            </label>
            <div class="col-6 col-sm-4">
              <input type="number" v-model="material.costo" min="1" class="form-control text-right" />
            </div>
          </div>
        </div>
        <!-- <div class="modal-footer">
          <div class="row form-group col-md-12 d-flex justify-content-around">
            <load-button @confirm-button="accionar" :btnCharge.sync="btnCharge"></load-button>
            <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cancelar</button>
          </div>
        </div>-->
        <div class="modal-footer justify-content-around">
          <div v-if="modal.btnSuccess!=null">
            <button
              class="btn btn-success"
              @click="accionar()"
              :disabled="modal.loading"
            >
              <span v-if="!modal.loading" class="" v-text="modal.btnSuccess"></span>
              <span v-else class="fas fa-spinner fa-spin fa-lg" role="status" aria-hidden="true"></span>
            </button>
          </div>
          <div v-if="modal.btnCancel!=null" >
            <button class="btn btn-secondary" @click="cerrarModal()">
              <span class="" v-text="modal.btnCancel"></span>
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
/** import: sweetalert*/
import mainAlert from "../../../globals/Main-alert";

// Components
import loadButton from "./Load-button";
import selectUnit from "./Select-unit";
import errorModal from "../../../globals/Error-modal";

export default {
  components: {
    loadButton,
    selectUnit,
    errorModal,
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
      initMaterial: {
        id: 0,
        nombre: "",
        subtipo: "",
        unidad: "",
        costo: "",
      },
      material: {
        id: 0,
        nombre: "",
        subtipo: "",
        unidad: "",
        costo: "",
      },
      btnCharge: {
        title: "",
        isPress: false,
      },
      //datos de errores
      error: {
        estado: 0,
        mensaje: [],
      },
      ruta: {
        material: "/material",
        data: "/data",
      },
    };
  },
  computed: {
  },
  watch: {},
  methods: {
    abrirModal({ modal, material }) {
      this.modal.estado = true;
      this.modal.tipo = modal.tipo;
      switch (this.modal.tipo) {
        case "agregar":
          this.abrirModalAgregar();
          break;
        case "editar":
          this.abrirModalEditar(material);
        default:
          console.error(
            'Tried to open a diferent modal type. (type = "' + modal.tipo + '")'
          );
          break;
      }
    },
    prepararModal(modal = {}) {
      this.modal.titulo = modal.titulo;
      this.modal.size = modal.size != undefined ? modal.size : "";
      this.modal.loading = false;
      this.modal.btnSuccess =
        modal.btnSuccess != undefined ? modal.btnSuccess : null;
      this.modal.btnCancel =
        modal.btnCancel != undefined ? modal.btnCancel : null;
    },
    abrirModalAgregar() {
      this.prepararModal({
        titulo: "Nuevo material",
        size: "modal-lg",
        btnSuccess: "Agregar",
        btnCancel: "Cancelar",
      });
      this.btnCharge.title = "Agregar";
      this.btnCharge.isPress = false;
    },
    abrirModalEditar(material) {
      this.prepararModal({
        titulo: "Editar material",
        size: "modal-lg",
        btnSuccess: "Editar",
        btnCancel: "Cancelar",
      });
      this.initMaterial.id = material.id;
      this.initMaterial.nombre = material.nombre;
      this.initMaterial.unidad = material.unidad;
      this.initMaterial.subtipo = material.subtipo;
      this.initMaterial.costo = material.costo;

      this.material.id = material.id;
      this.material.nombre = material.nombre;
      this.material.unidad = material.unidad;
      this.material.subtipo = material.subtipo;
      this.material.costo = material.costo;

      this.btnCharge.title = "Editar";
      this.btnCharge.isPress = false;
    },
    cerrarModal() {
      this.modal.estado = false;
      this.modal.tipo = "";
      this.$emit("clearMaterial");
      //TODO: Hacer que el componente Select-unit tenga su propio ESTADO

      this.material.id = 0;
      this.material.nombre = "";
      this.material.unidad = "";
      this.material.subtipo = "";
      this.material.costo = "";

      this.error.estado = 0;
      this.error.mensaje = [];
    },
    accionar() {
      this.modal.loading = true;
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

      if (!this.material.nombre)
        this.error.mensaje.push("Debe ingresar un nombre");
      if (!this.material.unidad)
        this.error.mensaje.push("Debe seleccionar una Unid. Medida");
      if (this.material.costo == 0 || this.material.costo < 0)
        this.error.mensaje.push("Debe ingresar un costo válido");

      if (this.error.mensaje.length) {
        this.error.estado = 1;

        //TODO:Tengo que corregir lo del btnCharge
        this.btnCharge.isPress = false;
        if (this.modal.tipo == "agregar") this.btnCharge.title = "Agregar";
        if (this.modal.tipo == "editar") this.btnCharge.title = "Editar";
      }
      return this.error.estado;
    },
    agregar() {
      if (this.validar()) return;
      const material = {
        nombre: this.material.nombre,
        subtipo: this.material.subtipo,
        unidad: this.material.unidad,
        costo: this.material.costo,
      };
      const me = this;
      axios
        .post(this.ruta.material + "/agregar", material)
        .then(function (res) {
          if (res.data.estado) {
            me.cerrar();
            me.$emit("list"); //emito el evento de listar al PADRE
            mainAlert.fire({
              icon: "success",
              title: "El material se ha AGREGADO correctamente",
            });
          } else {
            me.error.mensaje.push(
              `${material.nombre} ya se encuentra registrado`
            );
            me.error.estado = 1;
          }
        })
        .catch(function (error) {
          console.log("Error in method agregar() - Modal.vue" + error);
        })
        .then((res) => (this.btnCharge.isPress = false));
    },
    editar() {
      if (this.validar()) return;
      if (compareObj(this.initMaterial, this.material)) {
        console.log("No ha realizado ninguna modificación");
        this.cerrar();
        mainAlert.fire({
          icon: "success",
          title: "El Material se ha EDITADO correctamente",
        });
        return;
      }
      const material = {
        id: this.material.id,
        nombre: this.material.nombre,
        subtipo: this.material.subtipo,
        unidad: this.material.unidad,
        costo: this.material.costo,
      };
      const me = this;
      axios
        .put(me.ruta.material + "/editar", material)
        .then(function (response) {
          if (response.data.estado) {
            me.cerrar();
            me.$emit("list");
            mainAlert.fire({
              icon: "success",
              title: "El Material se ha EDITADO correctamente",
            });
          } else {
            me.error.mensaje.push(`${material.nombre} ya está registrado`);
            me.error.estado = 1;
          }
        })
        .catch(function (error) {
          console.log("Error in method editar() - Modal.vue" + error);
        });
    },
  },
  mounted() {
    this.$parent.$on("abrir-modal", this.abrirModal);
  },
};
</script>

<style>
</style>