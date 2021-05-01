const state = {
  key: 0,
  isActive: false,
  message: '',
  type: 'success',
};

const actions = {
  show({ commit }, payload) {
    commit('incrementKey');
    const key = state.key;
    commit('display', payload);
    setTimeout(() => {
      commit('hide', key);
    }, 2500);
  },
};

const mutations = {
  incrementKey(state) {
    state.key++;
  },
  display(state, payload) {
    state.message = payload.message;
    if (payload.type) {
      state.type = payload.type;
    } else {
      state.type = 'success';
    }
    state.isActive = true;
  },
  hide(state, key) {
    if (state.key === key) {
      state.isActive = false;
      state.message = '';
    }
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
};
