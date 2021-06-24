<template>
  <div class="eq-chat--send-block" v-if="!isInputHidden">
    <form
      @submit.prevent="onSubmitButton"
      autocomplete="off"
      type="submit"
      class="eq-chat--form-block"
      ref="form"
    >
      <textarea
        type="text"
        id="send-block-text-area"
        placeholder="Type a message..."
        class="send-block--input autoresize"
        v-model="inputText"
        @input="inputHandler"
        @keyup.enter.exact="onSubmitEnter"
        ref="textArea"
      />
      <div class="button-container">
        <button type="submit" class="send-block--btn">
          <img :src="`${publicPath}images/send-arrow.png`" alt="" />
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import { urlBaseToPicture } from "../../appConfig";

export default {
  name: "MessageInputChat",
  props: {
    value: String,
    messageCount: {
      type: Number,
      default: 0,
      required: false
    }
  },
  data: () => {
    return {
      inputText: "",
      textAreaHeight: "",
      publicPath: urlBaseToPicture
    };
  },
  computed: {
    isInputHidden() {
      return !this.$store.getters["settingHeader/isInputHidden"];
    }
  },
  watch: {
    messageCount: function() {
      this.inputText = "";
    }
  },
  mounted() {
    this.textAreaHeight = this.$store.getters["chat/isAdmin"] ? "105px" : "46px";
  },
  methods: {
    onSubmitButton() {
      this.$emit("sendMessage", this.inputText);
      this.$refs.textArea.value = "";
      this.$refs.textArea.style.height = `${this.textAreaHeight}`;
    },
    onSubmitEnter() {
      console.log("onSubmitEnter");
      if (this.$store.getters["chat/isAdmin"]) return;
      if (this.inputText === "" || this.inputText === "\n") {
        this.$refs.textArea.value = "";
        this.$refs.textArea.style.height = `${this.textAreaHeight}`;
        return;
      }

      this.$emit("sendMessage", this.inputText);
      this.$refs.textArea.value = "";
      this.$refs.textArea.style.height = `${this.textAreaHeight}`;
    },
    inputHandler(ev) {
      this.resize(ev);
      const payload = {
        inputText: this.inputText,
        textAreaHeight: this.textAreaHeight,
        textAreaRef: this.$refs.textArea
      };
      this.$emit("currentInputText", payload);
    },
    resize(ev) {
      ev.target.style.height = `${this.textAreaHeight}`;
      ev.target.style.height = `${ev.target.scrollHeight}px`;
    },
    async onRightClick(ev) {
      // todo: uncomment before production
      // feature is temporarily disabled (doesn't work with http, only https)
      // @click.right.prevent="onRightClick"
      try {
        this.$refs.textArea.focus();
        this.inputText = await window.navigator.clipboard.readText();
        setTimeout(() => {
          ev.target.style.height = `${this.textAreaHeight}`;
          ev.target.style.height = `${ev.target.scrollHeight}px`;
        });
      } catch (e) {
        console.log(e);
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.eq-chat--send-block .send-block--input {
  resize: none;
  z-index: 10;
}

.eq-chat--send-block .send-block--input::placeholder {
  color: var(--chat-input-placeholder);
}

.eq-chat--form-block {
  width: 100%;
  display: flex;
}

.button-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  background-color: var(--chat-message-list);
}

.send-block--btn img {
  width: 70%;
}
</style>
