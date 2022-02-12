<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section>
        <view class="flex items-center q-pb-md">
          <view class="flex items-center">
            <view class="text-h4">{{ basicInfo.nickname }}</view>
            <q-chip
              v-if="basicInfo.account_type"
              color="primary"
              dense
              square
              class="text-white"
            >
              {{ getTypeName(basicInfo.account_type) }}
            </q-chip>
          </view>
          <q-space />
          <q-avatar size="40px">
            <q-icon
              class="cursor-pointer"
              color="grey"
              size="40px"
              name="r_qr_code"
              @click="toMakeQR"
            >
              <q-tooltip>去生成二维码</q-tooltip>
            </q-icon>
          </q-avatar>
        </view>
        <q-separator class="q-mb-md" />
        <view>
          <view
            v-if="basicInfo.nickname_info"
            class="q-pb-none panel-form-item"
          >
            <view class="panel-form-item-label">名称</view>
            <view class="panel-form-item-value">
              <div>{{ basicInfo.nickname_info.nickname || "空" }}</div>
              <div class="panel-form-item-value-tip">
                本年度共可修改
                {{ basicInfo.nickname_info.modify_quota }} 次，已使用
                {{ basicInfo.nickname_info.modify_used_count }} 次
              </div>
            </view>
          </view>
          <view
            v-if="basicInfo.head_image_info"
            class="q-pb-none panel-form-item"
          >
            <view class="panel-form-item-label">头像</view>
            <view class="panel-form-item-value">
              <div>
                <q-avatar>
                  <q-img
                    alt="头像"
                    :src="basicInfo.head_image_info.head_image_url"
                  ></q-img>
                </q-avatar>
              </div>
              <div class="panel-form-item-value-tip">
                本年度共可修改
                {{ basicInfo.head_image_info.modify_quota }} 次，已使用
                {{ basicInfo.head_image_info.modify_used_count }} 次
              </div>
            </view>
          </view>
          <view class="q-pb-none panel-form-item">
            <view class="panel-form-item-label">APP ID</view>
            <view class="panel-form-item-value">
              <div>{{ basicInfo.appid }}</div>
              <div class="panel-form-item-value-tip">
                唯一标识，微信接口使用
              </div>
            </view>
          </view>
          <view class="q-pb-none panel-form-item">
            <view class="panel-form-item-label">主体类型</view>
            <view class="panel-form-item-value">
              <div>{{ getPrincipleTypeName(basicInfo.principal_type) }}</div>
            </view>
          </view>
          <view class="q-pb-none panel-form-item">
            <view class="panel-form-item-label">主体名称</view>
            <view class="panel-form-item-value">
              <div class="text-accent">
                {{ basicInfo.principal_name || "空" }}
              </div>
            </view>
          </view>
          <view class="q-pb-none panel-form-item">
            <view class="panel-form-item-label">主体标识</view>
            <view class="panel-form-item-value">
              <div>{{ basicInfo.credential || "空" }}</div>
            </view>
          </view>
          <view
            v-if="basicInfo.realname_status"
            class="q-pb-none panel-form-item"
          >
            <view class="panel-form-item-label">实名信息</view>
            <view class="panel-form-item-value">
              <div>
                {{ basicInfo.realname_status === 1 ? "已实名" : "未实名" }}
              </div>
            </view>
          </view>
          <view
            v-if="basicInfo.wx_verify_info"
            class="q-pb-none panel-form-item"
          >
            <view class="panel-form-item-label">微信认证信息</view>
            <view class="panel-form-item-value">
              <q-chip
                square
                dense
                class="q-ml-none"
                :color="
                  basicInfo.wx_verify_info.qualification_verify
                    ? 'primary'
                    : 'grey-5'
                "
                text-color="white"
                label="资质认证"
              >
              </q-chip>
              <q-chip
                square
                dense
                class="q-ml-none"
                :color="
                  basicInfo.wx_verify_info.naming_verify ? 'primary' : 'grey-5'
                "
                text-color="white"
                label="名称认证"
              >
              </q-chip>
              <q-chip
                v-if="basicInfo.wx_verify_info.annual_review"
                square
                dense
                class="q-ml-none"
                :color="
                  basicInfo.wx_verify_info.annual_review === 1
                    ? 'primary'
                    : 'grey-5'
                "
                text-color="white"
              >
                待年审
              </q-chip>
              <q-chip
                v-if="basicInfo.wx_verify_info.annual_review"
                square
                dense
                class="q-ml-none"
                color="primary"
                text-color="white"
              >
                年审时间：
                {{ annual_review_begin_time + "--" + annual_review_end_time }}
              </q-chip>
              <div class="panel-form-item-value-tip">
                绿色为已认证，或待处理
              </div>
            </view>
          </view>
          <view
            v-if="basicInfo.signature_info"
            class="q-pb-none panel-form-item"
          >
            <view class="panel-form-item-label">功能介绍</view>
            <view class="panel-form-item-value">
              <div>{{ basicInfo.signature_info.signature || "空" }}</div>
              <div class="panel-form-item-value-tip">
                本年度共可修改
                {{ basicInfo.signature_info.modify_quota }} 次，已使用
                {{ basicInfo.signature_info.modify_used_count }} 次
              </div>
            </view>
          </view>
          <view class="q-pb-none panel-form-item">
            <view class="panel-form-item-label">注册国家码</view>
            <view class="panel-form-item-value">
              <div>{{ basicInfo.registered_country }}</div>
              <div class="panel-form-item-value-tip">
                1017 中国大陆，其它码可
                <a
                  target="_blank"
                  href="https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/Mini_Program_Basic_Info/Mini_Program_Information_Settings.html#%E6%B3%A8%E5%86%8C%E5%9B%BD%E5%AE%B6"
                >
                  参见文档
                </a>
              </div>
            </view>
          </view>
        </view>
      </q-card-section>
      <q-inner-loading :showing="!showAuthorizerDetail">
        <q-spinner-gears size="50px" color="primary" />
      </q-inner-loading>
    </q-card>
  </q-page>
</template>

<script>
import { useStore } from "vuex";
import { computed } from "vue";

export default {
  name: "MpIndex",
  setup() {
    const store = useStore();
    return {
      basicInfo: computed(() => store.state.basicInfo),
    };
  },
  data: () => ({
    showAuthorizerDetail: false,
  }),
  mounted() {
    this.initData();
  },
  methods: {
    initData() {
      setTimeout(() => {
        this.showAuthorizerDetail = true;
      }, 500);
    },
    getTypeName(type) {
      let name = "未知";
      switch (type) {
        case 1:
          name = "订阅号";
          break;
        case 2:
          name = "服务号";
          break;
        case 3:
          name = "小程序";
          break;
      }
      return name;
    },
    getPrincipleTypeName(type) {
      let name = "未知";
      switch (type) {
        case 0:
          name = "个人";
          break;
        case 1:
          name = "企业";
          break;
        case 2:
          name = "媒体";
          break;
        case 3:
          name = "政府";
          break;
        case 4:
          name = "其他组织";
          break;
      }
      return name;
    },
    toMakeQR() {
      this.$router.push({
        name: "mp-qr",
      });
    },
  },
};
</script>

<style scoped></style>
