<template>
  <q-layout view="hHh LpR lFf">
    <q-header class="q-px-lg" :height-hint="56" :elevated="true">
      <q-toolbar class="q-px-none">
        <q-btn
          dense
          round
          outline
          size="sm"
          icon="r_menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title class="cursor-pointer" @click="$router.push('/')">
          <text class="text-primary text-weight-medium">微信</text>
          平台管理
        </q-toolbar-title>

        <div>{{ $store.state.basicInfo.nickname_info.nickname }}</div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      :width="256"
      class="drawer-right-bordered"
    >
      <q-btn
        dense
        round
        outline
        size="sm"
        v-if="$q.screen.lt.md"
        icon="r_navigate_before"
        aria-label="back"
        class="q-ml-lg"
        @click="toggleLeftDrawer"
      />
      <q-list dense>
        <view v-for="(menuItem, index) in menuList" :key="menuItem.title">
          <q-expansion-item
            v-if="menuItem.children"
            :label="menuItem.title"
            :icon="menuItem.icon"
            :model-value="true"
          >
            <q-item
              :inset-level="1"
              v-for="subMenuItem in menuItem.children"
              :key="subMenuItem.title"
              :active="$route.name === subMenuItem.to"
              clickable
              v-ripple
              @click="$router.push({ name: subMenuItem.to })"
            >
              <q-item-section>{{ subMenuItem.title }}</q-item-section>
            </q-item>
          </q-expansion-item>
          <q-item
            v-else
            :active="$route.name === menuItem.to"
            clickable
            v-ripple
            @click="$router.push({ name: menuItem.to })"
          >
            <q-item-section avatar>
              <q-icon :name="menuItem.icon" />
            </q-item-section>
            <q-item-section>{{ menuItem.title }}</q-item-section>
          </q-item>
          <q-separator
            v-if="index !== menuList.length - 1"
            class="menu-separator"
          />
        </view>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import pk from "../../package.json";
import { defineComponent, ref } from "vue";

const menuList = [
  {
    title: "首页",
    icon: "r_home",
    to: "subMiniProgramIndex",
    children: null,
  },
  {
    title: "配置中心",
    icon: "r_settings",
    to: null,
    children: [
      {
        title: "基础设置",
        to: "basicInformation",
      },
      {
        title: "信任域名",
        to: "trustDomain",
      },
      {
        title: "服务类目",
        to: "category",
      },
      {
        title: "用户隐私保护",
        to: "privacy",
      },
    ],
  },
  {
    title: "开发",
    icon: "r_code",
    to: null,
    children: [
      {
        title: "成员管理",
        to: "tester",
      },
      {
        title: "版本管理",
        to: "codeManage",
      },
    ],
  },
  {
    title: "素材管理",
    icon: "r_image",
    to: null,
    children: [
      {
        title: "素材管理",
        to: "mediaManage",
      },
    ],
  },
];

export default defineComponent({
  name: "SubManageLayout",

  components: {},

  setup() {
    const leftDrawerOpen = ref(false);

    return {
      menuList: menuList,
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
    };
  },
  data: () => ({
    title: "微信平台管理",
    version: pk.version,
  }),
  beforeMount() {
    this.$store.dispatch("loadSubBasicInfo", {
      opId: this.opId,
      appId: this.appId,
    });
  },
});
</script>
