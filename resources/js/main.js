import Vue from 'vue';

import axios from './axios';
Vue.prototype.$http = axios;

import router from './router';
import store from './store';
import vuetify from './vuetify';

import VueAnalytics from 'vue-analytics';
Vue.use(VueAnalytics, {
  id: 'UA-131467484-2',
  router,
});

import './validate';

import App from './App.vue';

// import common style
import '../sass/common.scss';

export default new Vue({
  el: '#app',
  router,
  store,
  vuetify,
  components: { App },
  template: '<App />',
});
