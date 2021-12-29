<template>
  <q-layout view="hHh LpR lFf">
    <q-header :height-hint="56" :elevated="true">
      <q-toolbar class="q-px-lg">
        <q-toolbar-title>
          <text class="text-primary text-weight-medium">微信</text>
          平台管理
        </q-toolbar-title>

        <q-btn flat :label="version">
          <q-menu>
            <q-list style="min-width: 100px">
              <q-item clickable v-close-popup to="/operation-logs">
                <q-item-section>查看操作日志</q-item-section>
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

import { defineComponent, ref } from "vue";

export default defineComponent({
  name: "MainLayout",

  data: () => ({
    title: "微信平台管理",
    version: pk.version,
  }),
});
</script>
