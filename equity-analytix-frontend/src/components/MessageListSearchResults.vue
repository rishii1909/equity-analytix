<template>
  <div id="messages-list-id" class="messages-list search-results">
    <div class="eq-chat--message-list" ref="messageList">
      <div
        class="eq-chat--message-item bg-black"
        v-for="(message, index) in feedSearchResults"
        :key="index"
      >
        <MessageItem
          :feed="feedSearchResults"
          :index="index"
          :id="message.id"
          :created="message.created_at"
          :time="message.time"
          :date="message.date"
          :text="message.text"
          :status="message.status"
          :currentTime="currentTime"
        />
      </div>
    </div>
  </div>
</template>

<script>
// import MessageItem from "@/components/MessageItem";
import MessageItem from "/components/MessageItem";

export default {
  name: "MessageListSearchResults",
  components: {
    MessageItem,
  },
  props: {
    feedSearchResults: {
      type: Array,
      default: [],
      required: false,
    },
    currentTime: {
      default: "",
      required: false,
    },
  },
};
</script>
