<template>
  <div id="messages-list-id" class="messages-list" ref="messageListContainer">
    <div class="eq-chat--message-list" ref="messageList" @scroll="onScroll">
      <div class="eq-chat--message-item bg-black" v-for="(message, index) in feed" :key="index">
        <MessageItem
          :feed="feed"
          :index="index"
          :id="message.id"
          :created="message.created_at"
          :time="message.time"
          :text="message.text"
          :status="message.status"
          :flashingData="flashingData"
          :currentTime="currentTime"
          @clickToDelete="clickToDelete"
          @lastMessageIsMounted="startObservingMessages"
        />
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import MessageItem from "./MessageItem";

export default {
  name: "MessagesList",
  components: {
    MessageItem,
  },
  props: {
    feed: {
      type: Array,
      default: function () { return [] },
      required: false,
    },
    isNeedToUpdateObservers: {
      type: Boolean,
      default: false,
      required: false,
    },
    currentTime: {
      default: "",
      required: false,
    },
  },
  data: () => ({
    flashingData: {
      isColorReset: true,
    },
    intersectionObserver: null,
  }),
  watch: {
    isNeedToUpdateObservers: function (value) {
      if (value) {
        this.startObservingMessages();
      }
    },
  },
  mounted() {
    const isAdmin = this.$store.getters["chat/isAdmin"];
    if (!isAdmin) {
      const observerOptions = {
        root: document.querySelector("#messages-list-id"),
        threshold: 0.75,
      };
      const observerCallback = (entries) => {

        entries.forEach((target) => {
          if (target.isIntersecting) {
            this.markMessageAsViewedOnServer(target.target.messageId);
          }
        });
      };
      this.messageObserver = new IntersectionObserver(observerCallback, observerOptions);
      this.$store.commit("chat/setMessageObserver", this.messageObserver);
    }
  },
  methods: {
    onScroll(event) {
      this.$emit("scrolling", event);
    },
    startObservingMessages() {
      if (!this.isAdmin && this.messageObserver) {
        const messages = this.$refs.messageList.children;
        for (let i = 0; i <= messages.length - 1; i++) {
          this.messageObserver.unobserve(messages[i]);
          // add message id to DOM node (for checks in the observer callback)
          messages[i].messageId = this.feed[i].id;
          this.messageObserver.observe(messages[i]);
        }
      }
    },
    markMessageAsViewedOnServer(id) {
      if (this.$store.getters["settingHeader/isKeySearchOpen"]) return;

      for (const message of this.feed) {
        if (message.id === id && message.isViewed) {
          return;
        }
      }

      this.activateAlertFlashes(id);

      // marks locally that the message has been viewed to avoid repeated requests to the server
      this.$emit("successfullyMarkedAsViewed", { id, markingWasSuccessful: true });

      const userId = +this.$store.getters["chat/userInfo"].info.id;
      console.log(moment().unix())
      this.$store
        .dispatch("chat/markMessageAsViewedOnServer", {
          timestamp: moment().unix(),
          messages: [
            {
              id,
              userId,
            },
          ],
        })
        .catch((error) => {
          console.error(error.response ? error.response.data.data.message : error);
        });
    },
    activateAlertFlashes(messageId) {
      if (this.$store.getters["chat/isAdmin"]) return;

      this.$store.commit("chat/setFlashingMessages", { id: messageId, type: "add" });

      let numberOfFlashes = +this.$store.getters["settings/numberOfFlashes"];
      const flashingSpeed = +this.$store.getters["settings/flashingSpeed"];

      let delay;
      let pause = false;
      switch (flashingSpeed) {
        case 1:
          delay = 1500;
          break;
        case 2:
          delay = 1200;
          break;
        case 3:
          delay = 1000;
          break;
        case 4:
          delay = 800;
          // pause = true;
          break;
        case 5:
          delay = 650;
          // pause = true;
          break;
        default:
          delay = 1000;
      }

      if (numberOfFlashes) {
        let flashCounter = 0;
        const flash = () => {
          flashCounter += 1;
          if (
            pause &&
            (flashCounter === 4 ||
              flashCounter === 8 ||
              flashCounter === 12 ||
              flashCounter === 16 ||
              flashCounter === 20 ||
              flashCounter === 24)
          ) {
            numberOfFlashes = numberOfFlashes + 1;
            return;
          }
          if (flashCounter > numberOfFlashes) {
            clearInterval(flashInterval);
            this.$store.commit("chat/setFlashingMessages", { id: messageId, type: "delete" });
          }
          this.flashingData.isColorReset = false;
          setTimeout(() => {
            this.flashingData.isColorReset = true;
          }, delay / 2);
        };
        flash();
        const flashInterval = setInterval(flash, delay);
      }
    },
    clickToDelete(messageId) {
      this.$emit("clickToDelete", messageId);
    },
  },
  beforeDestroy() {
    const messageObserver = this.$store.getters["chat/messageObserver"];
    messageObserver.disconnect();
    this.$store.commit("chat/setMessageObserver", null);
  },
};
</script>

<style>
.eq-chat--message-item {
  transition: background 0.3s linear;
}

.eq-chat--alerts-counter span {
  font-size: 14px;
}
</style>
