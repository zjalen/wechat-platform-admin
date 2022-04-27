<template>
  <q-layout view="hHh LpR lFf">
    <q-header :height-hint="56" :elevated="true">
      <q-toolbar class="q-px-lg">
        <q-toolbar-title>
          <text class="text-primary text-weight-medium">微信</text>
          平台管理
        </q-toolbar-title>

        <div class="flex flex-center">
          <div class="cursor-pointer">
            {{ $store.state.currentPlatformInfo.name }}
            <q-menu>
              <q-list style="min-width: 100px">
                <q-item clickable to="/operation-logs">
                  <q-item-section>查看操作日志</q-item-section>
                </q-item>
                <q-item clickable @click="logout">
                  <q-item-section>退出登录</q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </div>
          <q-chip
            color="primary"
            dense
            square
            class="text-white"
            label="第三方"
          ></q-chip>
        </div>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import pk from "../../package.json";

import { defineComponent } from "vue";
import { mixinOpenPlatformParams } from "src/mixins/open-platform-params.js";

export default defineComponent({
  name: "MainLayout",
  mixins: [mixinOpenPlatformParams],

  data: () => ({
    title: "微信平台管理",
    version: pk.version,
  }),
  beforeMount() {
    const id = this.$route.params.id;
    this.$store.dispatch("loadCurrentPlatformInfo", id);
  },
  methods: {
    logout() {
      this.$store.dispatch("setToken", null);
      this.$router.replace({ name: "login" });
    },
  },
});
</script>
