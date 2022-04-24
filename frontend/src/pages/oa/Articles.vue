<template>
  <q-page class="q-pa-lg q-gutter-lg">
    <div>
      <div class="text-h4">发表记录</div>
      <div class="text-caption text-grey q-mt-sm">
        当前为公众号已上传的素材，与微信公众号后台一致
      </div>
    </div>
    <div>
      <div class="row">
        <div class="text-subtitle1">文章（共{{ totalCount }}条）</div>
        <q-space />
      </div>
      <div v-if="articleList.length === 0" class="text-center q-pa-lg">
        暂无已发布文章
      </div>
      <div class="row q-col-gutter-md q-mt-none">
        <div
          class="col-12"
          v-for="article in articleList"
          :key="article.article_id"
        >
          <q-card class="row">
            <q-card-section>
              <q-img
                width="80px"
                :src="article.content.news_item[0].thumb_url"
                :alt="article.content.news_item[0].title"
                :ratio="1"
              ></q-img>
            </q-card-section>
            <q-card-section class="col">
              <div class="text-subtitle1">
                <a
                  :href="article.content.news_item[0].url"
                  target="_blank"
                  class="text-black"
                  >{{ article.content.news_item[0].title }}</a
                >
              </div>
              <div class="text-caption q-py-sm">
                {{ article.content.news_item[0].digest }}
              </div>
              <div class="text-caption text-grey">
                更新于：{{ formatDate(article.content.update_time) }}
              </div>
            </q-card-section>
            <q-card-section class="flex flex-center">
              <q-btn round outline dense icon="r_more_horiz" color="grey">
                <q-menu>
                  <q-list separator>
                    <q-item
                      clickable
                      v-close-popup
                      @click="onCopyClick(article.content.news_item[0].url)"
                    >
                      <q-item-section>复制链接</q-item-section>
                    </q-item>
                    <q-item
                      clickable
                      v-close-popup
                      @click.stop="onDeleteClick(article.article_id)"
                    >
                      <q-item-section>删除</q-item-section>
                    </q-item>
                  </q-list>
                </q-menu>
              </q-btn>
            </q-card-section>
          </q-card>
        </div>
      </div>
      <div class="row">
        <q-space />
        <q-pagination
          v-model="page"
          :max="Math.ceil(totalCount / pageSize)"
          input
          @update:model-value="initData"
        />
      </div>
    </div>
  </q-page>
</template>

<script>
import { copyToClipboard, date } from "quasar";
import {
  deleteArticle,
  getArticles,
} from "src/api/authorizer-official-account.js";

export default {
  name: "OfficialAccountArticles",
  data: () => ({
    articleList: [],
    totalCount: 0,
    page: 1,
    pageSize: 24,
    showUploader: false,
    disableUpload: true,
    chosenMedia: {},
    currentMedia: {},
  }),
  beforeMount() {
    this.initData();
  },
  methods: {
    initData() {
      getArticles(this.opId, this.appId, {
        offset: (this.page - 1) * this.pageSize,
        count: this.pageSize,
      }).then((res) => {
        this.articleList = res.item;
        this.totalCount = res.total_count;
      });
    },
    onMediaClick(article) {
      this.currentMedia = article;
      this.showMediaDetail = true;
    },
    onCopyClick(text) {
      copyToClipboard(text)
        .then(() => {
          this.$q.notify("复制成功");
        })
        .catch(() => {
          this.$q.notify({
            message: "复制失败，请手动复制",
            color: "negative",
          });
        });
    },
    formatDate(timestamp) {
      return date.formatDate(timestamp * 1000, "YYYY-MM-DD HH:mm:ss");
    },
    onDeleteClick(articleId) {
      this.$q
        .dialog({
          title: "确定删除吗",
          message: "删除后不可找回，请谨慎操作。",
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          deleteArticle(this.opId, this.appId, articleId).then(() => {
            this.$q.notify("删除成功");
            this.initData();
          });
        });
    },
  },
};
</script>

<style scoped lang="scss">
.q-item__section--side {
  width: 80px;
}
a:hover {
  color: $primary !important;
}
</style>
