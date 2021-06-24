<template>
  <div class="eq-chat">
    <div class="eq-chat--inner">
      <div class="loader">
        <Loader :isLoading="isLoading" :inscription="false" />
      </div>
      <HeaderChat
        @toggleSettings="toggleSettings"
        @clickOnLogo="onLogoClick"
        @toggleNotifications="toggleNotifications"
        :currentTime="currentTime"
      />
      <div class="eq-chat-container">
        <SettingsLayout v-if="isSettingsOpen" />
        <MessagesList :feed="feed" :author-id="0" class="messages-list" />
        <div class="input-container">
          <MessageInputChat
            :messageCount="feed.length"
            @sendMessage="sendNews"
            @currentInputText="inputTextHandler"
          />
          <MessageStatusPanel
            v-model="message.status"
            :messageCount="feed.length"
            @clickToStatus="clickToStatusHandler"
          />
        </div>
        <UsersList
          @openModalUser="toggleModalUsersList"
          @clickOutside="closeModalUsersList"
          :is-active="isUsersModalOpen"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { token, webSocketUrl } from '../../appConfig'
import moment from 'moment-timezone'
import MessagesList from '../components/MessageList'
import HeaderChat from '../components/Header/HeaderChat'
// import SettingsLayout from '@/components/Settings/SettingsLayout'
import SettingsLayout from '/components/Settings/SettingsLayout'
import MessageInputChat from '../components/MessageInputChat'
import MessageStatusPanel from '../components/MessageStatusPanel'
import UsersList from '../components/UsersList'
// import Loader from '@/components/Loader'
import Loader from '/components/Loader'
// import { loadSound, playSound, soundPath } from '@/assets/helpers/soundProcessing'
import { loadSound, playSound, soundPath } from '/assets/helpers/soundProcessing'

export default {
  name: 'ChatAdmin',
  components: {
    HeaderChat,
    SettingsLayout,
    UsersList,
    MessagesList,
    MessageInputChat,
    MessageStatusPanel,
    Loader,
  },
  props: {
    currentTime: {
      default: '',
      required: true,
    },
  },
  data: () => ({
    socket: null,
    reconnectInterval: null,
    isTryingToReconnect: false,
    feed: [],
    currentUserId: '',
    convertedUserSettingsFromServer: {},
    userSettingsLocal: {},
    isSettingsOpen: false,
    isUsersModalOpen: false,
    isNotificationsOn: false,
    audioContext: null,
    connectedSound: null,
    connectionLostSound: null,
    isLoading: false,
    currentInputText: '',
    textAreaHeight: '',
    textAreaRef: '',
    message: {
      token: token.key,
      status: 'none',
      user: '',
      text: '',
      messageType: 'news',
    },
  }),
  created() {
    this.currentUserId = +this.$store.getters['chat/userInfo'].info.id
    this.message.user = this.currentUserId

    this.getUserSettings()
    this.connectToWebSocketServer()
    this.getAllUsersData()

    this.audioContext = new (window.AudioContext || window.webkitAudioContext)()
    this.connectedSound = loadSound(soundPath.connected, this.audioContext)
    this.connectionLostSound = loadSound(soundPath.connectionLost, this.audioContext)

    window.addEventListener('online', this.setOnlineInternetStatus)
    window.addEventListener('offline', this.setOfflineInternetStatus)
    window.onbeforeunload = () => {
      window.removeEventListener('online', this.setOnlineInternetStatus)
      window.removeEventListener('offline', this.setOfflineInternetStatus)
      clearInterval(this.reconnectInterval)
    }
  },
  methods: {
    connectToWebSocketServer() {
      console.log('webSocketUrl', webSocketUrl)
      this.socket = new WebSocket(webSocketUrl)

      this.socket.onopen = (event) => {
        console.log('admin websocket open', event)
        this.$store.commit('chat/setIsWebSocketRunning', true)

        const serviceMessage = JSON.stringify({
          messageType: 'connect',
          token: token.key,
        })
        this.socket.send(serviceMessage)

        if (this.$store.getters['settingHeader/isNotificationsOn']) {
          playSound(this.connectedSound, this.audioContext)
        }
      }

      this.socket.onmessage = async (event) => {
        const parsedData = JSON.parse(event.data)
        if (parsedData.messageType === 'news') return

        if (parsedData.messageType === 'onlineUsers') {
          await this.getAllUsersData()
          const onlineUsers = parsedData.userIds
          for (const usersId of onlineUsers) {
            this.$store.commit('chat/setUserOnlineStatus', { id: usersId, status: 'online' })
          }
        } else if (parsedData.messageType === 'connect') {
          this.$store.commit('chat/setUserOnlineStatus', {
            id: parsedData.userId,
            status: parsedData.onlineStatus,
          })
        }
      }

      this.socket.onclose = (event) => {
        console.log('admin websocket close', event)
        this.$store.commit('chat/setIsWebSocketRunning', false)

        if (this.$store.getters['settingHeader/isNotificationsOn'] && !this.isTryingToReconnect) {
          playSound(this.connectionLostSound, this.audioContext)
        }

        if (!this.isTryingToReconnect) {
          this.socket = null
          this.reconnectInterval = setInterval(this.reconnectToWebSocketServer, 10000)
        }
      }

      this.socket.onerror = (event) => {
        console.error('admin websocket error', event)
      }
    },
    reconnectToWebSocketServer() {
      this.isTryingToReconnect = true
      if (!this.$store.getters['chat/isWebSocketRunning']) {
        console.log('trying to reconnect to websocket server...')
        this.connectToWebSocketServer()
      } else {
        console.log('connected to websocket server')
        clearInterval(this.reconnectInterval)
        this.isTryingToReconnect = false
      }
    },
    setOnlineInternetStatus() {
      this.$store.commit('chat/setIsOnline', true)

      if (this.$store.getters['settingHeader/isNotificationsOn']) {
        playSound(this.connectedSound, this.audioContext)
      }
    },
    setOfflineInternetStatus() {
      this.$store.commit('chat/setIsOnline', false)

      if (this.$store.getters['settingHeader/isNotificationsOn']) {
        playSound(this.connectionLostSound, this.audioContext)
      }
    },

    getAllNews(options = { showLoader: false }) {
      if (options.showLoader) this.isLoading = true

      const newsCountBeforeUpdate = this.feed.length
      this.$store
        .dispatch('chat/getAllNews', { timestamp: moment().unix(), onlyForLastDay: false })
        .then((response) => {
          this.$store.commit('chat/setAllNews', response.data.data)
          this.feed = this.$store.getters['chat/news']
          this.feed.map((item) => {
            const estTime = moment.tz(item.created_at, 'America/New_York')
            item.time = estTime.local().format('h:mm:ss')
          })
          if (this.feed.length > newsCountBeforeUpdate) {
            this.scrollingToEnd()
          }
          if (options.showLoader) this.isLoading = false
        })
    },
    getUserSettings() {
      this.isLoading = true
      this.$store.dispatch('settings/getUserSettings').then(async (response) => {
        const userSettingsFromServer = response.data.data
        for (const setting of userSettingsFromServer) {
          const capitalizeSettingName = setting.name.replace(/^\w/, (firstSymbol) =>
            firstSymbol.toUpperCase()
          )
          this.$store.commit(`settings/set${capitalizeSettingName}`, setting.signification)
          this.convertedUserSettingsFromServer[setting.name] = {
            value: setting.signification,
            id: setting.id,
          }
        }
 
        await this.initializeUserSettings()
        await this.getAllNews({ showLoader: true })
      })
    },
    saveUserSettings() {
      for (const settingLocalName in this.userSettingsLocal) {
        if (this.convertedUserSettingsFromServer[settingLocalName]) {
          if (
            this.userSettingsLocal[settingLocalName] !==
            this.convertedUserSettingsFromServer[settingLocalName].value
          ) {
            const editData = {
              userId: this.currentUserId,
              settingId: Number(this.convertedUserSettingsFromServer[settingLocalName].id),
              signification: this.userSettingsLocal[settingLocalName],
            }
            this.isLoading = true
            this.$store.dispatch('settings/editUserSetting', editData).finally(() => {
              this.isLoading = false
            })
            this.convertedUserSettingsFromServer[settingLocalName].value = this.userSettingsLocal[
              settingLocalName
            ]
          }
        } else {
          const createData = {
            userId: this.currentUserId,
            name: settingLocalName,
            signification: this.userSettingsLocal[settingLocalName],
          }

          this.$store
            .dispatch('settings/createUserSetting', createData)
            .then((response) => {
              if (this.convertedUserSettingsFromServer[settingLocalName]) {
                this.convertedUserSettingsFromServer[
                  settingLocalName
                ].value = this.userSettingsLocal[settingLocalName]
              } else {
                this.convertedUserSettingsFromServer[settingLocalName] = {
                  id: response.data.data.id,
                  value: this.userSettingsLocal[settingLocalName],
                }
              }
            })
            .catch((error) => {
              console.error(error)
            })
            .finally(() => {
              this.isLoading = false
            })
        }
      }

      this.initializeUserSettings()
    },
    initializeUserSettings() {
      const root = document.documentElement
      this.userSettingsLocal = this.$store.getters['settings/allUserSettings']
      document.getElementById('send-block-text-area').style.setProperty('height', '105px')

      for (const setting in this.userSettingsLocal) {
        switch (setting) {
          case 'webLinkColor':
            root.style.setProperty('--link-color', `${this.userSettingsLocal[setting]}`)
            break
          case 'stockSymbolColor':
            root.style.setProperty('--stock-symbol-color', `${this.userSettingsLocal[setting]}`)
            break
          case 'deliveredTimeColor':
            root.style.setProperty('--time-posted-color', `${this.userSettingsLocal[setting]}`)
            break
          case 'textSize':
            root.style.setProperty('--font-size-message', `${this.userSettingsLocal[setting]}px`)
            break
          case 'textBold':
            root.style.setProperty(
              '--font-weight-message',
              this.userSettingsLocal[setting] === 'true' ? '600' : '100'
            )
            break
          case 'theme':
            if (this.userSettingsLocal[setting] === 'dark') {
              document.body.classList.remove('light')
              root.style.setProperty('--body-background', '#111111')
            } else {
              document.body.classList.add('light')
              root.style.setProperty('--body-background', '#F1F1F1')
            }
            break
        }
      }
    },
    async getAllUsersData() {
      this.isLoading = true
      const response = await this.$store.dispatch('chat/getAllUsers', moment().unix())
      this.$store.commit('chat/setAllUsers', response.data.data[0])
      this.isLoading = false
    }, 
    sendNews(inputText) {
      this.message.text = inputText
      // console.log(JSON.stringify(this.message));
      this.socket.send(JSON.stringify(this.message))
      const createdAt = moment()
      const localMessage = {
        ...this.message,
        created_at: createdAt.format(),
        time: createdAt.format('h:mm:ss'),
      }

      this.isTheEndOfFeed = true
      setTimeout(() => {
        this.isTheEndOfFeed = false
      }, 1000)
      this.scrollingToEnd()
      this.feed.push(localMessage)
      this.message.text = ''
    },
    async scrollingToEnd() {
      const container = await this.$el.querySelector('.eq-chat--message-list')
      container.scrollTop = container.scrollHeight

      const endPosition = container.scrollHeight
      const duration = 800
      this.scrollTo(container, endPosition, duration)
    },
    scrollTo(element, endPosition, duration) {
      const startPosition = element.scrollTop
      const change = endPosition - startPosition
      let currentTime = 0
      const increment = 20
      const animateScroll = () => {
        currentTime += increment
        element.scrollTop = this.easeInOutQuad(currentTime, startPosition, change, duration)
        if (currentTime < duration) {
          setTimeout(animateScroll, increment)
        }
      }

      animateScroll()
    },
    easeInOutQuad(currentTime, startPosition, change, duration) {
      currentTime /= duration / 2
      if (currentTime < 1) return (change / 2) * currentTime * currentTime + startPosition
      currentTime--

      return (-change / 2) * (currentTime * (currentTime - 2) - 1) + startPosition
    },
    toggleModalUsersList() {
      this.isUsersModalOpen = !this.isUsersModalOpen
      this.$store.commit('settingHeader/toggleUsersModal', this.isUsersModalOpen)
    },
    closeModalUsersList() {
      this.isUsersModalOpen = false
      this.$store.commit('settingHeader/toggleUsersModal', false)
    },
    toggleSettings() {
      this.isSettingsOpen = !this.isSettingsOpen
      this.$store.commit('settingHeader/toggleSettings', this.isSettingsOpen)
      if (!this.isSettingsOpen) {
        this.saveUserSettings()
      }
    },
    onLogoClick() {
      if (this.isSettingsOpen) this.toggleSettings()
    },
    toggleNotifications() {
      this.isNotificationsOn = !this.isNotificationsOn
      this.$store.commit('settingHeader/toggleNotifications', this.isNotificationsOn)

      if (this.$store.getters['settingHeader/isNotificationsOn']) {
        // now Safari has gotten stricter — each sound file is now locked individually until it has been specifically played during a user tap
        // https://curtisrobinson.medium.com/how-to-auto-play-audio-in-safari-with-javascript-21d50b0a2765
        playSound(this.connectedSound, this.audioContext, true)
      }
    },
    clickToStatusHandler() {
      setTimeout(() => {
        if (!this.message.text) {
          if (
            this.message.status === 'bull' ||
            this.message.status === 'bear' ||
            this.message.status === 'alert' ||
            this.message.status === 'switch'
          ) {
            this.sendNews('ALERT')
          }
        } else {
          this.sendNews(this.message.text)

          this.textAreaRef.value = ''
          this.textAreaRef.style.height = `${this.textAreaHeight}`
        }
      })
    },
    inputTextHandler(payload) {
      this.message.text = payload.inputText
      this.textAreaHeight = payload.textAreaHeight
      this.textAreaRef = payload.textAreaRef
    },
  },

  beforeDestroy() {
    window.removeEventListener('online', this.setOnlineInternetStatus)
    window.removeEventListener('offline', this.setOfflineInternetStatus)
    clearInterval(this.reconnectInterval)
  },
}
</script>

<style lang="scss">
.loader {
  position: absolute;
}
.loader .btn {
  color: #98a6ad;
}
</style>
