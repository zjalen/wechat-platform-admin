<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section class="text-h4">
        用户隐私保护
        <div class="text-caption text-grey q-mt-sm">
          配置第三方代开发小程序的用户隐私保护指引
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section class="q-gutter-sm">
        <q-btn unelevated color="primary" @click="onSetPrivacyClick"
          >设置隐私保护指引
        </q-btn>
        <q-btn flat color="secondary" @click="onGetPrivacyClick(1)"
          >获取现网隐私保护指引
        </q-btn>
        <q-btn flat color="secondary" @click="onGetPrivacyClick(2)"
          >获取开发版隐私保护指引
        </q-btn>
      </q-card-section>
    </q-card>

    <q-card v-if="showPrivacySetting" class="q-mt-lg">
      <q-card-section class="text-subtitle1">
        {{ resultTitle }}
      </q-card-section>
      <q-separator />
      <q-card-section>
        <json-viewer :value="jsonData"></json-viewer>
      </q-card-section>
    </q-card>

    <q-banner
      v-if="showEditForm"
      class="bg-primary text-white q-mt-lg"
      rounded
      :inline-actions="true"
    >
      1.
      当现网没有隐私保护指引，只能先设置开发版，下个版本提交代码审核后生效。<br />
      2. 当有现网版本时，可直接修改 owner_setting
      里除自定义隐私保护指引外的其他配置项，修改后立即生效。<br />
      3. 当有现网版本时，如需修改其除 owner_setting
      的其他项或自定义保护指引，只能设置为开发版，下个版本提交代码审核后生效。
    </q-banner>
    <q-card class="q-mt-lg" v-if="showEditForm">
      <q-card-section class="text-subtitle1">设置隐私保护指引</q-card-section>
      <q-separator />
      <q-card-section>
        <q-form class="q-gutter-sm" @submit="onSubmitClick">
          <view class="edit-form-item">
            <view class="edit-form-item-label">版本</view>
            <view class="edit-form-item-value">
              <q-radio
                :model-value="formData.privacy_ver"
                :val="1"
                v-model="formData.privacy_ver"
                label="现网版本"
              ></q-radio>
              <q-radio
                :model-value="formData.privacy_ver"
                :val="2"
                v-model="formData.privacy_ver"
                label="开发版本"
              ></q-radio>
            </view>
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">收集方信息 owner_list</view>
            <view class="edit-form-item-value">
              <q-input
                outlined
                dense
                type="email"
                :model-value="formData.owner_setting.contact_email"
                v-model="formData.owner_setting.contact_email"
                label="收集方邮箱"
                hint="4 种联系方式至少填一种"
                :rules="[ownerContactRule]"
              ></q-input>
              <q-input
                outlined
                dense
                type="tel"
                model-value=""
                v-model="formData.owner_setting.contact_phone"
                label="收集方电话"
                hint="4 种联系方式至少填一种"
              ></q-input>
              <q-input
                outlined
                dense
                model-value=""
                v-model="formData.owner_setting.contact_qq"
                label="收集方 QQ"
                hint="4 种联系方式至少填一种"
              ></q-input>
              <q-input
                outlined
                dense
                model-value=""
                v-model="formData.owner_setting.contact_weixin"
                label="收集方微信"
                hint="4 种联系方式至少填一种"
              ></q-input>
              <q-input
                outlined
                dense
                model-value=""
                v-model="formData.owner_setting.notice_method"
                label="通知方式，如：弹窗，公告等 *"
                hint="当信息变动时，通过什么方式通知用户，按实际情况填写"
                lazy-rules
                :rules="[(val) => (val && val.length > 0) || '必填内容']"
              ></q-input>
              <q-input
                outlined
                dense
                model-value=""
                v-model="formData.owner_setting.ext_file_media_id"
                label="自定义隐私保护指引 media_id"
                hint="上传自定义隐私保护文件，获得 media_id，如不填则使用微信默认提供的"
              >
                <template v-slot:append>
                  <q-icon
                    name="r_upload_file"
                    class="cursor-pointer"
                    @click="onUploadClick"
                  >
                    <q-tooltip>上传自定义隐私保护指引</q-tooltip>
                  </q-icon>
                </template>
              </q-input>
              <q-input
                outlined
                dense
                model-value=""
                v-model="formData.owner_setting.store_expire_timestamp"
                label="收集用户信息存储时间，如：30天"
                hint="如果不填则展示为【开发者承诺，除法律法规另有规定，开发者对你的信息保存期限应当为实现处理目的所必要的最短时间】，如果填请填数字+天"
              ></q-input>
            </view>
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">配置列表 setting_list</view>
            <view
              v-if="formData.setting_list.length > 0"
              class="edit-form-item-value"
            >
              <div
                v-for="(item, index) in formData.setting_list"
                :key="index"
                class="flex q-gutter-md"
              >
                <q-select
                  style="flex: 1"
                  model-value=""
                  v-model="formData.setting_list[index].privacy_key"
                  dense
                  outlined
                  label="选项 key"
                  hint="请选择配置项"
                  :options="privacy_desc_list"
                  option-label="privacy_desc"
                  option-value="privacy_key"
                  emit-value
                  lazy-rules
                  :rules="[(val) => (val && val.length > 0) || '必选内容']"
                ></q-select>
                <q-input
                  style="flex: 1"
                  model-value=""
                  v-model="formData.setting_list[index].privacy_text"
                  dense
                  outlined
                  label="用途描述"
                  hint="描述使用场景，如：扫码支付"
                  lazy-rules
                  :rules="[(val) => (val && val.length > 0) || '必填内容']"
                >
                  <template v-slot:after>
                    <q-icon
                      name="r_remove"
                      class="cursor-pointer"
                      size="24px"
                      @click="onRemovePrivacySettingList(index)"
                    >
                      <q-tooltip>移除该条目</q-tooltip>
                    </q-icon>
                  </template>
                </q-input>
              </div>
            </view>
            <view class="flex-center q-field__marginal">
              <q-icon
                name="r_add"
                class="cursor-pointer q-pl-xs"
                size="24px"
                @click="onAddPrivacySettingList"
              >
                <q-tooltip>添加一条</q-tooltip>
              </q-icon>
            </view>
          </view>

          <view>
            <q-btn
              unelevated
              color="primary"
              label="提交"
              type="submit"
              icon="r_save"
            ></q-btn>
          </view>
        </q-form>
      </q-card-section>
    </q-card>

    <q-dialog v-model="showUploadDialog">
      <q-card style="width: 80vw">
        <q-card-section class="text-subtitle1 text-bold row">
          <view>点击加号添加文件</view>
        </q-card-section>
        <q-separator />
        <q-card-section class="q-pa-xs">
          <q-uploader
            class="no-box-shadow"
            ref="uploader"
            style="width: 100%"
            :url="uploadUrl()"
            max-file-size="102400"
            field-name="file"
            :headers="[
              { name: 'Authorization', value: 'Bearer ' + $store.state.token },
            ]"
            :label="`仅支持 txt 文件`"
            accept=".txt"
            @rejected="onRejected"
            @uploaded="onUploaded"
            @added="onAdded"
          />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="关闭" color="grey" v-close-popup />
          <q-btn
            unelevated
            label="上传"
            color="primary"
            :disable="disableUpload"
            @click="$refs.uploader.upload()"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { getPrivacySetting, setPrivacySetting } from "src/api/sub-mini-program";
import JsonViewer from "vue-json-viewer";

export default {
  name: "MpPrivacy",
  components: { JsonViewer },
  data: () => ({
    resultTitle: "",
    jsonData: {},
    showPrivacySetting: false,
    showEditForm: false,
    showUploadDialog: false,
    formData: {
      privacy_ver: 2,
      owner_setting: {
        contact_email: "",
        contact_phone: "",
        contact_qq: "",
        contact_weixin: "",
        ext_file_media_id: "",
        notice_method: "",
        store_expire_timestamp: "",
      },
      setting_list: [],
    },
    privacy_desc_list: [],
    disableUpload: true,
  }),
  methods: {
    ownerContactRule() {
      return new Promise((resolve) => {
        const check =
          !!this.formData.owner_setting.contact_email ||
          !!this.formData.owner_setting.contact_phone ||
          !!this.formData.owner_setting.contact_qq ||
          !!this.formData.owner_setting.contact_weixin;
        resolve(check || "* 4 种联系方式至少填一种");
      });
    },
    onGetPrivacyClick(version) {
      this.resultTitle = version === 1 ? "现网版本" : "开发版本";
      this.jsonData = {};
      getPrivacySetting(this.opId, this.appId, { privacyVer: version }).then(
        (res) => {
          this.jsonData = res;
          this.showPrivacySetting = true;
          this.privacy_desc_list = res.privacy_desc.privacy_desc_list;
        }
      );
    },
    onSetPrivacyClick() {
      this.showEditForm = true;
    },
    onAddPrivacySettingList() {
      if (this.privacy_desc_list.length < 1) {
        this.$q.loading.show();
        getPrivacySetting(this.opId, this.appId, { privacyVer: 2 })
          .then((res) => {
            this.privacy_desc_list = res.privacy_desc.privacy_desc_list;
            this.$q.loading.hide();
          })
          .catch(() => {
            this.$q.loading.hide();
          });
      }
      this.formData.setting_list.push({ privacy_key: "", privacy_text: "" });
    },
    onRemovePrivacySettingList(index) {
      this.formData.setting_list.splice(index, 1);
    },
    onSubmitClick() {
      setPrivacySetting(this.opId, this.appId, this.formData).then(() => {
        this.$q.notify("设置完成");
      });
    },
    onUploadClick() {
      this.showUploadDialog = true;
    },
    uploadUrl() {
      return (
        "/api/open-platform/" +
        this.opId +
        "/mp/" +
        this.appId +
        "/upload-privacy-file"
      );
    },
    onRejected() {
      this.$q.notify({
        type: "negative",
        message: `文件添加失败，超过 100K`,
      });
    },
    onAdded(files) {
      if (files.length > 0) {
        this.disableUpload = false;
      }
    },
    onUploaded(info) {
      this.disableUpload = true;
      this.showUploadDialog = false;
      const response = JSON.parse(info.xhr.response);
      this.formData.owner_setting.ext_file_media_id =
        response.data.ext_file_media_id;
      this.$q.notify("上传成功");
    },
  },
};
</script>

<style scoped></style>
