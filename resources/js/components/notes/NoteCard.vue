<template>
  <v-card tile max-width="450" elevation="4">
    <v-list-item class="ma-auto py-1">
      <v-list-item-avatar size="32" @click="user">
        <v-img :src="userImagePath" cover>
          <template v-slot:placeholder>
            <v-skeleton-loader min-height="220" class="mx-auto" type="image" />
          </template>
        </v-img>
      </v-list-item-avatar>
      <v-list-item-content @click="user">
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
    <v-img
      class="
        v-img
        white--text
        align-start align-content-space-between
        clickable
      "
      height="220px"
      :src="noteImagePath"
      @click="view"
    >
      <template v-slot:placeholder>
        <v-skeleton-loader class="mx-auto" tile type="image" />
      </template>
      <div>
        <v-chip
          small
          class="white--text mt-2 ml-2"
          color="blue"
          @click="go(`/notes?category_id=${note.category_id}`)"
        >
          <v-icon small left> mdi-folder </v-icon>
          {{ note.category.name }}
        </v-chip>
        <v-chip
          v-if="note.countries.length > 0"
          small
          class="white--text mt-2 ml-2"
          color="green lighten-1"
          @click="go(`/notes?country_id=${note.countries[0].id}`)"
        >
          <v-icon small left> mdi-earth </v-icon>
          {{ note.countries[0].name }}
        </v-chip>
        <v-chip
          v-if="note.isBest"
          small
          class="white--text mt-2 ml-2"
          color="warning lighten-1"
          @click="go(`/notes?is_best=1`)"
        >
          <v-icon small left> mdi-star </v-icon>
          Best Note
        </v-chip>
      </div>
      <div
        v-if="!hideFav"
        class="pb-2 pr-2"
        style="position: absolute; bottom: 0; right: 0; opacity: 0.95"
        @click="emitFav"
      >
        <v-btn fab :color="iconColor" :class="textColor">
          <v-icon small> mdi-heart </v-icon>
          {{ note.fav_users_count }}
        </v-btn>
      </div>
    </v-img>
    <v-list-item>
      <v-list-item-content>
        <v-list-item-title
          class="title py-1 font-weight-bold clickable"
          color="indigo"
          @click="view"
        >
          {{ note.title }}
        </v-list-item-title>
        <v-list-item-title class="caption mt-1 blue--text">
          <p
            v-for="tag in note.tags"
            :key="tag.id"
            class="d-inline-block mr-2 mb-0"
          >
            {{ `#${tag.name}` }}
          </p>
        </v-list-item-title>
      </v-list-item-content>
    </v-list-item>
  </v-card>
</template>
<script>
export default {
  props: {
    note: {
      type: Object,
      required: true,
    },
    hideFav: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  data: () => ({
    preventLink: false,
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
      return this.isFavNote ? 'white--text' : 'white--text';
    },
  },
  methods: {
    view() {
      if (this.preventLink) {
        this.preventLink = false;
      } else {
        this.$router.push(`/notes/${this.note.id}`);
      }
    },
    user() {
      if (this.note.user.id) {
        this.$router.push(`/users/${this.note.user.id}`);
      } else {
        this.$store.dispatch('snackbar/show', {
          message: '既に退会したユーザーです。',
          type: 'accent',
        });
      }
    },
    emitFav() {
      this.preventLink = true;
      this.$emit('fav', this.note.id);
    },
    go(to) {
      this.preventLink = true;
      if (this.$route.fullPath != to) {
        this.$router.push(to);
      }
    },
  },
};
</script>
