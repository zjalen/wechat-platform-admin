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

        <div class="flex flex-center">
          <span>{{ $store.state.basicInfo.name }}</span>
          <q-chip
            color="primary"
            dense
            square
            class="text-white"
            label="公众号"
          ></q-chip>
        </div>
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
import { mixinOfficialAccountParams } from "src/mixins/official-account-params.js";

const menuList = [
  {
    title: "首页",
    icon: "r_home",
    to: "officialAccountIndex",
    children: null,
  },
  {
    title: "内容与互动",
    icon: "r_article",
    to: null,
    children: [
      {
        title: "自动回复",
        to: "officialAccountAutoReplyRules",
      },
      {
        title: "自定义菜单",
        to: "officialAccountCustomMenu",
      },
    ],
  },
  {
    title: "素材管理",
    icon: "r_image",
    to: null,
    children: [
      {
        title: "服务器素材",
        to: "officialAccountMediaManage",
      },
      {
        title: "公众号素材库",
        to: "officialAccountOnlineMediaManage",
      },
      {
        title: "草稿箱",
        to: "officialAccountDrafts",
      },
      {
        title: "发表记录",
        to: "officialAccountArticles",
      },
    ],
  },
];

export default defineComponent({
  name: "OfficialAccountLayout",

  mixins: [mixinOfficialAccountParams],

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
    const id = this.$route.params.oaId;
    this.$store.dispatch("loadOfficialAccountBasicInfo", {
      id: id,
    });
  },
});
</script>
