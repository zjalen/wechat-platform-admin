<template>
  <q-page class="q-pa-lg">
    <q-banner
      v-if="showBanner"
      class="bg-primary text-white q-mb-lg"
      rounded
      :inline-actions="true"
    >
      注意信息可修改权限与次数请参照微信官方文档说明
      <template v-slot:action>
        <q-btn flat color="white" label="隐藏" @click="showBanner = false" />
      </template>
    </q-banner>
    <q-card>
      <q-card-section class="text-h4">
        <div class="row">
          服务器域名配置
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
        <div class="text-caption text-grey q-mt-sm">
          可进行 api 调用等通讯的域名，<a
            href="https://developers.weixin.qq.com/miniprogram/dev/framework/ability/network.html#1.%20%E6%9C%8D%E5%8A%A1%E5%99%A8%E5%9F%9F%E5%90%8D%E9%85%8D%E7%BD%AE"
            target="_blank"
            >参见链接</a
          >
        </div>
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-list separator>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>request 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label
                v-for="url in serverDomain.requestdomain"
                :key="url"
              >
                {{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>ws/socket 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label
                v-for="url in serverDomain.wsrequestdomain"
                :key="url"
              >
                {{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>uploadFile 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in serverDomain.uploaddomain" :key="url">
                {{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>downloadFile 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label
                v-for="url in serverDomain.downloaddomain"
                :key="url"
              >
                {{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>udp 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in serverDomain.udpdomain" :key="url"
                >{{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>tcp 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in serverDomain.tcpdomain" :key="url"
                >{{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>

          <q-item v-if="serverDomain.invalid_requestdomain.length > 0">
            <q-item-section thumbnail>
              <q-item-label>无效 request 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label
                v-for="url in serverDomain.invalid_requestdomain"
                :key="url"
                >{{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item v-if="serverDomain.invalid_wsrequestdomain.length > 0">
            <q-item-section thumbnail>
              <q-item-label>无效 ws 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label
                v-for="url in serverDomain.invalid_wsrequestdomain"
                :key="url"
                >{{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item v-if="serverDomain.invalid_uploaddomain.length > 0">
            <q-item-section thumbnail>
              <q-item-label>无效 upload 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label
                v-for="url in serverDomain.invalid_uploaddomain"
                :key="url"
                >{{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item v-if="serverDomain.invalid_downloaddomain.length > 0">
            <q-item-section thumbnail>
              <q-item-label>无效 download</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label
                v-for="url in serverDomain.invalid_downloaddomain"
                :key="url"
                >{{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item v-if="serverDomain.invalid_udpdomain.length > 0">
            <q-item-section thumbnail>
              <q-item-label>无效 udp 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label
                v-for="url in serverDomain.invalid_udpdomain"
                :key="url"
                >{{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item v-if="serverDomain.invalid_tcpdomain.length > 0">
            <q-item-section thumbnail>
              <q-item-label>无效 tcp 域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label
                v-for="url in serverDomain.invalid_tcpdomain"
                :key="url"
                >{{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item v-if="serverDomain.no_icp_domain.length > 0">
            <q-item-section thumbnail>
              <q-item-label>未备案域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in serverDomain.no_icp_domain" :key="url"
                >{{ url }}
              </q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
    </q-card>
    <q-card class="q-mt-lg">
      <q-card-section class="text-h4">
        <div class="row">
          业务域名配置
          <q-space />
          <div class="q-gutter-md">
            <q-btn
              unelevated
              dense
              color="primary"
              @click="toSetDefaultWebDomain"
              >同步全部
            </q-btn>
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
        <view class="text-caption text-grey-6 q-mt-sm"
          >可在 webview 中打开链接的域名，<a
            href="https://developers.weixin.qq.com/miniprogram/dev/framework/ability/domain.html"
            target="_blank"
            >参见链接</a
          >
        </view>
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-list separator>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>业务域名</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label v-for="url in webDomain" :key="url" lines="1"
                >{{ url }}
              </q-item-label>
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
          <q-item-label class="text-grey q-mb-md"
            >请输入域名(如 www.qq.com)，无需输入协议(如
            https://)，多个域名以英文分号(;)隔开
          </q-item-label>
          <q-form
            v-if="targetDomain === 'server'"
            @submit="submitServerDomain"
            class="q-gutter-sm"
          >
            <q-input
              dense
              outlined
              label="request 域名"
              :model-value="inputServerDomain.request"
              v-model="inputServerDomain.request"
            >
            </q-input>
            <q-input
              dense
              outlined
              label="ws/socket 域名"
              :model-value="inputServerDomain.ws"
              v-model="inputServerDomain.ws"
            >
            </q-input>
            <q-input
              dense
              outlined
              label="uploadFile 域名"
              :model-value="inputServerDomain.upload"
              v-model="inputServerDomain.upload"
            >
            </q-input>
            <q-input
              dense
              outlined
              label="download 域名"
              :model-value="inputServerDomain.download"
              v-model="inputServerDomain.download"
            >
            </q-input>
            <q-input
              dense
              outlined
              label="udp 域名"
              :model-value="inputServerDomain.udp"
              v-model="inputServerDomain.udp"
            >
            </q-input>
            <q-input
              dense
              outlined
              label="tcp 域名"
              :model-value="inputServerDomain.tcp"
              v-model="inputServerDomain.tcp"
            >
            </q-input>
            <div class="row q-gutter-md q-mt-none">
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
          <q-form v-else @submit="submitWebDomain" class="q-gutter-sm">
            <q-input
              dense
              outlined
              label="请输入业务域名"
              :model-value="inputWebDomain"
              v-model="inputWebDomain"
            >
            </q-input>

            <div class="row q-gutter-md q-mt-none">
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
  setServerDomain,
  setWebDomain,
  syncWebDomain,
} from "src/api/authorizer-mini-program.js";

export default {
  name: "DomainSettings",
  data: () => ({
    showBanner: true,
    serverDomain: {
      requestdomain: [],
      wsrequestdomain: [],
      uploaddomain: [],
      downloaddomain: [],
      udpdomain: [],
      tcpdomain: [],
      invalid_requestdomain: [],
      invalid_wsrequestdomain: [],
      invalid_uploaddomain: [],
      invalid_downloaddomain: [],
      invalid_udpdomain: [],
      invalid_tcpdomain: [],
      no_icp_domain: [],
    },
    webDomain: [],

    showDomainEditForm: false,
    inputServerDomain: {
      request: "",
      ws: "",
      upload: "",
      download: "",
      udp: "",
      tcp: "",
    },
    inputDomainLabel: "",
    inputWebDomain: "",
    currentAction: "",
    targetDomain: null,
  }),
  beforeMount() {
    this.toGetServerDomain();
    this.toGetWebDomain();
  },
  methods: {
    parseServerDomain(res) {
      this.serverDomain = res;
    },
    toGetServerDomain() {
      getServerDomain(
        this.$store.state.currentOpId,
        this.$store.state.currentAppId
      ).then((res) => {
        this.parseServerDomain(res);
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
    toSetDefaultWebDomain() {
      this.$q
        .dialog({
          title: "是否确定同步",
          message:
            "同步是将第三方开放平台下所有的业务域名，全部添加为该小程序的业务域名。",
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          syncWebDomain(
            this.$store.state.currentOpId,
            this.$store.state.currentAppId
          ).then((res) => {
            this.webDomain = res.webviewdomain;
            this.$q.notify("同步完成");
          });
        });
    },
    toGetWebDomain() {
      getWebDomain(
        this.$store.state.currentOpId,
        this.$store.state.currentAppId
      ).then((res) => {
        this.webDomain = res.webviewdomain;
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
          const data = {};
          Object.keys(this.inputServerDomain).forEach((val) => {
            data[val] = this.inputServerDomain[val]
              ? this.inputServerDomain[val].split(";")
              : [];
          });
          if (this.currentAction === "add") {
            addServerDomain(
              this.$store.state.currentOpId,
              this.$store.state.currentAppId,
              data
            ).then((res) => {
              this.parseServerDomain(res);
              this.showDomainEditForm = false;
            });
          } else if (this.currentAction === "set") {
            setServerDomain(
              this.$store.state.currentOpId,
              this.$store.state.currentAppId,
              data
            ).then((res) => {
              this.parseServerDomain(res);
              this.showDomainEditForm = false;
            });
          } else if (this.currentAction === "delete") {
            deleteServerDomain(
              this.$store.state.currentOpId,
              this.$store.state.currentAppId,
              data
            ).then((res) => {
              this.parseServerDomain(res);
              this.showDomainEditForm = false;
            });
          }
        });
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
          const data = {
            webDomain: this.inputWebDomain.split(";"),
          };
          if (this.currentAction === "add") {
            addWebDomain(
              this.$store.state.currentOpId,
              this.$store.state.currentAppId,
              data
            ).then((res) => {
              this.webDomain = res.webviewdomain;
              this.showDomainEditForm = false;
            });
          } else if (this.currentAction === "set") {
            setWebDomain(
              this.$store.state.currentOpId,
              this.$store.state.currentAppId,
              data
            ).then((res) => {
              this.webDomain = res.webviewdomain;
              this.showDomainEditForm = false;
            });
          } else if (this.currentAction === "delete") {
            deleteWebDomain(
              this.$store.state.currentOpId,
              this.$store.state.currentAppId,
              data
            ).then((res) => {
              this.webDomain = res.webviewdomain;
              this.showDomainEditForm = false;
            });
          }
        });
    },
  },
};
</script>

<style scoped></style>
