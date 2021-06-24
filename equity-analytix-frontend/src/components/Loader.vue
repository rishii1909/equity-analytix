<template>
  <div v-if="isLoading">
    <b-button class="btn-loader" variant="white" disabled>
      <b-spinner small type="grow"></b-spinner>
      <span v-show="inscription"> Loading...</span>
    </b-button>
  </div>
</template>

<script>
export default {
  name: 'Loader',
  props: {
    isLoading: Boolean,
    inscription: false,
  },
}
</script>
<style>
.btn-loader {
  position: absolute;
  left: 100px;
}
</style>
