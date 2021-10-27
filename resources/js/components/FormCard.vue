<template>
  <form>
    <v-card
      class="mx-2 mx-lg-auto mt-6 mt-lg-6 mb-6"
      style="max-width: 1100px; margin: auto"
    >
      <v-card-subtitle class="blue-grey subtitle-1 white--text">
        {{ title }}
      </v-card-subtitle>
      <v-progress-linear
        v-if="showProgress"
        indeterminate
        color="blue-grey darken-1"
      />
      <div v-if="showContent" class="pt-2 pt-lg-3 pb-6">
        <slot />
      </div>
    </v-card>
  </form>
</template>
<script>
export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    showContent: {
      type: Boolean,
      required: false,
      default: true,
    },
    showProgress: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
};
</script>
