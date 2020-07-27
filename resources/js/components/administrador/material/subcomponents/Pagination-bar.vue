<template>
  <nav class="d-flex justify-content-center">
    <ul class="pagination">
      <li class="page-item">
        <a href="#" @click="changePage(paginacion.currentPage-1)" class="page-link">Anterior</a>
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
        <a href="#" @click="changePage(paginacion.currentPage+1)" class="page-link">Siguiente</a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  props: ["paginacion"],
  data() {
    return {
      pagination: this.paginacion,
      //TODO: Revisar el objecto navegation
      navegation: {
        offset: 3,
      },
    };
  },
  computed: {
    //TODO: Revisar la funcion paginas, para ver si optimizo codigo
    paginas() {
      if (!this.pagination.lastItem) return [];

      let from = this.pagination.currentPage - this.navegation.offset;
      if (from < 1) from = 1;

      let to = this.pagination.currentPage + this.navegation.offset * 2;
      if (to > this.pagination.lastPage) to = this.pagination.lastPage;

      let pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
  },
  methods: {
    changePage(page) {
      if(page >= 1 && page <= this.pagination.lastPage) this.$emit('list', page)
    },
  },
};
</script>

<style>
</style>