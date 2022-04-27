<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section>
        <view class="flex items-center q-pb-md">
          <view class="flex items-center">
            <view class="text-h4">{{ basicInfo.name }}</view>
            <q-chip
              v-if="basicInfo.type"
              color="primary"
              dense
              square
              class="text-white"
            >
              {{ getTypeName(basicInfo.type) }}
            </q-chip>
          </view>
          <q-space />
          <q-btn
            flat
            round
            :icon="visible ? 'r_visibility' : 'r_visibility_off'"
            :color="visible ? 'primary' : 'grey'"
            @click="visible = !visible"
          ></q-btn>
        </view>
        <q-separator class="q-mb-md" />
        <view>
          <view v-if="basicInfo.description" class="q-pb-none panel-form-item">
            <view class="panel-form-item-label">简介</view>
            <view class="panel-form-item-value">
              <div>{{ basicInfo.description }}</div>
              <div class="panel-form-item-value-tip">
                简介信息是存储在本地的描述信息，与线上简介不同
              </div>
            </view>
          </view>
          <view class="q-pb-none panel-form-item">
            <view class="panel-form-item-label">APP ID</view>
            <view class="panel-form-item-value">
              <div>{{ basicInfo.app_id }}</div>
              <div class="panel-form-item-value-tip">
                唯一标识，微信接口使用
              </div>
            </view>
          </view>
          <view class="q-pb-none panel-form-item">
            <view class="panel-form-item-label">AppSecret</view>
            <view class="panel-form-item-value">
              <div>{{ visibleData(basicInfo.app_secret) }}</div>
              <div class="panel-form-item-value-tip">
                公众号密钥，相当于密码，谨慎保存
              </div>
            </view>
          </view>
          <view class="q-pb-none panel-form-item">
            <view class="panel-form-item-label">服务器地址</view>
            <view class="panel-form-item-value">
              <div>{{ basicInfo.serve_url || "加载中" }}</div>
              <div class="panel-form-item-value-tip">
                用于公众号接收平台推送的消息与事件，用户发送的消息，点击的菜单事件，关注取关消息等都会推送到此地址。
              </div>
            </view>
          </view>
          <view class="panel-form-item">
            <view class="panel-form-item-label">令牌(Token)</view>
            <view class="panel-form-item-value">
              <div>{{ visibleData(basicInfo.token) }}</div>
              <div class="panel-form-item-value-tip">
                公众号或小程序用此Token来校验服务器是否合法。
              </div>
            </view>
          </view>
          <view class="panel-form-item">
            <view class="panel-form-item-label"
              >消息加解密密钥EncodingAESKey</view
            >
            <view class="panel-form-item-value">
              <div>{{ visibleData(basicInfo.aes_key) }}</div>
              <div class="panel-form-item-value-tip">
                在公众号或小程序加密解密消息过程中使用。必须是长度为43位的字符串，只能是字母和数字。
              </div>
            </view>
          </view>
          <view class="panel-form-item">
            <view class="panel-form-item-label">AccessToken</view>
            <view class="panel-form-item-value">
              <div>{{ visibleData(basicInfo.access_token) }}</div>
              <div
                v-if="visible && basicInfo.errMsg"
                class="panel-form-item-value-tip text-negative"
              >
                {{ basicInfo.errMsg }}
              </div>
              <div class="panel-form-item-value-tip">
                微信官方票据换取的身份标识，接口调用必需参数，该参数会随使用自动更新，最长
                2 小时内有效
              </div>
            </view>
          </view>
        </view>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { useStore } from "vuex";
import { computed } from "vue";

export default {
  name: "OaIndex",
  setup() {
    const store = useStore();
    return {
      basicInfo: computed(() => store.state.basicInfo),
    };
  },
  data: () => ({
    showAuthorizerDetail: false,
    visible: false,
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
        case 0:
          name = "开放平台";
          break;
        case 1:
          name = "公众号";
          break;
        case 2:
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
        name: "mpQR",
      });
    },
    visibleData(data) {
      return this.visible ? data : "******";
    },
  },
};
</script>

<style scoped></style>
