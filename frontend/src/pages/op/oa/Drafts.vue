<template>
  <q-page class="q-pa-lg q-gutter-lg">
    <q-card>
      <q-card-section>
        <div class="text-h4">草稿箱</div>
        <div class="text-caption text-grey q-mt-sm">
          当前为公众号已上传的素材，与微信公众号后台一致
        </div>
      </q-card-section>
    </q-card>
    <q-card>
      <q-card-section>
        <div
          class="full-width text-center q-my-lg cursor-pointer"
          @click="onCreateClick"
        >
          <q-icon name="r_add" size="30px"></q-icon>
          <div>新的创作</div>
        </div>
      </q-card-section>
    </q-card>
    <div>
      <div class="row">
        <div class="text-subtitle1">草稿（共{{ totalCount }}条）</div>
        <q-space />
      </div>
      <div v-if="mediaList.length === 0" class="text-center q-pa-lg">
        暂无草稿
      </div>
      <div class="row q-col-gutter-md q-mt-none">
        <div
          class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
          v-for="media in mediaList"
          :key="media.media_id"
        >
          <q-card>
            <q-img
              :src="media.content.news_item[0].thumb_url"
              :alt="media.content.news_item[0].title"
              :ratio="21 / 9"
            ></q-img>
            <view class="absolute-top-right q-gutter-sm q-pa-sm">
              <q-btn
                round
                outline
                size="sm"
                dense
                icon="r_delete"
                color="grey"
                @click.stop="onDeleteClick(media.media_id)"
              >
                <q-tooltip>删除草稿</q-tooltip>
              </q-btn>
              <q-btn
                outline
                rounded
                size="sm"
                label="发表"
                color="primary"
                @click="onPublishClick(media.media_id)"
              >
              </q-btn>
            </view>
            <q-card-section>
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
  deleteDraft,
  getDrafts,
  publishDraft,
} from "src/api/authorizer-official-account.js";

export default {
  name: "OfficialAccountDrafts",
  data: () => ({
    mediaList: [],
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
      getDrafts(this.$store.state.currentOpId, this.$store.state.currentAppId, {
        offset: (this.page - 1) * this.pageSize,
        count: this.pageSize,
      }).then((res) => {
        this.mediaList = res.item;
        this.totalCount = res.total_count;
      });
    },
    onMediaClick(media) {
      this.currentMedia = media;
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
    onCreateClick() {
      this.$q.dialog({
        title: "暂不支持",
        message: "暂不支持草稿的创建和编辑，请通过微信公众号后台操作。",
      });
    },
    onDeleteClick(mediaId) {
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
          deleteDraft(
            this.$store.state.currentOpId,
            this.$store.state.currentAppId,
            mediaId
          ).then(() => {
            this.$q.notify("删除成功");
            this.initData();
          });
        });
    },
    onPublishClick(mediaId) {
      this.$q
        .dialog({
          title: "发布确认",
          message:
            "发布成功后，文章将出现在发表记录里，文章可以通过单独发布或微信菜单公开访问，但不会群发，也不占用群发条数。",
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          publishDraft(
            this.$store.state.currentOpId,
            this.$store.state.currentAppId,
            mediaId
          ).then(() => {
            this.$q.notify("发布成功");
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
</style>
