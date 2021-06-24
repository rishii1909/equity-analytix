<template>
  <div class="eq-chat--control-panel">
    <TitleChat @clickOnLogo="$emit('clickOnLogo')" />
    <CurrentTime :currentTime="currentTime" />
    <SettingIconChat
      @toggleInput="$emit('toggleInput')"
      @toggleSettings="$emit('toggleSettings')"
      @deleteAllMessages="$emit('deleteAllMessages')"
      @refreshAllMessages="$emit('refreshAllMessages')"
      @toggleKeySearch="$emit('toggleKeySearch')"
      @toggleNotifications="$emit('toggleNotifications')"
    />
  </div>
</template>

<script>
import TitleChat from "./TitleChat";
import CurrentTime from "./CurrentTime";
import SettingIconChat from "./SettingIconChat.vue";

export default {
  props: {
    getAllNews: {
      type: Function,
    },
    currentTime: {
      default: "",
      required: true,
    },
  },
  name: "HeaderChat",
  components: {
    TitleChat,
    CurrentTime,
    SettingIconChat,
  },
};
</script>
