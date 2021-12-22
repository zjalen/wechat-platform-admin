<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section>
        <sub-platform-card :info="authorizerInfo"></sub-platform-card>
      </q-card-section>
      <q-inner-loading :showing="!showAuthorizerDetail">
        <q-spinner-gears size="50px" color="primary" />
      </q-inner-loading>
    </q-card>
  </q-page>
</template>

<script>
import SubPlatformCard from 'components/SubPlatformContent'
import { useStore } from 'vuex'
import { computed } from 'vue'

export default {
  name: 'Index',
  components: { SubPlatformCard },
  setup () {
    const store = useStore()
    return {
      authorizerInfo: computed(() => store.state.currentAuthorizerInfo),
    }
  },
  data: () => ({
    openPlatformId: null,
    id: null,
    item: {},
    showAuthorizerDetail: false,
  }),
  mounted () {
    this.initData()
  },
  methods: {
    initData () {
      setTimeout(() => {
        this.showAuthorizerDetail = true
      }, 500)
    },
  },
}
</script>

<style scoped>

</style>
