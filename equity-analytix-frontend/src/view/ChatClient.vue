<template>
  <div class="eq-chat--inner">
    <div class="loader">
      <Loader :isLoading="isLoading" :inscription="false" />
    </div>
    <HeaderChat
      @toggleInput="toggleInput"
      @toggleSettings="toggleSettings"
      @clickOnLogo="onLogoClick"
      @deleteAllMessages="deleteAllMessages"
      @refreshAllMessages="refreshAllMessages"
      @toggleKeySearch="toggleKeySearch"
      @toggleNotifications="toggleNotifications"
      :currentTime="currentTime"
    />
    <KeySearchInput
      v-if="isKeySearchOpen"
      :isSearchResultsEmpty="isSearchResultsEmpty"
      @keywordSubmitted="getSearchResults"
    />
    <div class="eq-chat-container">
      <SettingsLayout v-if="isSettingsOpen" />
      <MessageListSearchResults
        v-if="isSearchResultsInFeed"
        :feedSearchResults="feedSearchResults"
        :currentTime="currentTime"
      />
      <MessagesList
        v-show="!isKeySearchOpen && !isSearchResultsInFeed"
        ref="chatContainer"
        :feed="feed"
        :isNeedToUpdateObservers="isNeedToUpdateObservers"
        @clickToDelete="clickToDelete"
        @successfullyMarkedAsViewed="markMessageAsViewedLocal"
        @scrolling="saveScrollingData"
        :currentTime="currentTime"
      />
      <MessageInputChat />
      <AlertCounter
        :numberOfNotViewedMessages="numberOfNotViewedMessages"
        :isKeySearchOpen="isKeySearchOpen"
        :scrollingToEnd="scrollingToEnd"
        :isTheEndOfFeed="isTheEndOfFeed"
        :messageListScrollTop="messageListScrollTop"
        :messageListScrollHeight="messageListScrollHeight"
        :messageListOffsetHeight="messageListOffsetHeight"
      />
    </div>
  </div>
</template>

<script>
import moment from 'moment-timezone'
import MessageInputChat from '../components/MessageInputChat'
import MessagesList from '../components/MessageList'
import HeaderChat from '../components/Header/HeaderChat'
// import AlertCounter from '@/components/AlertCounter/AlertCounter'
import AlertCounter from '/components/AlertCounter/AlertCounter'
// import SettingsLayout from '@/components/Settings/SettingsLayout'
import SettingsLayout from '/components/Settings/SettingsLayout'
// import Loader from '@/components/Loader'
import Loader from '/components/Loader'
// import KeySearchInput from '@/components/KeySearchInput'
import KeySearchInput from '/components/KeySearchInput'
// import MessageListSearchResults from '@/components/MessageListSearchResults'
import MessageListSearchResults from '/components/MessageListSearchResults'
// import { loadSound, playSound, soundPath } from '@/assets/helpers/soundProcessing'
import { loadSound, playSound, soundPath } from '/assets/helpers/soundProcessing'
import { token, webSocketUrl } from '../../appConfig'

export default {
  name: 'ChatClient',
  components: {
    HeaderChat,
    KeySearchInput,
    SettingsLayout,
    MessagesList,
    MessageListSearchResults,
    AlertCounter,
    MessageInputChat,
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
    feedInitial: [],
    feedReversed: [],
    feedSearchResults: [],
    currentUserId: '',
    convertedUserSettingsFromServer: {},
    userSettingsLocal: {},
    isInputHidden: false,
    isNeedToReturnInput: false,
    isSettingsOpen: false,
    isKeySearchOpen: false,
    isSearchResultsEmpty: false,
    isNotificationsOn: false,
    audioContext: null,
    neutralMessageSound: null,
    positiveMessageSound: null,
    negativeMessageSound: null,
    warningMessageSound: null,
    connectedSound: null,
    connectionLostSound: null,
    isLoading: false,
    isNeedToUpdateObservers: false,
    messageListScrollHeight: null,
    messageListScrollTop: null,
    messageListOffsetHeight: null,
    isTheEndOfFeed: false,
  }),
  computed: {
    isSearchResultsInFeed: function () {
      return this.$store.getters['chat/keyword']
    },
    numberOfNotViewedMessages: function () {
      return this.feed.filter((message) => {
        return message && !message.isViewed
      }).length
    },
  },
  created() {
    this.currentUserId = +this.$store.getters['chat/userInfo'].info.id

    this.getUserSettings()
    this.connectToWebSocketServer()

    this.audioContext = new (window.AudioContext || window.webkitAudioContext)()
    this.neutralMessageSound = loadSound(soundPath.neutral, this.audioContext)
    this.positiveMessageSound = loadSound(soundPath.positive, this.audioContext)
    this.negativeMessageSound = loadSound(soundPath.negative, this.audioContext)
    this.warningMessageSound = loadSound(soundPath.warning, this.audioContext)
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
  mounted() {
    this.$store.commit('settingHeader/changeVisibleInput', false)
  },
  methods: {
    connectToWebSocketServer() {
      this.socket = new WebSocket(webSocketUrl)

      this.socket.onopen = (event) => {
        console.log('client websocket open', event)
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

      this.socket.onmessage = (event) => {
        const parsedData = JSON.parse(event.data)
        // service messages
        if (parsedData.messageType !== 'news') return

        const message = {
          ...parsedData,
          id: parsedData.id.value,
          created_at: parsedData.createdAt,
          time: moment(parsedData.createdAt).format('h:mm:ss'),
        }
        const incomingMessagesDirection = this.$store.getters['settings/incomingMessages']
        if (!this.messageListScrollTop) {
          this.messageListScrollTop = 0
        }
        if (incomingMessagesDirection === 'bottom') {
          if (
            this.messageListScrollHeight - this.messageListScrollTop ===
              this.messageListOffsetHeight ||
            (this.feed.length < 10 && this.messageListScrollTop === 0)
          ) {
            this.isTheEndOfFeed = true
            this.scrollingToEnd()
            setTimeout(() => {
              this.isTheEndOfFeed = false
            }, 1000)
          }
          this.feed.push(message)
        } else {
          this.feed.unshift(message)
          if (this.messageListScrollTop === 0) {
            this.isTheEndOfFeed = true
            this.scrollingToEnd(true)
          }
          setTimeout(() => {
            this.isTheEndOfFeed = false
          }, 1000)
        }
        this.feedInitial.push(message)
        this.feedReversed.unshift(message)

        if (this.$store.getters['settingHeader/isNotificationsOn']) {
          let sound = this.neutralMessageSound
          if (
            message.status === 'bull' ||
            message.status === 'arrowPos' ||
            message.status === 'sightPos' ||
            message.status === 'eyePos' ||
            message.status === 'xfilesPos'
          ) {
            sound = this.positiveMessageSound
          } else if (
            message.status === 'bear' ||
            message.status === 'arrowNeg' ||
            message.status === 'sightNeg' ||
            message.status === 'eyeNeg' ||
            message.status === 'xfilesNeg'
          ) {
            sound = this.negativeMessageSound
          } else if (message.status === 'alert') {
            sound = this.warningMessageSound
          }

          if (this.$store.getters['settingHeader/isNotificationsOn']) {
            playSound(sound, this.audioContext)
          }
        }
      }

      this.socket.onclose = (event) => {
        console.log('client websocket close', event)
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
        console.error('client websocket error', event)
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
      if (this.isSearchResultsInFeed) return

      if (options.showLoader) this.isLoading = true
      this.$store
        .dispatch('chat/getAllNews', { timestamp: moment().unix(), onlyForLastDay: true })
        .then((response) => {
          const viewedNews = response.data.data.viewed
          const notViewedNews = response.data.data.notViewed
          const allNews = notViewedNews.slice()
          viewedNews.forEach((message) => {
            message.isViewed = true
            allNews.push(message)
          })
          allNews.sort((a, b) => {
            const diff = moment(b.created_at).diff(moment(a.created_at))
            return diff < 0 ? 1 : -1
          })
          this.$store.commit('chat/setAllNews', allNews)
          this.feed = this.$store.getters['chat/news']
          this.feed.map((message) => {
            const estTime = moment.tz(message.created_at, 'America/New_York')
            message.time = estTime.local().format('h:mm:ss')
            message.created_at = estTime.local().format()
          })
          this.feedInitial = this.feed.slice()
          this.feedReversed = this.feed.slice().reverse()
          this.feed =
            this.$store.getters['settings/incomingMessages'] === 'up'
              ? this.feedReversed.slice()
              : this.feedInitial.slice()
          if (options.showLoader) this.isLoading = false
          this.scrollingToEnd()
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
          case 'incomingMessages':
            if (this.userSettingsLocal[setting] === 'up') {
              this.feed = this.feedReversed.slice()
              this.$refs.chatContainer.$refs.messageListContainer.style.setProperty(
                'justify-content',
                'flex-start'
              )
            } else {
              this.feed = this.feedInitial.slice()
              this.$refs.chatContainer.$refs.messageListContainer.style.setProperty(
                'justify-content',
                'flex-start'
              )
            }
            this.isNeedToUpdateObservers = true
            setTimeout(() => {
              this.isNeedToUpdateObservers = false
            }, 1000)
            this.scrollingToEnd()
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
    markMessageAsViewedLocal(payload) {
      this.feed = this.feed.map((message) => {
        if (message.id === payload.id) message.isViewed = payload.markingWasSuccessful
        return message
      })

      const incomingMessagesDirection = this.$store.getters['settings/incomingMessages']
      this.feedInitial =
        incomingMessagesDirection === 'bottom' ? this.feed.slice() : this.feed.slice().reverse()
      this.feedReversed =
        incomingMessagesDirection === 'bottom' ? this.feed.slice().reverse() : this.feed.slice()
    },
    toggleInput() {
      this.isInputHidden = !this.isInputHidden
      this.isInputHidden = this.$store.getters['settingHeader/isInputHidden']
      this.$store.commit('settingHeader/changeVisibleInput', !this.isInputHidden)
    },
    toggleSettings() {
      this.isSettingsOpen = !this.isSettingsOpen
      this.$store.commit('settingHeader/toggleSettings', this.isSettingsOpen)
      if (!this.isSettingsOpen) {
        this.saveUserSettings()
      } else {
        if (this.isKeySearchOpen) this.toggleKeySearch()
      }
    },
    toggleKeySearch() {
      if (this.isSettingsOpen && !this.isKeySearchOpen) return

      if (!this.isInputHidden) {
        this.toggleInput()
        this.isNeedToReturnInput = true
      }
      if (this.isKeySearchOpen && this.isNeedToReturnInput) {
        this.toggleInput()
        this.isNeedToReturnInput = false
      }

      this.isKeySearchOpen = !this.isKeySearchOpen
      if (this.isSearchResultsEmpty) this.isSearchResultsEmpty = false
      this.$store.commit('settingHeader/toggleKeySearch', this.isKeySearchOpen)
      if (!this.isKeySearchOpen && this.isSearchResultsInFeed) {
        this.$store.commit('chat/setSearchResults', { searchResults: [], keyword: null })
      }
    },
    toggleNotifications() {
      this.isNotificationsOn = !this.isNotificationsOn
      this.$store.commit('settingHeader/toggleNotifications', this.isNotificationsOn)

      if (this.$store.getters['settingHeader/isNotificationsOn']) {
        // now Safari has gotten stricter — each sound file is now locked individually until it has been specifically played during a user tap
        // https://curtisrobinson.medium.com/how-to-auto-play-audio-in-safari-with-javascript-21d50b0a2765
        playSound(this.neutralMessageSound, this.audioContext, true)
      }
    },
    clickToDelete(messageId) {
      this.feed = this.feed.filter((message) => message.id !== messageId)
      this.feedInitial = this.feed.filter((message) => message.id !== messageId)
      this.feedReversed = this.feed.filter((message) => message.id !== messageId)
      this.$store.dispatch('chat/deleteMessage', {
        id: messageId,
        userId: this.currentUserId,
      })
    },
    deleteAllMessages() {
      if (this.isSettingsOpen || this.isKeySearchOpen || this.isSearchResultsInFeed) return

      this.feed = []
      this.$store
        .dispatch('chat/deleteAllMessages')
        .then(() => {
          this.getAllNews()
        })
        .catch((error) => {
          console.error(error)
          this.isLoading = false
        })
    },
    refreshAllMessages() {
      if (this.isSettingsOpen || this.isKeySearchOpen || this.isSearchResultsInFeed) return

      this.isLoading = true
      this.$store
        .dispatch('chat/refreshAllMessages')
        .then(() => {
          this.getAllNews({ showLoader: true })
        })
        .catch((error) => {
          console.error(error)
          this.isLoading = false
        })
    },
    onLogoClick() {
      if (this.isSettingsOpen) this.toggleSettings()
      if (this.isKeySearchOpen) this.toggleKeySearch()
    },
    async getSearchResults(keyword) {
      if (keyword) {
        this.feedSearchResults = []
        this.isLoading = true
        await this.$store.dispatch('chat/getSearchResults', keyword)

        const searchResults = this.$store.getters['chat/searchResults']
        const convertedSearchResults = searchResults
          .map((item) => {
            item.date = moment(item.created_at).format('MM/D/yy')
            item.time = moment(item.created_at).format('h:mm:ss')
            return item
          })
          .sort((a, b) => {
            return new Date(b.created_at) - new Date(a.created_at)
          })

        this.feedSearchResults = convertedSearchResults
        if (convertedSearchResults.length === 0) this.isSearchResultsEmpty = true
        this.isLoading = false
      }
    },
    async scrollingToEnd(isManualAnimation) {
      const isMessagesFromBottom = this.$store.getters['settings/incomingMessages'] === 'bottom'
      const container = await this.$el.querySelector('.eq-chat--message-list')
      const endPosition = isMessagesFromBottom ? container.scrollHeight : 0
      if (isManualAnimation === true) {
        const intermediatePosition = 120
        const intermediateDuration = 30
        this.scrollTo(container, intermediatePosition, intermediateDuration)
      }
      const duration = isManualAnimation ? 300 : 800
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
    saveScrollingData(event) {
      this.messageListScrollHeight = event.target.scrollHeight
      this.messageListScrollTop = event.target.scrollTop
      this.messageListOffsetHeight = event.target.offsetHeight
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
