<template>
  <div v-if="listaAbasto.length" class="table-responsive">
    <table class="table table-borderless table-sm text-gray-900">
      <thead>
        <tr class="table-info">
          <th
            v-for="head in ['Proveedor', 'Almacén de destino', 'Fecha de envío', 'Costo total', 'Tipo', 'Estado de envío']"
            :key="head"
            v-text="head"
          ></th>
          <th class="text-center">Opciones</th>
        </tr>
      </thead>
      <tbody>
        <tr class="row-table" v-for="abasto in listaAbasto" :key="abasto.id">
          <td v-text="abasto.nombre"></td>
          <td v-text="abasto.unidad"></td>
          <td v-text="abasto.costo"></td>
          <td class="text-center">
            <button
              type="button"
              @click="abrirModalEditar(abasto)"
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
    listaAbasto: {
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
    abrirModalEditar(abasto) {
      this.$emit('runParentMethod', 'abrirModalEditar', abasto)
    },
    listar(page) {
      this.$emit('runParentMethod', 'listar', {page});
    }
  }
}
</script>

<style>
  
</style>