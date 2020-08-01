<template>
  <div v-if="error.estado" class="row d-flex justify-content-center">
    <div class="alert alert-danger">
      <button
        type="button"
        @click="cerrar()"
        class="close text-primary"
        data-dismiss="alert"
      >×</button>
      <strong>Corregir los siguentes errores:</strong>
      <ul>
        <li v-for="(err, i) in error.mensaje" :key="i" v-text="err"></li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    error: {
      type: Object,
      default: () => {
        return {estado: 0, mensaje: []}
      }
    }
  },
  data() {
    return {
    }
  },
  methods: {
    cerrar() {
      this.$emit('update:error', {estado: 0, mensaje: []})
    }
  }
}
</script>
