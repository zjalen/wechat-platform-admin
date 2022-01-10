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
      <q-card-section class="text-h4">
        <div class="row">
          设置第三方平台服务器域名
          <q-space />
          <div class="q-gutter-md">
            <q-btn flat dense color="primary" @click="toGetServerDomain"
              >查询
            </q-btn>
            <q-btn flat dense color="secondary" @click="toAddServerDomain"
              >添加
            </q-btn>
            <q-btn flat dense color="secondary" @click="toSetServerDomain"
              >修改
            </q-btn>
            <q-btn flat dense color="secondary" @click="toDeleteServerDomain"
              >删除
            </q-btn>
          </div>
        </div>
        <div class="text-caption text-grey">
          只有先添加为第三方平台可信任的域名，才可被授权的小程序、公众号使用
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <q-list separator>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>已全网发布域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label>
                {{ serverDomain.published || "空" }}
              </q-item-label>
              <q-item-label caption>
                已发布表示已上线的程序可以正常使用的域名
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>测试可用域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label>
                {{ serverDomain.testing || "空" }}
              </q-item-label>
              <q-item-label caption>
                测试版本表示尚未发布，正在开发的版本可以使用，线上如需使用必须全网发布，全网发布详见开放平台后台
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item v-if="serverDomain.invalid">
            <q-item-section thumbnail>
              <q-item-label>无效域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label>
                {{ serverDomain.invalid || "空" }}
              </q-item-label>
              <q-item-label caption> 无效表示域名不可用</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
    </q-card>
    <q-card class="q-mt-lg">
      <q-card-section class="text-h4">
        <div class="row">
          设置第三方平台业务域名
          <q-space />
          <div class="q-gutter-md">
            <q-btn flat dense color="primary" @click="toGetWebDomain"
              >查询
            </q-btn>
            <q-btn flat dense color="secondary" @click="toAddWebDomain"
              >添加
            </q-btn>
            <q-btn flat dense color="secondary" @click="toSetWebDomain"
              >修改
            </q-btn>
            <q-btn flat dense color="secondary" @click="toDeleteWebDomain"
              >删除
            </q-btn>
          </div>
        </div>
        <div class="text-caption text-grey">
          只有先添加为第三方平台可信任的域名，才可被授权的小程序、公众号使用
          <div class="q-mb-sm text-negative">
            操作前请先下载
            <a href="javascript:" @click="downloadCheckFile">服务器验证文件</a
            >， 您需将文件放置到域名对应服务器的根目录，确保可以直接访问到
          </div>
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <q-list separator>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>已全网发布域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label>
                {{ webDomain.published || "空" }}
              </q-item-label>
              <q-item-label caption>
                已发布表示已上线的程序可以正常使用的域名
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>测试可用域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label>
                {{ webDomain.testing || "空" }}
              </q-item-label>
              <q-item-label caption>
                测试版本表示尚未发布，正在开发的版本可以使用，线上如需使用必须全网发布，全网发布详见开放平台后台
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item v-if="webDomain.invalid">
            <q-item-section thumbnail>
              <q-item-label>无效域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label>
                {{ webDomain.invalid || "空" }}
              </q-item-label>
              <q-item-label caption> 无效表示域名不可用</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
    </q-card>

    <q-dialog v-model="showDomainEditForm">
      <q-card style="width: 80vw">
        <q-card-section class="text-subtitle1"
          >{{ inputDomainLabel }}
        </q-card-section>
        <q-separator />
        <q-card-section>
          <q-chip
            v-if="currentAction === 'set'"
            color="negative"
            class="text-white q-mb-md"
            >修改时请务必慎重，新域名列表将完全代替原域名列表，原域名列表将失效。
          </q-chip>
          <q-form @submit="submit">
            <q-input
              dense
              outlined
              :label="inputDomainLabel"
              :model-value="inputDomain"
              v-model="inputDomain"
              hint="请以英文分号(;)隔开，只能输入域名(如：www.qq.com)，不要输入 http://或https://，不能加路径"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '不能为空']"
            >
            </q-input>
            <q-toggle
              label="同时全网发布"
              v-model="isPublishedTogether"
              :model-value="isPublishedTogether"
            />
            <div class="row q-gutter-md">
              <q-space />
              <q-btn flat color="grey" label="取消" v-close-popup></q-btn>
              <q-btn
                unelevated
                color="primary"
                label="提交"
                type="submit"
              ></q-btn>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import {
  addServerDomain,
  addWebDomain,
  deleteServerDomain,
  deleteWebDomain,
  getServerDomain,
  getWebDomain,
  getWebDomainCheckFile,
  setServerDomain,
  setWebDomain,
} from "src/api/open-platform";

export default {
  name: "OpenPlatformDomain",
  data: () => ({
    id: null,
    serverDomain: {
      published: "",
      testing: "",
      invalid: "",
    },
    webDomain: {
      published: "",
      testing: "",
      invalid: "",
    },
    inputDomain: "",
    isPublishedTogether: false,
    inputDomainLabel: "",
    showDomainEditForm: false,
    currentAction: null,
    targetDomain: null,
  }),
  beforeMount() {
    this.id = this.$route.params.id;
    this.toGetServerDomain();
    this.toGetWebDomain();
  },
  methods: {
    toGetServerDomain() {
      this.showDomainEditForm = false;
      getServerDomain(this.id).then((res) => {
        this.parseServerDomainList(res);
      });
    },
    toAddServerDomain() {
      this.targetDomain = "server";
      this.currentAction = "add";
      this.inputDomainLabel = "添加新域名";
      this.showDomainEditForm = true;
    },
    toSetServerDomain() {
      this.targetDomain = "server";
      this.currentAction = "set";
      this.inputDomainLabel = "修改(覆盖)原域名";
      this.showDomainEditForm = true;
    },
    toDeleteServerDomain() {
      this.targetDomain = "server";
      this.currentAction = "delete";
      this.inputDomainLabel = "删除域名";
      this.showDomainEditForm = true;
    },
    submitServerDomain() {
      this.$q
        .dialog({
          title: "请谨慎确认",
          message: "您是否要" + this.inputDomainLabel,
          cancel: {
            color: "grey",
            flat: true,
          },
        })
        .onOk(() => {
          if (this.currentAction === "add") {
            addServerDomain(this.id, {
              domainList: this.inputDomain,
            }).then((res) => {
              this.parseServerDomainList(res);
              this.showDomainEditForm = false;
            });
          } else if (this.currentAction === "set") {
            setServerDomain(this.id, {
              domainList: this.inputDomain,
            }).then((res) => {
              this.parseServerDomainList(res);
              this.showDomainEditForm = false;
            });
          } else if (this.currentAction === "delete") {
            deleteServerDomain(this.id, {
              domainList: this.inputDomain,
            }).then((res) => {
              this.parseServerDomainList(res);
              this.showDomainEditForm = false;
            });
          }
        });
    },
    parseServerDomainList(obj) {
      if (obj.published_wxa_server_domain) {
        this.serverDomain.published = obj.published_wxa_server_domain;
      }
      if (obj.testing_wxa_server_domain) {
        this.serverDomain.testing = obj.testing_wxa_server_domain;
      }
      if (obj.invalid_wxa_server_domain) {
        this.serverDomain.invalid = obj.invalid_wxa_server_domain;
      }
    },
    downloadCheckFile() {
      getWebDomainCheckFile(this.id).then((res) => {
        const a = document.createElement("a");
        const blob = new Blob([res.file_content], {
          type: "application/octet-stream",
        });
        a.href = window.URL.createObjectURL(blob);
        a.download = res.file_name;
        a.click();
      });
    },
    toGetWebDomain() {
      this.showDomainEditForm = false;
      getWebDomain(this.id).then((res) => {
        this.parseWebDomainList(res);
      });
    },
    toAddWebDomain() {
      this.targetDomain = "web";
      this.currentAction = "add";
      this.inputDomainLabel = "添加新域名";
      this.showDomainEditForm = true;
    },
    toSetWebDomain() {
      this.targetDomain = "web";
      this.currentAction = "set";
      this.inputDomainLabel = "修改(覆盖)原域名";
      this.showDomainEditForm = true;
    },
    toDeleteWebDomain() {
      this.targetDomain = "web";
      this.currentAction = "delete";
      this.inputDomainLabel = "删除域名";
      this.showDomainEditForm = true;
    },
    submitWebDomain() {
      this.$q
        .dialog({
          title: "请谨慎确认",
          message: "您是否要" + this.inputDomainLabel,
          cancel: {
            color: "grey",
            flat: true,
          },
        })
        .onOk(() => {
          if (this.currentAction === "add") {
            addWebDomain(this.id, {
              domainList: this.inputDomain,
            }).then((res) => {
              this.parseWebDomainList(res);
              this.showDomainEditForm = false;
            });
          } else if (this.currentAction === "set") {
            setWebDomain(this.id, {
              domainList: this.inputDomain,
            }).then((res) => {
              this.parseWebDomainList(res);
              this.showDomainEditForm = false;
            });
          } else if (this.currentAction === "delete") {
            deleteWebDomain(this.id, {
              domainList: this.inputDomain,
            }).then((res) => {
              this.parseWebDomainList(res);
              this.showDomainEditForm = false;
            });
          }
        });
    },
    parseWebDomainList(obj) {
      if (obj.published_wxa_jump_h5_domain) {
        this.webDomain.published = obj.published_wxa_jump_h5_domain;
      }
      if (obj.testing_wxa_jump_h5_domain) {
        this.webDomain.testing = obj.testing_wxa_jump_h5_domain;
      }
      if (obj.invalid_wxa_jump_h5_domain) {
        this.webDomain.invalid = obj.invalid_wxa_jump_h5_domain;
      }
    },
    submit() {
      if (this.targetDomain === "server") {
        this.submitServerDomain();
      } else if (this.targetDomain === "web") {
        this.submitWebDomain();
      } else {
        this.$q.notify({ color: "negative", message: "参数异常" });
      }
    },
  },
};
</script>

<style scoped></style>
