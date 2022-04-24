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
                @click="
                  formData.type = 0;
                  type = 'article';
                "
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
                @click="
                  formData.type = 2;
                  type = 'image';
                "
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
                @click="
                  formData.type = 3;
                  type = 'voice';
                "
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
                @click="
                  formData.type = 4;
                  type = 'video';
                "
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
            <template #append>
              <q-icon
                name="r_search"
                class="cursor-pointer"
                @click="
                  showMediaDialog = true;
                  getMediaList(type);
                "
              ></q-icon>
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
            <template #append>
              <q-icon
                name="r_search"
                class="cursor-pointer"
                @click="
                  showMediaDialog = true;
                  getMediaList(type);
                "
              ></q-icon>
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
            v-model="contentForm[5].image"
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

    <q-dialog v-model="showMediaDialog" full-width>
      <q-card>
        <q-card-section class="text-h6 flex flex-center">
          <div>选择素材/文章</div>
          <q-space />
          <q-btn flat icon="r_close" color="negative" v-close-popup></q-btn>
        </q-card-section>
        <q-card-section class="row q-col-gutter-sm" style="min-height: 600px">
          <div v-if="mediaList[type].length === 0" class="col-12 text-center">
            暂无内容
          </div>
          <div v-if="type === 'article'" class="col-12 col">
            <q-card
              v-for="(media, key) in mediaList[type]"
              :key="key"
              class="row cursor-pointer bg-grey-3"
              :class="media.article_id === chosenMediaId ? 'border-chosen' : ''"
              @click="chosenMediaId = media.article_id"
            >
              <q-card-section>
                <q-img
                  width="80px"
                  :src="media.content.news_item[0].thumb_url"
                  :alt="media.content.news_item[0].title"
                  :ratio="1"
                ></q-img>
              </q-card-section>
              <q-card-section class="col">
                <div class="text-subtitle1">
                  {{ media.content.news_item[0].title }}
                </div>
                <div class="text-caption q-py-sm">
                  {{ media.content.news_item[0].digest }}
                </div>
                <div class="text-caption text-grey">
                  更新于：{{ formatDate(media.content.update_time) }}
                </div>
              </q-card-section>
            </q-card>
          </div>
          <div v-else class="col-12 row q-col-gutter-md">
            <view
              class="col-xs-3 col-sm-4 col-md-2 col-lg-1"
              v-for="(media, key) in mediaList[type]"
              :key="key"
            >
              <view
                class="flex flex-center q-pa-xs cursor-pointer relative-position"
                :class="media.media_id === chosenMediaId ? 'border-chosen' : ''"
              >
                <q-img
                  :src="media.url"
                  :alt="media.name"
                  class="cursor-pointer"
                  @click="chosenMediaId = media.media_id"
                ></q-img>
                <view class="absolute-bottom text-white bg-grey text-body2">
                  {{ media.name }}
                </view>
              </view>
            </view>
          </div>
        </q-card-section>
        <div class="row">
          <q-space />
          <q-pagination
            v-model="page[type]"
            :max="Math.ceil(totalCount[type] / pageSize)"
            input
            @update:model-value="getMediaList(type)"
          />
        </div>
        <q-separator />
        <q-card-section class="flex flex-center q-gutter-md">
          <q-btn
            unelevated
            color="primary"
            :disable="!chosenMediaId"
            label="确定"
            @click="onConfirmClick"
          ></q-btn>
          <q-btn
            unelevated
            label="取消"
            @click="showMediaDialog = false"
          ></q-btn>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import {
  createAutoReplyRule,
  getArticles,
  getAutoReplyRule,
  getMaterials,
  updateAutoReplyRule,
} from "src/api/authorizer-official-account.js";
import { date } from "quasar";

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
    showMediaDialog: false,
    mediaList: {
      image: [],
      voice: [],
      video: [],
      article: [],
    },
    type: "article",
    page: {
      image: 1,
      voice: 1,
      video: 1,
      article: 1,
    },
    totalCount: {
      image: 0,
      voice: 0,
      video: 0,
      article: 0,
    },
    pageSize: 24,
    chosenMediaId: null,
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
    if (this.id) {
      this.initData();
    }
  },
  methods: {
    initData() {
      getAutoReplyRule(this.opId, this.appId, this.id).then((res) => {
        this.formData = res;
        Object.keys(this.contentForm[res.type]).forEach((key) => {
          this.contentForm[res.type][key] = res.content[key];
        });
      });
    },
    getMediaList(type) {
      if (type === "article") {
        getArticles(this.opId, this.appId, {
          offset: (this.page[type] - 1) * this.pageSize,
          count: this.pageSize,
        }).then((res) => {
          this.mediaList[type] = res.item;
          this.totalCount[type] = res.total_count;
        });
      } else {
        getMaterials(this.opId, this.appId, {
          type: type,
          offset: (this.page[type] - 1) * this.pageSize,
          count: this.pageSize,
        }).then((res) => {
          this.mediaList[type] = res.item;
          this.totalCount[type] = res.total_count;
        });
      }
    },
    onConfirmClick() {
      if (this.type === "article") {
        this.contentForm[0].article_id = this.chosenMediaId;
      } else {
        this.contentForm[2].media_id = this.chosenMediaId;
      }
      this.showMediaDialog = false;
    },
    onSubmitClick() {
      this.formData.content = this.contentForm[this.formData.type];
      if (!this.id) {
        createAutoReplyRule(this.opId, this.appId, this.formData).then(() => {
          this.$q.notify("创建成功");
          this.$router.back();
        });
      } else {
        updateAutoReplyRule(this.opId, this.appId, this.id, this.formData).then(
          () => {
            this.$q.notify("更新成功");
            this.$router.back();
          }
        );
      }
    },
    formatDate(timestamp) {
      return date.formatDate(timestamp * 1000, "YYYY-MM-DD HH:mm:ss");
    },
  },
};
</script>

<style scoped lang="scss">
.border-chosen {
  border: 1px solid $primary;
}
</style>
