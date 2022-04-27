<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section class="text-h4">
        生成二维码
        <div class="text-caption text-grey q-mt-sm">
          生成小程序二维码以供使用
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <q-form @submit="submit">
          <q-input
            clearable
            dense
            v-model="form.path"
            outlined
            label="小程序路径"
            hint="扫码打开后进入的路径，如：pages/index/index"
            lazy-rules
            :rules="[(val) => (val && val.length > 0) || '不能为空']"
          />
          <q-input
            clearable
            dense
            type="number"
            hide-bottom-space
            v-model="form.width"
            outlined
            label="图片宽度(px)"
            hint="请输入 280-1280 之间的数字"
            lazy-rules
            :rules="[
              (val) =>
                (Number(val) >= 280 && Number(val) <= 1280) || '不能为空',
            ]"
          />
          <div>
            <q-toggle v-model="form.auto_color" label="是否使用默认黑色线条" />
          </div>
          <div v-if="!form.auto_color" class="flex q-gutter-sm">
            <q-input
              clearable
              dense
              type="number"
              hide-bottom-space
              v-model="form.line_color.r"
              outlined
              label="R"
              hint="RGB 10 进制背景色(0-255)"
              lazy-rules
              :rules="[
                (val) => (Number(val) >= 0 && Number(val) <= 255) || '不能为空',
              ]"
            />
            <q-input
              clearable
              dense
              type="number"
              hide-bottom-space
              v-model="form.line_color.g"
              outlined
              label="G"
              hint="RGB 10 进制背景色(0-255)"
              lazy-rules
              :rules="[
                (val) => (Number(val) >= 0 && Number(val) <= 255) || '不能为空',
              ]"
            />
            <q-input
              clearable
              dense
              type="number"
              hide-bottom-space
              v-model="form.line_color.b"
              outlined
              label="B"
              hint="RGB 10 进制背景色(0-255)"
              lazy-rules
              :rules="[
                (val) => (Number(val) >= 0 && Number(val) <= 255) || '不能为空',
              ]"
            />
          </div>
          <div>
            <q-toggle
              v-model="form.is_hyaline"
              label="是否使用透明背景(默认白底)"
            />
          </div>
          <q-separator />
          <div>
            <q-toggle v-model="form.unlimited" label="生成 unlimited 二维码" />
          </div>
          <q-input
            v-if="form.unlimited"
            clearable
            dense
            v-model="form.scene"
            outlined
            label="scene 场景信息"
            hint="scene 会传递到小程序页面中，以供获取使用，详见 https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/qrcode/getwxacodeunlimit.html"
            lazy-rules
            :rules="[(val) => (val && val.length > 0) || '不能为空']"
          />
          <q-separator />
          <div class="q-mt-md">
            <q-btn unelevated color="primary" type="submit" label="确定生成">
            </q-btn>
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { getQr } from "src/api/authorizer-mini-program.js";

export default {
  name: "MpQR",
  data: () => ({
    form: {
      path: "pages/index/index",
      width: 430,
      auto_color: false,
      line_color: {
        r: 0,
        g: 0,
        b: 0,
      },
      is_hyaline: false,
      unlimited: false,
      scene: "",
    },
  }),
  beforeMount() {},
  methods: {
    submit() {
      getQr(
        this.$store.state.currentOpId,
        this.$store.state.currentAppId,
        this.form
      ).then((res) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          // 转换完成，创建一个a标签用于下载
          let a = document.createElement("a");
          a.download = this.form.path;
          a.href = e.target.result;
          document.body.append(a); // 修复firefox中无法触发click
          a.click();
          a.remove && a.remove();
        };
        reader.readAsDataURL(res); // 转换为base64，可以直接放入a标签的href
      });
    },
  },
};
</script>

<style scoped></style>
