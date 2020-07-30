<template>
  <div class="row form-group">
    <label class="col-md-3 font-weight-bold">
      Unid.Medida&nbsp;
      <span class="text-danger">*</span>
    </label>
    <div class="col-md-4">
      <select @change="changeNames()" v-model="subtipo" class="custom-select">
        <option value disabled>tipo</option>
        <option
          v-for="(u, i) in selectUnits"
          :key="i"
          :value="u"
          v-text="u"
          class="text-gray-900"
        ></option>
      </select>
    </div>
    <div class="col-md-5">
      <select v-model="unit" class="custom-select">
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
  props: {
    subtipo: {
      type: String,
      default: () => '',
      required: true
    },
    unit: {
      type: String,
      default: () => '',
      required: true
    },
    numberModal: {
      type: Number,
      default: () => 0,
      required: true
    }
  },
  data() {
    return {
      // subtipo: this.theSubtipo,
      // unit: this.theUnit,
      // numberModal: this.theNumberModal,
      unitsRaw: [],
      selectUnits: [],
      selectNames: [],
      ruta: {
        data: "/data",
      },
      ticketCounter: 0
    }
  },
  computed: {
    getNames() {
      console.log("run getNames");
      this.ticketCounter++;
      if(this.numberModal == 2 && this.ticketCounter == 1) { //Modal editar && ticketCounter es 1
      }else {
        this.unit = "";
      }
      this.selectNames = this.unitsRaw
        .filter((el) => el.subtipo == this.subtipo)
        .map((el) => el.nombre);
    },
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
          // me.initialNames();
          // if (me.numberModal == 2) me.initialNames();
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    // changeNames() {
    //   console.log("run changeNames");
    //   this.unit = "";
    //   this.selectNames = this.unitsRaw
    //     .filter((el) => el.subtipo == this.subtipo)
    //     .map((el) => el.nombre);
    // },
    initialNames() {
      console.log("run initialNames");
      this.selectNames = this.unitsRaw
        .filter((el) => el.subtipo == this.subtipo)
        .map((el) => el.nombre);
    },
    onlyUnits() {
      console.log("run onlyUnits");
      const arr = this.unitsRaw.map((el) => el.subtipo);
      this.selectUnits = arr.filter(
        (el, index, self) => self.indexOf(el) === index
      );
    },
  },
  mounted() {
    // console.log(this.$props)
    this.getUnitsRaw()
  }
};
</script>

<style>
</style>