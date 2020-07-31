<template>
  <!-- <div v-if="modal.estado" class="modal text-gray-900 mostrar"> -->
  <div :class="{'modal text-gray-900': true, 'mostrar': modal.estado}">
    <div
      class="modal-dialog modal-dialog-centered animate__animated animate__bounceIn animate__fast"
    >
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

          <select-unit
            :initTipo.sync="material.subtipo"
            :initUnit.sync="material.unidad"
            :estadoModal="modal.estado"
          ></select-unit>

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
        <div class="modal-footer">
          <div class="row form-group col-md-12 d-flex justify-content-around">
            <load-button @confirm-button="action" :action="modal.accion"></load-button>
            <button type="button" @click="cerrar()" class="btn btn-secondary">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/** import: sweetalert, animate, compare-obj*/
import mainAlert from "../../../globals/Main-alert";
import "animate.css";
import compareObj from '../../../globals/Compare-obj'

// Components
import loadButton from "./Load-button";
import selectUnit from "./Select-unit";

export default {
  components: {
    loadButton,
    selectUnit,
  },
  props: {
    modal: {
      type: Object,
      default: () => {},
      required: true,
    },
    initMaterial: {
      type: Object,
      default: () => {},
      required: true,
    },
  },
  data() {
    return {
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
  watch: {
    initMaterial: {
      deep: true,
      handler: function (newVal) {
        this.material.id = newVal.id;
        this.material.nombre = newVal.nombre;
        this.material.unidad = newVal.unidad;
        this.material.subtipo = newVal.subtipo;
        this.material.costo = newVal.costo;
      },
    },
  },
  methods: {
    cerrar() {
      this.$emit("clearModal");
      this.$emit("clearMaterial");

      this.error.estado = 0;
      this.error.mensaje = [];
      this.clearInputs();
    },
    clearInputs() {
      this.material.id = 0;
      this.material.nombre = "";
      this.material.unidad = "";
      this.material.subtipo = "";
      this.material.costo = "";
    },
    action(act) {
      switch (act) {
        case "Agregar": {
          this.agregar();
          break;
        }
        case "Editar": {
          this.editar();
          break;
        }
        case "Activar": {
          this.activar();
          break;
        }
        case "Desactivar": {
          this.desactivar();
          break;
        }
      }
    },
    validar() {
      this.error.estado = 0;
      this.error.mensaje = [];

      if (!this.material.nombre) this.error.mensaje.push("Debe ingresar un nombre");
      if (!this.material.unidad) this.error.mensaje.push("Debe seleccionar una Unid. Medida");
      if (this.material.costo == 0 || this.material.costo < 0) this.error.mensaje.push("Debe ingresar un costo válido");

      if (this.error.mensaje.length) this.error.estado = 1;
      
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
        });
    },
    editar() {
      if (this.validar()) return;
      if (compareObj(this.initMaterial, this.material)) {
        console.log('No ha realizado ninguna modificación');
        this.cerrar();
        mainAlert.fire({
          icon: "success",
          title: "El Material se ha EDITADO correctamente",
        });
        return
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
};
</script>

<style>
</style>