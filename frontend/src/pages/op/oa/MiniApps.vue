<template>
  <q-page class="q-pa-lg q-gutter-lg">
    <div class="row">
      <div>
        <div class="text-h4">关联小程序</div>
        <div class="text-caption text-grey q-mt-sm">
          可以查看和管理公众号已关联的小程序
        </div>
      </div>
      <q-space />
      <div>
        <q-btn
          label="绑定小程序"
          color="primary"
          unelevated
          @click="onBindClick"
        ></q-btn>
      </div>
    </div>
    <div>
      <div v-if="miniAppList.length === 0" class="text-center q-pa-lg">
        暂无已关联小程序
      </div>
      <div class="row q-col-gutter-md q-mt-none">
        <div
          class="col-xs-12 col-sm-6 col-md-4"
          v-for="(item, key) in miniAppList"
          :key="key"
        >
          <q-card>
            <q-card-section class="row">
              <div class="text-h6">{{ item.nickname }}</div>
              <q-space />
              <div>
                <q-btn
                  flat
                  icon="r_close"
                  color="red"
                  @click="onUnBindClick(item.appid)"
                ></q-btn>
              </div>
            </q-card-section>
            <q-card-section>
              <view class="q-pb-none panel-form-item">
                <view class="panel-form-item-label">APP ID</view>
                <view class="panel-form-item-value">
                  <div>{{ item.appid }}</div>
                </view>
              </view>
              <view class="q-pb-none panel-form-item">
                <view class="panel-form-item-label">原始 ID</view>
                <view class="panel-form-item-value">
                  <div>{{ item.username }}</div>
                </view>
              </view>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import {
  bindMiniApps,
  getMiniApps,
  unbindMiniApps,
} from "src/api/authorizer-official-account.js";

export default {
  name: "OfficialAccountMiniApps",
  data: () => ({
    miniAppList: [],
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
      getMiniApps(
        this.$store.state.currentOpId,
        this.$store.state.currentAppId
      ).then((res) => {
        this.miniAppList = res.wxopens.items;
      });
    },
    onBindClick() {
      this.$q
        .dialog({
          title: "请输入APP ID",
          message: "请输入你需要绑定的小程序的 APP ID",
          prompt: {
            model: "",
            type: "text", // optional
          },
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk((data) => {
          if (!data) {
            this.$q.notify({ message: "不能为空", color: "red" });
            return;
          }
          bindMiniApps(
            this.$store.state.currentOpId,
            this.$store.state.currentAppId,
            data
          ).then(() => {
            this.$q.notify("创建成功");
          });
        });
    },
    onUnBindClick(mid) {
      this.$q
        .dialog({
          title: "确定删除吗",
          message: "删除后需要重新绑定，请谨慎操作。",
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          unbindMiniApps(
            this.$store.state.currentOpId,
            this.$store.state.currentAppId,
            mid
          ).then(() => {
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
