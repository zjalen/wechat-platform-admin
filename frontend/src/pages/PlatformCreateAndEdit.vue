<template>
  <q-page class="q-pa-lg">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="平台管理" to="/platforms" :replace="true" />
      <q-breadcrumbs-el :label="breadcrumbTitle" />
    </q-breadcrumbs>

    <q-card class="q-mt-lg q-pa-md">
      <q-card-section>
        <q-form @submit="onSubmit" class="q-gutter-sm edit-form">
          <view class="edit-form-item">
            <view class="edit-form-item-label">项目名称</view>
            <q-input
              outlined
              dense
              :square="true"
              class="edit-form-item-value"
              hint="输入项目名称，如公众号名称，仅作标题显示用"
              v-model="formData.name"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            />
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">AppId</view>
            <q-input
              outlined
              dense
              :square="true"
              class="edit-form-item-value"
              hint="参照微信官方后台的 AppId 填写，后续作为接口必要参数调用"
              v-model="formData.app_id"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            />
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">AppSecret</view>
            <q-input
              outlined
              dense
              :square="true"
              class="edit-form-item-value"
              :type="isPwd ? 'password' : 'text'"
              v-model="formData.app_secret"
              hint="参照微信官方后台的 App Secret 填写，后续作为接口必要参数调用"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            >
              <template v-slot:append>
                <q-icon
                  :name="isPwd ? 'r_visibility_off' : 'r_visibility'"
                  class="cursor-pointer"
                  @click="isPwd = !isPwd"
                />
              </template>
            </q-input>
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">token</view>
            <q-input
              outlined
              dense
              :square="true"
              :type="isPwd ? 'password' : 'text'"
              class="edit-form-item-value"
              v-model="formData.token"
              hint="参照微信官方后台的 token 填写，验证微信官方消息的必需项"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            />
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">aes_key</view>
            <q-input
              outlined
              dense
              :square="true"
              class="edit-form-item-value"
              :type="isPwd ? 'password' : 'text'"
              v-model="formData.aes_key"
              hint="参照微信官方后台的 aes_key 填写，解析微信加密消息的必需项"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            />
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">简介</view>
            <q-input
              outlined
              dense
              :square="true"
              class="edit-form-item-value"
              hint="简介信息仅做展示使用，方便在列表中了解该平台的作用"
              v-model="formData.description"
            />
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">是否对外开放</view>
            <q-toggle
              v-model="formData.is_open"
              class="edit-form-item-value q-pb-md"
              label="不开放意味者对应接口不可被外部调用，不影响本平台内部使用"
            />
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">对外标识</view>
            <q-input
              outlined
              dense
              :square="true"
              class="edit-form-item-value"
              hint="标识用作对外提供服务，请勿随意更改，可能会影响微信官方消息推送"
              v-model="formData.slug"
            />
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">类型</view>
            <div class="q-gutter-sm edit-form-item-value q-pb-md">
              <q-radio
                v-model="formData.type"
                :val="0"
                label="开放平台-第三方"
              />
              <q-radio v-model="formData.type" :val="1" label="公众号" />
              <q-radio v-model="formData.type" :val="2" label="小程序" />
            </div>
          </view>

          <div>
            <q-btn label="提交" unelevated type="submit" color="primary" />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { createPlatform, getPlatform, updatePlatform } from "src/api";
import { ref } from "vue";

export default {
  name: "PlatformCreateAndEdit",
  setup() {
    return {
      isPwd: ref(true),
    };
  },
  data: () => ({
    id: null,
    formData: {
      name: "",
      app_id: "",
      app_secret: "",
      token: "",
      aes_key: "",
      description: "",
      slug: "",
      is_open: false,
      type: 0,
    },
    breadcrumbTitle: "添加",
  }),
  beforeMount() {
    this.id = this.$route.params.id;
    this.breadcrumbTitle = !this.id ? "添加" : "编辑";
    if (this.id) {
      this.initData();
    }
  },
  methods: {
    initData() {
      getPlatform(this.id).then((res) => {
        Object.keys(this.formData).forEach((value) => {
          this.formData[value] = res[value];
        });
      });
    },
    onSubmit() {
      if (!this.id) {
        createPlatform(this.formData).then(() => {
          this.$q.notify("创建成功");
          this.$router.back();
        });
      } else {
        updatePlatform(this.id, this.formData).then(() => {
          this.$q.notify("更新成功");
          this.$router.back();
        });
      }
    },
  },
};
</script>

<style scoped></style>
