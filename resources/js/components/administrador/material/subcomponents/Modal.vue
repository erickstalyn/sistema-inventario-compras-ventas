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
                <option
                  v-for="(unit, i) in onlyUnits"
                  :key="i"
                  :value="unit"
                  v-text="unit"
                  class="text-gray-900"
                ></option>
              </select>
            </div>
            <div class="col-md-5">
              <select v-model="material.unidad" class="custom-select" id="cat">
                <option value="" disabled>subtipo</option>
                <option
                  v-for="unidad in namesUnits"
                  :key="unidad"
                  :value="unidad"
                  v-text="unidad"
                  class="text-gray-900"
                ></option>
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

export default {
  components: {
    LoadButton,
  },
  props: ["the-modal", "the-material"],
  data() {
    return {
      modal: this.theModal,
      material: this.theMaterial,
      units: [],
      //datos de errores
      error: {
        estado: 0,
        mensaje: [],
      },
      Ruta: {
        data: "/data",
      },
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

      // this.YaIngrese = 0;
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

      //Recorrere la lista de material
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
        if (
          this.material.nombre == this.MaterialOrigen.nombre &&
          this.material.subtipo == this.MaterialOrigen.subtipo &&
          this.material.unidad == this.MaterialOrigen.unidad &&
          this.material.costo == this.MaterialOrigen.costo
        ) {
          this.error.mensaje.push("Ningun cambio realizado");
        } else {
          if (!this.material.nombre)
            this.error.mensaje.push("Debe ingresar un nombre");
          if (!this.material.unidad)
            this.error.mensaje.push("Debe seleccionar una Unid. Medida");
          if (this.material.costo == 0 || this.material.costo < 0)
            this.error.mensaje.push("Debe ingresar un costo válido");
        }
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
        .post("/material/agregar", {
          nombre: this.material.nombre,
          subtipo: this.material.subtipo,
          unidad: this.material.unidad,
          costo: this.material.costo,
        })
        .then(function (response) {
          if (response.data.estado) {
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
    selectUnidad() {
      const me = this;
      const url = this.Ruta.data + "/selectUnidad";
      axios
        .get(url)
        .then(function (res) {
          me.units = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
  computed: {
    onlyUnits() {
      const arr = this.units.map((el) => el.subtipo);
      return arr.filter((el, index, self) => self.indexOf(el) === index);
    },
    namesUnits() {
      //FIXME: Debo hacer que la unidad se queda "" cuando se cambia de Unidad ( OnlyUnits)
      // if(this.modal.numero == 1) this.material.unidad = ''; //Si es el modal de agregar...
      return this.units
        .filter((el) => el.subtipo == this.material.subtipo)
        .map((el) => el.nombre);
    },
  },
  mounted() {
    this.selectUnidad();
  },
};
</script>

<style>
</style>