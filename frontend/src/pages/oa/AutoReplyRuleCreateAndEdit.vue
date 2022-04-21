<template>
  <q-page class="q-pa-lg">
    <q-breadcrumbs>
      <q-breadcrumbs-el
        label="平台管理"
        :to="`/open-platform/${this.opId}/official-account/${this.appId}/auto-reply-rules`"
        :replace="true"
      />
      <q-breadcrumbs-el label="添加自动回复规则" />
    </q-breadcrumbs>
    <q-card class="q-mt-md">
      <q-card-section>
        <q-form @submit="onSubmitClick">
          <q-input
            outlined
            dense
            hint="规则最多60个字"
            v-model="formData.name"
            lazy-rules
            :rules="[(val) => !!val || '必填项目']"
          >
            <template #before>
              <div class="text-body2" style="width: 100px">规则名称</div>
            </template>
          </q-input>
          <div class="q-gutter-sm row flex items-center q-pb-md">
            <div class="text-body2" style="width: 100px">匹配方式</div>
            <q-radio v-model="formData.match_type" :val="1" label="全匹配" />
            <q-radio v-model="formData.match_type" :val="0" label="半匹配" />
          </div>
          <q-input
            outlined
            dense
            hint="输入关键词"
            v-model="formData.keyword"
            lazy-rules
            :rules="[(val) => !!val || '必填项目']"
          >
            <template #before>
              <div class="text-body2" style="width: 100px">关键词</div>
            </template>
          </q-input>
          <div class="q-gutter-sm row flex items-center q-pb-lg">
            <div class="text-body2" style="width: 100px">回复类型</div>
            <q-list class="bg-grey-3 row">
              <q-item
                :active="formData.type === 0"
                clickable
                @click="formData.type = 0"
                dense
                class="q-px-sm"
              >
                <q-item-section side class="q-pr-xs">
                  <q-icon name="r_description"></q-icon>
                </q-item-section>
                <q-item-section> 已发表的内容 </q-item-section>
              </q-item>
              <q-item
                :active="formData.type === 1"
                clickable
                @click="formData.type = 1"
                dense
                class="q-px-sm"
              >
                <q-item-section side class="q-pr-xs">
                  <q-icon name="r_title"></q-icon>
                </q-item-section>
                <q-item-section> 文字 </q-item-section>
              </q-item>
              <q-item
                :active="formData.type === 2"
                clickable
                @click="formData.type = 2"
                dense
                class="q-px-sm"
              >
                <q-item-section side class="q-pr-xs">
                  <q-icon name="r_image"></q-icon>
                </q-item-section>
                <q-item-section> 图片 </q-item-section>
              </q-item>
              <q-item
                :active="formData.type === 3"
                clickable
                @click="formData.type = 3"
                dense
                class="q-px-sm"
              >
                <q-item-section side class="q-pr-xs">
                  <q-icon name="r_volume_up"></q-icon>
                </q-item-section>
                <q-item-section> 音频 </q-item-section>
              </q-item>
              <q-item
                :active="formData.type === 4"
                clickable
                @click="formData.type = 4"
                dense
                class="q-px-sm"
              >
                <q-item-section side class="q-pr-xs">
                  <q-icon name="r_movie"></q-icon>
                </q-item-section>
                <q-item-section> 视频 </q-item-section>
              </q-item>
              <q-item
                :active="formData.type === 5"
                clickable
                @click="formData.type = 5"
                dense
                class="q-px-sm"
              >
                <q-item-section side class="q-pr-xs">
                  <q-icon name="r_newspaper"></q-icon>
                </q-item-section>
                <q-item-section> 图文链接 </q-item-section>
              </q-item>
            </q-list>
          </div>
          <q-input
            v-if="formData.type === 0"
            outlined
            dense
            hint="请选择文章 ID"
            v-model="contentForm[0].article_id"
          >
            <template #before>
              <div class="text-body2" style="width: 100px">已发表内容</div>
            </template>
          </q-input>
          <q-input
            v-if="formData.type === 1"
            outlined
            type="textarea"
            dense
            hint="请输入回复文字"
            v-model="contentForm[1].text"
            lazy-rules
            :rules="[(val) => !!val || '必填项目']"
          >
            <template #before>
              <div class="text-body2" style="width: 100px">回复文字</div>
            </template>
          </q-input>
          <q-input
            v-if="
              formData.type === 2 || formData.type === 3 || formData.type === 4
            "
            outlined
            dense
            hint="请选择素材 ID"
            v-model="contentForm[2].media_id"
            lazy-rules
            :rules="[(val) => !!val || '必填项目']"
          >
            <template #before>
              <div class="text-body2" style="width: 100px">回复素材</div>
            </template>
          </q-input>
          <q-input
            v-if="formData.type === 5"
            outlined
            dense
            hint="请输入图文标题"
            v-model="contentForm[5].title"
            lazy-rules
            :rules="[(val) => !!val || '必填项目']"
          >
            <template #before>
              <div class="text-body2" style="width: 100px">图文标题</div>
            </template>
          </q-input>
          <q-input
            v-if="formData.type === 5"
            outlined
            dense
            hint="请输入图文描述"
            v-model="contentForm[5].description"
            lazy-rules
            :rules="[(val) => !!val || '必填项目']"
          >
            <template #before>
              <div class="text-body2" style="width: 100px">图文描述</div>
            </template>
          </q-input>
          <q-input
            v-if="formData.type === 5"
            outlined
            dense
            hint="请输入封面链接"
            v-model="contentForm[5].pic_url"
            lazy-rules
            :rules="[(val) => !!val || '必填项目']"
          >
            <template #before>
              <div class="text-body2" style="width: 100px">封面链接</div>
            </template>
          </q-input>
          <q-input
            v-if="formData.type === 5"
            outlined
            dense
            hint="跳转链接"
            v-model="contentForm[5].url"
            lazy-rules
            :rules="[(val) => !!val || '必填项目']"
          >
            <template #before>
              <div class="text-body2" style="width: 100px">跳转链接</div>
            </template>
          </q-input>
          <div class="row">
            <q-space />
            <q-btn
              unelevated
              label="提交"
              icon="r_save"
              type="submit"
              color="primary"
            ></q-btn>
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { createAutoReplyRule } from "src/api/authorizer-official-account.js";

export default {
  name: "AutoReplyRuleCreateAndEdit",
  data: () => ({
    id: null,
    formData: {
      name: null,
      keyword: null,
      match_type: 1,
      type: 0,
      content: "",
    },
    content: "",
    contentForm: {
      0: { article_id: "" },
      1: { text: "" },
      2: { media_id: "" },
      5: { title: "", description: "", image: "", url: "" },
    },
  }),
  mounted() {
    this.id = this.$route.params.id;
  },
  methods: {
    onSubmitClick() {
      this.formData.content = this.contentForm[this.formData.type];
      if (!this.id) {
        createAutoReplyRule(this.opId, this.appId, this.formData).then(() => {
          this.$q.notify("创建成功");
        });
      }
    },
  },
};
</script>

<style scoped></style>
