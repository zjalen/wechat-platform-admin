<template>
  <q-page class="q-pa-lg">
    <q-card v-if="showAuthorizerDetail">
      <q-card-section>
        <sub-platform-card :info="currentAuthorizer.authorizer_info"></sub-platform-card>
      </q-card-section>
    </q-card>
    <q-card v-else>
      <q-card-section>
        <sub-platform-card :info="item"></sub-platform-card>
      </q-card-section>
      <q-separator class="q-mx-md" />
      <q-card-actions align="right">
        <q-btn flat color="primary" @click="loadAuthorizer(item.app_id)">更多详情</q-btn>
      </q-card-actions>
    </q-card>
  </q-page>
</template>

<script>
import SubPlatformCard from 'components/SubPlatformContent'
import { getAuthorizer, getSubPlatform } from 'src/api/open-platform'

export default {
  name: 'Index',
  components: { SubPlatformCard },
  data: () => ({
    openPlatformId: null,
    id: null,
    item: {},
    showAuthorizerDetail: false,
    currentAuthorizer: {},
  }),
  beforeMount () {
    this.openPlatformId = this.$route.params.opId
    this.id = this.$route.params.id
    this.initData()
  },
  methods: {
    initData () {
      getSubPlatform(this.openPlatformId, this.id).then(res => {
        this.item = res
      })
    },
    loadAuthorizer (appId) {
      this.$q.loading.show()
      const timer = setTimeout(() => {
        this.$q.loading.hide()
      }, 5000)
      this.currentAuthorizer = {}
      getAuthorizer(this.openPlatformId, appId).then(res => {
        clearTimeout(timer)
        this.$q.loading.hide()
        this.currentAuthorizer = res
        this.showAuthorizerDetail = true
      })
    },
  },
}
</script>

<style scoped>

</style>
