import Vue from "vue";
import App from "./App.vue";
import store from "./store";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import linkify from "vue-linkify";
import clickOutside from "/directives/click-outside.directive";
// import clickOutside from "@/directives/click-outside.directive";
import { ColorPickerPlugin } from "@syncfusion/ej2-vue-inputs";

// Install BootstrapVue
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);
Vue.use(ColorPickerPlugin);
Vue.directive("linkified", linkify);
Vue.directive("click-outside", clickOutside);
Vue.config.productionTip = false;

new Vue({
  render: (h) => h(App),
  store,
}).$mount("#app");
