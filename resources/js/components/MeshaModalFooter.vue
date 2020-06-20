<template>
  <div class="modal-footer d-flex justify-content-between">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
      Cancelar
    </button>
    <button type="submit" class="btn btn-success">
      <div class="d-flex">
        <div
          v-if="loading"
          class="spinner-border text-white spinner-border-sm mr-1"
          role="status"
        >
          <span class="sr-only">Loading...</span>
        </div>
        <div>Salvar</div>
      </div>
    </button>
  </div>
</template>

<script>
export default {
  props: {
    loading: {
      type: Boolean,
      required: false,
      default: false
    }
  }
}
</script>