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
      <v-col cols="10" class="pa-0" style="max-width: 400px">
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
              <v-row style="max-width: 400px" class="mt-4 ma-auto">
                <v-col cols="4" class="pa-0 d-flex">
                  <v-btn
                    class="white--text mt-auto mb-auto"
                    :loading="loading"
                    :disabled="loading"
                    color="indigo lighten-1"
                    large
                    @click="passes(login)"
                  >
                    Sign in
                  </v-btn>
                </v-col>
                <v-col class="ml-2 pa-0 d-flex">
                  <v-checkbox
                    v-model="remember"
                    label="自動ログイン"
                    color="indigo lighten-1"
                  />
                </v-col>
              </v-row>
            </v-col>
          </v-form>
        </validation-observer>
      </v-col>
    </v-row>
    <v-row>
      <p
        class="mx-auto mt-7 mb-5 text-center grey--text text--darken-3"
        style="width: 256px"
      >
        OR
      </p>
    </v-row>
    <v-row>
      <v-col cols="10" class="mx-auto mt-4 pa-0" style="max-width: 400px">
        <div class="ma-auto" style="width: 256px">
          <sign-in-with-discord-button link="/redirect/discord" />
        </div>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="10" class="mx-auto mt-4 pa-0" style="max-width: 400px">
        <div class="ma-auto" style="width: 256px">
          <sign-in-with-slack-button link="/redirect/slack" />
          <p class="mt-2 subtitle-1 text-center grey--text text--darken-1">
            Workspace: en2-ynu
          </p>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { mapState } from 'vuex';
import SignInWithSlackButton from '@/js/components/SignInWithSlackButton';
import SignInWithDiscordButton from '../../components/SignInWithDiscordButton.vue';
export default {
  components: {
    ValidationObserver,
    ValidationProvider,
    SignInWithSlackButton,
    SignInWithDiscordButton,
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
