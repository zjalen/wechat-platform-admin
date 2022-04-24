<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section>
        <div class="text-h4">公众号素材库</div>
        <div class="text-caption text-grey q-mt-sm">
          当前为公众号已上传的素材，与微信公众号后台一致
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section class="q-pa-none">
        <q-tabs
          v-model="tab"
          dense
          class="text-grey"
          active-color="primary"
          indicator-color="primary"
          align="justify"
          narrow-indicator
          @update:model-value="initData"
        >
          <q-tab name="image" label="图片" />
          <q-tab name="video" label="视频" />
          <q-tab name="voice" label="音频" />
        </q-tabs>

        <q-separator />

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel v-for="type in typeList" :key="type" :name="type">
            <view class="row q-gutter-sm q-mb-md">
              <div class="text-subtitle1">
                素材（共{{ totalCount[type] }}条）
              </div>
              <q-space />
              <q-btn color="primary" dense unelevated @click="onUploadClick"
                >上传新文件
              </q-btn>
            </view>
            <view class="row q-col-gutter-sm">
              <div
                v-if="mediaList[type].length === 0"
                class="full-width text-center"
              >
                暂无素材
              </div>
              <view
                class="col-xs-3 col-sm-4 col-md-2 col-lg-1"
                v-for="media in mediaList[type]"
                :key="media.media_id"
              >
                <view
                  class="full-width full-height flex flex-center q-pa-xs cursor-pointer relative-position"
                  style="border: 1px solid #ccc"
                >
                  <q-img
                    :src="media.url"
                    :alt="media.name"
                    class="cursor-pointer"
                    @click="onMediaClick(media)"
                  ></q-img>
                  <view class="absolute-bottom text-white bg-grey text-body2">
                    {{ media.name }}
                  </view>
                  <view class="absolute-top-left">
                    <q-btn
                      flat
                      round
                      size="16px"
                      dense
                      icon="r_delete"
                      color="negative"
                      @click.stop="onDeleteClick(media.media_id)"
                    >
                      <q-tooltip>删除素材</q-tooltip>
                    </q-btn>
                  </view>
                  <view class="absolute-top-right">
                    <a :href="media.url" target="_blank">
                      <q-btn
                        flat
                        round
                        size="16px"
                        dense
                        icon="r_search"
                        color="primary"
                      >
                        <q-tooltip>查看素材</q-tooltip>
                      </q-btn>
                    </a>
                  </view>
                </view>
              </view>
            </view>
            <div class="row">
              <q-space />
              <q-pagination
                v-model="page[type]"
                :max="Math.ceil(totalCount[type] / pageSize)"
                input
                @update:model-value="initData(tab)"
              />
            </div>
          </q-tab-panel>
        </q-tab-panels>
      </q-card-section>
    </q-card>

    <q-dialog v-model="showMediaDetail">
      <q-card style="width: 700px; max-width: 90vw">
        <q-card-section class="text-subtitle1 row">
          素材详情
          <q-space />
          <q-btn
            round
            flat
            size="sm"
            color="negative"
            icon="r_close"
            v-close-popup
          >
            <q-tooltip>关闭</q-tooltip>
          </q-btn>
        </q-card-section>
        <q-separator />
        <q-card-section>
          <q-list bordered separator>
            <q-item>
              <q-item-section side>文件名</q-item-section>
              <q-item-section>{{ currentMedia.name }}</q-item-section>
            </q-item>
            <q-item>
              <q-item-section side>media_id</q-item-section>
              <q-item-section>{{ currentMedia.media_id }}</q-item-section>
              <q-item-section side>
                <q-btn
                  flat
                  dense
                  color="secondary"
                  label="复制"
                  @click="onCopyClick(currentMedia.media_id)"
                ></q-btn>
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section side>url</q-item-section>
              <q-item-section>
                <q-item-label lines="2">{{ currentMedia.url }} </q-item-label>
              </q-item-section>
              <q-item-section side>
                <q-btn
                  flat
                  dense
                  color="secondary"
                  label="复制"
                  @click="onCopyClick(currentMedia.url)"
                ></q-btn>
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section side>上传时间</q-item-section>
              <q-item-section>
                {{ formatDate(currentMedia.update_time) }}
              </q-item-section>
            </q-item>
          </q-list>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { copyToClipboard, date } from "quasar";
import {
  deleteMaterial,
  getMaterials,
} from "src/api/authorizer-official-account.js";

export default {
  name: "OnlineMediaManage",
  data: () => ({
    mediaList: {
      image: [],
      voice: [],
      video: [],
    },
    typeList: ["image", "voice", "video"],
    totalCount: {
      image: 0,
      voice: 0,
      video: 0,
    },
    page: {
      image: 1,
      voice: 1,
      video: 1,
    },
    pageSize: 24,
    showUploader: false,
    disableUpload: true,
    chosenMedia: {},
    currentMedia: {},
    selectAll: {},
    showMediaDetail: false,
    tab: "image",
    playVideo: false,
  }),
  beforeMount() {
    this.initData();
  },
  computed: {
    acceptType() {
      if (this.tab === "image") {
        return ".png,.jpg,.jpeg,.gif";
      } else if (this.tab === "voice") {
        return ".amr,.mp3";
      } else if (this.tab === "video") {
        return ".mp4";
      } else {
        return null;
      }
    },
  },
  methods: {
    onUploadClick() {
      this.$q.dialog({
        title: "上传提醒",
        message:
          "请通过「服务器素材」页面进行上传到服务器，再点击素材，上传为永久素材。",
      });
    },
    initData(type = "image") {
      getMaterials(this.opId, this.appId, {
        type: type,
        offset: (this.page[type] - 1) * this.pageSize,
        count: this.pageSize,
      }).then((res) => {
        this.mediaList[type] = res.item;
        this.totalCount[type] = res.total_count;
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
    onDeleteClick(mediaId) {
      this.$q
        .dialog({
          title: "确定删除吗",
          message: "删除后不可找回",
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          deleteMaterial(this.opId, this.appId, mediaId).then(() => {
            this.$q.notify("删除成功");
            this.initData(this.tab);
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
