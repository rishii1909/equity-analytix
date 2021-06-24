<template>
  <div id="app">
    <Chat :userOrAdmin="admin.role" />
    <Loader :isLoading="isLoading" :inscription="true" />
    {{ error }}
  </div>
</template>

<script>
import Chat from './view/Chat.vue'
import Loader from './components/Loader'

export default {
  name: 'App',
  components: {
    Loader,
    Chat,
  },
  created() {
    this.$store
      .dispatch('chat/getUserInfo')
      .then((response) => {
        console.log(response)
        this.isLoading = false
        this.admin = response.data.data
        this.admin.role = this.admin.role === 'administrator'
        this.$store.commit('chat/setUserInfo', this.admin)
      })
      .catch((error) => {
        console.log(error);

        this.error = error.response.data ? error.response.data.data.message : error
      })

    const root = document.documentElement
    root.style.setProperty('--body-background', '#111111')
  },
  data: () => ({
    admin: {role : ''},
    isLoading: true,
    error: null,
  }),
}
</script>

<style lang="scss">
@import 'assets/style/bootstrap';
@import 'assets/style/variables';
@import 'assets/style/public';
</style>
