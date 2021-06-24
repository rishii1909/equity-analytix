const state = {
  isInputHidden: true,
  isSettingsOpen: false,
  isKeySearchOpen: false,
  isUsersModalOpen: false,
  isNotificationsOn: false,
}

const getters = {
  isInputHidden: (state) => state.isInputHidden,
  isSettingsOpen: (state) => state.isSettingsOpen,
  isKeySearchOpen: (state) => state.isKeySearchOpen,
  isUsersModalOpen: (state) => state.isUsersModalOpen,
  isNotificationsOn: (state) => state.isNotificationsOn,
}

const mutations = {
  changeVisibleInput(state, payload) {
    state.isInputHidden = payload
  },
  toggleSettings(state, payload) {
    state.isSettingsOpen = payload
  },
  toggleKeySearch(state, payload) {
    state.isKeySearchOpen = payload
  },
  toggleUsersModal(state, payload) {
    state.isUsersModalOpen = payload
  },
  toggleNotifications(state, payload) {
    state.isNotificationsOn = payload
  },
}

export default {
  state,
  getters,
  mutations,
}
