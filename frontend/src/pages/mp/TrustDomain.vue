<template>
  <q-page class="q-pa-lg">
    <q-banner v-if="showBanner" class="bg-primary text-white q-mb-lg" rounded :inline-actions="true">
      注意信息可修改权限与次数请参照微信官方文档说明
      <template v-slot:action>
        <q-btn flat color="white" label="隐藏" @click="showBanner = false" />
      </template>
    </q-banner>
    <q-card>
      <q-card-section class="text-h4 flex items-center">
        服务器域名配置
        <view class="text-body2 text-grey-6 q-ml-sm">可进行 api 调用等通讯的域名，<a
          href="https://developers.weixin.qq.com/miniprogram/dev/framework/ability/network.html#1.%20%E6%9C%8D%E5%8A%A1%E5%99%A8%E5%9F%9F%E5%90%8D%E9%85%8D%E7%BD%AE"
          target="_blank">参见链接</a></view>
        <q-space />
        <q-btn unelevated dense color="primary" style="height: 30px">修改</q-btn>
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-list v-if="authorizerInfo.MiniProgramInfo" separator>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>request 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in authorizerInfo.MiniProgramInfo.network.RequestDomain" :key="url" lines="1">
                {{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>ws/socket 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in authorizerInfo.MiniProgramInfo.network.WsRequestDomain" :key="url" lines="1">
                {{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>uploadFile 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in authorizerInfo.MiniProgramInfo.network.UploadDomain" :key="url" lines="1">
                {{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>downloadFIle 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in authorizerInfo.MiniProgramInfo.network.DownloadDomain" :key="url" lines="1">
                {{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>udp 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in authorizerInfo.MiniProgramInfo.network.UdpDomain" :key="url" lines="1">{{
                  url
                }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>tcp 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in authorizerInfo.MiniProgramInfo.network.TcpDomain" :key="url" lines="1">{{
                  url
                }}
              </q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
    </q-card>
    <q-card class="q-mt-lg">
      <q-card-section class="text-h4 flex items-center">
        业务域名设置
        <view class="text-body2 text-grey-6 q-ml-sm">可在 webview 中打开链接的域名，<a
          href="https://developers.weixin.qq.com/miniprogram/dev/framework/ability/domain.html" target="_blank">参见链接</a>
        </view>
        <q-space />
        <q-btn unelevated dense color="primary" style="height: 30px">修改</q-btn>
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-list v-if="authorizerInfo.MiniProgramInfo" separator>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>业务域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in authorizerInfo.MiniProgramInfo.network.BizDomain" :key="url" lines="1">{{
                  url
                }}
              </q-item-label>
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
  name: 'DomainSettings',
  setup () {
    const store = useStore()
    return {
      authorizerInfo: computed(() => store.state.currentAuthorizerInfo),
    }
  },
  data: () => ({
    showBanner: true,
  }),
}
</script>

<style scoped>

</style>
