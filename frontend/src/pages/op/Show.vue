<template>
  <q-page class="q-pa-lg">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="平台管理" to="/platforms" :replace="true" />
      <q-breadcrumbs-el :label="breadcrumbTitle" />
    </q-breadcrumbs>
    <q-card class="q-mt-lg">
      <q-card-section class="panel-form">
        <view class="row">
          <view class="text-bold"
            >以下信息需与微信开放平台填写信息保持一致，注意保护隐私信息，防止泄露
          </view>
          <q-space />
          <q-btn
            flat
            round
            :icon="visible ? 'r_visibility' : 'r_visibility_off'"
            :color="visible ? 'primary' : 'grey'"
            @click="visible = !visible"
          ></q-btn>
        </view>
        <view class="q-pb-none panel-form-item">
          <view class="panel-form-item-label">AppID</view>
          <view class="panel-form-item-value">
            <div>{{ app_id || "加载中" }}</div>
            <div class="panel-form-item-value-tip">平台唯一标识</div>
          </view>
        </view>
        <view class="q-pb-none panel-form-item">
          <view class="panel-form-item-label">授权事件接收URL</view>
          <view class="panel-form-item-value">
            <div>{{ serve_url || "加载中" }}</div>
            <div class="panel-form-item-value-tip">
              <a
                href="https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/operation/thirdparty/prepare.html#_3%E3%80%81%E6%8E%88%E6%9D%83%E4%BA%8B%E4%BB%B6%E6%8E%A5%E6%94%B6-URL"
                target="_blank"
                >微信开放平台开发配置中所填链接地址必须与此URL保持一致</a
              >
              ，用于接收平台推送给第三方平台账号的消息与事件，如授权事件通知、component_verify_ticket等
            </div>
          </view>
        </view>
        <view class="panel-form-item">
          <view class="panel-form-item-label">可信任域名</view>
          <view class="panel-form-item-value">
            <a :href="`/#/open-platform/${id}/domain`">点击查看</a>
          </view>
        </view>
        <view class="panel-form-item">
          <view class="panel-form-item-label">AccessToken</view>
          <view class="panel-form-item-value">
            <div>{{ visibleData(access_token) }}</div>
            <div
              v-if="visible && errMsg"
              class="panel-form-item-value-tip text-negative"
            >
              {{ errMsg }}
            </div>
            <div class="panel-form-item-value-tip">
              微信官方票据换取的身份标识，接口调用必需参数，该参数会随使用自动更新，最长
              2 小时内有效
            </div>
          </view>
        </view>
      </q-card-section>
      <q-separator />
      <q-card-actions>
        <q-btn
          type="a"
          :href="bind_url"
          target="__blank"
          unelevated
          color="primary"
          label="绑定新账号"
        ></q-btn>
        <q-btn
          unelevated
          color="secondary"
          label="创建测试小程序"
          @click="showCreateBetaDialog = true"
        ></q-btn>
        <q-btn
          unelevated
          color="grey"
          @click="loadAuthorizers"
          label="远程获取已绑定账号"
        >
          <q-tooltip>从微信服务器的接口获取已绑定的账号</q-tooltip>
        </q-btn>
        <q-btn
          unelevated
          color="negative"
          :to="`/open-platform/${id}/code-manage`"
          label="代码管理"
        >
        </q-btn>
      </q-card-actions>
    </q-card>

    <view v-show="showRemoteList">
      <q-separator class="q-mt-lg" />
      <view class="row q-col-gutter-lg q-mt-none relative-position">
        <view v-if="showRemoteLoading" class="q-py-lg">加载中...</view>
        <view v-if="authorizers.length === 0" class="q-py-lg"
          >没有已绑定的账号
        </view>
        <view
          class="col-lg-3 col-md-4 col-sm-6 col-xs-12"
          v-for="(item, index) in authorizers"
          :key="index"
        >
          <q-card class="cursor-pointer">
            <q-card-section class="flex items-center">
              <view class="text-primary">{{ item.authorizer_appid }}</view>
              <q-space />
              <q-btn
                flat
                color="secondary"
                @click="loadAuthorizer(item.authorizer_appid)"
                >查看详情
              </q-btn>
            </q-card-section>
          </q-card>
        </view>
        <q-inner-loading :showing="showRemoteLoading">
          <q-spinner-gears size="50px" color="primary" />
        </q-inner-loading>
      </view>
      <q-separator class="q-mt-lg" />
    </view>

    <view v-if="subPlatforms.length > 0">
      <view class="row q-col-gutter-lg q-pt-lg">
        <view
          class="col-lg-3 col-md-4 col-sm-6 col-xs-12"
          v-for="(item, index) in subPlatforms"
          :key="index"
        >
          <q-card>
            <q-card-section>
              <platform-card :info="item"></platform-card>
            </q-card-section>
            <q-card-actions align="right">
              <q-btn flat color="negative" @click="deleteSubPlatform(item)"
                >删除
              </q-btn>
              <q-btn flat color="secondary" @click="loadAuthorizer(item.app_id)"
                >查看详情
              </q-btn>
              <q-btn
                unelevated
                color="primary"
                @click="onSubPlatformClick(item)"
                >管理
              </q-btn>
            </q-card-actions>
          </q-card>
        </view>
      </view>
      <view class="row q-mt-lg">
        <q-space />
        <q-pagination v-model="currentPage" :max="maxPage" input />
      </view>
    </view>

    <q-dialog v-model="showAuthorizerDetail">
      <q-card>
        <q-card-section>
          <platform-card
            :info="currentAuthorizer.authorizer_info"
          ></platform-card>
        </q-card-section>
        <q-separator />
        <q-card-actions align="right">
          <q-btn flat color="grey" @click="showAuthorizerDetail = false"
            >关闭
          </q-btn>
          <q-btn
            unelevated
            color="primary"
            @click="syncSubPlatform"
            label="同步到数据库"
          >
            <q-tooltip>先同步到数据库才能管理该账号</q-tooltip>
          </q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-dialog v-model="showCreateBetaDialog">
      <q-card style="width: 80vw">
        <q-card-section class="text-subtitle1"> 创建测试小程序</q-card-section>
        <q-separator />
        <q-card-section>
          <q-form>
            <q-input
              :model-value="betaName"
              v-model="betaName"
              label="请输入测试小程序名字"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            ></q-input>
            <q-input
              :model-value="betaOpenId"
              v-model="betaOpenId"
              label="请输入用户openid"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            ></q-input>
          </q-form>
          <a
            href="https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/beta_Mini_Programs/fastregister.html"
            target="_blank"
            >相关内容和参数请参考链接</a
          >
        </q-card-section>
        <q-separator />
        <q-card-actions align="right">
          <q-btn flat color="grey" @click="showCreateBetaDialog = false"
            >关闭
          </q-btn>
          <q-btn
            unelevated
            color="primary"
            @click="submitCreateBetaMiniProgram"
            label="开始创建"
          >
          </q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-dialog v-model="showBetaMpAuthDialog">
      <q-card style="width: 600px">
        <q-card-section class="text-subtitle1">
          您可以把二维码或链接通过微信发送给用户以创建
        </q-card-section>
        <q-separator />
        <q-card-section>
          <div ref="qrcode" class="flex flex-center"></div>
        </q-card-section>
        <q-card-section>
          {{ betaMpAuthUrl }}
        </q-card-section>
        <q-separator />
        <q-card-actions align="right">
          <q-btn
            unelevated
            color="primary"
            @click="showBetaMpAuthDialog = false"
            >关闭
          </q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import {
  createBetaMiniProgram,
  deleteSubPlatform,
  getAuthorizer,
  getAuthorizers,
  getSecretConfig,
  getSubPlatforms,
  saveSubPlatform,
} from "src/api/open-platform";
import PlatformCard from "components/SubPlatformContent";
import QRCode from "qrcodejs2";

export default {
  name: "ThirdPlatform",
  components: { PlatformCard },
  data: () => ({
    id: null,
    visible: false,
    breadcrumbTitle: "第三方平台",
    app_id: null,
    serve_url: null,
    bind_url: null,
    access_token: "",
    errMsg: null,
    authorizers: [],
    authorizerDetails: [],
    currentPage: 1,
    perPage: 20,
    totalCount: 0,
    subPlatforms: [],
    tab: "local",
    showRemoteList: false,
    showAuthorizerDetail: false,
    showRemoteLoading: false,
    currentAuthorizer: {},
    showCreateBetaDialog: false,
    betaName: "",
    betaOpenId: "",
    showBetaMpAuthDialog: false,
    betaMpAuthUrl: "",
  }),
  computed: {
    maxPage() {
      return Math.ceil(this.totalCount / this.perPage);
    },
  },
  beforeMount() {
    this.id = this.$route.params.id;
    this.initData();
  },
  methods: {
    initData() {
      getSecretConfig(this.id)
        .then((res) => {
          this.app_id = res.app_id;
          this.breadcrumbTitle = res.name;
          this.serve_url = res.serve_url;
          this.bind_url = res.bind_url;
          if (res.access_token) {
            this.errMsg = null;
            this.access_token = res.access_token.component_access_token;
          } else {
            this.access_token = "无效";
            this.errMsg = res.errMsg;
          }
        })
        .catch((err) => {
          this.access_token = "无效";
          this.errMsg = err.errMsg;
        });
      this.loadSubPlatforms();
    },
    loadSubPlatforms() {
      getSubPlatforms(this.id, {
        limit: this.perPage,
        skip: this.perPage * (this.currentPage - 1),
      }).then((res) => {
        this.totalCount = res.total_count;
        this.subPlatforms = res.list;
      });
    },
    loadAuthorizers() {
      this.showRemoteList = true;
      this.showRemoteLoading = true;
      this.authorizers = [];
      getAuthorizers(this.id).then((res) => {
        this.showRemoteLoading = false;
        this.totalCount = res.total_count;
        this.authorizers = res.list;
      });
    },
    visibleData(data) {
      return this.visible ? data : "******";
    },
    loadAuthorizer(appId) {
      this.$q.loading.show();
      const timer = setTimeout(() => {
        this.$q.loading.hide();
      }, 5000);
      this.currentAuthorizer = {};
      getAuthorizer(this.id, appId).then((res) => {
        clearTimeout(timer);
        this.$q.loading.hide();
        this.currentAuthorizer = res;
        this.showAuthorizerDetail = true;
      });
    },
    syncSubPlatform() {
      const params = {
        nick_name: this.currentAuthorizer.authorizer_info.nick_name,
        head_img: this.currentAuthorizer.authorizer_info.head_img,
        app_id: this.currentAuthorizer.authorization_info.authorizer_appid,
        principal_name: this.currentAuthorizer.authorizer_info.principal_name,
        qrcode_url: this.currentAuthorizer.authorizer_info.qrcode_url,
        user_name: this.currentAuthorizer.authorizer_info.user_name,
        service_type_info:
          this.currentAuthorizer.authorizer_info.service_type_info.id,
        is_mini_program: Object.hasOwnProperty.call(
          this.currentAuthorizer.authorizer_info,
          "MiniProgramInfo"
        ),
      };
      saveSubPlatform(this.id, params).then(() => {
        this.$q.notify("同步成功");
        this.showAuthorizerDetail = false;
        this.loadSubPlatforms();
      });
    },
    deleteSubPlatform(item) {
      this.$q
        .dialog({
          title: "删除确认",
          message: "删除后可通过远程获取重新绑定",
          ok: {
            label: "确认",
            unelevated: true,
          },
          cancel: {
            color: "grey",
            flat: true,
            label: "取消",
          },
        })
        .onOk(() => {
          deleteSubPlatform(this.id, item.id).then((res) => {
            this.$q.notify("删除成功");
            this.loadSubPlatforms();
          });
        });
    },
    onSubPlatformClick(item) {
      if (item.is_mini_program) {
        this.$router.push({
          name: "subMiniProgramIndex",
          params: {
            opId: this.id,
            appId: item.app_id,
          },
        });
      }
    },
    submitCreateBetaMiniProgram() {
      createBetaMiniProgram(this.id, {
        name: this.betaName,
        openid: this.betaOpenId,
      }).then((res) => {
        if (res.errcode === 0) {
          this.showBetaMpAuthDialog = true;
          this.showCreateBetaDialog = false;
          this.betaMpAuthUrl = res.authorize_url;
          setTimeout(() => {
            new QRCode(this.$refs.qrcode, {
              text: this.betaMpAuthUrl, // 需要转换为二维码的内容
              width: 300,
              height: 300,
              colorDark: "#000000",
              colorLight: "#ffffff",
              correctLevel: QRCode.CorrectLevel.H,
            });
          }, 500);
        }
      });
    },
  },
};
</script>

<style scoped></style>
