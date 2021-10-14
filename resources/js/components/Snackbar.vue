<template>
  <v-snackbar
    v-model="isActive"
    class="mb-3"
    cols="8"
    bottom
    :color="type"
    :timeout="-1"
  >
    {{ message }}
  </v-snackbar>
</template>
<script>
import { mapState } from 'vuex';
export default {
  computed: {
    ...mapState('snackbar', {
      isActive: state => state.isActive,
      message: state => state.message,
      type: state => state.type,
    }),
  },
};
</script>
