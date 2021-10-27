<template>
  <div v-if="!loading">
    <FormCard
      title="Register"
      :show-progress="submitting"
      class="mx-auto"
      style="max-width: 500px"
    >
      <div class="px-2 px-sm-4">
        <ValidationObserver ref="observer" v-slot="{ passes }">
          <v-chip color="green" class="white--text my-3" small label>
            認証情報
          </v-chip>
          <v-text-field
            label="Email"
            type="email"
            disabled
            :value="user.email"
          />
          <ValidationProvider
            v-slot="{ errors }"
            name="Password"
            :rules="{
              required: true,
              min: 8,
              max: 50,
              regex: /^[a-zA-Z0-9!-/:-@¥[-`{-~]+$/,
            }"
            vid="password"
          >
            <v-text-field
              v-model="password"
              class="mb-2"
              :error-messages="errors"
              label="Password"
              type="password"
              required
              :disabled="submitting"
            />
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Password Confirmation"
            rules="required|confirmed:password"
            vid="confirmation"
          >
            <v-text-field
              v-model="confirmation"
              class="mb-2"
              :error-messages="errors"
              label="Confirm Password"
              type="password"
              required
              :disabled="submitting"
            />
          </ValidationProvider>
          <v-chip color="blue" class="white--text my-3" small label>
            基本情報
          </v-chip>
          <ValidationProvider
            v-slot="{ errors }"
            name="First Name"
            rules="required|max:50|alpha"
          >
            <v-text-field
              v-model="firstName"
              class="mb-2"
              :error-messages="errors"
              label="First Name"
              placeholder="ex) Ryo"
              required
              :disabled="submitting"
            />
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Last Name"
            rules="required|max:50|alpha"
          >
            <v-text-field
              v-model="lastName"
              class="mb-2"
              :error-messages="errors"
              label="Last Name"
              placeholder="ex) Kobayashi"
              required
              :disabled="submitting"
            />
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Department"
            rules="required"
          >
            <v-select
              v-model="department"
              :items="departments"
              label="学部"
              :error-messages="errors"
              :disabled="submitting"
            />
          </ValidationProvider>
          <ValidationProvider v-slot="{ errors }" name="Major" rules="required">
            <v-select
              v-model="major"
              :items="majors"
              label="学科"
              :error-messages="errors"
              :disabled="!department || submitting"
            />
          </ValidationProvider>
          <v-checkbox
            v-model="isHennyu"
            label="編入の場合はチェックを入れてください。"
            :disabled="submitting"
          />
          <ValidationProvider
            v-slot="{ errors }"
            name="Admission Year"
            rules="required"
          >
            <v-select
              v-model="year"
              :items="years"
              :label="isHennyu ? '編入年度' : '入学年度'"
              :error-messages="errors"
              :disabled="submitting"
            />
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Generation"
            rules="required"
          >
            <v-select
              v-model="generation"
              :items="generations"
              label="入会時期"
              class="mb-2"
              :error-messages="errors"
              :disabled="submitting"
              :hint="`*${currYear}年度加入は${currYear - 2014}期生になります。`"
              persistent-hint
            />
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Instagram ID"
            rules="alpha_dash|max:50"
          >
            <v-text-field
              v-model="instagramId"
              :disabled="submitting"
              label="Instagram ID"
              class="mb-2"
              hint="*任意"
              :error-messages="errors"
              persistent-hint
            />
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Twitter ID"
            rules="alpha_dash|max:50"
          >
            <v-text-field
              v-model="twitterId"
              :disabled="submitting"
              class="mb-2"
              label="Twitter ID"
              hint="*任意 ＠は不要です"
              persistent-hint
              :error-messages="errors"
            />
          </ValidationProvider>
          <v-btn
            class="white--text mt-6 d-block"
            min-width="100px"
            :loading="submitting"
            color="blue-grey"
            large
            @click="passes(submit)"
          >
            Register
          </v-btn>
        </ValidationObserver>
      </div>
    </FormCard>
  </div>
</template>
<script>
import { mapActions } from 'vuex';
import FormCard from '@/js/components/FormCard.vue';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
export default {
  components: {
    FormCard,
    ValidationProvider,
    ValidationObserver,
  },
  data: () => ({
    loading: true,
    submitting: false,
    user: null,
    password: '',
    confirmation: '',
    firstName: '',
    lastName: '',
    department: null,
    major: null,
    year: new Date().getFullYear(),
    isHennyu: false,
    generation: new Date().getFullYear() - 2014,
    twitterId: '',
    instagramId: '',
  }),
  computed: {
    params() {
      return {
        password: this.password,
        name: `${this.firstName} ${this.lastName}`,
        department: this.department,
        department_id: this.departmentId,
        generation: this.generation,
        major: this.major,
        isHennyu: this.isHennyu ? 1 : 0,
        year: this.year,
        twitter_id: this.twitterId,
        instagram_id: this.instagramId,
        token: this.$route.params.token,
      };
    },
    departments() {
      return ['経済学部', '経営学部', '教育学部', '都市科学部', '理工学部'];
    },
    majors() {
      switch (this.departmentId) {
        case 1:
          return ['経済学科'];
        case 2:
          return ['経営学科'];
        case 3:
          return ['教育学科'];
        case 4:
          return [
            '都市社会共生学科',
            '環境リスク共生学科',
            '都市基盤学科',
            '建築学科',
          ];
        case 5:
          return [
            '機械・材料・海洋系学科',
            '化学・生命系学科',
            '数物・電子情報系学科',
          ];
        default:
          return [];
      }
    },
    departmentId() {
      return this.departments.indexOf(this.department) + 1;
    },
    currYear() {
      return new Date().getFullYear();
    },
    years() {
      const years = [];
      for (let i = 0; i < 4; i++) {
        years.push(this.currYear - i);
      }
      return years;
    },
    generations() {
      const generations = [];
      for (let i = 0; i < 4; i++) {
        const gen = this.currYear - 2014 - i;
        generations.push({
          text: `${gen}期生`,
          value: gen,
        });
      }
      return generations;
    },
  },
  watch: {
    department() {
      this.major = this.majors[0];
    },
  },
  async created() {
    const res = await this.verify({
      token: this.$route.params.token,
    });
    if (!res.ok) {
      this.showSnackbar({
        type: 'error',
        message: 'トークンが無効です。',
      });
      this.$router.push('/login');
    } else if (res.user.status != 0) {
      this.showSnackbar({
        type: 'accent',
        message: '既に登録済みです。',
      });
      this.$router.push('/login');
    }
    this.user = res.user;
    this.loading = false;
  },
  methods: {
    ...mapActions('register', ['verify', 'register']),
    ...mapActions('snackbar', { showSnackbar: 'show' }),
    async submit() {
      this.submitting = true;
      const ok = await this.register(this.params);
      if (ok) {
        this.showSnackbar({
          type: 'success',
          message: '登録が完了しました。\nさっそくログインしてみましょう！',
        });
        this.$router.push('/login');
      }
      this.submitting = false;
    },
  },
};
</script>
