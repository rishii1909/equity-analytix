<template>
  <div class="chat-window-control">
    <div class="window-icon window-icon--hide">
      <img :src="`${publicPath}images/hide-window.svg`" alt="Hide">
    </div>
    <div class="window-icon window-icon--expand">
      <img :src="`${publicPath}images/expand-window.svg`" alt="Expand">
    </div>
    <div class="window-icon window-icon--close">
      <img :src="`${publicPath}images/close-window.svg`" alt="Close">
    </div>
  </div>
</template>

<script>
  import {urlBaseToPicture} from "../../../appConfig";

  export default {
    name: 'ChatWindowControl',
    data: () => ({
      publicPath: urlBaseToPicture
    })
  }
</script>
