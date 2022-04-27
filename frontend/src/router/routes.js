const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      { path: "", redirect: { name: "index" } },
      {
        path: "platforms",
        component: () => import("pages/Index.vue"),
        name: "index",
      },
      {
        path: "platforms/create",
        component: () => import("pages/PlatformCreateAndEdit.vue"),
      },
      {
        path: "platforms/:id/edit",
        component: () => import("pages/PlatformCreateAndEdit.vue"),
      },
      {
        path: "operation-logs",
        component: () => import("pages/OperationLog.vue"),
        name: "operationLogs",
      },
    ],
  },
  {
    path: "/open-platform",
    component: () => import("layouts/ManageLayout.vue"),
    children: [
      {
        path: ":id",
        component: () => import("pages/op/Show.vue"),
      },
      {
        path: ":id/domain",
        component: () => import("pages/op/Domain.vue"),
      },
      {
        path: ":id/code-manage",
        component: () => import("pages/op/CodeManage.vue"),
      },
    ],
  },

  {
    path: "/open-platform/:opId/mini-program/:appId/",
    component: () => import("layouts/SubMiniProgramLayout.vue"),
    children: [
      {
        path: "",
        component: () => import("pages/op/mp/Index.vue"),
        name: "subMiniProgramIndex",
      },
      {
        path: "qr",
        component: () => import("pages/op/mp/QR.vue"),
        name: "mpQR",
      },
      {
        path: "basic-information",
        component: () => import("pages/op/mp/BasicInformation.vue"),
        name: "basicInformation",
      },
      {
        path: "category",
        component: () => import("pages/op/mp/Category.vue"),
        name: "category",
      },
      {
        path: "privacy",
        component: () => import("pages/op/mp/Privacy.vue"),
        name: "privacy",
      },
      {
        path: "trust-domain",
        component: () => import("pages/op/mp/DomainSettings.vue"),
        name: "trustDomain",
      },
      {
        path: "tester",
        component: () => import("pages/op/mp/Tester.vue"),
        name: "tester",
      },
      {
        path: "media-manage",
        component: () => import("pages/op/mp/MediaManage.vue"),
        name: "mediaManage",
      },
      {
        path: "code-manage",
        component: () => import("pages/op/mp/CodeManage.vue"),
        name: "codeManage",
      },
      {
        path: "code-manage-audit",
        component: () => import("pages/op/mp/CodeManageAudit.vue"),
        name: "codeManageAudit",
      },
    ],
  },

  {
    path: "/open-platform/:opId/official-account/:appId/",
    component: () => import("layouts/SubOfficialAccountLayout.vue"),
    children: [
      {
        path: "",
        component: () => import("pages/op/oa/Index.vue"),
        name: "subOfficialAccountIndex",
      },
      {
        path: "custom-menu",
        component: () => import("pages/op/oa/CustomMenu.vue"),
        name: "subOfficialAccountCustomMenu",
      },
      {
        path: "auto-reply-rules",
        component: () => import("pages/op/oa/AutoReplyRule.vue"),
        name: "subOfficialAccountAutoReplyRules",
      },
      {
        path: "auto-reply-rules/create",
        component: () => import("pages/op/oa/AutoReplyRuleCreateAndEdit.vue"),
        name: "subOfficialAccountAutoReplyRuleCreate",
      },
      {
        path: "auto-reply-rules/:id/edit",
        component: () => import("pages/op/oa/AutoReplyRuleCreateAndEdit.vue"),
        name: "subOfficialAccountAutoReplyRuleEdit",
      },
      {
        path: "media-manage",
        component: () => import("pages/op/oa/MediaManage.vue"),
        name: "subOfficialAccountMediaManage",
      },
      {
        path: "online-media-manage",
        component: () => import("pages/op/oa/OnlineMediaManage.vue"),
        name: "subOfficialAccountOnlineMediaManage",
      },
      {
        path: "drafts",
        component: () => import("pages/op/oa/Drafts.vue"),
        name: "subOfficialAccountDrafts",
      },
      {
        path: "articles",
        component: () => import("pages/op/oa/Articles.vue"),
        name: "subOfficialAccountArticles",
      },
    ],
  },

  // 直接通过 appId 和 secret 管理公众号
  {
    path: "/official-account/:oaId",
    component: () => import("layouts/OfficialAccountLayout.vue"),
    children: [
      {
        path: "",
        component: () => import("pages/oa/Index.vue"),
        name: "officialAccountIndex",
      },
      {
        path: "custom-menu",
        component: () => import("pages/oa/CustomMenu.vue"),
        name: "officialAccountCustomMenu",
      },
      {
        path: "auto-reply-rules",
        component: () => import("pages/oa/AutoReplyRule.vue"),
        name: "officialAccountAutoReplyRules",
      },
      {
        path: "auto-reply-rules/create",
        component: () => import("pages/oa/AutoReplyRuleCreateAndEdit.vue"),
        name: "officialAccountAutoReplyRuleCreate",
      },
      {
        path: "auto-reply-rules/:id/edit",
        component: () => import("pages/oa/AutoReplyRuleCreateAndEdit.vue"),
        name: "officialAccountAutoReplyRuleEdit",
      },
      {
        path: "media-manage",
        component: () => import("pages/oa/MediaManage.vue"),
        name: "officialAccountMediaManage",
      },
      {
        path: "online-media-manage",
        component: () => import("pages/oa/OnlineMediaManage.vue"),
        name: "officialAccountOnlineMediaManage",
      },
      {
        path: "drafts",
        component: () => import("pages/oa/Drafts.vue"),
        name: "officialAccountDrafts",
      },
      {
        path: "articles",
        component: () => import("pages/oa/Articles.vue"),
        name: "officialAccountArticles",
      },
    ],
  },

  {
    path: "/",
    component: () => import("layouts/NoAuthLayout.vue"),
    children: [
      {
        path: "login",
        component: () => import("pages/Login.vue"),
        name: "login",
      },
      // Always leave this as last one,
      // but you can also remove it
      {
        path: "/401",
        component: () => import("pages/PageError401.vue"),
        name: "401",
      },
      {
        path: "/:catchAll(.*)*",
        component: () => import("pages/PageError404.vue"),
        name: "404",
      },
    ],
  },
];

export default routes;
