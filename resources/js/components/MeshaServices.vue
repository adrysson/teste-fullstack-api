<template>
  <div>
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h5>Serviços</h5>
        <button type="button" class="btn btn-primary" @click="create()">
          Cadastrar
        </button>
      </div>

      <table
        v-if="services.data && services.data.length"
        class="table card-body"
      >
        <thead class="text-center">
          <th>Nome</th>
          <th>Ações</th>
        </thead>
        <tbody>
          <tr v-for="(service, key) in services.data" :key="`service-${key}`">
            <td>
              {{ service.name }}
            </td>
            <td class="text-center">
              <button
                type="button"
                class="btn btn-primary"
                @click="edit(service.id)"
              >
                Editar
              </button>
              <button
                type="button"
                @click="destroy(service.id)"
                class="btn btn-danger"
              >
                Excluir
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="card-body" v-else>
        <p>Não há serviços cadastrados</p>
      </div>
      <div class="card-footer" v-if="services.last_page > 1">
        <mesha-pagination :data="services"></mesha-pagination>
      </div>
    </div>
    <!-- Modal de cadastro de serviço -->
    <div
      id="service-modal"
      ref="service_modal"
      class="modal fade"
      tabindex="-1"
      role="dialog"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <mesha-modal-header>Cadastrar serviço</mesha-modal-header>
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
                <mesha-form-errors
                  :errors="forms.create.errors.name"
                ></mesha-form-errors>
              </div>
            </div>
            <mesha-modal-footer
              :loading="forms.create.loading"
            ></mesha-modal-footer>
          </form>
        </div>
      </div>
    </div>
    <!-- Fim do modal de cadastro de serviço -->
    <!-- Modal edição de serviço -->
    <div
      id="service-edit-modal"
      ref="service_edit_modal"
      class="modal fade"
      tabindex="-1"
      role="dialog"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <mesha-modal-header>Editar serviço</mesha-modal-header>
          <form @submit.prevent="update(forms.edit.body.id)">
            <div class="modal-body">
              <div class="form-group">
                <label for="name" class="col-form-label">Nome</label>
                <input
                  required
                  type="text"
                  class="form-control"
                  v-model="forms.edit.body.name"
                />
                <mesha-form-errors
                  :errors="forms.edit.errors.name"
                ></mesha-form-errors>
              </div>
            </div>
            <mesha-modal-footer
              :loading="forms.edit.loading"
            ></mesha-modal-footer>
          </form>
        </div>
      </div>
    </div>
    <!-- Fim do modal de edição de serviço -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      forms: {
        create: {
          loading: false,
          body: {
            name: ''
          },
          errors: {}
        },
        edit: {
          body: {
            loading: false,
            id: '',
            name: ''
          },
          errors: {}
        }
      },
      services: {}
    };
  },
  created() {
    this.index();
  },
  methods: {
    // Busca lista de serviços
    async index() {
      const response = await axios.get('api/v1/services');
      this.services = response.data;
    },
    // Exibe modal de criação de serviço
    create() {
      $(this.$refs.service_modal).modal('show');
    },
    // Cria serviço
    async store() {
      try {
        this.forms.create.loading = true;
        this.forms.create.errors = {};
        const response = await axios.post(
          'api/v1/services',
          this.forms.create.body
        );
        this.index();
        $(this.$refs.service_modal).modal('hide');
      } catch (error) {
        this.forms.create.errors = error.response.data.errors;
      } finally {
        this.forms.create.loading = false;
      }
    },
    // Exibe modal de edição de serviço
    edit(id) {
      const service = this.services.data.find(function(service) {
        return service.id === id;
      });
      this.forms.edit.body = Object.assign({}, service);
      $(this.$refs.service_edit_modal).modal('show');
    },
    // Atualiza um serviço
    async update(id) {
      try {
        this.forms.edit.loading = true;
        this.forms.edit.errors = {};
        const response = await axios.put(
          `api/v1/services/${id}`,
          this.forms.edit.body
        );
        this.index();
        $(this.$refs.service_edit_modal).modal('hide');
      } catch (error) {
        this.forms.edit.errors = error.response.data.errors;
      } finally {
        this.forms.edit.loading = false;
      }
    },
    // Apaga um serviço
    async destroy(id) {
      if (confirm('Tem certeza que deseja apagar o serviço?')) {
        const response = await axios.delete(`api/v1/services/${id}`);
        this.index();
      }
    }
  }
};
</script>
