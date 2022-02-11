<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section class="row items-center">
        <div class="text-h4">代码管理</div>
      </q-card-section>
      <q-separator />
      <q-card-section class="q-gutter-sm">
        <q-btn unelevated color="primary" @click="editCard = 'commit'"
          >设置体验版
        </q-btn>
        <q-btn flat color="secondary" @click="onGetCodePages"
          >获取代码页面列表
        </q-btn>
        <q-btn flat color="secondary" @click="onGetTestCodeQR"
          >体验版二维码
        </q-btn>
        <q-btn flat color="secondary" @click="onGetSupportVersionClick"
          >获取基础版本库状态</q-btn
        >
        <q-btn flat color="secondary" @click="onSetSupportVersionClick"
          >设置基础版本库</q-btn
        >
      </q-card-section>
      <q-separator />
      <q-card-section class="q-gutter-sm">
        <q-btn
          unelevated
          color="primary"
          @click="$router.push('code-manage-audit')"
          >提交审核
        </q-btn>
        <q-btn flat color="secondary" @click="onGetAuditResultClick"
          >获取审核结果
        </q-btn>
        <q-btn flat color="negative" @click="onWithdrawClick">审核撤销</q-btn>
        <q-btn flat color="negative" @click="onSpeedAuditClick">加急审核</q-btn>
      </q-card-section>
      <q-separator />
      <q-card-section class="q-gutter-sm">
        <q-btn unelevated color="primary" @click="onReleaseClick"
          >线上发布
        </q-btn>
        <q-btn flat color="secondary" @click="onReleaseHistoriesClick"
          >可回退版本查询
        </q-btn>
        <q-btn flat color="negative" @click="onRollbackClick">版本回退</q-btn>

        <q-btn flat color="secondary" @click="onGrayReleaseClick"
          >分阶段发布</q-btn
        >
        <q-btn flat color="secondary" @click="onGetGrayReleaseClick"
          >分阶段发布查询</q-btn
        >
        <q-btn flat color="secondary" @click="onRevertGrayReleaseClick"
          >取消分阶段发布</q-btn
        >
      </q-card-section>
    </q-card>

    <my-request-form-card
      v-if="editCard === 'commit'"
      class="q-mt-lg"
      :form-data="commitFormData"
      title="设置体验版"
      @append-click="onAppendClick"
      @submit="onCommitSubmit"
    ></my-request-form-card>

    <my-request-form-card
      v-else-if="editCard === 'grayRelease'"
      class="q-mt-lg"
      :form-data="grayReleaseForm"
      title="分阶段发布"
      @submit="onGrayReleaseSubmit"
    ></my-request-form-card>

    <q-dialog v-model="showTemplateList" full-width>
      <q-card class="q-mt-lg">
        <q-card-section class="text-h4 row">
          小程序模板库
          <q-space />
          <q-btn flat icon="r_close" color="negative" v-close-popup></q-btn>
        </q-card-section>
        <q-card-section class="q-pa-none">
          <open-platform-code-list-card
            :open-platform-id="openPlatformId"
            @template-choose="onTemplateIdChoose"
          ></open-platform-code-list-card>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="showJsonCard">
      <json-card :title="jsonCardTitle" :data="jsonCardData"></json-card>
    </q-dialog>

    <q-dialog v-model="showQrDialog">
      <q-card>
        <q-card-section class="text-subtitle1 row">
          体验版二维码
          <q-space />
          <q-btn flat color="negative" icon="r_close" v-close-popup></q-btn>
        </q-card-section>
        <q-separator />
        <q-card-section>
          <q-img width="300px" :src="qrCodeSrc" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import MyRequestFormCard from "components/MyRequestFormCard";
import OpenPlatformCodeListCard from "components/OpenPlatformCodeList";
import {
  codeCommit,
  codeRelease,
  codeRollbackRelease,
  getCodeAuditStatus,
  getCodePages,
  getCodeReleaseHistories,
  getCodeTestQr,
  getGrayReleaseInfo,
  getSpeedAuditCount,
  getSupportVersion,
  grayRelease,
  revertGrayRelease,
  setSupportVersion,
  speedAudit,
  withdrawCodeAudit,
} from "src/api/authorizer-mini-program";
import JsonCard from "components/JsonCard";

export default {
  name: "CodeManage",
  components: { JsonCard, OpenPlatformCodeListCard, MyRequestFormCard },
  data: () => ({
    openPlatformId: null,
    editCard: "",
    showTemplateList: false,
    commitFormData: [
      {
        label: "模板 id",
        name: "template_id",
        value: "",
        hint: "模板 id 为开放平台代码库里的标准模板或普通模板",
        required: true,
        append: {
          icon: "r_search",
          slug: "template_id",
        },
      },
      {
        label: "ext_json",
        name: "ext_json",
        value: "",
        hint: "模板 id 为开放平台代码库里的标准模板或普通模板",
        required: true,
        type: "textarea",
      },
      {
        label: "版本号",
        name: "user_version",
        value: "",
        required: true,
      },
      {
        label: "版本描述",
        name: "user_desc",
        value: "",
        required: true,
      },
    ],
    auditFormData: [
      {
        label: "版本描述",
        name: "version_desc",
        value: "",
        hint: "简要描述本次更新涉及的内容点",
        required: true,
        type: "textarea",
      },
      {
        label: "小程序截图",
        name: "pic_list",
        value: "",
        hint: "可上传最多 5 张截图以辅助审核",
        required: true,
        children: [
          {
            name: 0,
            label: "截图1",
            value: "",
          },
          {
            name: 1,
            label: "截图1",
            value: "",
          },
          {
            name: 2,
            label: "截图1",
            value: "",
          },
          {
            name: 3,
            label: "截图1",
            value: "",
          },
          {
            name: 4,
            label: "截图1",
            value: "",
          },
        ],
      },
      {
        label: "版本号",
        name: "user_version",
        value: "",
        required: true,
      },
      {
        label: "版本描述",
        name: "user_desc",
        value: "",
        required: true,
      },
    ],
    grayReleaseForm: [
      {
        label: "发布百分比(1-100)",
        name: "gray_percentage",
        value: "",
        hint: "请输入1-100 之间的数字",
        required: true,
        type: "number",
      },
      {
        label: "仅发布给体验成员",
        name: "support_experiencer_first",
        value: false,
        type: "toggle",
      },
      {
        label: "仅发布给项目成员",
        name: "support_debuger_first",
        value: false,
        type: "toggle",
      },
    ],
    defaultExtJson: {
      extAppid: "",
      ext: {},
      window: {},
    },
    showJsonCard: false,
    jsonCardTitle: "",
    jsonCardData: {},
    showQrDialog: false,
    qrCodeSrc: null,
    showGrayRelease: true,
    visible: "open",
  }),
  beforeMount() {
    this.openPlatformId = this.opId;
    this.defaultExtJson.extAppid = this.appId;
    this.commitFormData[1].value = JSON.stringify(this.defaultExtJson);
  },
  methods: {
    onAppendClick(slug) {
      if (slug === "template_id") {
        this.showTemplateList = true;
      }
    },
    onTemplateIdChoose(id) {
      this.commitFormData[0].value = id.toString();
      this.showTemplateList = false;
    },
    onCommitSubmit(data) {
      codeCommit(this.opId, this.appId, data).then(() => {
        this.$q.notify("设置成功");
        this.editCard = null;
      });
    },
    onGetCodePages() {
      getCodePages(this.opId, this.appId).then((res) => {
        this.jsonCardTitle = "页面列表";
        this.jsonCardData = res.page_list;
        this.showJsonCard = true;
      });
    },
    onGetTestCodeQR() {
      getCodeTestQr(this.opId, this.appId)
        .then((res) => {
          const reader = new FileReader();
          reader.onload = (e) => {
            this.qrCodeSrc = e.target.result;
          };
          reader.readAsDataURL(res);
        })
        .then(() => {
          this.showQrDialog = true;
        });
    },
    onGetAuditResultClick() {
      this.$q
        .dialog({
          title: "查询审核结果",
          prompt: {
            model: "",
            label: "您可输入指定 audit_id 或留空以查询最近提交",
            type: "text",
            outlined: true,
          },
          ok: {
            label: "确定",
            unelevated: true,
          },
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk((data) => {
          let params = null;
          if (data) {
            params = { audit_id: data };
          }
          getCodeAuditStatus(this.opId, this.appId, params).then((res) => {
            this.jsonCardTitle = "查询结果";
            this.jsonCardData = res;
            this.showJsonCard = true;
          });
        });
    },
    onWithdrawClick() {
      this.$q
        .dialog({
          title: "确定撤销吗",
          message: "点击确定将会撤回最近一次提交的审核请求。",
          ok: {
            label: "确定",
            unelevated: true,
          },
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          withdrawCodeAudit(this.opId, this.appId).then(() => {
            this.$q.notify("撤销成功");
          });
        });
    },
    onReleaseClick() {
      this.$q
        .dialog({
          title: "确定发布吗",
          message: "确定之后，已审核成功的最新版将在线上发布。",
          ok: {
            label: "确定",
            unelevated: true,
          },
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          codeRelease(this.opId, this.appId).then(() => {
            this.$q.notify("发布成功");
          });
        });
    },
    onRollbackClick() {
      this.$q
        .dialog({
          title: "确定回滚吗",
          prompt: {
            model: "",
            label: "您可以输入指定版本号(app_version)或留空回滚到上一个版本",
            type: "number",
            outlined: true,
          },
          ok: {
            label: "确定",
            unelevated: true,
          },
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk((data) => {
          codeRollbackRelease(this.opId, this.appId, {
            app_version: data,
          }).then(() => {
            this.$q.notify("回滚成功");
          });
        });
    },
    onReleaseHistoriesClick() {
      getCodeReleaseHistories(this.opId, this.appId).then((res) => {
        this.jsonCardTitle = "历史版本";
        this.jsonCardData = res.version_list;
        this.showJsonCard = true;
      });
    },
    onGetGrayReleaseClick() {
      getGrayReleaseInfo(this.opId, this.appId).then((res) => {
        this.jsonCardTitle = "分阶段发布详情";
        this.jsonCardData = res.gray_release_plan;
        this.showJsonCard = true;
      });
    },
    onGrayReleaseClick() {
      this.editCard = "grayRelease";
    },
    onRevertGrayReleaseClick() {
      this.$q
        .dialog({
          title: "确定撤销分阶段发布吗",
          message: "撤销后，已发布的分阶段灰度版本将会失效。",
          ok: {
            label: "确定",
            unelevated: true,
          },
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          revertGrayRelease(this.opId, this.appId).then(() => {
            this.$q.notify("撤销成功");
          });
        });
    },
    onGrayReleaseSubmit(data) {
      grayRelease(this.opId, this.appId, data).then(() => {
        this.$q.notify("发布成功");
      });
    },
    onSpeedAuditClick() {
      this.$q.loading.show();
      getSpeedAuditCount(this.opId, this.appId).then((res) => {
        this.$q.loading.hide();
        this.$q
          .dialog({
            title: "是否使用加急审核",
            prompt: {
              model: "",
              label: "请输入提交审核时所返回的 audit_id",
              hint: `总可用${res.speedup_rest}次，当月可用${res.speedup_limit}次`,
              outlined: true,
            },
            ok: {
              label: "确定",
              unelevated: true,
            },
            cancel: {
              flat: true,
              color: "grey",
            },
          })
          .onOk((data) => {
            speedAudit(this.opId, this.appId, {
              auditId: data,
            }).then(() => {
              this.$q.notify("设置成功");
            });
          });
      });
    },
    onGetSupportVersionClick() {
      getSupportVersion(this.opId, this.appId).then((res) => {
        this.jsonCardTitle = "当前基础库详情";
        this.jsonCardData = res;
        this.showJsonCard = true;
      });
    },
    onSetSupportVersionClick() {
      this.$q
        .dialog({
          title: "基础库设置",
          prompt: {
            model: "",
            label: "请输入您想设置的最低基础库版本号",
            isValid: (val) => !!val,
            outlined: true,
          },
          ok: {
            label: "确定",
            unelevated: true,
          },
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk((data) => {
          setSupportVersion(this.opId, this.appId, {
            version: data,
          }).then(() => {
            this.$q.notify("设置成功");
          });
        });
    },
  },
};
</script>

<style scoped lang="scss">
.my-custom-toggle {
  border: 1px solid $primary;
}
</style>
