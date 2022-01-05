<template>
  <q-page class="q-pa-lg">
    <q-breadcrumbs>
      <q-breadcrumbs-el
        label="代码管理"
        :to="`/open-platform/${opId}/mini-program/${appId}/code-manage`"
        :replace="true"
      />
      <q-breadcrumbs-el label="代码审核" />
    </q-breadcrumbs>

    <q-card class="q-mt-lg">
      <q-card-section class="text-h4">提交审核</q-card-section>
      <q-separator />
      <q-card-section>
        <q-form @submit="onSubmit" class="edit-form">
          <view class="edit-form-item">
            <view class="edit-form-item-label">版本描述 *</view>
            <q-input
              class="edit-form-item-value"
              dense
              outlined
              label="版本描述 *"
              hint="简要描述本次更新涉及的内容点"
              model-value=""
              v-model="formData.version_desc"
              type="textarea"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            ></q-input>
          </view>

          <view class="edit-form-item">
            <view class="edit-form-item-label">小程序截图</view>
            <view
              v-if="formData.preview_info.pic_id_list.length > 0"
              class="edit-form-item-value"
            >
              <div
                v-for="(item, index) in formData.preview_info.pic_id_list"
                :key="index"
              >
                <q-input
                  dense
                  outlined
                  :label="`图片 ${index + 1}`"
                  model-value=""
                  hint="非必填，上传截图目的为辅助审核，大小不能超过 2 M"
                  v-model="formData.preview_info.pic_id_list[index]"
                  lazy-rules
                  :rules="[
                    (val) =>
                      (val && val.length > 0) || '请选择媒体文件或移除此项',
                  ]"
                >
                  <template v-slot:append>
                    <q-icon
                      name="r_image"
                      class="cursor-pointer"
                      @click="onChooseMediaClick('pic_' + index, 'image')"
                    ></q-icon>
                  </template>
                  <template v-slot:after>
                    <q-icon
                      name="r_remove"
                      class="cursor-pointer q-pl-xs"
                      size="24px"
                      @click="onRemove('pic_id_list', index)"
                    >
                      <q-tooltip>移除</q-tooltip>
                    </q-icon>
                  </template>
                </q-input>
              </div>
            </view>
            <view
              v-if="formData.preview_info.pic_id_list.length < 5"
              class="flex-center q-field__marginal"
            >
              <q-icon
                name="r_add"
                class="cursor-pointer q-pl-xs"
                size="24px"
                @click="onAppendMediaClick('pic_id_list')"
              >
                <q-tooltip>增加1项，最多5项</q-tooltip>
              </q-icon>
            </view>
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">小程序录屏</view>
            <view
              v-if="formData.preview_info.video_id_list.length > 0"
              class="edit-form-item-value"
            >
              <div
                v-for="(item, index) in formData.preview_info.video_id_list"
                :key="index"
              >
                <q-input
                  dense
                  outlined
                  :label="`视频 ${index + 1}`"
                  model-value=""
                  hint="非必填，上传录屏目的为辅助审核，大小不能超过 10 M"
                  v-model="formData.preview_info.video_id_list[index]"
                  lazy-rules
                  :rules="[
                    (val) =>
                      (val && val.length > 0) || '请选择媒体文件或移除此项',
                  ]"
                >
                  <template v-slot:append>
                    <q-icon
                      name="r_movie"
                      class="cursor-pointer"
                      @click="onChooseMediaClick('video_' + index, 'video')"
                    ></q-icon>
                  </template>
                  <template v-slot:after>
                    <q-icon
                      name="r_remove"
                      class="cursor-pointer q-pl-xs"
                      size="24px"
                      @click="onRemove('video_id_list', index)"
                    >
                      <q-tooltip>移除</q-tooltip>
                    </q-icon>
                  </template>
                </q-input>
              </div>
            </view>
            <view
              v-if="formData.preview_info.video_id_list.length < 5"
              class="flex-center q-field__marginal"
            >
              <q-icon
                name="r_add"
                class="cursor-pointer q-pl-xs"
                size="24px"
                @click="onAppendMediaClick('video_id_list')"
              >
                <q-tooltip>增加1项，最多5项</q-tooltip>
              </q-icon>
            </view>
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">审核项 item_list</view>
            <view
              v-if="formData.item_list.length > 0"
              class="edit-form-item-value"
            >
              <div v-for="(item, index) in formData.item_list" :key="index">
                <q-input
                  dense
                  outlined
                  label="address"
                  model-value=""
                  hint="小程序页面地址，可通过 获取代码页面 列表获得"
                  v-model="formData.item_list[index].address"
                  lazy-rules
                  :rules="[
                    (val) => (val && val.length > 0) || '请填写或移除此项',
                  ]"
                >
                  <template v-slot:after>
                    <q-icon
                      name="r_remove"
                      class="cursor-pointer q-pl-xs"
                      size="24px"
                      @click="onRemoveFormItemListClick(index)"
                    >
                      <q-tooltip>移除</q-tooltip>
                    </q-icon>
                  </template>
                </q-input>
                <q-input
                  dense
                  outlined
                  label="title"
                  model-value=""
                  hint="该页面的名称"
                  v-model="formData.item_list[index].title"
                >
                </q-input>
                <q-input
                  dense
                  outlined
                  label="tag"
                  model-value=""
                  hint="页面标签，以空格隔开，最多 10 个，单个最长 20 个字符"
                  v-model="formData.item_list[index].tag"
                >
                </q-input>
                <view class="row q-col-gutter-sm">
                  <q-input
                    dense
                    outlined
                    label="first_class"
                    hint="类目 1 名称"
                    model-value=""
                    v-model="formData.item_list[index].first_class"
                  ></q-input>
                  <q-input
                    dense
                    outlined
                    label="second_class"
                    model-value=""
                    hint="类目 2 名称"
                    v-model="formData.item_list[index].second_class"
                  ></q-input>
                  <q-input
                    dense
                    outlined
                    label="third_class"
                    model-value=""
                    hint="类目 3 名称"
                    v-model="formData.item_list[index].third_class"
                  ></q-input>
                  <q-input
                    dense
                    outlined
                    label="first_id"
                    type="number"
                    model-value=""
                    hint="类目 1 id"
                    v-model="formData.item_list[index].first_id"
                  ></q-input>
                  <q-input
                    dense
                    outlined
                    label="second_id"
                    model-value=""
                    type="number"
                    hint="类目 2 id"
                    v-model="formData.item_list[index].second_id"
                  ></q-input>
                  <q-input
                    dense
                    outlined
                    label="third_id"
                    model-value=""
                    type="number"
                    hint="类目 3 id"
                    v-model="formData.item_list[index].third_id"
                  ></q-input>
                </view>
                <q-separator class="q-mb-sm" />
              </div>
            </view>

            <view
              v-if="formData.item_list.length < 5"
              class="flex-center q-field__marginal"
            >
              <q-icon
                name="r_add"
                class="cursor-pointer q-pl-xs"
                size="24px"
                @click="onAppendFormItemListClick"
              >
                <q-tooltip>增加1项，最多5项</q-tooltip>
              </q-icon>
            </view>
          </view>

          <view class="edit-form-item">
            <view class="edit-form-item-label">驳回反馈说明</view>
            <view class="edit-form-item-value">
              <q-toggle
                model-value=""
                v-model="showFeedbackInfo"
                label="驳回反馈"
              ></q-toggle>

              <q-input
                v-if="showFeedbackInfo"
                dense
                outlined
                label="驳回反馈说明"
                hint="审核被驳回时可以填写此项目进行说明，否则将忽略此项"
                model-value=""
                v-model="formData.feedback_info"
                type="textarea"
              ></q-input>
            </view>
          </view>

          <view v-if="showFeedbackInfo" class="edit-form-item">
            <view class="edit-form-item-label">驳回反馈截图</view>
            <view
              v-if="feedback_stuff_list.length > 0"
              class="edit-form-item-value"
            >
              <div v-for="(pic, index) in feedback_stuff_list" :key="index">
                <q-input
                  dense
                  outlined
                  :label="`图片 ${index + 1}`"
                  model-value=""
                  hint="被驳回时，可以提交说明图片辅助重新审核"
                  v-model="feedback_stuff_list[index]"
                >
                  <template v-slot:append>
                    <q-icon
                      name="r_image"
                      class="cursor-pointer"
                      @click="
                        onChooseMediaClick('pic_' + index, 'image', false)
                      "
                    ></q-icon>
                  </template>
                  <template v-slot:after>
                    <q-icon
                      name="r_remove"
                      class="cursor-pointer q-pl-xs"
                      size="24px"
                      @click="onRemove('feedback_stuff_list', index)"
                    >
                      <q-tooltip>移除</q-tooltip>
                    </q-icon>
                  </template>
                </q-input>
              </div>
            </view>
            <view
              v-if="feedback_stuff_list.length < 5"
              class="flex-center q-field__marginal"
            >
              <q-icon
                name="r_add"
                class="cursor-pointer q-pl-xs"
                size="24px"
                @click="onAppendMediaClick('feedback_stuff_list')"
              >
                <q-tooltip>增加1项，最多5项</q-tooltip>
              </q-icon>
            </view>
          </view>
          <view class="edit-form-item">
            <view class="edit-form-item-label">UGC 场景说明</view>
            <view class="edit-form-item-value">
              <view>
                <q-toggle
                  :model-value="showUgcItem"
                  v-model="showUgcItem"
                  label="UGC 声明"
                ></q-toggle>
              </view>
              <view v-if="showUgcItem">
                <div class="q-gutter-sm">
                  <q-checkbox
                    model-value=""
                    v-model="formData.ugc_declare.scene"
                    label="不涉及用户生成内容"
                    :val="0"
                  />
                  <q-checkbox
                    model-value=""
                    v-model="formData.ugc_declare.scene"
                    label="用户资料"
                    :val="1"
                  />
                  <q-checkbox
                    model-value=""
                    v-model="formData.ugc_declare.scene"
                    label="图片"
                    :val="2"
                  />
                  <q-checkbox
                    model-value=""
                    v-model="formData.ugc_declare.scene"
                    label="视频"
                    :val="3"
                  />
                  <q-checkbox
                    model-value=""
                    v-model="formData.ugc_declare.scene"
                    label="文本"
                    :val="4"
                  />
                  <q-checkbox
                    model-value=""
                    v-model="formData.ugc_declare.scene"
                    label="其他"
                    :val="5"
                  />
                </div>

                <q-input
                  v-if="formData.ugc_declare.scene.includes(5)"
                  dense
                  outlined
                  label="other_scene_desc"
                  model-value=""
                  type="textarea"
                  hint="当scene选其他时的说明,不超时256字"
                  v-model="formData.ugc_declare.other_scene_desc"
                >
                </q-input>
                <q-separator />
                <div class="q-gutter-sm">
                  <q-checkbox
                    model-value=""
                    v-model="formData.ugc_declare.method"
                    :val="1"
                    label="使用平台建议的内容安全API"
                  />
                  <q-checkbox
                    model-value=""
                    v-model="formData.ugc_declare.method"
                    :val="2"
                    label="使用其他的内容审核产品"
                  />
                  <q-checkbox
                    model-value=""
                    v-model="formData.ugc_declare.method"
                    :val="3"
                    label="通过人工审核把关"
                  />
                  <q-checkbox
                    model-value=""
                    v-model="formData.ugc_declare.method"
                    label="未作内容审核"
                    :val="4"
                  />
                </div>
                <q-separator />
                <div class="q-gutter-sm">
                  <q-radio
                    :model-value="formData.ugc_declare.has_audit_team"
                    v-model="formData.ugc_declare.has_audit_team"
                    :val="0"
                    label="无审核团队"
                  />
                  <q-radio
                    :model-value="formData.ugc_declare.has_audit_team"
                    v-model="formData.ugc_declare.has_audit_team"
                    :val="1"
                    label="有审核团队"
                  />
                </div>
                <q-input
                  dense
                  outlined
                  label="audit_desc"
                  model-value=""
                  type="textarea"
                  hint="说明当前对UGC内容的审核机制,不超过256字"
                  v-model="formData.ugc_declare.audit_desc"
                >
                </q-input>
              </view>
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

    <q-dialog v-model="showMediaChooseDialog" full-width full-height>
      <media-choose-card
        :type="mediaType"
        :slug="formSlug"
        :is-for-audit="isForAudit"
        @media-chosen="onMediaChosen"
      ></media-choose-card>
    </q-dialog>
  </q-page>
</template>

<script>
import MediaChooseCard from "components/MediaChooseCard";
import { codeAudit } from "src/api/sub-mini-program";

export default {
  name: "CodeManageAudit",
  components: { MediaChooseCard },
  data: () => ({
    formData: {
      version_desc: "",
      preview_info: {
        pic_id_list: [],
        video_id_list: [],
      },
      item_list: [],
      feedback_info: "",
      feedback_stuff: "",
      ugc_declare: {
        scene: [],
        other_scene_desc: "",
        method: [],
        has_audit_team: 0,
        audit_desc: "",
      },
    },
    feedback_stuff_list: [],
    mediaType: "",
    formSlug: "",
    showMediaChooseDialog: false,
    isForAudit: false,
    showUgcItem: false,
    showFeedbackInfo: false,
  }),
  methods: {
    onSubmit() {
      this.formData.feedback_stuff = this.feedback_stuff_list.join("|");
      const data = this.formData;
      if (!this.showUgcItem) {
        delete data.ugc_declare;
      }
      if (!this.showFeedbackInfo) {
        delete data.feedback_stuff;
        delete data.feedback_info;
      }
      codeAudit(this.opId, this.appId, data).then((res) => {
        this.$q.dialog({
          title: "提交成功",
          message:
            "审核 ID (auditid) 为：" + res.auditid + "，如有需要请自行储存",
        });
      });
    },
    onChooseMediaClick(slug, type, isForAudit = true) {
      this.mediaType = type;
      this.formSlug = slug;
      this.isForAudit = isForAudit;
      this.showMediaChooseDialog = true;
    },
    onAppendMediaClick(key) {
      if (
        key === "feedback_stuff_list" &&
        this.feedback_stuff_list.length < 5
      ) {
        this.feedback_stuff_list.push("");
      } else if (this.formData.preview_info[key].length < 5) {
        this.formData.preview_info[key].push("");
      }
    },
    onRemove(key, index) {
      if (key === "feedback_stuff_list") {
        this.feedback_stuff_list.splice(index, 1);
      } else {
        this.formData.preview_info[key].splice(index, 1);
      }
    },
    onAppendFormItemListClick() {
      if (this.formData.item_list.length < 5) {
        const item = {
          address: "",
          tag: "",
          first_class: "",
          second_class: "",
          third_class: "",
          first_id: null,
          second_id: null,
          third_id: null,
          title: "",
        };
        this.formData.item_list.push(item);
      }
    },
    onRemoveFormItemListClick(index) {
      this.formData.item_list.splice(index, 1);
    },
    onMediaChosen(data) {
      const arr = data.slug.split("_");
      if (arr[0] === "pic") {
        this.formData.preview_info.pic_id_list[arr[1]] = data.mediaId;
      } else if (arr[0] === "video") {
        this.formData.preview_info.video_id_list[arr[1]] = data.mediaId;
      }
      this.showMediaChooseDialog = false;
    },
  },
};
</script>

<style scoped></style>
