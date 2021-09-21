<template>
  <v-container fluid background="white" class="container">
    <v-row class="ma-auto" align="center" justify="center">
      <v-col class="pb-2 pt-4" cols="12">
        <v-img
          class="ma-auto"
          contain
          src="/img/logo/transparent.png"
          position="center center"
          max-width="400px"
        />
        <p
          class="subtitle-1 mt-3 text-center d-block grey--text text--darken-1"
        >
          A web platform for En2 members.
        </p>
      </v-col>
    </v-row>
    <v-row align="center" justify="center">
      <v-col cols="10" class="pa-0" style="max-width:400px;">
        <validation-observer v-slot="{ passes }" slim>
          <v-form>
            <v-col cols="12" class="pa-0">
              <validation-provider
                v-slot="{ errors }"
                name="Email"
                rules="required"
              >
                <v-text-field
                  v-model="email"
                  :error-messages="errors"
                  type="email"
                  label="Email"
                  prepend-icon="mdi-email"
                  color="indigo lighten-1"
                  required
                />
              </validation-provider>
              <validation-provider
                v-slot="{ errors }"
                name="Password"
                rules="required"
              >
                <v-text-field
                  v-model="password"
                  type="password"
                  label="Password"
                  :error-messages="errors"
                  prepend-icon="mdi-key-variant"
                  color="indigo lighten-1"
                  required
                />
              </validation-provider>
              <v-row style="max-width:400px;" class="mt-4 ma-auto">
                <v-col cols="4" class="pa-0 d-flex">
                  <v-btn
                    class="white--text mr-8 mt-auto mb-auto"
                    :loading="loading"
                    :disabled="loading"
                    color="indigo lighten-1"
                    large
                    @click="passes(login)"
                  >
                    Sign in
                  </v-btn>
                </v-col>
                <v-col cols="" class="pa-0 d-flex">
                  <v-checkbox v-model="remember" label="自動ログイン" color="indigo lighten-1" />
                </v-col>
              </v-row>
            </v-col>
          </v-form>
        </validation-observer>
      </v-col>
    </v-row>
    <v-row>
      <p class="mx-auto mt-8 mb-6 text-center" style="width:256px;">
        OR
      </p>
    </v-row>
    <v-row>
      <v-col cols="10" class="mx-auto mt-4 pa-0" style="max-width:400px;">
        <div class="ma-auto" style="width:256px;">
          <a href="/redirect/slack" style="align-items:center;color:#000;background-color:#fff;border:1px solid #ddd;border-radius:4px;display:inline-flex;font-family:Lato, sans-serif;font-size:16px;font-weight:600;height:48px;justify-content:center;text-decoration:none;width:256px"><svg xmlns="http://www.w3.org/2000/svg" style="height:20px;width:20px;margin-right:12px" viewBox="0 0 122.8 122.8"><path d="M25.8 77.6c0 7.1-5.8 12.9-12.9 12.9S0 84.7 0 77.6s5.8-12.9 12.9-12.9h12.9v12.9zm6.5 0c0-7.1 5.8-12.9 12.9-12.9s12.9 5.8 12.9 12.9v32.3c0 7.1-5.8 12.9-12.9 12.9s-12.9-5.8-12.9-12.9V77.6z" fill="#e01e5a" /><path d="M45.2 25.8c-7.1 0-12.9-5.8-12.9-12.9S38.1 0 45.2 0s12.9 5.8 12.9 12.9v12.9H45.2zm0 6.5c7.1 0 12.9 5.8 12.9 12.9s-5.8 12.9-12.9 12.9H12.9C5.8 58.1 0 52.3 0 45.2s5.8-12.9 12.9-12.9h32.3z" fill="#36c5f0" /><path d="M97 45.2c0-7.1 5.8-12.9 12.9-12.9s12.9 5.8 12.9 12.9-5.8 12.9-12.9 12.9H97V45.2zm-6.5 0c0 7.1-5.8 12.9-12.9 12.9s-12.9-5.8-12.9-12.9V12.9C64.7 5.8 70.5 0 77.6 0s12.9 5.8 12.9 12.9v32.3z" fill="#2eb67d" /><path d="M77.6 97c7.1 0 12.9 5.8 12.9 12.9s-5.8 12.9-12.9 12.9-12.9-5.8-12.9-12.9V97h12.9zm0-6.5c-7.1 0-12.9-5.8-12.9-12.9s5.8-12.9 12.9-12.9h32.3c7.1 0 12.9 5.8 12.9 12.9s-5.8 12.9-12.9 12.9H77.6z" fill="#ecb22e" /></svg>Sign in with Slack</a>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { mapState } from 'vuex';
export default {
  components: {
    ValidationObserver,
    ValidationProvider,
  },
  data: () => ({
    loading: false,
    email: '',
    password: '',
    remember: false,
  }),
  computed: {
    ...mapState('auth', ['isAuth']),
    postData() {
      return {
        email: this.email,
        password: this.password,
        remember: this.remember,
      };
    },
  },
  mounted() {
    if (this.isAuth) {
      this.$router.push('/notes');
    }
  },
  methods: {
    async login() {
      this.loading = true;
      await this.$store.dispatch('auth/login', this.postData);
      this.loading = false;
    },
  },
};
</script>
<style lang="scss" scoped>
.container {
  min-height: 100%;
}
</style>
