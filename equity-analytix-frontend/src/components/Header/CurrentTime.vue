<template>
  <div class="time">{{ convertedCurrentTime }}</div>
</template>

<script>
export default {
  name: "CurrentTime",
  props: {
    currentTime: {
      default: "",
      required: true,
    },
  },
  computed: {
    convertedCurrentTime() {
      return this.currentTime ? this.currentTime.format("h:mm:ss") : "";
    },
  },
};
</script>

<style lang="scss">
.current-time {
  text-align: center;
}
</style>
