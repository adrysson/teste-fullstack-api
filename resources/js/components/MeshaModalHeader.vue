<template>
  <div class="modal-header">
    <h5 class="modal-title">
      <slot></slot>
    </h5>
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
</template>
