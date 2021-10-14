<template>
  <v-app>
    <v-main>
      <Header />
      <router-view />
      <Snackbar />
    </v-main>
  </v-app>
</template>

<script>
import Header from '@/js/components/Header.vue';
import Snackbar from '@/js/components/Snackbar.vue';
export default {
  name: 'App',
  components: {
    Header,
    Snackbar,
  },
};
</script>
