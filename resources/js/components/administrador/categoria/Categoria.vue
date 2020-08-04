<template>
  <main>
    <div class="container-small">
      <!-- Encabezado -->
      <div class="row form-group">
        <div class="col-12 col-sm-6 col-md-4 mb-2">
          <i class="fas fa-map-signs mr-1"></i>
          <span class="h3 mb-0 text-gray-900">Categorias</span>
        </div>
        <div class="col-12 col-sm-6 col-md-8">
          <button type="button" class="btn btn-success" @click="abrirModalAgregar()">
            <i class="fas fa-hammer mr-2"></i>Nuevo
          </button>
          <button type="button" class="btn btn-danger" @click="generatePdf()">
            <i class="far fa-file-pdf mr-2"></i>PDF
          </button>
        </div>
      </div>
      <!-- Inputs de busqueda -->
      <div class="row mb-3">
        <div class="col-12 col-sm-8">
          <div class="input-group">
            <input
              type="search"
              class="form-control"
              v-model="busqueda.texto"
              placeholder="Buscar por nombre"
              @keyup.enter="listar()"
            />
            <button type="button" class="btn btn-primary" @click="listar()">
              <i class="fa fa-search"></i>&nbsp; Buscar
            </button>
          </div>
        </div>
        <div class="col-12 col-sm-4 d-flex justify-content-end">
          <div class="form-inline">
            <label class="mr-2">N° filas</label>
            <select
              class="custom-select mr-sm-2 text-gray-900"
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
      </div>

      <my-table
        :listaCategoria="listaCategoria"
        :paginacion="paginacion"
        @abrirModalEditar="abrirModalEditar"
        @runParentMethod="runParentMethod"
      ></my-table>
      <!-- Modales -->
      <my-modal @runParentMethod="runParentMethod"></my-modal>
    </div>
  </main>
</template>

<script>
/** Components */
import myTable from "./subcomponents/Table";
import myModal from "./subcomponents/modal";

export default {
  components: {
    myTable,
    myModal,
  },
  data() {
    return {
      listaCategoria: [],
      busqueda: {
        texto: "",
        estado: 2,
        filas: 5,
      },
      paginacion: {
        total: 0,
        currentPage: 0,
        lastPage: 0,
        perPage: 0,
        firstItem: 0,
        lastItem: 0,
      },
      ruta: {
        categoria: "/categoria"
      },
    }
  },
  methods: {
    listar({page = 1} = {}) {
      this.paginacion.currentPage = page;
      const url =
        this.ruta.categoria +
        "?page=" +
        this.paginacion.currentPage +
        "&texto=" +
        this.busqueda.texto +
        "&filas=" +
        this.busqueda.filas;
      axios
        .get(url)
        .then((res) => {
          this.listaCategoria = res.data.categorias.data;
          this.paginacion = res.data.paginacion;
        })
        .catch(error => console.log(`Error: "${error}" in method listar() in Categoria.vue`));
    },
    abrirModalAgregar() {
      const data = {
        modal: {
          tipo: "agregar",
        },
        categoria: {},
      };
      this.$emit('runChildMethod', "abrirModalAgregar", data);
    },
    abrirModalEditar(categoria = {}) {
      const data = {
        modal: {
          tipo: "editar",
        },
        categoria
      };
      this.$emit("runChildMethod", 'abrirModalEditar', data);
    },
    runParentMethod(method = '', data = {}) {
      switch (method) {
        case "listar":
          this.listar(data);
          break;
        case "abrirModalEditar":
          this.abrirModalEditar(data);
          break;
        default:
          `Option "${method}" don't found in runParentMethod() function in Categoria.vue`;
          break;
      }
    },
  },
  mounted() {
    this.listar()
  }
}
</script>

<style>

</style>