// import { getUserSettings, createUserSetting, editUserSetting } from '@/api/chatApi'
import { getUserSettings, createUserSetting, editUserSetting } from '/api/chatApi'

const state = {
  user: {
    numberOfFlashes: '3',
    flashingSpeed: '3',
    webLinkColor: '#B9F2C8',
    stockSymbolColor: '#64D0EE',
    deliveredTimeColor: '#FFFFFF',
    textSize: '16',
    textBold: 'false',
    incomingMessages: 'bottom',
    hideIcons: 'false',
    theme: 'dark',
    isLightThemeSelectedFirstTime: 'false'
    // allowCopyPaste: "false",
  },

}

const getters = {
  numberOfFlashes: (state) => state.user.numberOfFlashes,
  flashingSpeed: (state) => state.user.flashingSpeed,
  webLinkColor: (state) => state.user.webLinkColor,
  stockSymbolColor: (state) => state.user.stockSymbolColor,
  deliveredTimeColor: (state) => state.user.deliveredTimeColor,
  textSize: (state) => state.user.textSize,
  textBold: (state) => state.user.textBold,
  incomingMessages: (state) => state.user.incomingMessages,
  hideIcons: (state) => state.user.hideIcons,
  theme: (state) => state.user.theme,
  isLightThemeSelectedFirstTime: (state) => state.user.isLightThemeSelectedFirstTime,
  allUserSettings: (state) => state.user,

}

const actions = {
  // createUserSetting({}, payload) {
  createUserSetting(payload) {
    return createUserSetting(payload)
  },
  // editUserSetting({}, payload) {
  editUserSetting(payload) {
    return editUserSetting(payload)
  },
  getUserSettings() {
    return getUserSettings()
  },
}

const mutations = {
  setNumberOfFlashes(state, payload) {
    state.user.numberOfFlashes = payload
  },
  setFlashingSpeed(state, payload) {
    state.user.flashingSpeed = payload
  },
  setWebLinkColor(state, payload) {
    state.user.webLinkColor = payload
  },
  setStockSymbolColor(state, payload) {
    state.user.stockSymbolColor = payload
  },
  setDeliveredTimeColor(state, payload) {
    state.user.deliveredTimeColor = payload
  },
  setTextSize(state, payload) {
    state.user.textSize = payload
  },
  setTextBold(state, payload) {
    state.user.textBold = payload
  },
  setIncomingMessages(state, payload) {
    state.user.incomingMessages = payload
  },
  setHideIcons(state, payload) {
    state.user.hideIcons = payload
  },
  setTheme(state, payload) {
    state.user.theme = payload
  },
  setIsLightThemeSelectedFirstTime(state, payload) {
    state.user.isLightThemeSelectedFirstTime = payload
  }
}

export default {
  state,
  getters,
  actions,
  mutations,
}
