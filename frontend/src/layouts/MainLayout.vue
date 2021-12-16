<template>
  <q-layout view="hHh LpR lFf">
    <q-header :height-hint=56 :elevated="true">
      <q-toolbar class="q-px-lg">
        <q-toolbar-title>
          <text class="text-primary text-weight-medium">微信</text>平台管理
        </q-toolbar-title>

        <div>V {{ version }}</div>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import pk from '../../package.json'

import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'MainLayout',

  data: () => ({
    title: '微信平台管理',
    version: pk.version
  })
})
</script>
