<template>
  <q-page>
    首页 {{ openPlatformId }} {{ id}}
  </q-page>
</template>

<script>
export default {
  name: 'Index',
  data: () => ({
    openPlatformId: null,
    id: null
  }),
  beforeMount () {
    this.openPlatformId = this.$route.params.opId
    this.id = this.$route.params.id
  }
}
</script>

<style scoped>

</style>
