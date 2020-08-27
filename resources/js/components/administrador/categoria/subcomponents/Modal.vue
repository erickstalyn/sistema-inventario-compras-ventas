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
                v-model="categoria.nombre"
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
      initCategoria: {
        id: 0,
        nombre: "",
      },
      categoria: {
        id: 0,
        nombre: "",
      },
      //datos de errores
      error: {
        estado: 0,
        mensaje: [],
      },
      ruta: {
        categoria: "/categoria",
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
        titulo: "Nueva categoria",
        size: "modal-lg",
        btnSuccess: "Agregar",
        btnCancel: "Cancelar",
      });
    },
    abrirModalEditar({categoria}) {
      this.modal.estado = true;
      this.modal.tipo = 'editar';

      this.prepararModal({
        titulo: "Editar categoria",
        size: "modal-lg",
        btnSuccess: "Editar",
        btnCancel: "Cancelar",
      });
      this.initCategoria.id = categoria.id;
      this.initCategoria.nombre = categoria.nombre;

      this.categoria.id = categoria.id;
      this.categoria.nombre = categoria.nombre;
    },
    cerrarModal() {

      this.modal.estado = false;
      this.modal.tipo = "";
      this.modal.titulo = "",
      this.modal.size = "",
      this.modal.loading = false,
      this.modal.btnSuccess = "",
      this.modal.btnCancel = "",

      this.initCategoria.id = 0;
      this.initCategoria.nombre = '';

      this.categoria.id = 0;
      this.categoria.nombre = "";

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

      if (!this.categoria.nombre)
        this.error.mensaje.push("Debe ingresar un nombre");

      if (this.error.mensaje.length) {
        this.error.estado = 1;
        this.modal.loading = false;
      }
      return this.error.estado;
    },
    agregar() {
      if (this.validar()) return;
      const categoria = {
        nombre: this.categoria.nombre,
      };
      axios
        .post(this.ruta.categoria + "/agregar", categoria)
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
              `${categoria.nombre} ya se encuentra registrada`
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
      if (compareObj(this.initCategoria, this.categoria)) {
        console.log("No ha realizado ninguna modificación");
        this.cerrarModal();
        mainAlert.fire({
          icon: "success",
          title: "La Categoría se ha EDITADO correctamente",
        });
        return;
      }
      const categoria = {
        id: this.categoria.id,
        nombre: this.categoria.nombre,
      };
      axios
        .put(this.ruta.categoria + "/editar", categoria)
        .then((res) => {
          if (res.data.success) {
            this.cerrarModal();
            this.$emit('runParentMethod', "listar");
            mainAlert.fire({
              icon: "success",
              title: "La Categoría se ha EDITADO correctamente",
            });
          } else {
            this.error.mensaje.push(`${categoria.nombre} ya está registrada`);
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