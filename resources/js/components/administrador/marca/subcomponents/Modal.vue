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
            <label class="col-md-3 font-weight-bold">
              Nombre
              <span class="text-danger">*</span>
            </label>
            <div class="col-md-9">
              <input
                type="text"
                v-model="marca.nombre"
                class="form-control"
                placeholder="ingrese el nombre"
              />
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
      initMarca: {
        id: 0,
        nombre: "",
      },
      marca: {
        id: 0,
        nombre: "",
      },
      //datos de errores
      error: {
        estado: 0,
        mensaje: [],
      },
      ruta: {
        marca: "/marca",
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
        titulo: "Nueva marca",
        size: "modal-lg",
        btnSuccess: "Agregar",
        btnCancel: "Cancelar",
      });
    },
    abrirModalEditar({marca}) {
      this.modal.estado = true;
      this.modal.tipo = 'editar';

      this.prepararModal({
        titulo: "Editar marca",
        size: "modal-lg",
        btnSuccess: "Editar",
        btnCancel: "Cancelar",
      });
      this.initMarca.id = marca.id;
      this.initMarca.nombre = marca.nombre;

      this.marca.id = marca.id;
      this.marca.nombre = marca.nombre;
    },
    cerrarModal() {

      this.modal.estado = false;
      this.modal.tipo = "";
      this.modal.titulo = "",
      this.modal.size = "",
      this.modal.loading = false,
      this.modal.btnSuccess = "",
      this.modal.btnCancel = "",

      this.initMarca.id = 0;
      this.initMarca.nombre = '';

      this.marca.id = 0;
      this.marca.nombre = "";

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

      if (!this.marca.nombre)
        this.error.mensaje.push("Debe ingresar un nombre");

      if (this.error.mensaje.length) {
        this.error.estado = 1;
        this.modal.loading = false;
      }
      return this.error.estado;
    },
    agregar() {
      if (this.validar()) return;
      const marca = {
        nombre: this.marca.nombre,
      };
      axios
        .post(this.ruta.marca + "/agregar", marca)
        .then((res) => {
          if (res.data.success) {
            this.cerrarModal();
            this.$emit('runParentMethod', "listar");
            mainAlert.fire({
              icon: "success",
              title: "La Categoria se ha AGREGADO correctamente",
            });
          } else {
            this.error.mensaje.push(
              `${marca.nombre} ya se encuentra registrada`
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
      if (compareObj(this.initMarca, this.marca)) {
        console.log("No ha realizado ninguna modificación");
        this.cerrarModal();
        mainAlert.fire({
          icon: "success",
          title: "La Categoría se ha EDITADO correctamente",
        });
        return;
      }
      const marca = {
        id: this.marca.id,
        nombre: this.marca.nombre,
      };
      axios
        .put(this.ruta.marca + "/editar", marca)
        .then((res) => {
          if (res.data.success) {
            this.cerrarModal();
            this.$emit('runParentMethod', "listar");
            mainAlert.fire({
              icon: "success",
              title: "La Categoría se ha EDITADO correctamente",
            });
          } else {
            this.error.mensaje.push(`${marca.nombre} ya está registrada`);
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