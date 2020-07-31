<template>
  <div class="row form-group">
    <label class="col-md-3 font-weight-bold">
      Unid.Medida&nbsp;
      <span class="text-danger">*</span>
    </label>
    <div class="col-md-4">
      <select @change="changeTipo()" v-model="tipo" class="custom-select">
        <option value disabled>tipo</option>
        <option
          v-for="(tipo, i) in selectTipoUnidad"
          :key="i"
          :value="tipo"
          v-text="tipo"
          class="text-gray-900"
        ></option>
      </select>
    </div>
    <div class="col-md-5">
      <select v-model="unit" @change="changeUnit()" class="custom-select">
        <option value disabled>seleccione</option>
        <option
          v-for="unidad in selectUnidad"
          :key="unidad.unidad_nombre"
          :value="unidad.unidad_nombre"
          v-text="unidad.unidad_nombre"
          class="text-gray-900"
        ></option>
      </select>
    </div>
  </div>
</template>

<script>
export default {
  //TODO: Seguir creando este componente
  props: {
    initTipo: {
      type: String,
      default: "",
      required: true,
    },
    initUnit: {
      type: String,
      default: "",
      required: true,
    },
    estadoModal: {
      type: Number,
      default: 0,
      required: true,
    },
  },
  data() {
    return {
      tipo: "",
      unit: "",
      selectTipoUnidad: [],
      unidadesRaw: [],
      ruta: {
        unidad: "/unidad",
      },
    };
  },
  computed: {
    selectUnidad: function () {
      return this.unidadesRaw.filter(
        (e, i, self) => e.tipo_unidad_nombre == this.tipo
      );
    }
  },
  watch: {
    estadoModal: function (newEstado, oldEstado) {
      if (newEstado == 0) {
        this.tipo = "";
        this.unit = "";
      }
    },
    initTipo: function(newVal) {
      this.tipo = newVal;
    },
    initUnit: function(newVal) {
      this.unit = newVal;
    }

  },
  methods: {
    getUnitsRaw() {
      console.log("Obteniendo datos...");
      const me = this;
      const url = this.ruta.unidad + "/getUnidades";
      axios
        .get(url)
        .then(function (res) {
          const data = res.data.unidades;
          console.log(data);
          const arrTipoUnidadRaw = data.map((e) => e.tipo_unidad_nombre);
          me.selectTipoUnidad = arrTipoUnidadRaw.filter(
            (e, i, self) => self.indexOf(e) == i
          );

          me.unidadesRaw = data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    changeTipo() {
      this.$emit("update:initTipo", this.tipo);
      this.$emit("update:initUnit", this.unit);
      this.unit = "";
    },
    changeUnit() {
      this.$emit("update:initUnit", this.unit);
    },
  },
  mounted() {
    this.getUnitsRaw();
  },
  updated() {
  },
};
</script>

<style>
</style>