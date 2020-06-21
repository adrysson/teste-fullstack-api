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
        <mesha-form-errors
          :errors="form.errors.name"
        ></mesha-form-errors>
      </div>
    </div>
    <mesha-modal-footer :loading="form.loading"></mesha-modal-footer>
  </div>
</template>

<script>
export default {
  props: {
    form: {
      type: Object,
      required: true
    }
  }
};
</script>
