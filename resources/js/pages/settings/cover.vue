<template>
  <FormCard
    title="Upload Cover Image"
    :show-progress="loading"
    :show-content="!loading"
    class="max-width"
  >
    <ValidationObserver v-if="!loading" ref="observer" v-slot="{ passes }">
      <div class="px-4">
        <ValidationProvider v-slot="{ errors }" name="Icon" rules="image">
          <v-file-input
            accept="image/png,image/jpeg,image/gif"
            show-size
            prepend-inner-icon="mdi-camera"
            :error-messages="errors"
            prepend-icon=""
            :disabled="submitting || uploading"
            label="Select Image"
            @change="handleFileUpload"
          />
        </ValidationProvider>
        <div
          class="preview-container"
          :style="previewPath && !uploading ? 'border:none;' : ''"
        >
          <p v-if="uploading" class="pa-0 ma-auto text-center">
            ファイルをアップロード中です...
          </p>
          <p v-else-if="!previewPath" class="pa-0 ma-auto text-center">
            ここにプレビューが表示されます。
          </p>
          <div
            v-else
            class="preview"
            :style="`background-image:url(${previewPath})`"
          >
            <v-avatar class="user-image" size="80">
              <v-img :src="userImagePath" />
            </v-avatar>
            <p
              class="
                user-name
                text-center
                title
                font-weight-regular
                mt-2
                white--text
                mb-0
              "
            >
              {{ $store.state.auth.user.name }}
            </p>
          </div>
        </div>
      </div>
      <v-btn
        class="white--text ml-4 mt-6 d-block"
        min-width="100px"
        :loading="submitting"
        :disabled="uploading || !previewPath"
        color="blue-grey"
        large
        @click="passes(submit)"
      >
        Save
      </v-btn>
    </ValidationObserver>
  </FormCard>
</template>
<script>
import FormCard from '@/js/components/FormCard.vue';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { mapActions } from 'vuex';
export default {
  components: {
    FormCard,
    ValidationProvider,
    ValidationObserver,
  },
  data: () => ({
    loading: false,
    canSubmit: false,
    uploading: false,
    submitting: false,
    file: null,
    previewPath: null,
  }),
  computed: {
    params() {
      return {
        type: 'cover',
        file: this.file,
      };
    },
    userImagePath() {
      return this.$store.state.auth.user.avater_path
        ? this.$store.state.auth.user.avater_path
        : '/img/categories/user.png';
    },
  },
  methods: {
    ...mapActions('user', ['upload', 'saveCover']),
    async handleFileUpload(e) {
      if (e && e['type'].split('/')[0] === 'image') {
        this.uploading = true;
        this.file = e;
        this.previewPath = await this.upload(this.params);
        this.uploading = false;
      } else {
        this.previewPath = null;
      }
    },
    async submit() {
      this.submitting = true;
      const user = await this.saveCover({ path: this.previewPath });
      if (user) {
        this.$router.push('/mypage');
      }
      this.submitting = false;
    },
  },
};
</script>
<style lang="scss" scoped>
@import '@/sass/_variables.scss';
.preview-container {
  max-width: 375px;
  margin: auto;
  height: 150px;
  display: flex;
  align-items: center;
  justify-items: center;
  border: 1px dashed #aaa;
  color: #888;

  .preview {
    display: flex;
    justify-content: center;
    align-items: center;
    align-content: center;
    flex-wrap: wrap;
    width: 100%;
    height: 100%;
    position: relative;
    z-index: 1;
    background-size: cover;
    background-position: center center;

    &::after {
      display: block;
      width: 100%;
      height: 100%;
      position: absolute;
      background: linear-gradient(
        rgba($color: $indigo-darken3, $alpha: 0.1),
        rgba($color: $indigo-darken3, $alpha: 0.85)
      );
      content: '';
      z-index: -1;
    }
    .user-name {
      width: 100%;
    }
  }
}
</style>
