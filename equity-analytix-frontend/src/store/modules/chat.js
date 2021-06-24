import {
  deleteAllMessages,
  deleteMessage,
  getAllNews,
  getAllUsers,
  getSearchResults,
  getUserInfo,
  markMessageAsViewedOnServer,
  refreshAllMessages,
} from "/api/chatApi";
// } from "@/api/chatApi";

const state = {
  userInfo: null,
  users: [],
  news: [],
  currentTime: null,
  isOnline: true,
  isWebSocketRunning: false,
  searchResults: [],
  keyword: null,
  messageObserver: null,
  flashingMessages: [],
};

const getters = {
  userInfo: (state) => state.userInfo,
  isAdmin: (state) => state.userInfo.info.role === "administrator",
  users: (state) => state.users,
  news: (state) => state.news,
  currentTime: (state) => state.currentTime,
  isOnline: (state) => state.isOnline,
  isWebSocketRunning: (state) => state.isWebSocketRunning,
  searchResults: (state) => state.searchResults,
  keyword: (state) => state.keyword,
  messageObserver: (state) => state.messageObserver,
  flashingMessages: (state) => state.flashingMessages,
};

const actions = {
  getUserInfo() {
    return getUserInfo();
  },

  getAllUsers() {
    return getAllUsers();
  },

  // getAllNews({}, payload) {
  getAllNews(payload) {
    return getAllNews(payload);
  },

  // markMessageAsViewedOnServer({}, payload) {
  markMessageAsViewedOnServer(payload) {
    return markMessageAsViewedOnServer(payload);
  },

  // deleteMessage({}, payload) {
  deleteMessage(payload) {
    return deleteMessage(payload);
  },

  deleteAllMessages() {
    return deleteAllMessages();
  },

  refreshAllMessages() {
    return refreshAllMessages();
  },

  getSearchResults({ commit }, payload) {
    return getSearchResults(payload).then((response) => {
      commit("setSearchResults", {
        keyword: payload,
        searchResults: response.data.data.news,
      });
    });
  },
};

const mutations = {
  setUserInfo(state, payload) {
    state.userInfo = payload;
  },

  setAllUsers(state, payload) {
    state.users = payload.map((user) => {
      return { ...user, isOnline: false };
    });
  },

  setUserOnlineStatus(state, payload) {
    const users = state.users.map((user) => {
      if (+user.id === payload.id) {
        user.isOnline = payload.status === "online";
      }
      return user;
    });
    state.users = users;
  },

  setAllNews(state, payload) {
    state.news = payload;
  },

  setCurrentTime(state, payload) {
    state.currentTime = payload;
  },

  setIsOnline(state, payload) {
    state.isOnline = payload;
  },

  setIsWebSocketRunning(state, payload) {
    state.isWebSocketRunning = payload;
  },

  setSearchResults(state, payload) {
    state.searchResults = payload.searchResults;
    state.keyword = payload.keyword;
  },

  setMessageObserver(state, payload) {
    state.messageObserver = payload;
  },

  setFlashingMessages(state, payload) {
    if (payload.type === "add") {
      state.flashingMessages.push(payload.id);
    } else {
      const targetIndex = state.flashingMessages.indexOf(payload.id);
      if (targetIndex > -1) {
        state.flashingMessages.splice(targetIndex, 1);
      }
    }
  },
};

export default {
  state,
  getters,
  actions,
  mutations,
};
