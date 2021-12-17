<template>
  <q-page class="q-pa-lg">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="平台管理" to="/platforms" :replace="true" />
      <q-breadcrumbs-el :label="breadcrumbTitle" />
    </q-breadcrumbs>
    <q-card class="q-mt-lg">
      <q-card-section class="panel-form">
        <view class="row">
          <view class="text-bold">以下信息需与微信开放平台填写信息保持一致，注意保护隐私信息，防止泄露</view>
          <q-space />
          <q-btn flat round :icon="visible ? 'visibility' : 'visibility_off'" :color="visible ? 'primary' : 'grey'"
                 @click="visible = !visible"></q-btn>
        </view>
        <view class="q-pb-none panel-form-item">
          <view class="panel-form-item-label">AppID</view>
          <view class="panel-form-item-value">
            <div>{{ app_id }}</div>
            <div class="panel-form-item-value-tip">平台唯一标识</div>
          </view>
        </view>
        <view class="q-pb-none panel-form-item">
          <view class="panel-form-item-label">授权事件接收URL</view>
          <view class="panel-form-item-value">
            <div>{{ serve_url }}</div>
            <div class="panel-form-item-value-tip">
              <a
                href="https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/operation/thirdparty/prepare.html#_3%E3%80%81%E6%8E%88%E6%9D%83%E4%BA%8B%E4%BB%B6%E6%8E%A5%E6%94%B6-URL"
                target="_blank">微信开放平台开发配置中所填链接地址必须与此URL保持一致</a>
              ，用于接收平台推送给第三方平台账号的消息与事件，如授权事件通知、component_verify_ticket等
            </div>
          </view>
        </view>
        <view class="panel-form-item">
          <view class="panel-form-item-label">AccessToken</view>
          <view class="panel-form-item-value">
            <div>{{ visibleData(access_token) }}</div>
            <div v-if="visible && errMsg" class="panel-form-item-value-tip text-negative">{{
                errMsg
              }}
            </div>
            <div class="panel-form-item-value-tip">微信官方票据换取的身份标识，接口调用必需参数，该参数会随使用自动更新，最长 2 小时内有效</div>
          </view>
        </view>
      </q-card-section>
    </q-card>

    <view class="row q-col-gutter-lg q-pt-lg">
      <view class="col-lg-3 col-md-4 col-sm-6 col-xs-12" v-for="(item, index) in authorizers" :key="index">
        <q-card class="cursor-pointer" @click="onSubPlatformClick(item.authorizer_appid)">
          <q-card-section class="text-h6 text-primary">
            {{ item.authorizer_appid }}
          </q-card-section>
        </q-card>
      </view>
    </view>
    <view class="row q-mt-lg">
      <q-space />
      <q-pagination
        v-model="currentPage"
        :max="Math.ceil(totalCount / perPage)"
        input
      />
    </view>
  </q-page>
</template>

<script>
import { getAuthorizer, getAuthorizers, getSecretConfig } from 'src/api/open-platform'

export default {
  name: 'ThirdPlatform',
  data: () => ({
    id: null,
    visible: false,
    breadcrumbTitle: '第三方平台',
    app_id: null,
    serve_url: null,
    access_token: '',
    errMsg: null,
    authorizers: [],
    currentPage: 1,
    perPage: 20,
    totalCount: 0,
  }),
  created () {
    this.id = this.$route.params.id
    this.initData()
  },
  methods: {
    initData () {
      getSecretConfig(this.id).then(res => {
        this.app_id = res.app_id
        this.breadcrumbTitle = res.name
        this.serve_url = res.serve_url
        if (res.access_token) {
          this.errMsg = null
          this.access_token = res.access_token.component_access_token
          this.loadAuthorizers()
        }
        else {
          this.access_token = '无效'
          this.errMsg = res.errMsg
        }
      })
    },
    loadAuthorizers () {
      getAuthorizers(this.id).then(res => {
        this.totalCount = res.total_count
        this.authorizers = res.list
      })
    },
    visibleData (data) {
      return this.visible ? data : '******'
    },
    onSubPlatformClick (appId) {
      getAuthorizer(this.id, appId).then(res => {
        console.log(res)
      })
    }
  },
}
</script>

<style scoped>

</style>
