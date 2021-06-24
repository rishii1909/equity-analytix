<template>
  <div class="users-list" :class="{ open: isActive }" v-click-outside="clickOutsideHandler">
    <div class="users-list-btn" :class="{ open: isActive }" @click="$emit('openModalUser')">
      <span></span>
      <span></span>
    </div>
    <div class="users-list--item" v-for="user in users">
      <div class="status" :class="user.isOnline ? 'online' : 'offline'"></div>
      <div class="name">{{ user.user_name }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "UsersList",
  props: {
    isActive: Boolean,
  },
  data: () => ({}),
  computed: {
    users: function () {
      const allUsers = this.$store.getters["chat/users"];
      return allUsers.sort((a, b) => {
        if (a.isOnline === b.isOnline) {
          return a.user_name.toLowerCase() > b.user_name.toLowerCase() ? 1 : -1;
        }
        if (a.isOnline) return -1;
      });
    },
  },
  methods: {
    clickOutsideHandler() {
      const isUsersModalOpen = this.$store.getters["settingHeader/isUsersModalOpen"];
      if (isUsersModalOpen) this.$emit("clickOutside");
    },
  },
};
</script>
