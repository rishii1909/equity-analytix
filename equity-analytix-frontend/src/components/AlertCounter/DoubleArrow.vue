<template>
  <div>
    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path
        v-if="incomingMessagesOnTop"
        d="M 1.184 5.162 L 4.41 1.846 L 7.719 5.14 M 1.184 9.162 L 4.41 5.846 L 7.719 9.14"
        stroke="white"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
      <path
        v-else
        d="M1.25 1.875L4.5 5.125L7.75 1.875 M1.25 5.875L4.5 9.125L7.75 5.875"
        stroke="white"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
    </svg>
  </div>
</template>

<script>
export default {
  name: "DoubleArrow",
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
