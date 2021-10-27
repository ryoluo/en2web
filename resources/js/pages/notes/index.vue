<template>
  <div v-scroll="onScroll">
    <v-tabs
      color="indigo lighten-1"
      show-arrows
      centered
      :hide-slider="!doesMatchTabUrl"
    >
      <v-tab exact to="/notes"> ALL </v-tab>
      <v-tab exact to="/notes?is_best=1"> Best Note </v-tab>
      <v-tab exact to="/notes?category_id=6"> 思考の共有 </v-tab>
      <v-tab exact to="/notes?category_id=2"> 語学学習 </v-tab>
      <v-tab exact to="/notes?category_id=4"> 就活・仕事 </v-tab>
      <v-tab exact to="/notes?category_id=3"> 奨学金 </v-tab>
      <v-tab exact to="/notes?category_id=5"> 本・記事 </v-tab>
      <v-tab exact to="/notes?category_id=1"> 月一報告 </v-tab>
    </v-tabs>
    <div class="d-flex justify-center">
      <v-card
        v-if="conditions.length"
        id="conditions"
        class="mt-7 mb-1 mx-2 d-flex"
        outlined
        width="100%"
        max-width="450px"
      >
        <v-list dense subheader nav width="100%">
          <v-subheader class="subtitle-2 pr-1 d-flex justify-space-between">
            <div class="d-flex">
              <v-icon small class="mr-1"> mdi-filter </v-icon>
              <p class="mb-0" style="margin-top: 2px">Filter</p>
              <p class="mb-0 ml-3" style="margin-top: 2px">{{ total }} notes</p>
            </div>
            <div>
              <v-icon
                color="grey darken-1"
                size="20"
                @click="$router.push('/notes/search')"
              >
                mdi-pencil
              </v-icon>
              <v-icon
                class="ml-2"
                color="grey darken-1"
                size="20"
                @click="$router.push('/notes')"
              >
                mdi-close
              </v-icon>
            </div>
          </v-subheader>
          <v-list-item v-for="(c, i) in conditions" :key="i">
            <v-list-item-icon class="mr-4">
              <v-icon size="28" color="#559" v-text="c.icon" />
            </v-list-item-icon>
            <v-list-item-content>
              {{ c.text }}
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card>
    </div>
    <v-container
      v-if="!loading"
      id="note-container"
      ref="container"
      :style="style"
      class="px-0"
    >
      <NoteCard
        v-for="n in displayNotes"
        :key="n.id"
        :note="n"
        class="mx-auto mt-8 mb-12"
        @fav="fav"
      />
      <Period v-if="currentPage == lastPage" />
    </v-container>
    <v-container v-if="loading || fetching" class="px-0">
      <v-skeleton-loader
        v-for="i in 2"
        :key="i"
        class="mx-auto mt-6 mb-12"
        type="list-item-avatar,image,list-item-two-line"
        max-width="450"
        elevation="4"
      />
    </v-container>
  </div>
</template>
<script>
import { mapState, mapMutations, mapActions } from 'vuex';
import NoteCard from '@/js/components/notes/NoteCard.vue';
import Period from '@/js/components/notes/Period.vue';
export default {
  components: { NoteCard, Period },
  data: () => ({
    loading: true,
    fetching: false,
    offset: 0,
    offsetTop: 0,
  }),
  computed: {
    ...mapState('note', [
      'notes',
      'conditions',
      'currentPage',
      'lastPage',
      'to',
      'total',
      'savedOffset',
      'savedFullPath',
    ]),
    params() {
      return {
        page: this.currentPage + 1,
        category_id: parseInt(this.$route.query.category_id) || 0,
        user_id: parseInt(this.$route.query.user_id) || 0,
        keyword: this.$route.query.keyword || '',
        country_id: parseInt(this.$route.query.country_id) || 0,
        tag_id: parseInt(this.$route.query.tag_id) || 0,
        is_best: parseInt(this.$route.query.is_best) || 0,
      };
    },
    needFetch() {
      return !this.fetching && this.currentPage != this.lastPage;
    },
    noteHeight() {
      return 352 + 48;
    },
    firstIndex() {
      const res =
        Math.floor((this.offset - this.offsetTop) / this.noteHeight) - 2;
      return res > 0 ? res : 0;
    },
    lastIndex() {
      return this.firstIndex + 8;
    },
    displayNotes() {
      const _this = this;
      return this.notes.filter((n, i) => {
        n.index = i;
        return i >= _this.firstIndex && i <= _this.lastIndex;
      });
    },
    style() {
      return {
        paddingTop: this.noteHeight * this.firstIndex + 'px',
        paddingBottom:
          this.noteHeight * (this.notes.length - this.lastIndex - 1) + 'px',
      };
    },
    doesMatchTabUrl() {
      const tabUrls = [
        '/notes',
        '/notes?is_best=1',
        '/notes?category_id=1',
        '/notes?category_id=2',
        '/notes?category_id=3',
        '/notes?category_id=4',
        '/notes?category_id=5',
        '/notes?category_id=6',
      ];
      return tabUrls.indexOf(this.$route.fullPath) !== -1;
    },
  },
  watch: {
    $route: async function () {
      this.loading = true;
      this.clearNotes();
      this.scrollTop();
      this.saveFullPath(this.$route.fullPath);
      await this.fetchNotes();
      this.loaded();
    },
  },
  async mounted() {
    if (this.$route.fullPath !== this.savedFullPath) {
      this.loading = true;
      this.clearNotes();
      this.scrollTop();
      await this.fetchNotes();
      this.saveFullPath(this.$route.fullPath);
    }
    this.loaded();
  },
  beforeDestroy() {
    this.saveOffset(this.offset);
  },
  methods: {
    ...mapMutations('note', [
      'saveOffset',
      'clearNotes',
      'saveFullPath',
      'setParams',
    ]),
    ...mapActions('note', ['fav']),
    async fetchNotes() {
      this.fetching = true;
      await this.$store.dispatch('note/index', this.params);
      this.fetching = false;
    },
    onScroll() {
      this.offset = window.pageYOffset;
      if (this.needFetch && this.lastIndex - 2 >= this.to) {
        this.fetchNotes();
      }
    },
    scrollTop() {
      this.$vuetify.goTo(0, { duration: 0 });
    },
    async loaded() {
      this.loading = false;
      this.setParams(this.params);
      await this.$nextTick();
      this.offsetTop =
        this.$refs.container.getBoundingClientRect().top + window.pageYOffset;
    },
  },
};
</script>
