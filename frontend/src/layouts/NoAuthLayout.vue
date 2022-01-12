<template>
  <q-layout view="hHh LpR lFf">
    <q-header class="q-px-md" :height-hint="56" :elevated="true">
      <q-toolbar class="q-px-lg">
        <q-toolbar-title>
          <text class="text-primary text-weight-medium">微信</text>
          平台管理
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>

    <q-footer class="footer text-grey-6 text-center q-mb-sm">
      一个管理微信平台的非官方整合工具 | &copy; 2021
    </q-footer>
  </q-layout>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "MainLayout",
  data: () => ({
    title: "微信平台管理",
  }),
});
</script>
<style scoped lang="scss">
.footer {
  background-color: rgba(0, 0, 0, 0);
}

.header {
  border-bottom: 1px solid #0000001f;
}
</style>
