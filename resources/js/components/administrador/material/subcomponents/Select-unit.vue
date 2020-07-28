<template>
  <div class="row form-group">
    <label class="col-md-3 font-weight-bold" for="des">
      Unid.Medida&nbsp;
      <span class="text-danger">*</span>
    </label>
    <div class="col-md-4">
      <select @change="changeNames()" v-model="subtipo" class="custom-select">
        <option value disabled>tipo</option>
        <option
          v-for="(unit, i) in selectUnits"
          :key="i"
          :value="unit"
          v-text="unit"
          class="text-gray-900"
        ></option>
      </select>
    </div>
    <div class="col-md-5">
      <select v-model="unit" class="custom-select" id="cat">
        <option value disabled>subtipo</option>
        <option
          v-for="unidad in selectNames"
          :key="unidad"
          :value="unidad"
          v-text="unidad"
          class="text-gray-900"
        ></option>
      </select>
    </div>
  </div>
</template>

<script>
export default {
  //TODO: Seguir creando este componente
  props: ['the-subtipo', 'the-unit', 'the-number-modal'],
  data() {
    return {
      subtipo: this.theSubtipo,
      unit: this.theUnit,
      numberModal: this.theNumberModal,
      unitsRaw: [],
      selectUnits: [],
      selectNames: [],
      ruta: {
        data: "/data",
      },
    }
  },
  methods: {
    getUnitsRaw() {
      console.log('Obteniendo datos...')
      const me = this;
      const url = this.ruta.data + "/selectUnidad";
      axios
        .get(url)
        .then(function (res) {
          me.unitsRaw = res.data;
          me.onlyUnits();
          if (me.numberModal == 2) me.initialNames();
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    changeNames() {
      // console.log("run changeNames");
      this.unit = "";
      this.selectNames = this.unitsRaw
        .filter((el) => el.subtipo == this.subtipo)
        .map((el) => el.nombre);
    },
    initialNames() {
      // console.log("run initialNames");
      // this.unit = "";
      this.selectNames = this.unitsRaw
        .filter((el) => el.subtipo == this.subtipo)
        .map((el) => el.nombre);
    },
    onlyUnits() {
      // console.log("run onlyUnits");
      const arr = this.unitsRaw.map((el) => el.subtipo);
      this.selectUnits = arr.filter(
        (el, index, self) => self.indexOf(el) === index
      );
    },
  },
  computed: {
    
  },
  created() {
    this.getUnitsRaw();
  },
};
</script>

<style>
</style>