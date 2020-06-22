<template>
  <div>
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h5>Contratos</h5>
        <button
          type="button"
          data-toggle="tooltip"
          title="O arquivo deve conter os cabeçalhos: cliente(texto), entrega(data) e paginas(inteiro)"
          class="btn btn-primary"
          @click="create()"
        >
          Importar
        </button>
      </div>

      <div class="overflow-auto card-body">
        <table v-if="contracts.data && contracts.data.length" class="table">
          <thead class="text-center">
            <th>Cliente</th>
            <th>Prazo</th>
            <th>Páginas</th>
            <th>Ações</th>
          </thead>
          <tbody>
            <tr
              v-for="(contract, key) in contracts.data"
              :key="`contract-${key}`"
            >
              <td class="text-nowrap">
                {{ contract.client }}
              </td>
              <td>
                {{ contract.term }}
              </td>
              <td class="text-nowrap">
                {{ contract.pages }}
              </td>
              <td class="text-center">
                <div class="d-flex">
                  <button
                    type="button"
                    @click="destroy(contract.id)"
                    class="btn btn-danger"
                  >
                    Excluir
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <p v-else>Não há contratos cadastrados</p>
      </div>
      <div class="card-footer" v-if="contracts.last_page > 1">
        <mesha-pagination :data="contracts"></mesha-pagination>
      </div>
    </div>
    <!-- Modal de importação de contratos -->
    <div
      id="contract-modal"
      ref="contract_modal"
      class="modal fade"
      tabindex="-1"
      role="dialog"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <mesha-modal-header>Importar contratos</mesha-modal-header>
          <form @submit.prevent="store()">
            <mesha-contracts-form :form="forms.create"></mesha-contracts-form>
          </form>
        </div>
      </div>
    </div>
    <!-- Fim do modal de importação de contratos -->
  </div>
</template>

<script>
$(function() {
  $('[data-toggle="tooltip"]').tooltip();
});
import { mask } from 'vue-the-mask';

export default {
  directives: { mask },
  data() {
    return {
      forms: {
        create: {
          loading: false,
          body: {
            file: ''
          },
          errors: []
        }
      },
      contracts: {}
    };
  },
  created() {
    this.index();
  },
  methods: {
    // Busca lista de contratos
    async index() {
      const response = await axios.get('api/v1/contracts');
      this.contracts = response.data;
    },
    // Exibe modal de criação de contrato
    create() {
      $(this.$refs.contract_modal).modal('show');
    },
    // Cria contrato
    async store() {
      try {
        this.forms.create.loading = true;
        this.forms.create.errors = [];

        let formData = new FormData();

        formData.append('file', this.forms.create.body.file);

        const response = await axios.post('api/v1/contracts', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        this.index();
        $(this.$refs.contract_modal).modal('hide');
      } catch (error) {
        // Erros no envio do arquivo
        if (error.response.data.errors.file) {
          this.forms.create.errors = error.response.data.errors.file;
        } else {
          // Tratando erros de validação
          this.forms.create.errors = error.response.data.errors.map(error => {
            return error[0];
          });
        }
      } finally {
        this.forms.create.loading = false;
      }
    },
    // Apaga um contrato
    async destroy(id) {
      if (confirm('Tem certeza que deseja apagar o contrato?')) {
        const response = await axios.delete(`api/v1/contracts/${id}`);
        this.index();
      }
    }
  }
};
</script>
