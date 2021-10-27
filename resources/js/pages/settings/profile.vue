<template>
  <FormCard
    title="Edit Profile"
    :show-progress="loading"
    :show-content="!loading"
  >
    <div v-if="!loading" class="px-2 px-sm-4">
      <validation-observer ref="observer" v-slot="{ passes }">
        <div class="d-flex flex-wrap justify-space-between">
          <div class="px-2 px-sm-4" style="width: 100%; max-width: 400px">
            <v-text-field
              v-model="user.instagram_id"
              class="mb-2"
              :disabled="submitting"
              label="Instagram ID"
            />
            <validation-provider>
              <v-text-field
                v-model="user.twitter_id"
                class="mb-2"
                :disabled="submitting"
                label="Twitter ID"
              />
            </validation-provider>
            <v-combobox
              v-model="user.countries"
              class="mb-6"
              :items="countryNames"
              label="留学先国"
              hint="*任意 リストにない国は直接入力してください。"
              :disabled="submitting"
              multiple
              persistent-hint
            >
              <template v-slot:selection="data">
                <v-chip
                  :key="JSON.stringify(data.item)"
                  v-bind="data.attrs"
                  :input-value="data.selected"
                  :disabled="data.disabled"
                  @click:close="data.parent.selectItem(data.item)"
                >
                  <template v-if="getEnglishName(data.item)">
                    <v-avatar class="accent white--text" left>
                      <img
                        :src="`/img/flags/${getEnglishName(data.item)}.png`"
                      />
                    </v-avatar>
                  </template>
                  <template v-else>
                    <v-avatar
                      class="accent white--text"
                      left
                      v-text="data.item.slice(0, 1).toUpperCase()"
                    />
                  </template>
                  {{ data.item }}
                </v-chip>
              </template>
            </v-combobox>
            <v-text-field
              v-model="user.university"
              class="mb-2"
              :disabled="submitting"
              label="留学先大学・機関"
            />
            <v-text-field
              v-model="user.job"
              class="mb-2"
              :disabled="submitting"
              label="進路・就職先等"
              hint="*卒業生, および決まっている方のみ"
              persistent-hint
            />
            <v-switch
              v-model="user.isOverseas"
              :disabled="submitting"
              class="mt-4"
              label="留学中"
              type="checkbox"
              hide-details
            />
            <v-switch
              v-model="user.isOB"
              :disabled="submitting"
              class="mt-4"
              label="OB/OG"
              type="checkbox"
              hide-details
            />
          </div>
          <div class="flex-grow-1 mt-6 px-2 px-sm-4">
            <v-textarea
              v-model="user.profile"
              class="mt-3 mt-lg-5 mb-0"
              height="450px"
              :disabled="submitting"
              outlined
              label="Profile"
              hide-details
            />
            <p class="caption mt-3 grey--text text--darken-1">
              %%見出し%% と入力すると、
              <span class="heading-sample">見出し</span>になります。
            </p>
          </div>
        </div>
        <v-btn
          class="white--text ml-2 ml-sm-4 mt-2 d-block"
          min-width="100px"
          :loading="submitting"
          color="blue-grey"
          large
          @click="passes(submit)"
        >
          Save
        </v-btn>
      </validation-observer>
    </div>
  </FormCard>
</template>
<script>
import { mapActions } from 'vuex';
import FormCard from '@/js/components/FormCard.vue';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
export default {
  components: { FormCard, ValidationObserver, ValidationProvider },
  data: () => ({
    user: null,
    loading: true,
    submitting: false,
    countries: [],
  }),
  computed: {
    countryNames() {
      return this.countries.map(c => c.name);
    },
    params() {
      return {
        twitter_id: this.user.twitter_id,
        instagram_id: this.user.instagram_id,
        countries: this.user.countries,
        university: this.user.university,
        job: this.user.job,
        isOverseas: this.user.isOverseas,
        isOB: this.user.isOB,
        profile: this.user.profile,
      };
    },
  },
  async created() {
    this.countries = await this.getCountries();
    this.user = await this.getUser(this.$store.state.auth.user.id);
    this.user.countries = this.user.countries.map(c => c.name);
    this.loading = false;
  },
  methods: {
    ...mapActions('country', { getCountries: 'index' }),
    ...mapActions('user', { getUser: 'get', updateUser: 'update' }),
    getEnglishName(jpName) {
      let res;
      for (let i = 0; i < this.countries.length; i++) {
        if (this.countries[i].name === jpName) {
          res = this.countries[i].english_name;
          break;
        }
      }
      return res;
    },
    async submit() {
      this.submitting = true;
      this.$refs.observer.validate();
      const returnedUser = await this.updateUser(this.params);
      if (returnedUser) {
        this.$router.push('/mypage');
      }
      this.submitting = false;
    },
  },
};
</script>
<style lang="scss" scoped>
@import '@/sass/_variables.scss';
.heading-sample {
  color: $indigo-lighten2;
  border-left: 5px solid $indigo-lighten2;
  padding: 4px 10px;
}
</style>
