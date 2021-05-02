<template>
  <v-list dense>
    <v-list-item>
      <img class="ma-auto" width="80%" src="/img/logo/transparent.png" alt="" />
    </v-list-item>
    <v-list-item
      v-if="$store.state.auth.user"
      two-line
      class="mx-auto"
      :ripple="false"
    >
      <v-list-item-avatar size="45">
        <v-img
          cover
          :src="
            $store.state.auth.user.avater_path
              ? $store.state.auth.user.avater_path
              : '/img/categories/user.png'
          "
        />
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title class="subtitle-1 font-weight-medium">
          {{ $store.state.auth.user.name }}
        </v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-divider />
    <v-list-item
      v-for="i in items"
      :key="i.name"
      link
      :to="i.link"
      color="indigo"
    >
      <v-list-item-action>
        <v-icon size="28">
          {{ i.icon }}
        </v-icon>
      </v-list-item-action>
      <v-list-item-content>
        <v-list-item-title class="subtitle-2">
          {{ i.name }}
        </v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-divider />
    <v-list-item class="mt-3" @click="logout">
      <v-list-item-action>
        <v-icon>
          mdi-logout-variant
        </v-icon>
      </v-list-item-action>
      <v-list-item-content>
        <v-list-item-title>Logout</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
  </v-list>
</template>
<script>
export default {
  data: () => ({
    loading: false,
  }),
  computed: {
    items() {
      return [
        {
          name: 'Mypage',
          link: '/mypage',
          icon: 'mdi-home',
        },
        {
          name: 'Notes',
          link: '/notes',
          icon: 'mdi-file-document',
        },
        {
          name: 'Users',
          link: '/users',
          icon: 'mdi-account-multiple',
        },
      ];
    },
  },
  methods: {
    async logout() {
      if (!this.loading) {
        this.loading = true;
        this.$store.dispatch('auth/logout');
        this.loading = false;
      }
    },
    goMypage() {
      if (this.$route.path !== '/mypage') {
        this.$router.push('/mypage');
      }
    },
  },
};
</script>
