<template>
  <q-layout view="hHh LpR lFf">
    <q-header :height-hint="56" :elevated="true">
      <q-toolbar class="q-px-lg">
        <q-toolbar-title>
          <text class="text-primary text-weight-medium">微信</text>
          平台管理
        </q-toolbar-title>

        <q-btn flat :label="$store.state.currentPlatformInfo.name || version">
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
        </q-btn>
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

export default defineComponent({
  name: "MainLayout",

  data: () => ({
    title: "微信平台管理",
    version: pk.version,
  }),
  methods: {
    logout() {
      this.$store.dispatch("setToken", null);
      this.$router.replace({ name: "login" });
    },
  },
});
</script>
