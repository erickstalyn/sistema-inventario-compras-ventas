<template>
  <div v-if="listaMaterial.length" class="table-responsive">
    <table class="table table-borderless table-sm text-gray-900">
      <thead>
        <tr class="table-info">
          <th
            v-for="head in ['Nombre', 'Unid. Medida', 'Costo Unit.']"
            :key="head"
            v-text="head"
          ></th>
          <th class="text-center">Opciones</th>
        </tr>
      </thead>
      <tbody>
        <tr class="row-table" v-for="material in listaMaterial" :key="material.id">
          <td v-text="material.nombre"></td>
          <td v-text="material.unidad"></td>
          <td v-text="material.costo"></td>
          <td class="text-center">
            <button
              type="button"
              @click="abrirModalEditar(material)"
              title="EDITAR"
              class="btn btn-outline-warning btn-sm"
            >
              <i class="fas fa-user-edit"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <!-- Barra de navegacion -->
    <pagination-bar @listar="listar" :paginacion="paginacion"></pagination-bar>
  </div>
  <div v-else>
    <h5>No se han encontrado resultados</h5>
  </div>
</template>

<script>
import paginationBar from "../../../globals/Pagination-bar";

export default {
  components: {
    paginationBar
  },
  props: {
    listaMaterial: {
      type: Array,
      default: []
    },
    paginacion: {
      type: Object,
      defautl: () => {
        return {
          total: 0,
          currentPage: 0,
          lastPage: 0,
          perPage: 0,
          firstItem: 0,
          lastItem: 0,
        }
      }
    }
  },
  methods: {
    abrirModalEditar(material) {
      this.$emit('runParentMethod', 'abrirModalEditar', material)
    },
    listar(page) {
      this.$emit('runParentMethod', 'listar', {page});
    }
  }
}
</script>

<style>
  
</style>