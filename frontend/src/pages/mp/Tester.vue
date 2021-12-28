<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section class="text-h4 row items-center">
        小程序体验者
      </q-card-section>
      <q-card-section>
        <q-form @submit="bindTester">
          <q-input
            style="width: 200px"
            clearable
            dense
            v-model="wechatId"
            :model-value="wechatId"
            outlined
            label="输入微信号，不是昵称"
            lazy-rules
            :rules="[(val) => (val && val.length > 0) || '不能为空']"
          />
          <q-btn unelevated dense color="primary" type="submit">绑定</q-btn>
          <q-btn
            flat
            dense
            color="grey"
            class="q-ml-lg"
            @click="unBindTester(wechatId, true)"
            >用微信号解绑
          </q-btn>
        </q-form>
      </q-card-section>
    </q-card>
    <view class="row q-mt-none q-col-gutter-lg">
      <view
        class="col-lg-3 col-md-4 col-sm-6 col-xs-12"
        v-for="item in testers"
        :key="item.userstr"
      >
        <q-card>
          <q-card-section class="flex items-center q-py-sm">
            <view>
              {{ item.wechat_id || "无微信号" }}
              <q-tooltip>微信号，如无微信号说明数据库中未记录</q-tooltip>
            </view>
            <q-space />
            <q-btn
              flat
              rounded
              dense
              color="warning"
              icon="r_close"
              @click="unBindTester(item.user_str)"
            >
              <q-tooltip>点击解绑该体验者</q-tooltip>
            </q-btn>
          </q-card-section>
          <q-separator />
          <q-card-section class="q-pa-sm q-pr-md">
            <q-chip
              color="green-2"
              style="max-width: 100%"
              clickable
              text-color="primary"
              @click="showUserStr(item.user_str)"
              :label="item.user_str"
            >
              <q-tooltip>点击查看完整的 user str</q-tooltip>
            </q-chip>
          </q-card-section>
        </q-card>
      </view>
    </view>
  </q-page>
</template>

<script>
import { bindTester, getTesters, unBindTester } from "src/api/sub-mini-program";

export default {
  name: "MpTester",
  data: () => ({
    wechatId: null,
    testers: [],
  }),
  beforeMount() {
    this.initData();
  },
  methods: {
    initData() {
      getTesters(this.opId, this.appId).then((response) => {
        this.testers = response;
      });
    },
    bindTester() {
      bindTester(this.opId, this.appId, {
        wechatId: this.wechatId,
      }).then(() => {
        this.$q.notify("绑定成功");
        this.initData();
      });
    },
    unBindTester(slug, useWechatId = false) {
      this.$q
        .dialog({
          title: "解绑确认",
          message: "解绑后必须重新根据微信号绑定",
          ok: {
            label: "确认",
            unelevated: true,
          },
          cancel: {
            color: "grey",
            flat: true,
            label: "取消",
          },
        })
        .onOk(() => {
          let params = null;
          if (useWechatId) {
            params = { useWechatId: true };
          }
          unBindTester(this.opId, this.appId, slug, params).then(() => {
            this.$q.notify("解绑成功");
            this.initData();
          });
        });
    },
    showUserStr(str) {
      this.$q.dialog({
        title: "微信接口提供的用户标识",
        message: str,
        fullWidth: true,
      });
    },
  },
};
</script>

<style scoped></style>
