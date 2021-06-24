<template>
  <div>
    <div
      v-if="numberOfNotViewedMessages && !isKeySearchOpen && !isCounterHidden && !isTheEndOfFeed"
      class="eq-chat--alert-counter-container"
      :class="[incomingMessagesOnTop ? alertTopClass : alertBottomClass]"
      ref="alertCounterContainer"
      @click.prevent="scrollingToEnd"
    >
      <div class="eq-chat--alert-counter">
        <span>
          <Arrow :incomingMessagesOnTop="incomingMessagesOnTop" />
        </span>
        <span class="eq-chat--alert-counter--label"
          >{{ `${numberOfNotViewedMessages} Alerts` }}
        </span>
      </div>
    </div>
    <div
      v-if="isNeedScrollButton && !numberOfNotViewedMessages && !isCounterHidden"
      class="eq-chat--alert-counter-container"
      :class="[incomingMessagesOnTop ? alertTopClass : alertBottomClass]"
      ref="alertCounterContainer"
      @click.prevent="scrollingToEnd"
    >
      <div class="eq-chat--alert-counter">
        <span>
          <DoubleArrow :incomingMessagesOnTop="incomingMessagesOnTop" />
        </span>
      </div>
    </div>
  </div>
</template>

<script>
// import Arrow from "@/components/AlertCounter/Arrow";
import Arrow from "/components/AlertCounter/Arrow";
// import DoubleArrow from "@/components/AlertCounter/DoubleArrow";
import DoubleArrow from "/components/AlertCounter/DoubleArrow";
export default {
  name: "AlertCounter",
  components: { DoubleArrow, Arrow },
  props: {
    numberOfNotViewedMessages: {
      type: Number,
      default: 0,
      required: true,
    },
    isKeySearchOpen: {
      type: Boolean,
      default: false,
      required: true,
    },
    scrollingToEnd: {
      type: Function,
      default: null,
      required: true,
    },
    isTheEndOfFeed: {
      type: Boolean,
      default: false,
      required: false,
    },
    messageListScrollTop: {
      type: Number,
      default: null,
      required: false,
    },
    messageListScrollHeight: {
      type: Number,
      default: null,
      required: false,
    },
    messageListOffsetHeight: {
      type: Number,
      default: null,
      required: false,
    },
  },
  data: () => ({
    isCounterHidden: false,
    alertTopClass: "alert-counter-container-top",
    alertBottomClass: "alert-counter-container-bottom",
  }),
  computed: {
    incomingMessagesOnTop: function () {
      return this.$store.getters["settings/incomingMessages"] === "up";
    },
    isNeedScrollButton: function () {
      if (this.incomingMessagesOnTop && this.messageListScrollHeight) {
        return this.messageListScrollTop !== 0;
      } else if (!this.incomingMessagesOnTop && this.messageListScrollHeight) {
        return (
          this.messageListScrollHeight - this.messageListScrollTop !== this.messageListOffsetHeight
        );
      }
    },
  },
  watch: {
    numberOfNotViewedMessages: function () {
      if (this.numberOfNotViewedMessages === 1) {
        this.isCounterHidden = true;
        setTimeout(() => {
          this.isCounterHidden = false;
        }, 500);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.eq-chat--alert-counter-container {
  position: absolute;
  left: 42%;
  user-select: none;
}

.alert-counter-container-top {
  top: 16%;
}
.alert-counter-container-bottom {
  bottom: 14%;
}

.eq-chat--alert-counter {
  width: 93px;
  height: 22px;
  line-height: 22px;
  text-align: center;
  background: #0055aa;
  color: #ffffff;
  border-radius: 4px;
  cursor: pointer;
}

.eq-chat--alert-counter--label {
  margin-left: 10px;
}
</style>
