<template>
  <main>
    <!-- Interfaz Principal -->
    <div class="container-small">
      <!-- Encabezado principal -->
      <div class="row form-group">
        <i class="fas fa-map-signs"></i>&nbsp;&nbsp;
        <span class="h3 mb-0 text-gray-900">Materiales&nbsp;</span>
        <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
          <i class="fas fa-hammer"></i>&nbsp; Nuevo
        </button>&nbsp;
        <button type="button" class="btn btn-danger" @click="generatePdf()">
          <i class="far fa-file-pdf"></i>&nbsp; PDF
        </button>
      </div>

      <!-- Inputs de busqueda -->
      <div class="row form-group">
        <div class="col-md-8">
          <div class="input-group">
            <input
              type="search"
              class="form-control"
              v-model="busqueda.texto"
              placeholder="Buscar por NOMBRE"
              @keyup.enter="listar()"
            />
            <button type="button" class="btn btn-primary" @click="listar()">
              <i class="fa fa-search"></i>&nbsp; Buscar
            </button>
          </div>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-1" align="right">
          <label>N° filas:</label>
        </div>
        <div class="col-md-1">
          <select
            class="custom-select custom-select-sm text-gray-900"
            v-model="busqueda.filas"
            @change="listar()"
          >
            <option
              v-for="item in [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17]"
              :key="item"
              :value="item"
              v-text="item"
            ></option>
          </select>
        </div>
      </div>

      <my-table :listaMaterial="listaMaterial" @edit="abrirModalEditar"></my-table>
    </div>

    <!-- Modales -->
    <my-modal @list="listar"></my-modal>
    
  </main>
</template>

<script>
/** Components */
// import materialRow from "./subcomponents/Material-row";
import myTable from './subcomponents/Table'
import paginationBar from "../../globals/Pagination-bar";
import myModal from "./subcomponents/modal";

export default {
  components: {
    myTable,
    paginationBar,
    myModal
  },
  data() {
    return {
      //datos generales
      listaMaterial: [],
      //datos de busqueda y filtracion
      busqueda: {
        texto: "",
        estado: 2,
        filas: 5,
      },
      //datos de paginacion
      paginacion: {
        total: 0,
        currentPage: 0,
        lastPage: 0,
        perPage: 0,
        firstItem: 0,
        lastItem: 0,
      },
      ruta: {
        material: "/material",
        data: "/data",
        serverPhp: "http://127.0.0.1:8000",
      },
    };
  },
  computed: {
  },
  methods: {
    listar(page = 1) {
      this.paginacion.currentPage = page;
      const url =
        this.ruta.material +
        "?page=" +
        this.paginacion.currentPage +
        "&estado=" +
        this.busqueda.estado +
        "&texto=" +
        this.busqueda.texto +
        "&filas=" +
        this.busqueda.filas;
      const me = this;
      axios
        .get(url)
        .then(function (response) {
          me.listaMaterial = response.data.materiales.data;
          me.paginacion = response.data.paginacion;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    abrirModalAgregar() {
      const data = {
        numModal: 1,
        material: {}
      }
      this.$emit('abrir-modal', data);
    },
    abrirModalEditar(material) {
      const data = {
        numModal: 2,
        material: material
      }
      this.$emit('abrir-modal', data);
    },
    generatePdf() {
      window.open(this.ruta.serverPhp + "/material/generatePdf", "_blank");
    },
  },
  mounted() {
    this.listar();
  },
};
</script>


