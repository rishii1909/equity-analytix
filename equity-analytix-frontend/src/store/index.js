import Vue from "vue";
import Vuex from "vuex";
import settingHeader from "./modules/settingHeader";
import chat from "./modules/chat";
import settings from "./modules/settings";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    chat: {
      namespaced: true,
      ...chat,
    },
    settingHeader: {
      namespaced: true,
      ...settingHeader,
    },
    settings: {
      namespaced: true,
      ...settings,
    },
  },
  actions: {
    set: ({ commit }, payload) => commit("set", payload),
  },
  getters: {},
  mutations: {
    set(state, payload) {
      if (payload.child) state[payload.prop][payload.child] = payload.val;
      else state[payload.prop] = payload.val;
    },
  },
});
