<!-- eslint-disable vue/no-v-html -->
<template>
  <v-container class="container max-width pa-0 mx-a mb-0" background="white">
    <div v-if="!loading">
      <v-img
        class="v-img white--text align-start align-content-space-between"
        :src="noteImagePath"
        height="220px"
      >
        <template v-slot:placeholder>
          <v-skeleton-loader class="mx-auto" tile height="220px" type="image" />
        </template>
      </v-img>
      <div
        class="chip-group mt-3 pr-2 pb-4"
        style="overflow: scroll; white-space: nowrap"
      >
        <v-chip small class="white--text ml-2" color="indigo lighten-1">
          <v-icon small left> mdi-folder </v-icon>
          {{ note.category.name }}
        </v-chip>
        <v-chip
          v-for="country in note.countries"
          :key="`country${country.id}`"
          small
          class="white--text ml-2"
          color="green lighten-1"
        >
          <v-icon small left> mdi-map-marker </v-icon>
          {{ country.name }}
        </v-chip>
        <v-chip
          v-if="note.isBest"
          tile
          small
          class="white--text ml-2"
          color="warning lighten-1"
        >
          <v-icon small left> mdi-star </v-icon>
          Best Note
        </v-chip>
        <v-chip
          v-for="tag in note.tags"
          :key="`tag${tag.id}`"
          small
          class="white--text ml-2"
          color="blue lighten-1"
        >
          <v-icon small left> mdi-tag </v-icon>
          {{ tag.name }}
        </v-chip>
      </div>

      <v-list-item class="headline pt-0 pl-3 font-weight-bold">
        {{ note.title }}
      </v-list-item>

      <v-list-item class="pl-3 py-1">
        <v-list-item-avatar size="32" @click="goToUserPage()">
          <v-img :src="userImagePath" cover>
            <template v-slot:placeholder>
              <v-skeleton-loader class="mx-auto" type="image" />
            </template>
          </v-img>
        </v-list-item-avatar>
        <v-list-item-content @click="goToUserPage()">
          <v-list-item-title class="subtitle-1 font-weight-medium">
            {{ note.user.name }}
          </v-list-item-title>
        </v-list-item-content>
        <v-list-item-content>
          <v-list-item-title
            class="body-2 text-right grey--text text--darken-1 mt-1"
          >
            {{ note.date }}
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <div
        class="
          d-inline-block
          ml-4
          mt-2
          mb-0
          font-weight-medium
          subtitle-2
          blue-grey--text
        "
        style="font-size: 18px; text-decoration: none"
        :to="`/notes/${$route.params.id}/users`"
      >
        {{ note.fav_users_count + ajustFavCount }} liked users
      </div>

      <div
        class="content blue-grey--text text--darken-4 mt-5 mb-8"
        v-html="note.content"
      />

      <div class="d-flex mb-4 pr-10 mr-12 justify-end">
        <v-btn fab dark color="indigo lighten-1" @click="scrollTop">
          <v-icon dark> mdi-arrow-up </v-icon>
        </v-btn>
      </div>
      <div class="float-btn-wrapper">
        <v-btn fab dark fixed :color="iconColor" bottom @click="fav">
          <v-icon dark small> mdi-heart </v-icon>
          {{ note.fav_users_count + ajustFavCount }}
        </v-btn>
      </div>
    </div>
    <div v-else>
      <v-skeleton-loader
        class="mb-4"
        type="card,list-item,list-item-avatar,list-item-three-line,list-item-two-line"
      />
    </div>
  </v-container>
</template>
<script>
export default {
  data: () => ({
    note: null,
    loading: true,
    ajustFavCount: 0,
  }),
  computed: {
    userImagePath() {
      return this.note.user.avater_path
        ? this.note.user.avater_path
        : '/img/categories/user.png';
    },
    noteImagePath() {
      return this.note.photos.length
        ? this.note.photos[0]['path']
        : `/img/note_cover_photo/${this.note.category_id}.jpg`;
    },
    isFavNote() {
      return this.$store.state.note.favNotes.indexOf(this.note.id) !== -1;
    },
    iconColor() {
      return this.isFavNote ? 'red' : 'grey';
    },
    textColor() {
      return this.isFavNote ? 'red--text' : 'grey--text';
    },
  },
  async created() {
    this.note = await this.$store.dispatch('note/get', this.$route.params.id);
    if (
      this.$store.state.auth.user.isAdmin ||
      this.$store.state.auth.user.id == this.note.user_id
    ) {
      this.$store.dispatch('meta/setActions', [
        {
          icon: 'mdi-pencil',
          to: `/notes/${this.note.id}/edit`,
        },
        {
          icon: 'mdi-delete',
          to: `/notes/${this.note.id}/delete`,
        },
        {
          icon: 'mdi-plus',
          to: '/notes/create',
        },
      ]);
    }
    this.loading = false;
  },
  methods: {
    fav() {
      if (this.isFavNote) {
        this.ajustFavCount--;
      } else {
        this.ajustFavCount++;
      }
      this.$store.dispatch('note/fav', this.note.id);
    },
    scrollTop() {
      this.$vuetify.goTo(0, { duration: 300 });
    },
    goToUserPage() {
      if (this.note.user.id) {
        this.$router.push(`/users/${this.note.user.id}`);
      } else {
        this.$store.dispatch('snackbar/show', {
          message: '既に退会したユーザーです。',
          type: 'accent',
        });
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.container {
  position: relative;
}
.content {
  white-space: normal;
  word-break: break-all;
  width: 95%;
  font-size: 16px;
  margin: 16px auto;
  line-height: 2;
}
.float-btn-wrapper {
  float: right;
  margin-right: calc(56 * 1.2px);
}
</style>
