<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section class="text-h4">
        查看订单页 PATH
        <div class="text-caption text-grey q-mt-sm">
          新规小程序涉及消费下单的必须设置订单页 PATH 以供查看
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section class="q-gutter-sm">
        <q-btn unelevated color="primary" @click="onGetOrderPathClick(0)"
          >获取线上版订单页 PATH 信息
        </q-btn>
        <q-btn unelevated color="secondary" @click="onGetOrderPathClick(1)"
          >获取审核版订单页 PATH 信息
        </q-btn>
        <q-btn unelevated color="negative" @click="showSettingForm = true"
          >设置订单 PATH
        </q-btn>
      </q-card-section>
    </q-card>
    <q-card v-if="showOrderPath" class="q-mt-lg">
      <q-card-section class="text-subtitle1"> 查看结果 </q-card-section>
      <q-separator />
      <q-card-section>
        <json-viewer :value="jsonData"></json-viewer>
      </q-card-section>
    </q-card>
    <q-card v-if="showSettingForm" class="q-mt-lg">
      <q-card-section class="text-h4">
        设置订单页 PATH
        <div class="text-caption text-grey q-mt-sm">
          新规小程序涉及消费下单的必须设置订单页 PATH 以供查看
        </div>
      </q-card-section>
      <q-card-section>
        <q-form @submit="submit">
          <q-input
            clearable
            dense
            v-model="form.path"
            outlined
            label="小程序订单路径"
            hint="打开 path 可以看到用户订单，如：pages/order/order"
            lazy-rules
            :rules="[(val) => (val && val.length > 0) || '不能为空']"
          />
          <q-input
            clearable
            dense
            v-model="form.img_list"
            outlined
            label="图片 url，以逗号隔开"
            hint="申请提交的图片 url，审核版会显示"
          />
          <q-input
            clearable
            dense
            v-model="form.video"
            outlined
            label="视频 url"
            hint="申请提交的视频url，审核版会显示"
          />
          <q-input
            clearable
            dense
            v-model="form.test_account"
            outlined
            label="测试账号"
            hint="申请提交的测试账号，审核版会显示"
          />
          <q-input
            clearable
            dense
            v-model="form.test_pwd"
            outlined
            label="测试密码"
            hint="申请提交的测试密码，审核版会显示"
          />
          <q-input
            clearable
            dense
            v-model="form.test_remark"
            outlined
            label="测试备注"
            hint="申请提交的测试备注，审核版会显示"
          />
          <q-input
            clearable
            dense
            v-model="form.appid_list"
            outlined
            label="appid，以逗号隔开"
            hint="申请提交的批量的appid"
            lazy-rules
            :rules="[(val) => (val && val.length > 0) || '不能为空']"
          />
          <q-separator />
          <div class="q-mt-md">
            <q-btn unelevated color="primary" type="submit" label="提交">
            </q-btn>
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { getOrderPath, setOrderPath } from "src/api/authorizer-mini-program.js";
import JsonViewer from "vue-json-viewer";

export default {
  name: "MpQR",
  components: { JsonViewer },
  data: () => ({
    form: {
      path: "",
      appid: [],
      img_list: "",
      video: "",
      test_account: "",
      test_pwd: "",
      test_remark: "",
    },
    showOrderPath: false,
    showSettingForm: false,
    jsonData: {},
  }),
  beforeMount() {},
  mounted() {
    this.form.appid_list = this.$store.state.currentAppId;
  },
  methods: {
    onGetOrderPathClick(type) {
      getOrderPath(
        this.$store.state.currentOpId,
        this.$store.state.currentAppId,
        { infoType: type }
      ).then((res) => {
        this.jsonData = res;
        this.showOrderPath = true;
      });
    },
    submit() {
      setOrderPath(
        this.$store.state.currentOpId,
        this.$store.state.currentAppId,
        this.form
      ).then((res) => {
        this.jsonData = res;
        this.showOrderPath = true;
      });
    },
  },
};
</script>

<style scoped></style>
