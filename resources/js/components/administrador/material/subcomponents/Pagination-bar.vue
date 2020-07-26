<template>
  <nav class="d-flex justify-content-center">
    <ul class="pagination">
      <li class="page-item">
        <a
          href="#"
          @click="changePage(paginacion.currentPage-1)"
          class="page-link"
        >Anterior</a>
      </li>
      <li
        class="page-item"
        v-for="page in paginas"
        :key="page"
        :class="[page==paginacion.currentPage?'active':'']"
      >
        <a href="#" @click="changePage(page)" v-text="page" class="page-link"></a>
      </li>
      <li class="page-item">
        <a
          href="#"
          @click="changePage(paginacion.currentPage+1)"
          class="page-link"
        >Siguiente</a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  props: ['paginacion', 'paginas'],
  data(){
    return {

    }
  },
  methods: {
    changePage(page) {
      this.$emit('change-page', page);
    }
  }
}
</script>

<style>

</style>