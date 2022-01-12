<template>
  <div>
    <q-card>
      <q-card-section class="q-pb-none text-subtitle1"
        >{{ title }}
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-form @submit="onSubmit" class="q-gutter-sm q-ml-none">
          <view v-for="(item, index) in formParams" :key="index">
            <q-input
              :ref="`form[${index}]`"
              outlined
              hide-bottom-space
              dense
              :label="item.required ? item.label + ' *' : item.label"
              :hint="item.hint"
              v-model="item.value"
              :model-value="item.value"
              :type="item.type || 'text'"
              lazy-rules
              :rules="
                item.required
                  ? [(val) => (val && val.length > 0) || '必填内容']
                  : []
              "
            >
              <template v-if="!!item.media" v-slot:append>
                <q-icon
                  name="r_image"
                  class="cursor-pointer"
                  @click="loadMediaList(item.media, index)"
                ></q-icon>
              </template>
              <template v-else-if="item.append" v-slot:append>
                <q-icon
                  :name="item.append.icon"
                  class="cursor-pointer"
                  @click="onAppendClick(item.append.slug)"
                ></q-icon>
              </template>
            </q-input>
          </view>
          <q-btn
            class="q-ml-none q-mt-md"
            unelevated
            color="primary"
            type="submit"
            label="提交"
            icon="r_save"
          ></q-btn>
        </q-form>
      </q-card-section>
    </q-card>

    <q-dialog v-model="showMediaPicker" full-width full-height>
      <q-card style="width: 80vw" class="column">
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
              :class="
                media.name === currentMedia.name ? 'bg-green-3' : 'bg-grey-3'
              "
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
            :loading="uploading"
            color="primary"
            unelevated
            label="确定"
            @click="addTemplateMedia()"
          ></q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>

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
  </div>
</template>

<script>
import {
  getLocalMediaList,
  uploadTemplateFile,
} from "src/api/sub-mini-program";

export default {
  name: "MyRequestFormCard",
  props: {
    title: {
      type: String,
      default: () => "",
    },
    formData: {
      default: () => [],
      type: Array(Object),
    },
  },
  data: () => ({
    currentFormKey: null,
    currentMediaType: null,
    currentMedia: {},
    mediaList: [],
    showMediaPicker: false,
    uploading: false,
    showUploader: false,
    disableUpload: true,
    formParams: [],
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
    this.formParams = this.formData;
  },
  methods: {
    onSubmit() {
      const data = {};
      this.formParams.forEach((item) => {
        if (item.value) {
          data[item.name] = item.value;
        }
        if (item.children) {
          data[item.name] = {};
          item.children.forEach((subItem) => {
            if (subItem.value) {
              data[item.name][subItem.name] = subItem.value;
            }
          });
        }
      });
      this.$emit("submit", data);
    },
    onAppendClick(slug) {
      this.$emit("append-click", slug);
    },
    loadMediaList(type, formKey) {
      this.currentFormKey = formKey;
      this.currentMediaType = type;
      getLocalMediaList(this.opId, this.appId, { type: type }).then((res) => {
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
        this.formParams[this.currentFormKey].value = res.media_id;
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
