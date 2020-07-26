<template>
  <div v-if="modal.estado" class="modal text-gray-900 mostrar">
    <div class="modal-dialog modal-dialog-centered animated bounceIn fast">
      <div class="modal-content modal-lg">
        <div class="modal-header">
          <h3 v-text="modal.titulo" class="modal-title"></h3>
          <button type="button" @click="cerrar()" class="close">X</button>
        </div>

        <div class="modal-body">
          <div v-if="error.estado" class="row d-flex justify-content-center">
              <div class="alert alert-danger">
                <button
                  type="button"
                  @click="error.estado=0"
                  class="close text-primary"
                  data-dismiss="alert"
                >×</button>
                <strong>Corregir los siguentes errores:</strong>
                <ul>
                  <li v-for="(error, i) in error.mensaje" :key="i" v-text="error"></li>
                </ul>
              </div>
          </div>
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
          <div class="row form-group">
            <label class="col-md-3 font-weight-bold" for="des">
              Unid.Medida&nbsp;
              <span class="text-danger">*</span>
            </label>
            <div class="col-md-4">
              <select v-model="material.subtipo" class="custom-select">
                <option value disabled>tipo</option>
                <!-- <option
                    v-for="item in SelectTipoFiltrado"
                    :key="item"
                    :value="item"
                    v-text="item"
                    class="text-gray-900"
                ></option>-->
              </select>
            </div>
            <div class="col-md-5">
              <select v-model="material.unidad" class="custom-select" id="cat">
                <option value disabled>subtipo</option>
                <!-- <option
                    v-for="unidad in selectUnidadFiltrado"
                    :key="unidad.id"
                    :value="unidad.nombre"
                    v-text="unidad.nombre"
                    class="text-gray-900"
                ></option>-->
              </select>
            </div>
          </div>
          <div class="row form-group">
            <label class="col-md-3 font-weight-bold" for="nom">
              Costo por Unidad&nbsp;
              <span class="text-danger">*</span>
            </label>
            <div class="col-md-4">
              <input type="number" v-model="material.costo" min="1" class="form-control text-right" />
            </div>
          </div>
        </div>
        /**TODO: REVISAR LO DEL DATO Button */
        <div class="modal-footer">
          <div class="row form-group col-md-12 d-flex justify-content-around">
            <button
              @click="action(modal.accion)"
              class="btn btn-success"
              :disabled="Button.press"
            >
              <div v-if="!Button.press">{{modal.accion}}</div>
              <span
                v-else
                class="spinner-border spinner-border-sm"
                role="status"
                aria-hidden="true"
              ></span>
            </button>
            <button type="button" @click="cerrar()" class="btn btn-secondary">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["the-modal", "the-material", "the-error"],
  data() {
    return {
      modal: this.theModal,
      error: this.theError,
      material: this.theMaterial
    };
  },
  methods: {
    cerrar() {
      this.modal.numero = 0;
      this.modal.estado = 0;
      this.modal.mensaje = [];

      this.error.estado = 0;
      this.error.mensaje = [];
      this.clearInputs();

      // this.Button.press = false;
      // this.YaIngrese = 0;
    },
    clearInputs(){
      this.material.id = 0;
      this.material.nombre = "";
      this.material.unidad = "";
      this.material.subtipo = "";
      this.material.costo = "";
    },
    action() {

    }
  },
};
</script>

<style>
</style>