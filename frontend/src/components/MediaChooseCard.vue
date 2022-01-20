<template>
  <q-card class="column">
    <q-card-section class="text-subtitle1 row">
      选择图片
      <q-space />
      <q-btn
        unelevated
        icon="r_add"
        color="primary"
        label="添加新文件"
        @click="showUploader = true"
      ></q-btn>
    </q-card-section>
    <q-separator />
    <q-card-section class="row q-col-gutter-sm">
      <view
        class="col-lg-1 col-md-2 col-sm-3 col-xs-4"
        v-for="media in mediaList"
        :key="media.name"
      >
        <view
          class="full-width full-height flex flex-center cursor-pointer relative-position q-pa-xs"
          :class="media.name === currentMedia.name ? 'bg-green-3' : 'bg-grey-3'"
          @click="onMediaClick(media)"
        >
          <q-img :src="media.url" :alt="media.name"></q-img>
          <view class="absolute-bottom text-white bg-grey text-body2"
            >{{ media.name }}
          </view>
        </view>
      </view>
    </q-card-section>
    <q-space />
    <q-separator />
    <q-card-actions class="flex-center">
      <q-btn color="grey" flat label="关闭" v-close-popup></q-btn>
      <q-btn
        v-if="isForAudit"
        :loading="uploading"
        :disable="!currentMedia.name"
        color="primary"
        unelevated
        label="上传为审核素材"
        @click="addAuditMedia"
      ></q-btn>
      <q-btn
        v-else
        :loading="uploading"
        :disable="!currentMedia.name"
        color="primary"
        unelevated
        label="上传为临时素材"
        @click="addTemplateMedia"
      ></q-btn>
    </q-card-actions>
  </q-card>

  <q-dialog v-model="showUploader">
    <q-card>
      <q-card-section class="text-subtitle1 text-bold row">
        <view
          >点击加号添加文件
          <div class="text-body2">
            请注意<span class="text-bold text-negative">不要上传同名文件</span
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
          :loading="uploading"
          label="上传"
          color="primary"
          @click="$refs.uploader.upload()"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import {
  uploadCodeAuditMedia,
  uploadTemplateFile,
} from "src/api/authorizer-mini-program";
import { getLocalResources } from "src/api/platform";

export default {
  name: "MediaChooseCard",
  props: {
    slug: String,
    type: String,
    isForAudit: {
      type: Boolean,
      default: false,
    },
  },
  emits: ["media-chosen"],
  data: () => ({
    currentMediaType: null,
    currentMedia: {},
    mediaList: [],
    showMediaPicker: false,
    uploading: false,
    showUploader: false,
    disableUpload: true,
  }),

  computed: {
    acceptType() {
      if (this.currentMediaType === "image") {
        return ".png,.jpg,.jpeg,.gif";
      } else if (this.currentMediaType === "voice") {
        return ".amr,.mp3";
      } else if (this.currentMediaType === "video") {
        return ".mp4";
      } else {
        return null;
      }
    },
  },
  beforeMount() {
    this.loadMediaList(this.type, this.slug);
  },
  methods: {
    loadMediaList(type, formKey) {
      this.currentFormKey = formKey;
      this.currentMediaType = type;
      getLocalResources(this.appId, { type: type }).then((res) => {
        this.mediaList = res[type];
        this.showMediaPicker = true;
      });
    },
    onMediaClick(media) {
      this.currentMedia = media;
    },
    addTemplateMedia() {
      this.uploading = true;
      uploadTemplateFile(this.opId, this.appId, {
        fileName: this.currentMedia.name,
        type: this.currentMediaType,
      }).then((res) => {
        this.uploading = false;
        this.showMediaPicker = false;
        this.$emit("media-chosen", { slug: this.slug, mediaId: res.media_id });
      });
    },
    addAuditMedia() {
      this.uploading = true;
      uploadCodeAuditMedia(this.opId, this.appId, {
        fileName: this.currentMedia.name,
        type: this.currentMediaType,
      }).then((res) => {
        this.uploading = false;
        this.showMediaPicker = false;
        this.$emit("media-chosen", { slug: this.slug, mediaId: res.mediaid });
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
      this.loadMediaList(this.currentMediaType, this.currentFormKey);
      this.$q.notify("上传成功");
      this.showUploader = false;
    },
    uploadUrl() {
      return (
        "/api/open-platform/" +
        this.opId +
        "/mp/" +
        this.appId +
        "/local-media?type=" +
        this.currentMediaType
      );
    },
  },
};
</script>

<style scoped></style>
