<template>
  <div v-if="listaMaterial.length" class="table-responsive">
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
          <tr v-for="material in listaMaterial" :key="material.id">
            <td v-text="material.nombre"></td>
            <td v-text="material.unidad"></td>
            <td v-text="material.costo"></td>
            <td>
              <button
                type="button"
                @click="edit(material)"
                title="EDITAR"
                class="btn btn-outline-warning btn-sm"
              >
                <i class="fas fa-user-edit"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Barra de navegacion -->
    <!-- <pagination-bar @list="listar" :paginacion="paginacion"></pagination-bar> -->
  </div>
  <div v-else>
    <h5>No se han encontrado resultados</h5>
  </div>
</template>

<script>
export default {
  props: {
    listaMaterial: {
      type: Array,
      default: []
    }
  },
  methods: {
    edit(material) {
      this.$emit('edit', material)
    }
  }
}
</script>

<style>

</style>