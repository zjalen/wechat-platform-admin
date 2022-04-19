<template>
  <view>
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
      <q-tab name="normal" label="普通模板库" />
      <q-tab name="standard" label="标准模板库" />
    </q-tabs>
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="normal">
        <q-table
          bordered
          class="no-box-shadow"
          :rows="normalTemplateList"
          :columns="normalColumns"
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
                color="primary"
                @click="onChoose(props.row.template_id)"
                >选择模板
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
                color="primary"
                @click="onChoose(props.row.template_id)"
                >选择模板
              </q-btn>
            </q-td>
          </template>
        </q-table>
      </q-tab-panel>
    </q-tab-panels>
  </view>
</template>

<script>
import { copyToClipboard, date } from "quasar";
import { getCode } from "src/api/open-platform";

export default {
  name: "OpenPlatformCodeList",
  props: {
    openPlatformId: {
      default: null,
    },
  },
  emits: ["template-choose"],
  data: () => ({
    tab: "normal",
    normalTemplateList: [],
    standardTemplateList: [],
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
        format: (val) =>
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
        format: (val) =>
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
    this.loadCodeTemplate(0);
  },
  methods: {
    onTabChange(tab) {
      if (tab === "normal") {
        this.loadCodeTemplate(0);
      } else if (tab === "standard") {
        this.loadCodeTemplate(1);
      }
    },
    loadCodeTemplate(type) {
      getCode(this.openPlatformId, { type: type }).then((res) => {
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
    onChoose(id) {
      this.$emit("template-choose", id);
    },
  },
};
</script>

<style scoped></style>
