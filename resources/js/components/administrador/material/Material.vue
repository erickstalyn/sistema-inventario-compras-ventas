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
            <!-- <select class="col-md-3 custom-select text-gray-900" v-model="busqueda.estado" @change="listar()">
                            <option value="2">Todos</option>
                            <option value="1">Activados</option>
                            <option value="0">Desactivados</option>
            </select>-->
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

      <!-- Listado -->
      <div v-if="listaMaterial.length" class="table-responsive">
        <!-- Tabla -->
        <div class="overflow-auto">
          <table class="table table-borderless table-sm text-gray-900">
            <thead>
              <tr class="table-info">
                <th
                  v-for="head in ['Nombre', 'Unid. Medida', 'Costo Unit.', 'Opciones']"
                  :key="head"
                  v-text="head"
                ></th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(material, i) in listaMaterial"
                :key="i"
                is="material-row"
                :material="material"
                @edit-material="abrirModalEditar"
              ></tr>
            </tbody>
          </table>
        </div>
        <!-- Barra de navegacion -->
        <pagination-bar @list="listar" :paginacion="paginacion"></pagination-bar>
      </div>
      <div v-else>
        <h5>No se han encontrado resultados</h5>
      </div>
    </div>

    <!-- Modales -->
    <my-modal @list="listar"  :modal="modal" @clearModal="onClearModal" :initMaterial="material" @clearMaterial="onClearMaterial"></my-modal>
    
  </main>
</template>

<script>
/** Components */
import MaterialRow from "./subcomponents/Material-row";
import PaginationBar from "../../globals/Pagination-bar";
import myModal from "./subcomponents/modal";

export default {
  components: {
    MaterialRow,
    PaginationBar,
    myModal
  },
  data() {
    return {
      //datos generales
      listaMaterial: [],
      material: {
        id: 0,
        nombre: "",
        subtipo: "",
        unidad: "",
        costo: "",
      },
      //datos de busqueda y filtracion
      busqueda: {
        texto: "",
        estado: 2,
        filas: 5,
      },
      //datos de modales
      modal: {
        estado: 0,
        numero: 0,
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
      this.abrirModal(1);
    },
    abrirModalEditar(material) {
      this.material = material;
      this.abrirModal(2);
    },
    abrirModal(numero, titulo, accion) {
      this.modal.estado = 1;
      this.modal.numero = numero;
    },
    onClearModal(){
      this.modal = {estado: 0, numero: 0};
    },
    onClearMaterial(){
      this.material = {id: 0, nombre: "", subtipo: "", unidad: "", costo: ""};
    },
    generatePdf() {
      window.open(this.ruta.serverPhp + "/material/generatePdf", "_blank");
      this.Button.press = false;
    },
  },
  mounted() {
    this.listar();
  },
};
</script>


