<template>
  <tr>
    <td v-text="material.nombre"></td>
    <td v-text="material.unidad"></td>
    <td v-text="material.costo"></td>
    <td>
      <button
        type="button"
        @click="edit(material)"
        title="Editar"
        class="btn btn-outline-warning btn-sm"
      >
        <i class="fas fa-user-edit"></i>
      </button>
    </td>
  </tr>
</template>

<script>
export default {
  props: ["material"],
  methods: {
    edit(material) {
      this.$emit('edit-material', material)
    }
  },
};
</script>

<style>
</style>