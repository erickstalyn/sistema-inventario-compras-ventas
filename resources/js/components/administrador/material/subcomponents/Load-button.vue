<template>
  <button @click="press()" class="btn btn-success" :disabled="btnCharge.isPress">
    <div v-if="!btnCharge.isPress">{{btnCharge.title}}</div>
    <span v-else class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  </button>
</template>

<script>
export default {
  // props: ['action'],
  props: {
    btnCharge: {
      type: Object,
      defautl: () => {
        return {
          title: '',
          isPress: false
        }
      }
    }
  },
  data(){
    return {
    }
  },
  methods: {
    press() {
      this.$emit('update:btnCharge', {title: '', isPress: true});
      this.$emit('confirm-button', this.btnCharge.title);
    }
  }
};
</script>

<style>
</style>