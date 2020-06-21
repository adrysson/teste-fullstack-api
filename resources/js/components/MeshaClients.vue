<template>
  <div>
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h5>Clientes</h5>
        <button type="button" class="btn btn-primary" @click="create()">
          Cadastrar
        </button>
      </div>

      <table v-if="clients.data && clients.data.length" class="table card-body">
        <thead class="text-center">
          <th>Nome</th>
          <th>Email</th>
          <th>Telefone</th>
          <th>Serviços</th>
          <th>Ações</th>
        </thead>
        <tbody>
          <tr v-for="(client, key) in clients.data" :key="`client-${key}`">
            <td>
              {{ client.name }}
            </td>
            <td>
              {{ client.email }}
            </td>
            <td>
              {{ client.phone }}
            </td>
            <td>
              {{ client.services_list }}
            </td>
            <td class="text-center">
              <div class="d-flex">
                <button
                  type="button"
                  class="btn btn-primary mr-1"
                  @click="edit(client.id)"
                >
                  Editar
                </button>
                <button
                  type="button"
                  @click="destroy(client.id)"
                  class="btn btn-danger"
                >
                  Excluir
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="card-body" v-else>
        <p>Não há clientes cadastrados</p>
      </div>
      <div class="card-footer" v-if="clients.last_page > 1">
        <mesha-pagination :data="clients"></mesha-pagination>
      </div>
    </div>
    <!-- Modal de cadastro de serviço -->
    <div
      id="client-modal"
      ref="client_modal"
      class="modal fade"
      tabindex="-1"
      role="dialog"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <mesha-modal-header>Cadastrar serviço</mesha-modal-header>
          <form @submit.prevent="store()">
            <mesha-clients-form :form="forms.create"></mesha-clients-form>
          </form>
        </div>
      </div>
    </div>
    <!-- Fim do modal de cadastro de serviço -->
    <!-- Modal edição de serviço -->
    <div
      id="client-edit-modal"
      ref="client_edit_modal"
      class="modal fade"
      tabindex="-1"
      role="dialog"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <mesha-modal-header>Editar serviço</mesha-modal-header>
          <form @submit.prevent="update(forms.edit.body.id)">
            <mesha-clients-form :form="forms.edit"></mesha-clients-form>
          </form>
        </div>
      </div>
    </div>
    <!-- Fim do modal de edição de serviço -->
  </div>
</template>

<script>
import { mask } from 'vue-the-mask';

export default {
  directives: { mask },
  data() {
    return {
      forms: {
        create: {
          loading: false,
          body: {
            name: '',
            email: '',
            phone: '',
            services: []
          },
          errors: {}
        },
        edit: {
          body: {
            loading: false,
            id: '',
            email: '',
            phone: '',
            services: []
          },
          errors: {}
        }
      },
      clients: {}
    };
  },
  created() {
    this.index();
  },
  methods: {
    // Busca lista de clientes
    async index() {
      const response = await axios.get('api/v1/clients');
      this.clients = response.data;
    },
    // Exibe modal de criação de serviço
    create() {
      $(this.$refs.client_modal).modal('show');
    },
    // Cria serviço
    async store() {
      try {
        this.forms.create.loading = true;
        this.forms.create.errors = {};
        const response = await axios.post(
          'api/v1/clients',
          this.forms.create.body
        );
        this.index();
        $(this.$refs.client_modal).modal('hide');
      } catch (error) {
        this.forms.create.errors = error.response.data.errors;
      } finally {
        this.forms.create.loading = false;
      }
    },
    // Exibe modal de edição de serviço
    edit(id) {
      const client = this.clients.data.find(function(client) {
        return client.id === id;
      });
      this.forms.edit.body = Object.assign({}, client);
      $(this.$refs.client_edit_modal).modal('show');
    },
    // Atualiza um serviço
    async update(id) {
      try {
        this.forms.edit.loading = true;
        this.forms.edit.errors = {};
        const response = await axios.put(
          `api/v1/clients/${id}`,
          this.forms.edit.body
        );
        this.index();
        $(this.$refs.client_edit_modal).modal('hide');
      } catch (error) {
        this.forms.edit.errors = error.response.data.errors;
      } finally {
        this.forms.edit.loading = false;
      }
    },
    // Apaga um serviço
    async destroy(id) {
      if (confirm('Tem certeza que deseja apagar o serviço?')) {
        const response = await axios.delete(`api/v1/clients/${id}`);
        this.index();
      }
    }
  }
};
</script>
