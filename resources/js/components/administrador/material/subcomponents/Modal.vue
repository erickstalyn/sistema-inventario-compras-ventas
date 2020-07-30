<template>
  <!-- <div v-if="modal.estado" class="modal text-gray-900 mostrar"> -->
  <div :class="{'modal text-gray-900': true, 'mostrar': modal.estado}">
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

          <select-unit :initTipo.sync="material.subtipo" :initUnit.sync="material.unidad" :estadoModal="modal.estado"></select-unit>

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
// Components
import LoadButton from "./Load-button";
import selectUnit from './Select-unit'

export default {
  components: {
    LoadButton,
    selectUnit
  },
  props: {
    modal: {
      type: Object,
      default: () => {},
      required: true
    },
    initMaterial: {
      type: Object,
      default: () => {},
      required: true
    }
  },
  data() {
    return {
      material: {
        id: 0,
        nombre: "",
        subtipo: "",
        unidad: "",
        costo: "",
        estado: 0
      },
      // unitsRaw: [],
      // selectUnits: [],
      // selectNames: [],
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
    // material: function() {
    //   return this.material;
    // }
  },
  watch: {
    initMaterial: {
      deep: true,
      handler: function(newVal) {
        this.material.id = newVal.id;
        this.material.nombre = newVal.nombre;
        this.material.unidad = newVal.unidad;
        this.material.subtipo = newVal.subtipo;
        this.material.costo = newVal.costo;
      }
    }
  },
  methods: {
    cerrar() {
      this.$emit('clearModal');
      this.$emit('clearMaterial');

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

      //Recorrer la lista de material
      if (this.modal.numero == 1) {
        //Modal agregar
        if (!this.material.nombre)
          this.error.mensaje.push("Debe ingresar un nombre");
        if (!this.material.unidad)
          this.error.mensaje.push("Debe seleccionar una Unid. Medida");
        if (this.material.costo == 0 || this.material.costo < 0)
          this.error.mensaje.push("Debe ingresar un costo válido");
      } else {
        //Modal editar
      }

      if (this.error.mensaje.length) {
        this.error.estado = 1;
        // this.button.press = false;
      }
      return this.error.estado;
    },
    agregar() {
      if (this.validar()) return;
      const me = this;
      axios
        .post(this.ruta.material + "/agregar", {
          nombre: this.material.nombre,
          subtipo: this.material.subtipo,
          unidad: this.material.unidad,
          costo: this.material.costo,
        })
        .then(function (res) {
          if (res.data.estado) {
            me.cerrar();
            me.$emit("list"); //emito el evento de listar al PADRE
            Swal.fire({
              position: "top-end",
              toast: true,
              type: "success",
              title: "El material se ha AGREGADO correctamente",
              showConfirmButton: false,
              timer: 4500,
              animation: false,
              customClass: {
                popup: "animated bounceIn fast",
              },
            });
          } else {
            me.error.mensaje.push(
              "El material '" +
                me.material.nombre +
                "' ya se encuentra registrado"
            );
            me.error.estado = 1;
            console.log("hay errores");
          }
        })
        .catch(function (error) {
          console.log("soy el error" + error);
        });
    },
    // getUnitsRaw() {
    //   const me = this;
    //   const url = this.ruta.data + "/selectUnidad";
    //   axios
    //     .get(url)
    //     .then(function (res) {
    //       me.unitsRaw = res.data;
    //       me.onlyUnits();
    //       if (me.modal.numero == 2) me.initialNames();
    //     })
    //     .catch(function (error) {
    //       console.log(error);
    //     });
    // },
    // changeNames() {
    //   console.log("run changeNames");
    //   this.material.unidad = "";
    //   this.selectNames = this.unitsRaw
    //     .filter((el) => el.subtipo == this.material.subtipo)
    //     .map((el) => el.nombre);
    // },
    // initialNames() {
    //   console.log("run initialNames");
    //   this.selectNames = this.unitsRaw
    //     .filter((el) => el.subtipo == this.material.subtipo)
    //     .map((el) => el.nombre);
    // },
    // onlyUnits() {
    //   console.log("run onlyUnits");
    //   const arr = this.unitsRaw.map((el) => el.subtipo);
    //   this.selectUnits = arr.filter(
    //     (el, index, self) => self.indexOf(el) === index
    //   );
    // },
  }
};
</script>

<style>
</style>