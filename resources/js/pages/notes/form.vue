<template>
  <FormCard
    :title="isEdit ? 'Edit' : 'Create a New Note'"
    :show-progress="loading || submitting"
    :show-content="!loading"
  >
    <ValidationObserver v-if="!loading" ref="observer" v-slot="{ passes }">
      <div class="d-flex flex-wrap justify-space-between">
        <div class="px-2 px-sm-4" style="width: 100%; max-width: 400px">
          <ValidationProvider v-slot="{ errors }" name="Title" rules="required">
            <v-text-field
              v-model="note.title"
              class="mb-2"
              :error="errors.length !== 0"
              :disabled="submitting"
              label="Title"
              required
            />
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Author"
            rules="required"
          >
            <v-autocomplete
              v-model="note.user_id"
              :items="users"
              :error="errors.length !== 0"
              :disabled="submitting"
              item-value="id"
              item-text="name"
              :filter="customFilter"
              label="Author"
              :menu-props="{ maxHeight: 180 }"
              required
            />
          </ValidationProvider>
          <ValidationProvider v-slot="{ errors }" name="Date" rules="required">
            <v-menu
              ref="menu"
              v-model="menu"
              :close-on-content-click="false"
              :return-value.sync="note.date"
              transition="scale-transition"
              offset-y
              min-width="290px"
            >
              <template v-slot:activator="{ on }">
                <v-text-field
                  v-model="note.date"
                  :disabled="submitting"
                  :error="errors.length !== 0"
                  label="Date"
                  readonly
                  required
                  v-on="on"
                />
              </template>
              <v-date-picker v-model="note.date" no-title scrollable>
                <v-spacer />
                <v-btn text color="primary" @click="menu = false">
                  Cancel
                </v-btn>
                <v-btn text color="primary" @click="$refs.menu.save(note.date)">
                  OK
                </v-btn>
              </v-date-picker>
            </v-menu>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Category"
            rules="required"
          >
            <v-select
              v-model="note.category_id"
              :items="categories"
              label="Category"
              item-value="id"
              item-text="name"
              :disabled="submitting"
              :error="errors.length !== 0"
            />
          </ValidationProvider>
          <v-combobox
            v-model="note.countries"
            class="mb-4"
            :items="countryNames"
            label="Countries"
            hint="*任意 リストにない国は直接入力してください。"
            :disabled="submitting"
            multiple
            :menu-props="{ maxHeight: 180 }"
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
                    <img :src="`/img/flags/${getEnglishName(data.item)}.png`" />
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
          <v-combobox
            v-model="note.tags"
            class="mb-4"
            :items="tagNames"
            :disabled="submitting"
            label="Tags"
            multiple
            chips
            hint="*任意 リストにないタグも自由に入力できます。"
            :menu-props="{ maxHeight: 180 }"
            persistent-hint
          />
          <v-file-input
            accept="image/png,image/jpeg,image/gif"
            show-size
            prepend-inner-icon="mdi-camera"
            :disabled="submitting"
            prepend-icon=""
            counter
            multiple
            label="Photos"
            @change="handleFilesUpload"
          />
        </div>
        <div class="flex-grow-1 px-2 px-sm-4">
          <ValidationProvider
            v-slot="{ errors }"
            name="Content"
            rules="required"
          >
            <v-textarea
              v-model="note.content"
              class="mt-3 mt-lg-5"
              :disabled="submitting"
              outlined
              :error="errors.length !== 0"
              label="Content"
              rows="18"
            />
          </ValidationProvider>
        </div>
      </div>
      <div v-if="previews.length > 0" class="mt-2 mb-8" style="width: 100%">
        <p class="body-2 mx-2 mb-3 mx-sm-4 grey--text text--darken-1">
          プレビュー（プレビューでは画像の向きが正しく表示されないことがあります。）
        </p>
        <div class="d-flex mx-2 mx-sm-4" style="overflow-x: scroll">
          <div v-for="(preview, i) in previews" :key="i">
            <v-img
              :src="preview"
              width="200"
              height="113"
              class="d-block mr-4 flex-grow-0"
              cover
            />
          </div>
        </div>
      </div>
      <div v-if="note.photos.length > 0" class="mb-6" style="width: 100%">
        <p
          class="
            body-2
            mx-2
            mt-0 mt-sm-4
            mb-1
            mx-sm-4
            grey--text
            text--darken-1
          "
        >
          写真の削除
        </p>
        <div class="d-flex mx-2 mx-sm-4" style="overflow-x: scroll">
          <div v-for="photo in note.photos" :key="photo.id">
            <v-checkbox
              v-model="delete_photo_ids"
              :value="photo.id"
              hide-details
              class="mt-0 mb-2"
            />
            <v-img
              :src="photo.path"
              width="200"
              height="113"
              class="d-block mr-4 flex-grow-0"
              cover
            />
          </div>
        </div>
      </div>
      <v-switch
        v-if="!isEdit"
        v-model="shouldPostSlack"
        :disabled="submitting || postOthersNote"
        class="ml-4 mt-1 d-inline-block"
        label="Slackに通知する（推奨）"
        type="checkbox"
        hint="自分以外のメンバーのノートを投稿する場合は、通知がされません。"
        :persistent-hint="postOthersNote"
      />
      <v-switch
        v-model="note.isBest"
        :disabled="submitting"
        class="ml-4 mt-1 d-inline-block"
        label="ベストノートに指定する"
        type="checkbox"
      />
      <v-btn
        class="white--text ml-4 mt-2 mt-sm-4 d-block"
        min-width="100px"
        :loading="submitting"
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
    note: {
      title: '',
      user_id: null,
      date: new Date().toISOString().substr(0, 10),
      countries: [],
      tags: [],
      category_id: null,
      isBest: false,
      content: '',
      photos: [],
    },
    files: [],
    delete_photo_ids: [],
    menu: false,
    countries: [],
    tags: [],
    categories: [],
    users: [],
    loading: true,
    submitting: false,
    previews: [],
    shouldPostSlack: true,
  }),
  computed: {
    isEdit() {
      return this.$route.name === 'note.edit';
    },
    countryNames() {
      return this.countries.map(c => c.name);
    },
    tagNames() {
      return this.tags.map(t => t.name);
    },
    postOthersNote() {
      return this.$store.state.auth.user.id !== this.note.user_id;
    },
    params() {
      return {
        note_id: this.note.id ? this.note.id : 0,
        title: this.note.title,
        user_id: this.note.user_id,
        date: this.note.date,
        category_id: this.note.category_id,
        countries: this.note.countries,
        tags: this.note.tags,
        isBest: this.note.isBest ? 1 : 0,
        content: this.note.content,
        files: this.files,
        delete_photo_ids: this.delete_photo_ids,
        should_post_slack: this.shouldPostSlack ? 1 : 0,
      };
    },
  },
  watch: {
    'note.user_id': function (newUserId) {
      this.shouldPostSlack = newUserId === this.$store.state.auth.user.id;
    },
  },
  async created() {
    await this.initNote();
    this.shouldPostSlack = !this.isEdit;
    this.users = await this.getUsers();
    this.categories = await this.getCategories();
    this.tags = await this.getTags();
    this.countries = await this.getCountries();
    this.loading = false;
  },
  methods: {
    ...mapActions('user', { getUsers: 'index' }),
    ...mapActions('country', { getCountries: 'index' }),
    ...mapActions('note', {
      createNote: 'create',
      updateNote: 'update',
      getNote: 'getForEdit',
      getCategories: 'categories',
      getTags: 'tags',
    }),
    async initNote() {
      if (this.isEdit) {
        const note = await this.getNote(this.$route.params.id);
        if (
          note.user_id !== this.$store.state.auth.user.id &&
          !this.$store.state.auth.user.isAdmin
        ) {
          this.$store.dispatch('snackbar/show', {
            message: '権限がありません。',
            type: 'error',
          });
          this.$router.push('/notes');
        }
        this.note = note;
        this.note.countries = this.note.countries.map(c => c.name);
        this.note.tags = this.note.tags.map(t => t.name);
      } else {
        this.note.user_id = this.$store.state.auth.user.id;
      }
    },
    async submit() {
      this.submitting = true;
      this.$refs.observer.validate();
      let returnedNote;
      if (this.isEdit) {
        returnedNote = await this.updateNote(this.params);
      } else {
        returnedNote = await this.createNote(this.params);
      }
      if (returnedNote) {
        this.$router.push(`/notes/${returnedNote.id}`);
      }
      this.submitting = false;
    },
    customFilter(item, queryText) {
      const text = item.name.toLowerCase();
      const searchText = queryText.toLowerCase();

      return text.indexOf(searchText) > -1;
    },
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
    generateFileUrl(file) {
      return window.URL.createObjectURL(file);
    },
    handleFilesUpload(e) {
      this.files = e;
      let previews = [];
      e.forEach(file => {
        previews.push(window.URL.createObjectURL(file));
      });
      this.previews = previews;
    },
  },
};
</script>
