<template>
  <div class="text-danger" v-if="errors">
    <div
      v-for="(error, key) in errors"
      :key="`error-name-${key}`"
    >
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    errors: {
      type: Array,
      required: false,
      default: []
    }
  }
};
</script>
