<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section class="row flex-center">
        <div class="text-h6">自动回复</div>
        <q-space />
        <q-toggle
          dense
          left-label
          label="启用自动回复"
          v-model="isAutoReplyOpen"
          :model-value="isAutoReplyOpen"
          @update:model-value="onAutoReplyToggle"
        ></q-toggle>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <q-table
          class="no-box-shadow"
          :rows="rows"
          :columns="columns"
          :visible-columns="[]"
          row-key="id"
          v-model:pagination="pagination"
          :rows-per-page-options="[10, 20, 100]"
          :loading="loading"
          @request="initData"
        >
          <template #top>
            <div class="row" style="width: 120%; margin: -16px -16px 0">
              <q-input outlined label="搜索关键词/规则名称" dense></q-input>
              <q-space></q-space>
              <q-btn
                unelevated
                label="添加回复"
                color="primary"
                :to="{ name: 'autoReplyRuleCreate' }"
              ></q-btn>
            </div>
          </template>
          <template v-slot:body-cell-match_type="props">
            <q-td>
              <q-chip v-if="props.value === 0" dense color="warning"
                >半匹配</q-chip
              >
              <q-chip v-else dense color="warning">全匹配</q-chip>
            </q-td>
          </template>
          <template v-slot:body-cell-type="props">
            <q-td>
              <q-chip v-if="props.value === 0" dense color="warning"
                >已发表内容</q-chip
              >
              <q-chip v-else-if="props.value === 1" dense color="warning"
                >文本</q-chip
              >
              <q-chip v-else-if="props.value === 2" dense color="warning"
                >图片</q-chip
              >
              <q-chip v-else-if="props.value === 3" dense color="warning"
                >音频</q-chip
              >
              <q-chip v-else-if="props.value === 4" dense color="warning"
                >视频</q-chip
              >
              <q-chip v-else-if="props.value === 5" dense color="warning"
                >图文</q-chip
              >
            </q-td>
          </template>
          <template v-slot:body-cell-content="props">
            <q-td>
              <json-viewer :value="props.value"></json-viewer>
            </q-td>
          </template>
          <template v-slot:body-cell-actions="props">
            <q-td class="text-center">
              <q-btn
                flat
                dense
                label="编辑"
                color="secondary"
                @click="onEdit(props.row.id)"
              ></q-btn>
              <q-btn
                flat
                dense
                label="删除"
                color="secondary"
                @click="onDelete(props.row.id)"
              ></q-btn>
            </q-td>
          </template>
        </q-table>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import {
  deleteAutoReplyRule,
  getAutoReplyRules,
  updateAutoReplyState,
} from "src/api/authorizer-official-account.js";
import JsonViewer from "vue-json-viewer";

export default {
  name: "AutoReplyRule",
  components: {
    JsonViewer,
  },
  data: () => ({
    isAutoReplyOpen: false,
    columns: [
      {
        label: "id",
        field: "id",
        align: "center",
        required: true,
        sortable: true,
      },
      {
        label: "规则名称",
        field: "name",
        align: "left",
        required: true,
        sortable: true,
      },
      {
        label: "关键词",
        field: "keyword",
        align: "left",
        required: true,
        sortable: true,
      },
      {
        label: "匹配形式",
        field: "match_type",
        name: "match_type",
        align: "left",
        required: true,
        sortable: true,
      },
      {
        label: "回复类型",
        field: "type",
        name: "type",
        align: "left",
        required: true,
        sortable: true,
      },
      {
        label: "回复内容",
        field: "content",
        name: "content",
        align: "left",
        required: true,
        sortable: true,
      },
      {
        label: "操作",
        name: "actions",
        field: "actions",
        align: "center",
        required: true,
      },
    ],
    rows: [],
    pagination: {
      sortBy: "id",
      descending: true,
      page: 1,
      rowsPerPage: 20,
      rowsNumber: 0,
    },
    loading: false,
  }),
  watch: {
    "$store.state.basicInfo.is_auto_reply_open": {
      handler(newVal) {
        this.isAutoReplyOpen = newVal;
      },
      immediate: true,
      deep: true,
    },
  },
  mounted() {
    this.loading = true;
    this.initData();
  },
  methods: {
    initData(props) {
      if (!props) {
        props = { pagination: this.pagination };
      } else {
        this.pagination = props.pagination;
      }
      const params = {
        limit: props.pagination.rowsPerPage,
        skip: props.pagination.rowsPerPage * (props.pagination.page - 1),
        orderBy: props.pagination.sortBy,
        desc: props.pagination.descending,
      };
      getAutoReplyRules(this.opId, this.appId, params)
        .then((res) => {
          this.pagination.rowsNumber = res.total_account;
          this.rows = res.list;
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    onEdit(id) {
      this.$router.push({
        name: "autoReplyRuleEdit",
        params: {
          opId: this.opId,
          appId: this.appId,
          id: id,
        },
      });
    },
    onDelete(id) {
      this.$q
        .dialog({
          title: "确定删除吗",
          message: "删除后不可找回",
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          deleteAutoReplyRule(this.opId, this.appId, id).then(() => {
            this.$q.notify("删除成功");
            this.initData();
          });
        });
    },
    onAutoReplyToggle() {
      updateAutoReplyState(this.opId, this.appId, {
        is_auto_reply_open: this.isAutoReplyOpen,
      }).then(() => {
        this.$q.notify("更新成功");
        this.$store.dispatch("loadSubBasicInfo", {
          opId: this.opId,
          appId: this.appId,
        });
      });
    },
  },
};
</script>

<style scoped></style>
