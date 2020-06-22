<template>
  <div>
    <div class="modal-body">
      <div class="form-group">
        <label for="name" class="col-form-label">Arquivo</label>
        <div class="custom-file">
          <input
            required
            type="file"
            class="custom-file-input"
            id="validatedCustomFile"
            ref="file"
            @change="setFile"
          />
          <label class="custom-file-label" for="validatedCustomFile"
            >Escolha o arquivo</label
          >
        </div>
        <mesha-form-errors :errors="form.errors"></mesha-form-errors>
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
  },
  methods: {
    setFile(event) {
      this.form.body.file = this.$refs.file.files[0];
    }
  }
};
</script>
