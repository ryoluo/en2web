import { configure, extend } from 'vee-validate';
import { required, email } from 'vee-validate/dist/rules';
const config = {
  mode: 'lazy',
};
configure(config);

extend('required', {
  ...required,
  message: 'This field is required',
});
extend('email', {
  ...email,
  message: 'This field must be a valid email',
});