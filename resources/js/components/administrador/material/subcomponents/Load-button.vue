<template>
  <button @click="press(action)" class="btn btn-success" :disabled="isActive">
    <div v-if="!isActive">{{action}}</div>
    <span v-else class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  </button>
</template>

<script>
export default {
  props: ['action'],
  data(){
    return {
      isActive: false
    }
  },
  methods: {
    press(action) {
      this.$emit('confirm-button', action)
    }
  }
};
</script>

<style>
</style>