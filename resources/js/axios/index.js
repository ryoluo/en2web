import axios from 'axios';
import router from '@/js/router';
import store from '@/js/store';

const instance = axios.create({
  baseURL: '/api',
});

instance.interceptors.response.use(
  res => {
    return res;
  },
  err => {
    if (err.response.status == 401) {
      store.commit('auth/unauthenticated');
      router.push('/login');
    }
    return Promise.reject(err);
  },
);

export default instance;
