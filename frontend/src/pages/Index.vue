<template>
  <q-page class="q-pa-lg">
    <view class="flex justify-end">
      <q-btn unelevated color="primary" class="q-px-lg" to="/platforms/create"
        >添加新平台账号
      </q-btn>
    </view>
    <view class="row q-col-gutter-lg q-pt-lg">
      <view
        class="col-lg-3 col-md-4 col-sm-6 col-xs-12"
        v-for="(item, index) in platforms"
        :key="index"
      >
        <q-card>
          <q-card-section
            class="flex justify-between items-center text-primary cursor-pointer"
            @click="onShowClick(item)"
          >
            <text class="text-h6">{{ item.name }}</text>
            <q-btn
              flat
              round
              icon="r_close"
              size="sm"
              color="negative"
              @click.stop="deletePlatform(item.id)"
            ></q-btn>
          </q-card-section>
          <q-separator />
          <q-card-section class="panel-form">
            <view class="q-pb-none panel-form-item">
              <view class="panel-form-item-label">描述信息</view>
              <view class="panel-form-item-value">{{ item.description }}</view>
            </view>
            <view class="q-pb-none panel-form-item">
              <view class="panel-form-item-label">AppID</view>
              <view class="panel-form-item-value">{{ item.app_id }}</view>
            </view>
            <view class="panel-form-item">
              <view class="panel-form-item-label">平台类型</view>
              <view class="panel-form-item-value text-primary"
                >{{ item.type_name }}
              </view>
            </view>
          </q-card-section>
          <q-separator />
          <q-card-actions>
            <q-space />
            <q-btn flat color="secondary" :to="`/platforms/${item.id}/edit`"
              >编辑
            </q-btn>
            <q-btn unelevated color="primary" @click="onShowClick(item)"
              >查看
            </q-btn>
          </q-card-actions>
        </q-card>
      </view>
    </view>
  </q-page>
</template>

<script>
import { defineComponent } from "vue";
import { deletePlatform, getPlatforms } from "src/api";

export default defineComponent({
  name: "PageIndex",
  data: () => ({
    platforms: [],
  }),
  beforeMount() {
    this.initData();
  },
  methods: {
    initData() {
      getPlatforms().then((res) => {
        this.platforms = res;
      });
    },
    deletePlatform(id) {
      this.$q
        .dialog({
          title: "删除确认",
          message: "一旦删除，将不可找回",
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
          deletePlatform(id).then(() => {
            this.$q.notify("删除成功");
            this.initData();
          });
        });
    },
    onShowClick(item) {
      if (item.type === 0) {
        this.$router.push({ path: "/open-platform/" + item.id });
      }
    },
  },
});
</script>
