<template>
  <q-page class="q-pa-lg">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="平台管理" to="/platforms" :replace="true" />
      <q-breadcrumbs-el :label="breadcrumbTitle" />
    </q-breadcrumbs>
    <q-card class="q-mt-lg">
      <q-card-section class="text-h6 q-pb-none">操作日志</q-card-section>
      <q-card-section>
        <q-table
          class="no-box-shadow"
          :rows="rows"
          :columns="columns"
          row-key="id"
          v-model:pagination="pagination"
          :rows-per-page-options="[10, 20, 100]"
          :loading="loading"
          @request="initData"
          bordered
        >
        </q-table>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { getOperationLogs } from "src/api";

export default {
  name: "OperationLog",
  data: () => ({
    breadcrumbTitle: "操作日志",
    columns: [
      {
        name: "id",
        field: "id",
        label: "id",
        sortable: true,
      },
      {
        name: "ip",
        field: "ip",
        label: "ip",
        sortable: true,
      },
      {
        name: "method",
        field: "method",
        label: "请求方法",
        sortable: true,
      },
      {
        name: "path",
        field: "path",
        label: "请求路径",
        sortable: true,
      },
      {
        name: "params",
        field: "params",
        label: "参数",
        style: "width: 200px",
      },
      {
        name: "wx_result",
        field: "wx_result",
        label: "微信返回结果",
        style: "width: 500px",
      },
      {
        name: "created_at",
        field: "created_at",
        label: "请求时间",
        sortable: true,
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
  beforeMount() {
    this.loading = true;
    this.initData();
  },
  methods: {
    initData(props) {
      console.log(props);
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
      getOperationLogs(params)
        .then((res) => {
          this.pagination.rowsNumber = res.total_account;
          this.rows = res.list;
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
  },
};
</script>

<style scoped></style>
