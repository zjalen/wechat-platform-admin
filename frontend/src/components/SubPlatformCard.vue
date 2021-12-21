<template>
  <view>
    <view class="flex items-center q-pb-md">
      <view class="flex items-center">
        <view class="text-h6">{{ info.nick_name }}</view>
        <q-chip v-if="info.service_type_info" color="primary" dense square class="text-white">{{ getServiceTypeName() }}</q-chip>
        <q-chip v-if="info.service_type_name" color="primary" dense square class="text-white">{{ info.service_type_name }}</q-chip>
      </view>
      <q-space />
      <q-avatar size="40px"><img :src="info.head_img" /></q-avatar>
    </view>
    <q-separator class="q-mb-md" />
    <view>
      <view v-if="info.app_id" class="q-pb-none panel-form-item">
        <view class="panel-form-item-label">APP ID</view>
        <view class="panel-form-item-value">
          <div>{{ info.app_id }}</div>
          <div class="panel-form-item-value-tip">唯一标识，微信接口使用</div>
        </view>
      </view>
      <view v-if="info.user_name" class="q-pb-none panel-form-item">
        <view class="panel-form-item-label">原始 ID</view>
        <view class="panel-form-item-value">
          <div>{{ info.user_name }}</div>
          <div class="panel-form-item-value-tip">唯一标识，部分配置会用到，如第三方平台白名单</div>
        </view>
      </view>
      <view v-if="info.principal_name" class="q-pb-none panel-form-item">
        <view class="panel-form-item-label">认证信息</view>
        <view class="panel-form-item-value">
          <div class="text-accent">{{ info.principal_name }}</div>
        </view>
      </view>
      <view v-if="info.signature" class="q-pb-none panel-form-item">
        <view class="panel-form-item-label">描述信息</view>
        <view class="panel-form-item-value">
          <div>{{ info.signature }}</div>
        </view>
      </view>
      <view v-if="info.verify_type_info" class="q-pb-none panel-form-item">
        <view class="panel-form-item-label">认证类型</view>
        <view class="panel-form-item-value">
          <view class="text-primary">{{ getVerifyTypeName() }}</view>
        </view>
      </view>
      <view v-if="info.business_info" class="q-pb-none panel-form-item">
        <view class="panel-form-item-label">业务信息</view>
        <view class="panel-form-item-value">
          <q-chip v-for="(item, key) in businessTags" :key="key" square dense class="q-ml-none"
                  :color="item.value === 1 ? 'primary' : 'grey-5'"
                  text-color="white">
            {{ item.title }}
          </q-chip>
        </view>
      </view>
      <view v-if="isMiniProgram" class="q-pb-none panel-form-item">
        <view class="panel-form-item-label">小程序信息</view>
        <view class="panel-form-item-value">
          <view class="panel-form-item-value">
            <div v-for="(url, index) in info.MiniProgramInfo.network.RequestDomain" :key="index">{{ url }}</div>
            <div class="panel-form-item-value-tip">网络请求信赖域名</div>
          </view>
          <view class="panel-form-item-value">
            <div v-for="(url, index) in info.MiniProgramInfo.network.WsRequestDomain" :key="index">{{ url }}</div>
            <div class="panel-form-item-value-tip">ws 信赖域名</div>
          </view>
          <view class="panel-form-item-value">
            <div v-for="(url, index) in info.MiniProgramInfo.network.UploadDomain" :key="index">{{ url }}</div>
            <div class="panel-form-item-value-tip">上传信赖域名</div>
          </view>
          <view class="panel-form-item-value">
            <div v-for="(url, index) in info.MiniProgramInfo.network.DownloadDomain" :key="index">{{ url }}</div>
            <div class="panel-form-item-value-tip">下载信赖域名</div>
          </view>
          <view class="panel-form-item-value">
            <div v-for="(url, index) in info.MiniProgramInfo.network.BizDomain" :key="index">{{ url }}</div>
            <div class="panel-form-item-value-tip">业务信赖域名</div>
          </view>
          <view class="panel-form-item-value">
            <div>{{ JSON.stringify(info.MiniProgramInfo.categories) }}</div>
            <div class="panel-form-item-value-tip">小程序分类</div>
          </view>
        </view>
      </view>
      <view  v-if="info.qrcode_url" class="q-pb-none panel-form-item">
        <view class="panel-form-item-label">二维码</view>
        <view class="panel-form-item-value">
          <a target="_blank" :href="info.qrcode_url">查看</a>
        </view>
      </view>
    </view>
  </view>
</template>

<script>
import {
  getBusinessTypeInfo,
  getMiniProgramServiceTypeInfo,
  getMiniProgramVerifyTypeInfo,
  getOfficialAccountServiceTypeInfo,
  getOfficialAccountVerifyTypeInfo,
} from 'src/utils/platform'

export default {
  name: 'SubPlatformCard',
  props: {
    info: {
      type: Object,
      default: () => {
        return {}
      },
    },
  },
  data: () => ({}),
  computed: {
    isMiniProgram () {
      return Object.hasOwnProperty.call(this.info, 'MiniProgramInfo')
    },
    businessTags () {
      const businessTags = []
      if (Object.hasOwnProperty.call(this.info, 'service_type_info')) {
        Object.keys(this.info.business_info).forEach(value => {
          businessTags.push({
            title: getBusinessTypeInfo(value),
            value: this.info.business_info[value],
          })
        })
      }
      return businessTags
    },
  },
  methods: {
    getServiceTypeName () {
      if (Object.hasOwnProperty.call(this.info, 'service_type_info')) {
        if (this.isMiniProgram) {
          return getMiniProgramServiceTypeInfo(this.info.service_type_info.id)
        }
        else {
          return getOfficialAccountServiceTypeInfo(this.info.service_type_info.id)
        }
      }
      return ''
    },
    getVerifyTypeName () {
      if (Object.hasOwnProperty.call(this.info, 'verify_type_info')) {
        if (this.isMiniProgram) {
          return getMiniProgramVerifyTypeInfo(this.info.service_type_info.id)
        }
        else {
          return getOfficialAccountVerifyTypeInfo(this.info.service_type_info.id)
        }
      }
      return ''
    },
  },
}
</script>

<style scoped>

</style>
