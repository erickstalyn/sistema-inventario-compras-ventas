<template>
  <div v-if="listaCategoria.length" class="table-responsive">
    <table class="table table-borderless table-sm text-gray-900">
      <thead>
        <tr class="table-info">
          <th
            v-for="head in ['nombre']"
            :key="head"
            v-text="head"
          ></th>
          <th class="text-center">Opciones</th>
        </tr>
      </thead>
      <tbody>
        <tr class="row-table" v-for="categoria in listaCategoria" :key="categoria.id">
          <td v-text="categoria.nombre"></td>
          <td class="text-center">
            <button
              type="button"
              @click="abrirModalEditar(categoria)"
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
    listaCategoria: {
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
    abrirModalEditar(categoria) {
      this.$emit('runParentMethod', 'abrirModalEditar', categoria)
    },
    listar(page) {
      this.$emit('runParentMethod', 'listar', {page});
    }
  }
}
</script>

<style>
  
</style>