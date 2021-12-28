<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section>
        <div class="text-h4">服务器素材中心</div>
        <div class="text-body2 text-grey-6">
          点击素材上传到微信获得素材 id 以供审核等使用
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
        >
          <q-tab name="image" label="图片" />
          <q-tab name="video" label="视频" />
          <q-tab name="voice" label="音频" />
        </q-tabs>

        <q-separator />

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel v-for="type in typeList" :key="type" :name="type">
            <view class="row q-gutter-sm q-mb-md">
              <q-btn color="primary" unelevated @click="showUploader = true"
                >上传新文件
              </q-btn>
              <q-space />
              <q-checkbox
                :model-value="selectAll[type]"
                v-model="selectAll[type]"
                label="全选"
                @update:model-value="onSelectAll"
              ></q-checkbox>
              <q-btn color="negative" unelevated @click="deleteBatch">
                批量删除
              </q-btn>
            </view>
            <view class="row q-col-gutter-sm">
              <view
                class="col-lg-2 col-md-3 col-sm-4 col-xs-6"
                v-for="(media, index) in mediaList[type]"
                :key="media.name"
              >
                <view
                  class="full-width full-height flex flex-center q-pa-xs cursor-pointer relative-position"
                  :class="getChosenClass(type, media.name)"
                  @click="onMediaClick(media)"
                >
                  <q-img :src="media.url" :alt="media.name"></q-img>
                  <view class="absolute-bottom text-white bg-grey text-body2"
                    >{{ media.name }}
                  </view>
                  <q-checkbox
                    class="absolute q-pa-none"
                    size="40px"
                    dense
                    style="top: 1px; left: 1px; padding: 2px"
                    :true-value="media.name"
                    :model-value="chosenMedia[type][index]"
                    v-model="chosenMedia[type][index]"
                  />
                </view>
              </view>
            </view>
          </q-tab-panel>
        </q-tab-panels>
      </q-card-section>
    </q-card>

    <q-dialog v-model="showUploader">
      <q-card>
        <q-card-section class="text-subtitle1 text-bold row">
          <view
            >点击加号添加文件
            <div class="text-body2 text-negative">
              请注意<span class="text-bold">不要上传同名文件</span
              >，否则将会失败
            </div>
          </view>
        </q-card-section>
        <q-separator />
        <q-card-section class="q-pa-xs">
          <q-uploader
            class="no-box-shadow"
            ref="uploader"
            style="width: 100%"
            :url="uploadUrl()"
            :headers="[
              { name: 'Authorization', value: 'Bearer ' + $store.state.token },
            ]"
            :label="`仅支持 ${acceptType} 文件`"
            multiple
            :accept="acceptType"
            @rejected="onRejected"
            @uploaded="onUploaded"
            @added="onAdded"
          />
        </q-card-section>
        <q-separator />
        <q-card-actions align="right">
          <q-btn flat label="关闭" color="grey" v-close-popup />
          <q-btn
            unelevated
            :disable="disableUpload"
            label="上传"
            color="primary"
            @click="$refs.uploader.upload()"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

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
          <q-img v-if="!playVideo" :src="currentMedia.url"></q-img>
          <q-video
            v-if="(tab === 'video' || tab === 'voice') && playVideo"
            :src="currentMedia.url"
          ></q-video>
          <q-btn
            v-if="tab === 'video' || tab === 'voice'"
            flat
            icon="r_play_circle_filled"
            color="primary"
            class="absolute-bottom-left"
            style="bottom: 20px; left: 10px"
            @click="playVideo = true"
          >
          </q-btn>
          <view class="absolute-top text-white bg-grey text-body2"
            >{{ currentMedia.name }}
          </view>
          <q-item v-if="currentMedia.mediaId" class="q-pb-none">
            <q-item-section thumbnail style="width: 70px"
              >素材id:
            </q-item-section>
            <q-item-section>
              <q-input
                v-if="currentMedia.mediaId"
                dense
                readonly
                class="no-border"
                :model-value="currentMedia.mediaId"
              >
                <template v-slot:after>
                  <q-icon
                    name="r_copy"
                    class="cursor-pointer"
                    @click="onMediaIdClick(currentMedia.mediaId)"
                  >
                    <q-tooltip>请点击复制</q-tooltip>
                  </q-icon>
                </template>
              </q-input>
              <q-item-label class="q-mt-sm text-negative" caption lines="2">
                点击按钮复制，临时素材3天有效，本系统不保存 ID，请自行保存使用
              </q-item-label>
            </q-item-section>
          </q-item>
        </q-card-section>
        <q-separator />
        <q-card-actions>
          <q-btn
            unelevated
            color="primary"
            label="添加为临时素材"
            @click="addTemplateMedia"
          ></q-btn>
          <q-btn
            unelevated
            color="primary"
            label="添加为审核临时素材"
            @click="addTemplateMedia"
          ></q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import {
  deleteLocalMedia,
  getLocalMediaList,
  uploadTemplateFile,
} from "src/api/sub-mini-program";
import { copyToClipboard } from "quasar";

export default {
  name: "LocalMedia",
  data: () => ({
    mediaList: [],
    typeList: [],
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
    uploadUrl() {
      return (
        "/api/open-platform/" +
        this.opId +
        "/mp/" +
        this.appId +
        "/local-media?type=" +
        this.tab
      );
    },
    initData() {
      getLocalMediaList(this.opId, this.appId).then((res) => {
        this.mediaList = res;
        this.typeList = Object.keys(res);
        this.typeList.forEach((type) => {
          this.selectAll[type] = false;
          this.chosenMedia[type] = res[type].map(() => {
            return false;
          });
        });
      });
    },
    onRejected(rejectedEntries) {
      this.$q.notify({
        type: "negative",
        message: `${rejectedEntries.length} 个文件不支持`,
      });
    },
    onAdded(files) {
      if (files.length > 0) {
        this.disableUpload = false;
      }
    },
    onUploaded() {
      this.disableUpload = true;
      this.initData();
      this.$q.notify("上传成功");
    },
    addTemplateMedia() {
      uploadTemplateFile(this.opId, this.appId, {
        fileName: this.currentMedia.name,
        type: this.tab,
      }).then((res) => {
        this.currentMedia.mediaId = res.media_id;
      });
    },
    onMediaClick(media) {
      this.currentMedia = media;
      this.showMediaDetail = true;
    },
    getChosenClass(type, name) {
      return this.chosenMedia[type] && this.chosenMedia[type].includes(name)
        ? "bg-green-3"
        : "bg-grey-3";
    },
    onSelectAll() {
      const type = this.tab;
      if (this.selectAll[type]) {
        this.chosenMedia[type] = this.mediaList[type].map((value) => {
          return value.name;
        });
      } else {
        this.chosenMedia[type] = this.mediaList[type].map(() => {
          return false;
        });
      }
    },
    deleteBatch() {
      const fileNames = this.chosenMedia[this.tab].filter((value) => {
        return !!value;
      });
      if (fileNames.length === 0) {
        this.$q.notify({ color: "negative", message: "请先勾选文件" });
        return;
      }
      deleteLocalMedia(this.opId, this.appId, {
        fileNames: fileNames,
        type: this.tab,
      }).then(() => {
        this.$q.notify("删除成功");
        this.initData();
      });
    },
    onMediaIdClick(text) {
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
  },
};
</script>

<style scoped></style>
