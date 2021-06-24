<template>
  <div class="logo" @click="$emit('clickOnLogo')">
    <a href="#">
      <img :src="`${publicPath}images/eq-logo.svg`" alt="" />
    </a>
  </div>
</template>

<script>
import { urlBaseToPicture } from "../../../appConfig";

export default {
  name: "Title",
  data: () => ({
    publicPath: urlBaseToPicture,
  }),
};
</script>
