import axios from '@/js/axios';
import router from '@/js/router';
const state = {
  isAuth: false,
  user: null,
};

const actions = {
  async login({ commit, dispatch }, payload) {
    await axios
      .post('/login', payload)
      .then(async res => {
        localStorage.setItem('access_token', res.data.access_token);
        localStorage.setItem(
          'expired_at',
          new Date().setSeconds(res.data.expires_in),
        );
        await dispatch('me');
        commit('authenticated');
        router.push('/notes');
        dispatch(
          'snackbar/show',
          { message: 'ログインしました' },
          { root: true },
        );
      })
      .catch(err => {
        let message;
        switch (err.response.status) {
          case 401:
            message = 'メールアドレスまたはパスワードが違います';
            break;
          case 429:
            message = `一定回数以上ログインに失敗しました。${err.response.data.remain_seconds}秒後に再度お試しください。`;
            break;
          default:
            message = 'エラーが発生しました';
        }
        dispatch(
          'snackbar/show',
          { message: message, type: 'error' },
          { root: true },
        );
      });
  },
  async refresh({ commit, dispatch }) {
    await axios
      .post('/refresh', {})
      .then(async res => {
        localStorage.setItem('access_token', res.data.access_token);
        localStorage.setItem(
          'expired_at',
          new Date().setSeconds(res.data.expires_in),
        );
        await dispatch('me');
        commit('authenticated');
      })
      .catch(() => {
        commit('unauthenticated');
        commit('setUser', null);
        localStorage.removeItem('access_token', 'expired_at');
      });
  },
  async me({ commit }) {
    await axios.get('/me').then(res => {
      commit('setUser', res.data.user);
      commit('note/setFavNotes', res.data.favNotes, { root: true });
    });
  },
  async logout({ commit, dispatch }) {
    dispatch(
      'snackbar/show',
      { message: 'ログアウト中...', type: 'accent' },
      { root: true },
    );
    await axios.post('/logout').finally(() => {
      commit('unauthenticated');
      commit('setUser', null);
      localStorage.removeItem('access_token');
      router.push('/login');
      dispatch(
        'snackbar/show',
        { message: 'ログアウトしました', type: 'accent' },
        { root: true },
      );
    });
  },
  async remind({ dispatch }, payload) {
    let ok = true;
    await axios.post('/reset', payload).catch(() => {
      ok = false;
      dispatch(
        'snackbar/show',
        { message: 'エラーが発生しました', type: 'error' },
        { root: true },
      );
    });
    return ok;
  },
  async reset({ dispatch }, payload) {
    let ok = true;
    await axios.post('/reset/password', payload).catch(err => {
      ok = false;
      const msg = err.response.data.message || 'エラーが発生しました';
      dispatch(
        'snackbar/show',
        { message: msg, type: 'error' },
        { root: true },
      );
    });
    return ok;
  },
};

const mutations = {
  setUser(state, user) {
    state.user = user;
  },
  authenticated(state) {
    state.isAuth = true;
  },
  unauthenticated(state) {
    state.isAuth = false;
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
};
