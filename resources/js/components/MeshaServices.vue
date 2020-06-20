<template>
  <div>
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h5>Serviços</h5>
        <button
          type="button"
          class="btn btn-primary"
          data-toggle="modal"
          data-target="#servico-modal"
        >
          Cadastrar
        </button>
      </div>

      <table
        v-if="services.data && services.data.length"
        class="table card-body"
      >
        <thead>
          <th>Nome</th>
          <th>Ações</th>
        </thead>
        <tbody>
          <tr v-for="(service, key) in services.data" :key="`service-${key}`">
            <td>
              {{ service.name }}
            </td>
            <td>
              <button
                type="button"
                class="btn btn-primary"
                data-toggle="modal"
                data-target="#servico-editar-modal"
              >
                Editar
              </button>
              <button type="button" class="btn btn-danger">
                Excluir
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="card-body" v-else>
        <p>Não há serviços cadastrados</p>
      </div>
    </div>
    <div
      id="servico-modal"
      ref="servico_modal"
      class="modal fade"
      tabindex="-1"
      role="dialog"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Cadastrar serviço</h5>
            <button
              type="button"
              class="close"
              required
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="store()">
            <div class="modal-body">
              <div class="form-group">
                <label for="name" class="col-form-label">Nome</label>
                <input
                  required
                  type="text"
                  class="form-control"
                  v-model="forms.create.body.name"
                />
                <div class="text-danger" v-if="forms.create.errors.name">
                  <div
                    v-for="(error, key) in forms.create.errors.name"
                    :key="`error-name-${key}`"
                  >
                    {{ error }}
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                Cancelar
              </button>
              <button type="submit" class="btn btn-success">
                Salvar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      forms: {
        create: {
          body: {
            name: ''
          },
          errors: {}
        }
      },
      services: {}
    };
  },
  async created() {
    this.index();
  },
  methods: {
    async index() {
      try {
        const response = await axios.get('api/v1/services');
        this.services = response.data;
      } catch (error) {}
    },
    async store() {
      try {
        this.forms.create.errors = {};
        const response = await axios.post(
          'api/v1/services',
          this.forms.create.body
        );
        this.index();
        $(this.$refs.servico_modal).modal('hide');
      } catch (error) {
        this.forms.create.errors = error.response.data.errors;
      }
    }
  }
};
</script>
