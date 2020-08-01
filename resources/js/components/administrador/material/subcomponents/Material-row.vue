<template>
  <tr>
    <td v-text="material.nombre"></td>
    <td v-text="material.unidad"></td>
    <td v-text="material.costo"></td>
    <td>
      <button
        type="button"
        @click="edit()"
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
  // props: ["material"],
  props: {
    material: {
      type: Object,
      default: () => {
        return {
          id: 0,
          nombre: "",
          subtipo: "",
          unidad: "",
          costo: "",
        }
      },
    }
  },
  methods: {
    edit() {
      this.$emit('edit-material', this.material)
    }
  },
};
</script>

<style>
</style>