<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section class="text-h4">
        <div class="row">
          服务类目
          <q-space />
          <q-btn
            unelevated
            color="primary"
            label="添加类目"
            @click="onAddClick"
          ></q-btn>
        </div>
        <div class="text-caption text-grey q-mt-sm">
          已添加 {{ categories.length }} 个，最多
          {{ category_limit }} 个，本月可添加 {{ quota }} 次，每月可添加
          {{ limit }} 次。
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <q-table
          bordered
          class="no-box-shadow"
          :rows="categories"
          :columns="columns"
          :visible-columns="visibleColumns"
          hide-pagination
        >
          <template v-slot:body-cell-category_name="props">
            <q-td>
              {{ props.row.first_name }} > {{ props.row.second_name }}
            </q-td>
          </template>
          <template v-slot:body-cell-audit_status="props">
            <q-td>
              <view v-if="props.value === 1">审核中</view>
              <view v-else-if="props.value === 3">已通过</view>
              <view v-else class="text-negative"
                >未通过({{ props.row.audit_reason }})
              </view>
            </q-td>
          </template>
          <template v-slot:body-cell-actions="props">
            <q-td class="text-center">
              <q-btn
                flat
                color="secondary"
                label="删除"
                dense
                @click="onDeleteClick(props.row)"
              ></q-btn>
            </q-td>
          </template>
        </q-table>
      </q-card-section>
    </q-card>

    <q-card v-if="showAddCard" class="q-mt-lg">
      <q-card-section class="text-subtitle1">添加类目</q-card-section>
      <q-separator />
      <q-card-section>
        <q-form class="q-gutter-sm" @submit="onSubmit">
          <div v-for="(category, index) in formData" :key="index">
            <div class="row q-gutter-sm">
              <q-select
                class="col"
                :model-value="category.first"
                v-model="formData[index].first"
                dense
                outlined
                hide-bottom-space
                label="一级类目"
                :options="firstOptions()"
                option-label="name"
                option-value="id"
                lazy-rules
                :rules="[(val) => !!val || '选择类目或移除条目']"
              ></q-select>
              <q-select
                class="col"
                model-value=""
                v-model="formData[index].second"
                dense
                outlined
                hide-bottom-space
                label="二级类目"
                :options="secondOptions(formData[index].first)"
                option-label="name"
                option-value="id"
                @popup-show="currentFormIndex = index"
                lazy-rules
                :rules="[(val) => !!val || '选择类目或移除条目']"
                @update:model-value="onSecondOptionSelected"
              ></q-select>
              <view>
                <q-icon
                  name="r_remove"
                  class="cursor-pointer q-mt-sm"
                  size="24px"
                  @click="onRemoveCategory(index)"
                >
                  <q-tooltip>移除该类目</q-tooltip>
                </q-icon>
              </view>
            </div>
            <div class="q-mt-sm">
              <div
                v-for="(exter, key1) in formData[index].certificate_list"
                :key="key1"
              >
                <view>
                  <q-chip
                    v-for="(inner, key2) in exter.inner_list"
                    :key="key2"
                    clickable
                    @click="onCertChipClick(index, key2, inner.name)"
                  >
                    {{ inner.name }}
                  </q-chip>
                </view>
                <view
                  v-if="key1 !== formData[index].certificate_list.length - 1"
                >
                  或
                </view>
              </div>
            </div>
            <div
              v-for="(cert, key3) in formData[index].certicates"
              :key="key3"
              class="row q-mt-sm q-gutter-sm"
            >
              <q-input
                class="col"
                model-value=""
                v-model="formData[index].certicates[key3].key"
                dense
                outlined
                label="资质名称"
                hide-bottom-space
                :hint="formData[index].certicates[key3].hint"
                :rules="[(val) => (val && val.length > 0) || '填写或移除条目']"
              ></q-input>
              <q-input
                class="col"
                model-value=""
                v-model="formData[index].certicates[key3].value"
                dense
                hide-bottom-space
                outlined
                label="资质文件"
                lazy-rules
                :rules="[(val) => (val && val.length > 0) || '填写或移除条目']"
              >
                <template v-slot:append>
                  <q-icon
                    name="r_image"
                    class="cursor-pointer"
                    @click="onChooseMediaClick(key3)"
                  >
                    <q-tooltip>添加文件</q-tooltip>
                  </q-icon>
                </template>
              </q-input>
              <view>
                <q-icon
                  name="r_remove"
                  class="cursor-pointer q-mt-sm"
                  size="24px"
                  @click="onRemoveCertificate(key3)"
                >
                  <q-tooltip>移除该文件</q-tooltip>
                </q-icon>
              </view>
            </div>
            <q-separator />
          </div>
          <div>
            <q-btn
              dense
              flat
              color="secondary"
              label="增加一个类目"
              @click="onAddCategory"
            ></q-btn>
          </div>
          <div>
            <q-btn
              unelevated
              color="primary"
              label="提交"
              type="submit"
              icon="r_save"
            ></q-btn>
          </div>
        </q-form>
      </q-card-section>
    </q-card>

    <q-dialog full-height full-width v-model="showMediaChooseDialog">
      <media-choose-card
        type="image"
        :slug="currentMediaSlug"
        @media-chosen="onMediaChosen"
      ></media-choose-card>
    </q-dialog>
  </q-page>
</template>

<script>
import {
  addCategory,
  deleteCategory,
  getCategories,
  getCategory,
} from "src/api/authorizer-mini-program.js";
import MediaChooseCard from "components/MediaChooseCard.vue";

export default {
  name: "MpCategory",
  components: { MediaChooseCard },
  data: () => ({
    categories: [],
    showAddCard: false,
    visibleColumns: ["category_name", "audit_status", "actions"],
    columns: [
      {
        name: "first",
        label: "first",
        field: "first",
        align: "left",
      },
      {
        name: "first_name",
        label: "first_name",
        field: "first_name",
        align: "left",
      },
      {
        name: "second",
        label: "second",
        field: "second",
        align: "left",
      },
      {
        name: "second_name",
        label: "second_name",
        field: "second_name",
        align: "left",
      },
      {
        name: "audit_reason",
        label: "audit_reason",
        field: "audit_reason",
        align: "left",
      },
      {
        name: "category_name",
        label: "服务名称",
        field: "category_name",
        align: "left",
      },
      {
        name: "audit_status",
        label: "审核结果",
        field: "audit_status",
        align: "left",
      },
      {
        name: "actions",
        label: "操作",
        align: "center",
      },
    ],
    formData: [
      {
        first: null,
        second: null,
        certicates: [],
        certificate_list: [],
      },
    ],
    limit: 0,
    quota: 0,
    category_limit: 0,
    categories_list: [],

    currentFormIndex: 0,
    currentMediaSlug: null,
    showMediaChooseDialog: false,
  }),
  beforeMount() {
    this.initData();
  },
  methods: {
    initData() {
      getCategory(
        this.$store.state.currentOpId,
        this.$store.state.currentAppId
      ).then((res) => {
        this.categories = res.categories;
        this.category_limit = res.category_limit;
        this.limit = res.limit;
        this.quota = res.quota;
      });
    },
    onAddClick() {
      if (this.categories_list.length === 0) {
        this.$q.loading.show();
        getCategories(
          this.$store.state.currentOpId,
          this.$store.state.currentAppId,
          {
            type: this.$store.state.basicInfo.principal_type,
          }
        )
          .then((res) => {
            this.$q.loading.hide();
            this.categories_list = res.categories_list.categories;
          })
          .catch(() => {
            this.$q.loading.hide();
          });
      }
      this.showAddCard = true;
    },
    onDeleteClick(val) {
      this.$q
        .dialog({
          title: "确定删除吗",
          message:
            "删除之后将立刻生效，如需使用可重新添加，注意每个月有操作次数限制。",
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          deleteCategory(
            this.$store.state.currentOpId,
            this.$store.state.currentAppId,
            {
              first: val.first,
              second: val.second,
            }
          ).then(() => {
            this.$q.notify("删除完成");
            this.initData();
          });
        });
    },
    firstOptions() {
      return this.categories_list.filter((value) => {
        return value.level === 1;
      });
    },
    secondOptions(father) {
      if (father) {
        return this.categories_list.filter((value) => {
          return value.level === 2 && value.father === father.id;
        });
      }
    },
    onSecondOptionSelected(val) {
      this.formData[this.currentFormIndex].certificate_list =
        val.qualify.exter_list;
    },
    onCertChipClick(index, subIndex, hint) {
      this.formData[index].certicates.push({
        key: "",
        value: "",
        hint: hint,
      });
    },
    onMediaChosen({ slug, mediaId }) {
      const arr = slug.split("_");
      const index = arr[1];
      this.formData[this.currentFormIndex].certicates[index].value = mediaId;
      this.showMediaChooseDialog = false;
    },
    onChooseMediaClick(index) {
      this.currentMediaSlug = "media_" + index;
      this.showMediaChooseDialog = true;
    },
    onRemoveCertificate(index) {
      this.formData[this.currentFormIndex].certicates.splice(index, 1);
    },
    onAddCategory() {
      this.formData.push({
        first: "",
        second: "",
        certicates: [],
        certificate_list: [],
      });
    },
    onRemoveCategory(index) {
      this.formData.splice(index, 1);
    },
    onSubmit() {
      const data = [];
      for (let i = 0; i < this.formData.length; i++) {
        const value = this.formData[i];
        const first = value.first.id;
        const second = value.second.id;
        const certicates = value.certicates.map((val) => {
          return { key: val.key, value: val.value };
        });
        data.push({
          first: first,
          second: second,
          certicates: certicates,
        });
      }
      this.$q
        .dialog({
          title: "确定提交吗",
          message: "注意每个月有操作次数限制，以及总数最多 20 个类目限制。",
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          addCategory(
            this.$store.state.currentOpId,
            this.$store.state.currentAppId,
            {
              categories: data,
            }
          ).then(() => {
            this.$q.notify("添加完成");
            this.initData();
          });
        });
    },
  },
};
</script>

<style scoped></style>
