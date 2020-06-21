<template>
  <nav class="pagination" v-if="data.last_page > 1">
    <ul class="pagination">
      <li
        aria-disabled="true"
        aria-label="« Anterior"
        class="page-item"
        :class="{ disabled: !data.prev_page_url }"
      >
        <a
          v-if="data.prev_page_url"
          href="javascript:;"
          @click="get(data.current_page - 1)"
          rel="previous"
          class="page-link"
        >
          ‹
        </a>
        <span v-else aria-hidden="true" class="page-link">‹</span>
      </li>
      <li
        v-show="showPage(page)"
        :key="`page-${key}`"
        v-for="(page, key) in data.last_page"
        class="page-item"
        :class="{ active: page === data.current_page }"
      >
        <a
          @click="get(page)"
          v-if="page !== data.current_page"
          href="javascript:;"
          class="page-link"
        >
          {{ page }}
        </a>
        <span v-else class="page-link">
          {{ page }}
        </span>
      </li>
      <li class="page-item">
        <a
          v-if="data.next_page_url"
          href="javascript:;"
          @click="get(data.current_page + 1)"
          rel="next"
          class="page-link"
          >›</a
        >
        <span v-else aria-hidden="true" class="page-link">›</span>
      </li>
    </ul>
  </nav>
</template>

<style>
.pagination {
  overflow: auto;
}
</style>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: false,
      default: () => {
        return {};
      }
    }
  },
  methods: {
    // Busca dados da paginação de acordo com a página
    async get(page = null) {
      if (page !== this.data.current_page) {
        const response = await axios.get(`${this.data.path}?page=${page}`);

        this.data.current_page = response.data.current_page;
        this.data.data = response.data.data;
        this.data.first_page_url = response.data.first_page_url;
        this.data.from = response.data.from;
        this.data.last_page = response.data.last_page;
        this.data.last_page_url = response.data.last_page_url;
        this.data.next_page_url = response.data.next_page_url;
        this.data.path = response.data.path;
        this.data.per_page = response.data.per_page;
        this.data.prev_page_url = response.data.prev_page_url;
        this.data.to = response.data.to;
        this.data.total = response.data.total;
      }
    },
    // Verifica se opção da página pode ser exibida, para que sejam exibidas no máximo 10 páginas
    showPage(page) {
      // Base de cálculo para a última página exibida
      const baseMax = this.data.current_page + 5;

      // Primeira página a ser exibida
      let min = this.data.current_page - 4;

      // Definindo regra para última página a ser exibida
      let max = baseMax - min;

      // Excessões

      // Se o valor mínimo for maior que 0, aumentar o número de páginas no fim de acordo com a quantidade de páginas escondidas no início
      if (min > 1) {
        max += min - 1;
      }

      // Se a base de cálculo para a última página a ser exibida exceder o valor total de páginas + 1, acrescentar o valor excedido à menor página a ser exibida, para que não seja exibido menos que a quantidade mínima de páginas (9)
      if (baseMax > this.data.total + 1) {
        min -= baseMax - (this.data.total + 1);
      }

      if (page < min) {
        return false;
      }

      if (page > max) {
        return false;
      }
      return true;
    }
  }
};
</script>
