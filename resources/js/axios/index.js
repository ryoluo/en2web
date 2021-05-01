import axios from 'axios';
import router from '@/js/router';

const instance = axios.create({
  baseURL: '/api',
});
const unauthenticated = 'Unauthenticated.';

instance.interceptors.response.use(
  res => {
    return res;
  },
  err => {
    if (
      err.response.data.status == 500 &&
      err.response.data.errors == unauthenticated
    ) {
      router.push('/login');
    }
    return Promise.reject(err);
  },
);

export default instance;
