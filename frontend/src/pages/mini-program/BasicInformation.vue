<template>
  <q-page class="q-pa-lg">
    <q-banner v-if="showBanner" class="bg-primary text-white q-mb-lg" rounded :inline-actions="true">
      注意信息可修改权限与次数请参照微信官方文档说明
      <template v-slot:action>
        <q-btn flat color="white" label="隐藏" @click="showBanner = false" />
      </template>
    </q-banner>
    <q-card>
      <q-card-section class="text-h4">
        基本信息配置
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-list separator>
          <q-item>
            <q-item-section thumbnail top>
              <q-item-label>账号名称</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label lines="1">{{ authorizerInfo.nick_name }}</q-item-label>
              <q-item-label lines="1" caption>名称修改规则请参照微信官方文档说明</q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-btn flat dense label="修改" color="secondary"></q-btn>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>头像</q-item-label>
            </q-item-section>

            <q-item-section>
              <q-avatar><img :src="authorizerInfo.head_img"  alt="avatar"/></q-avatar>
            </q-item-section>

            <q-item-section side>
              <q-btn flat dense label="更换" color="secondary"></q-btn>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>简介</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label lines="1">{{ authorizerInfo.signature }}</q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-btn flat dense label="修改" color="secondary"></q-btn>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>服务类目</q-item-label>
            </q-item-section>
            <q-item-section v-if="authorizerInfo.MiniProgramInfo">
              <q-item-label lines="1">{{ authorizerInfo.MiniProgramInfo.categories[0].first }} > {{ authorizerInfo.MiniProgramInfo.categories[0].second }}</q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-btn flat dense label="修改" color="secondary"></q-btn>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { useStore } from 'vuex'
import { computed } from 'vue'

export default {
  name: 'BasicInformation',
  setup () {
    const store = useStore()
    return {
      authorizerInfo: computed(() => store.state.currentAuthorizerInfo)
    }
  },
  data: () => ({
    showBanner: true
  })
}
</script>

<style scoped>

</style>
