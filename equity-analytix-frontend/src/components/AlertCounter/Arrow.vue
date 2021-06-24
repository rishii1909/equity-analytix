<template>
  <svg width="9" height="7" viewBox="0 0 9 7" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path
      v-if="incomingMessagesOnTop"
      d="M 1.184 5.162 L 4.41 1.846 L 7.719 5.14"
      stroke="white"
      stroke-width="2"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
    <path
      v-else
      d="M1.25 1.875L4.5 5.125L7.75 1.875"
      stroke="white"
      stroke-width="2"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
  </svg>
</template>

<script>
export default {
  name: "Arrow",
  props: {
    incomingMessagesOnTop: {
      type: Boolean,
      default: null,
      required: false,
    },
  },
};
</script>

<style></style>
