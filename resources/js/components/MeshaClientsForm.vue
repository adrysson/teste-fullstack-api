<template>
  <div>
    <div class="modal-body">
      <div class="form-group">
        <label for="name" class="col-form-label">Nome</label>
        <input
          required
          type="text"
          class="form-control"
          v-model="form.body.name"
        />
        <mesha-form-errors :errors="form.errors.name"></mesha-form-errors>
      </div>
      <div class="form-group">
        <label for="email" class="col-form-label">Email</label>
        <input
          required
          type="email"
          class="form-control"
          v-model="form.body.email"
        />
        <mesha-form-errors :errors="form.errors.email"></mesha-form-errors>
      </div>
      <div class="form-group">
        <label for="phone" class="col-form-label">Telefone</label>
        <input
          required
          type="tel"
          v-mask="'(##) #####-####'"
          class="form-control"
          v-model="form.body.phone"
        />
        <mesha-form-errors :errors="form.errors.phone"></mesha-form-errors>
      </div>
      <div class="form-group">
        <label for="services" class="col-form-label">Serviços</label>
        <v-select
          multiple
          @search="getServices"
          v-model="form.body.services_selected"
          :options="serviceOptions"
        />
        <mesha-form-errors :errors="form.errors.services"></mesha-form-errors>
      </div>
    </div>
    <mesha-modal-footer :loading="form.loading"></mesha-modal-footer>
  </div>
</template>

<script>
import { mask } from 'vue-the-mask';

export default {
  directives: { mask },
  props: {
    form: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      serviceOptions: []
    };
  },
  computed: {
    services: {
      get(value) {
        return value;
      },
      set(value) {
        const serviceOptions = value.data.map(service => {
          return {
            code: service.id,
            label: service.name
          };
        });
        this.serviceOptions = serviceOptions;
      }
    }
  },
  created() {
    this.getServices();
  },
  watch: {
    'form.body': {
      handler: function() {
        this.getServices();
      },
      deep: true
    }
  },
  methods: {
    // Busca lista de serviços
    async getServices(search) {
      let url = 'api/v1/services';
      if (search) {
        url += `?name=${search}`;
      }
      const response = await axios.get(url);
      this.services = response.data;
    }
  }
};
</script>
