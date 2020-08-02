<template>
  <div class="modal-footer justify-content-around">
    <div v-if="btnSuccess!=null">
      <button
        class="btn btn-success"
        @click="accionar()"
        :disabled="modalLoading"
      >
        <span v-if="!modalLoading" v-text="btnSuccess"></span>
        <span v-else class="fas fa-spinner fa-spin fa-lg" role="status" aria-hidden="true"></span>
      </button>
    </div>
    <div v-if="btnCancel!=null" >
      <button class="btn btn-secondary" @click="cerrarModal()">
        <span class="" v-text="btnCancel"></span>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    modalLoading: {
      type: Boolean,
      default: false
    },
    btnSuccess: {
      type: String,
      default: ''
    },
    btnCancel: {
      type: String,
      default: ''
    }
  },
  data() {
    return {

    }
  },
  methods: {
    accionar() {
      this.$emit('update:modalLoading', true);
      this.$emit('accionar');
    },
    cerrarModal(){
      this.$emit('cerrar-modal');
    }
  }
}
</script>
