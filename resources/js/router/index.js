import Vue from 'vue';
import store from '@/js/store';

import VueRouter from 'vue-router';
Vue.use(VueRouter);
import routes from './routes';

const router = new VueRouter({
  mode: 'history',
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.path === '/notes') {
      return { x: 0, y: store.state.note.savedOffset };
    }
    if (savedPosition) {
      return savedPosition;
    } else {
      return { x: 0, y: 0 };
    }
  },
});

router.beforeEach(async (to, from, next) => {
  // 再ログイン処理
  if (!from.name && !store.state.auth.isAuth) {
    await store.dispatch('auth/check');
  }
  if (to.meta.requireAuth && !store.state.auth.isAuth) {
    next('/login');
  }
  // Guest middleware
  if (!to.meta.requireAuth && store.state.auth.isAuth) {
    next('/notes');
  }
  store.state.referrer = from.name;
  next();
});

router.afterEach(to => {
  store.dispatch('meta/setTitle', to.meta.title);
  store.dispatch('meta/setActions', to.meta.actions);
});

export default router;
