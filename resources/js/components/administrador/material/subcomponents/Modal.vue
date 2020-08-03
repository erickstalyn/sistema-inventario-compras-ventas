<template>
  <div :class="{'show-modal': modal.estado}" class="modal text-gray-900">
    <div
      :class="{'animate__animated animate__zoomIn animate__faster': modal.estado}"
      class="modal-dialog modal-dialog-centered"
    >
      <div class="modal-content" :class="modal.size">
        <div class="modal-header">
          <h3 v-text="modal.titulo" class="modal-title"></h3>
          <button @click="cerrarModal()" class="close">X</button>
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

          <formSelectUnit
            :initTipo.sync="material.subtipo"
            :initUnit.sync="material.unidad"
            :estadoModal="modal.estado"
          ></formSelectUnit>

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

        <footer-modal
          :modalLoading.sync="modal.loading"
          :btnSuccess.sync="modal.btnSuccess"
          :btnCancel.sync="modal.btnCancel"
          @cerrar-modal="cerrarModal"
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
import formSelectUnit from "./Modal-FormSelectUnit";
import footerModal from "../../../globals/Footer-modal";

export default {
  components: {
    errorModal,
    formSelectUnit,
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
  computed: {},
  watch: {},
  methods: {
    prepararModal({titulo, size = '', btnSuccess = null, btnCancel = null}) {
      this.modal.titulo = titulo;
      this.modal.size = size;
      this.modal.loading = false;
      this.modal.btnSuccess = btnSuccess;
      this.modal.btnCancel = btnCancel;
    },
    abrirModalAgregar() {
      this.modal.estado = true;
      this.modal.tipo = 'agregar';

      this.prepararModal({
        titulo: "Nuevo material",
        size: "modal-lg",
        btnSuccess: "Agregar",
        btnCancel: "Cancelar",
      });
    },
    abrirModalEditar({material}) {
      this.modal.estado = true;
      this.modal.tipo = 'editar';

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
    },
    cerrarModal() {

      this.modal.estado = false;
      this.modal.tipo = "";

      this.initMaterial.id = 0;
      this.initMaterial.nombre = '';
      this.initMaterial.unidad = '';
      this.initMaterial.subtipo = '';
      this.initMaterial.costo = '';

      this.material.id = 0;
      this.material.nombre = "";
      this.material.unidad = "";
      this.material.subtipo = "";
      this.material.costo = "";

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

      if (!this.material.nombre)
        this.error.mensaje.push("Debe ingresar un nombre");
      if (!this.material.unidad)
        this.error.mensaje.push("Debe seleccionar una Unid. Medida");
      if (this.material.costo == 0 || this.material.costo < 0)
        this.error.mensaje.push("Debe ingresar un costo válido");

      if (this.error.mensaje.length) {
        this.error.estado = 1;
        this.modal.loading = false;
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
      axios
        .post(this.ruta.material + "/agregar", material)
        .then((res) => {
          if (res.data.success) {
            this.cerrarModal();
            this.$emit('runParentMethod', "listar");
            mainAlert.fire({
              icon: "success",
              title: "El material se ha AGREGADO correctamente",
            });
          } else {
            this.error.mensaje.push(
              `${material.nombre} ya se encuentra registrado`
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
      if (compareObj(this.initMaterial, this.material)) {
        console.log("No ha realizado ninguna modificación");
        this.cerrarModal();
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
      axios
        .put(this.ruta.material + "/editar", material)
        .then((res) => {
          if (res.data.success) {
            this.cerrarModal();
            this.$emit('runParentMethod', "listar");
            mainAlert.fire({
              icon: "success",
              title: "El Material se ha EDITADO correctamente",
            });
          } else {
            this.error.mensaje.push(`${material.nombre} ya está registrado`);
            this.error.estado = 1;
          }
        })
        .catch((error) => {
          console.log("Error in method editar() - Modal.vue" + error);
        })
        .then((res) => (this.modal.loading = false));
    },
    runChildMethod(method = '', data = {}) {
      switch(method) {
        case 'abrirModalAgregar':
          this.abrirModalAgregar(); break;
        case 'abrirModalEditar':
          this.abrirModalEditar(data); break;
        default:
          `Option "${method}" don't found in runParentMethod() function in Modal.vue`;
          break;
      }
    }
  },
  mounted() {
    this.$parent.$on("runChildMethod", this.runChildMethod);
  },
};
</script>

<style>
</style>