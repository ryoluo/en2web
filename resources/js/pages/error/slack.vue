<template>
  <FormCard title="Error" class="max-width" :show-progress="submitting">
    <div class="px-2 pt-6">
      <p class="grey--text text--darken-3">
        En2::Webに未登録、もしくはEn2:WebアカウントとSlackアカウントが連携されていません。管理者に連絡してください。
      </p>
      <p class="grey--text text--darken-3">
        <router-link to="/login"> Back to top </router-link>
      </p>
    </div>
  </FormCard>
</template>
<script>
import FormCard from '@/js/components/FormCard.vue';
export default {
  components: {
    FormCard,
  },
};
</script>
