<template>
  <div class="eq-chat">
    <ChatAdmin v-if="userOrAdmin" :currentTime="currentTime" />
    <ChatClient v-if="userOrAdmin === false" :currentTime="currentTime" />
  </div>
</template>

<script>
import ChatAdmin from "./ChatAdmin";
import ChatClient from "./ChatClient";
import moment from "moment";

export default {
  components: {
    ChatClient,
    ChatAdmin,
  },
  props: {
    userOrAdmin: Boolean,
  },
  data: () => ({
    timer: null,
    currentTime: null,
  }),
  created() {
    this.timer = setInterval(this.moment, 1000);
  },
  methods: {
    moment: function () {
      this.currentTime = moment();
    },
  },
  beforeDestroy() {
    clearInterval(this.timer);
  },
};

// FIXME
// - fix a bug with elapsed time (after reloading the page, the time increases by 1 hour. due to daylight saving time?)
// - fix a bug with initial settings for a new user (incorrect "incoming messages direction" and "stock symbol color" initial values)
// - fix bug with loss of connection with websocket ("2006 MySQL server has gone away". somehow track this broken connection
//   with the database?re-create the connection every N minutes?)
// - fix a bug with a double sound notification when the Internet is lost (connection with internet and connection with web socket)
// - fix incorrect display of elapsed time in search results
// - optimize performance by getting rid of the "moment()" function call every second (cpu usage)
// - optimize performance by finding memory leak (dom nodes)
// - optimize performance for admin message list (if there are many messages, then the cpu usage is too high)
// - optimize requests to the server for archiving messages (instead of one request for each message, use one bulk request. the backend
//   is already configured to receive an array. debouncing?)
</script>
