<template>
  <q-page class="q-pa-lg">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="平台管理" to="/platforms" :replace="true" />
      <q-breadcrumbs-el
        :label="$store.state.currentPlatformInfo.name"
        :to="`/open-platform/${id}`"
        :replace="true"
      />
      <q-breadcrumbs-el label="可信任域名管理" />
    </q-breadcrumbs>
    <q-card class="q-mt-lg">
      <q-card-section class="text-h4">小程序模板库</q-card-section>
      <q-card-section class="q-pa-none">
        <q-tabs
          v-model="tab"
          dense
          class="bg-green-2"
          active-color="primary"
          indicator-color="primary"
          align="justify"
          narrow-indicator
          @update:model-value="onTabChange"
        >
          <q-tab name="draft" label="草稿" />
          <q-tab name="normal" label="普通模板库" />
          <q-tab name="standard" label="标准模板库" />
        </q-tabs>
        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="draft">
            <q-table
              bordered
              class="no-box-shadow"
              :rows="draftList"
              :columns="draftColumns"
              hide-pagination
            >
              <template v-slot:body-cell-actions="props">
                <q-td class="text-center">
                  <q-btn
                    flat
                    dense
                    color="secondary"
                    @click="onSetTemplateClick(props.row.draft_id, 0)"
                    >添加到普通模板
                  </q-btn>
                  <q-btn
                    flat
                    dense
                    color="secondary"
                    @click="onSetTemplateClick(props.row.draft_id, 1)"
                    >添加到标准模板
                  </q-btn>
                </q-td>
              </template>
            </q-table>
          </q-tab-panel>
          <q-tab-panel name="normal">
            <q-table
              bordered
              class="no-box-shadow"
              :rows="normalTemplateList"
              :columns="normalColumns"
              hide-pagination
            >
              <template v-slot:body-cell-template_id="props">
                <q-td class="text-center">
                  <q-chip
                    color="primary"
                    class="text-white"
                    :label="props.value"
                    clickable
                    @click="onTemplateClick(props.value)"
                  >
                    <q-tooltip>点击复制模板 id</q-tooltip>
                  </q-chip>
                </q-td>
              </template>
              <template v-slot:body-cell-actions="props">
                <q-td class="text-center">
                  <q-btn
                    flat
                    dense
                    color="secondary"
                    @click="onDeleteTemplateClick(props.row.template_id)"
                    >删除模板
                  </q-btn>
                </q-td>
              </template>
            </q-table>
          </q-tab-panel>
          <q-tab-panel name="standard">
            <q-table
              bordered
              class="no-box-shadow"
              :rows="standardTemplateList"
              :columns="standardColumns"
              hide-pagination
            >
              <template v-slot:body-cell-templateId="props">
                <q-td class="text-center">
                  <q-chip
                    color="primary"
                    class="text-white"
                    :label="props.value"
                    clickable
                    @click="onTemplateClick(props.value)"
                  >
                    <q-tooltip>点击复制模板 id</q-tooltip>
                  </q-chip>
                </q-td>
              </template>

              <template v-slot:body-cell-actions="props">
                <q-td class="text-center">
                  <q-btn
                    flat
                    dense
                    color="secondary"
                    @click="onDeleteTemplateClick(props.row.template_id)"
                    >删除模板
                  </q-btn>
                </q-td>
              </template>
            </q-table>
          </q-tab-panel>
        </q-tab-panels>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import {
  addCodeTemplate,
  deleteCodeTemplate,
  getCodeDrafts,
  getCodeTemplate,
} from "src/api/open-platform";
import { copyToClipboard, date } from "quasar";

export default {
  name: "CodeManage",
  data: () => ({
    id: null,
    tab: "draft",
    draftList: [],
    normalTemplateList: [],
    standardTemplateList: [],
    draftColumns: [
      {
        name: "draft_id",
        label: "草稿 id",
        field: "draft_id",
        align: "center",
      },
      {
        label: "版本号",
        field: "user_version",
        align: "center",
      },
      {
        label: "描述",
        field: "user_desc",
        align: "left",
      },
      {
        label: "开发小程序 appId",
        field: "source_miniprogram_appid",
        align: "center",
      },
      {
        label: "开发小程序名字",
        field: "source_miniprogram",
        align: "center",
      },
      {
        label: "上传时间",
        field: "create_time",
        align: "center",
        format: (val, row) =>
          `${date.formatDate(val * 1000, "YYYY-MM-DD HH:mm:ss")}`,
      },
      {
        name: "actions",
        label: "操作",
        field: "actions",
        align: "center",
      },
    ],
    normalColumns: [
      {
        name: "template_id",
        label: "模板 id",
        field: "template_id",
        align: "center",
      },
      {
        label: "版本号",
        field: "user_version",
        align: "center",
      },
      {
        label: "描述",
        field: "user_desc",
        align: "left",
      },
      {
        label: "开发小程序 appId",
        field: "source_miniprogram_appid",
        align: "center",
      },
      {
        label: "开发小程序名字",
        field: "source_miniprogram",
        align: "center",
      },
      {
        label: "添加到模板库时间",
        field: "create_time",
        align: "center",
        format: (val, row) =>
          `${date.formatDate(val * 1000, "YYYY-MM-DD HH:mm:ss")}`,
      },
      {
        name: "actions",
        label: "操作",
        field: "actions",
        align: "center",
      },
    ],
    standardColumns: [
      {
        name: "templateId",
        label: "模板 id",
        field: "template_id",
        align: "center",
      },
      {
        label: "版本号",
        field: "user_version",
        align: "center",
      },
      {
        label: "描述",
        field: "user_desc",
        align: "left",
      },
      {
        label: "开发小程序 appId",
        field: "source_miniprogram_appid",
        align: "center",
      },
      {
        label: "开发小程序名字",
        field: "source_miniprogram",
        align: "center",
      },
      {
        label: "添加到模板库时间",
        field: "create_time",
        align: "center",
        format: (val, row) =>
          `${date.formatDate(val * 1000, "YYYY-MM-DD HH:mm:ss")}`,
      },
      {
        name: "actions",
        label: "操作",
        field: "actions",
        align: "center",
      },
    ],
  }),
  beforeMount() {
    this.id = this.$route.params.id;
    this.loadDraftList();
  },
  methods: {
    onTabChange(tab) {
      if (tab === "draft") {
        this.loadDraftList();
      } else if (tab === "normal") {
        this.loadCodeTemplate(0);
      } else if (tab === "standard") {
        this.loadCodeTemplate(1);
      }
    },
    loadDraftList() {
      getCodeDrafts(this.id).then((res) => {
        this.draftList = res.draft_list;
      });
    },
    loadCodeTemplate(type) {
      getCodeTemplate(this.id, { type: type }).then((res) => {
        if (type === 0) {
          this.normalTemplateList = res.template_list;
        } else {
          this.standardTemplateList = res.template_list;
        }
      });
    },
    onTemplateClick(id) {
      copyToClipboard(id).then(() => this.$q.notify("已复制到剪切板"));
    },
    onSetTemplateClick(draftId, type) {
      this.$q
        .dialog({
          title: "确定要添加到模板库吗？",
          message:
            "普通模板库支持更多 ext_json 参数，需要每个小程序单独审核，标准模板库支持更少 ext_json，统一审核，部署时小程序无需再次审核。",
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          addCodeTemplate(this.id, {
            draftId: draftId,
            type: type,
          }).then(() => {
            this.$q.notify("设置成功");
          });
        });
    },
    onDeleteTemplateClick(templateId) {
      this.$q
        .dialog({
          title: "确定要删除吗？",
          message: "删除后不影响已部署的小程序，如需使用可重新添加",
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          deleteCodeTemplate(this.id, {
            templateId: templateId,
          }).then(() => {
            this.$q.notify("删除成功");
            if (this.tab === "normal") {
              this.loadCodeTemplate(0);
            } else if (this.tab === "standard") {
              this.loadCodeTemplate(1);
            }
          });
        });
    },
  },
};
</script>

<style scoped></style>
