<template>
  <q-page class="q-pa-lg">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="平台管理" to="/platforms" :replace="true" />
      <q-breadcrumbs-el :label="breadcrumbTitle" />
    </q-breadcrumbs>
    <q-card class="q-mt-lg">
      <q-card-section class="panel-form">
        <view class="row">
          <view class="text-bold text-negative"
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
        <view class="q-pb-none panel-form-item">
          <view class="panel-form-item-label">消息与事件接收配置</view>
          <view class="panel-form-item-value">
            <div>{{ notify_url || "加载中" }}</div>
            <div class="panel-form-item-value-tip">
              用于代授权的公众号或小程序的接收平台推送的消息与事件，该参数按规则填写（需包含/$APPID$，如www.abc.com/$APPID$/callback），实际接收消息时$APPID$将被替换为公众号或小程序AppId。
              注意：该URL的一级域名需与“授权事件接收配置”的一级域名一致
            </div>
          </view>
        </view>
        <view class="panel-form-item">
          <view class="panel-form-item-label">消息校验Token</view>
          <view class="panel-form-item-value">
            <div>{{ visibleData(token) }}</div>
            <div class="panel-form-item-value-tip">
              开发者在代替公众号或小程序接收到消息时，用此Token来校验消息。
            </div>
          </view>
        </view>
        <view class="panel-form-item">
          <view class="panel-form-item-label">消息加解密Key</view>
          <view class="panel-form-item-value">
            <div>{{ visibleData(aes_key) }}</div>
            <div class="panel-form-item-value-tip">
              在代替公众号或小程序收发消息过程中使用。必须是长度为43位的字符串，只能是字母和数字。
            </div>
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
        <view class="panel-form-item">
          <view class="panel-form-item-label">授权发起页域名</view>
          <view class="panel-form-item-value">
            <div>{{ domain }}</div>
            <div class="panel-form-item-value-tip">
              必须从本域名内网页跳转到登录授权页，才可完成登录授权
            </div>
          </view>
        </view>
        <view class="panel-form-item">
          <view class="panel-form-item-label">域名配置</view>
          <view class="panel-form-item-value">
            <a :href="`/#/open-platform/${id}/domain`">点击进行域名配置管理</a>
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
          color="primary"
          unelevated
          :to="`/open-platform/${id}/code-manage`"
          label="代码管理"
        >
        </q-btn>
        <q-btn
          flat
          color="secondary"
          label="创建测试小程序"
          @click="showCreateBetaDialog = true"
        ></q-btn>
        <q-btn
          flat
          color="secondary"
          @click="loadAuthorizers"
          label="获取已授权账号"
        >
          <q-tooltip>从微信服务器的接口获取已绑定的账号</q-tooltip>
        </q-btn>
      </q-card-actions>
    </q-card>

    <view v-show="showRemoteList">
      <q-separator class="q-mt-lg" />
      <view class="row q-col-gutter-lg q-mt-none relative-position">
        <view v-if="showRemoteLoading" class="q-py-lg q-mt-lg">
          <q-inner-loading
            style="left: 24px; top: 24px"
            :showing="showRemoteLoading"
          >
            <q-spinner-gears size="50px" color="primary" />
          </q-inner-loading>
        </view>
        <view v-else-if="authorizers.length === 0" class="q-py-lg"
          >没有已绑定的账号
        </view>
        <view
          class="col-lg-3 col-md-4 col-sm-6 col-xs-12"
          v-for="(item, index) in authorizers"
          :key="index"
        >
          <q-card>
            <q-card-section class="flex items-center">
              <view>{{ item.authorizer_appid }}</view>
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
      </view>
    </view>
    <q-separator class="q-mt-lg" />

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
              <q-btn
                v-if="item.service_type_name === '试用小程序'"
                flat
                color="negative"
                @click="onBetaVerifyClick(item)"
                >去转正
              </q-btn>
              <q-btn flat color="grey" @click="onDeleteClick(item)"
                >删除
              </q-btn>
              <q-btn
                v-if="
                  item.service_type_name === '服务号' ||
                  item.service_type_name === '订阅号'
                "
                flat
                color="secondary"
                @click="gotoFastCreateMp(item.app_id)"
                >快速创建小程序
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

    <q-dialog v-model="showBetaVerifyDialog">
      <q-card>
        <q-card-section>
          <div class="text-subtitle1">试用小程序认证转正</div>
          <div class="text-caption text-grey q-mt-sm">
            提交后需经过法人认证和管理员授权。详情请
            <a
              href="https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/beta_Mini_Programs/fastverify.html"
              target="_blank"
              >点击链接查看</a
            >
          </div>
        </q-card-section>
        <q-separator />
        <q-card-section>
          <q-form @submit="submitBetaVerify">
            <q-input
              dense
              outlined
              hide-bottom-space
              label="公司名"
              hint="公司名称"
              v-model="betaVerifyForm.enterprise_name"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            ></q-input>
            <div class="q-mb-sm q-gutter-sm">
              <q-radio
                v-model="betaVerifyForm.code_type"
                :val="1"
                label="统一信用代码(18位)"
              />
              <q-radio
                v-model="betaVerifyForm.code_type"
                :val="2"
                label="组织机构代码(9位)"
              />
              <q-radio
                v-model="betaVerifyForm.code_type"
                :val="3"
                label="营业执照注册号(15位)"
              />
            </div>
            <q-input
              dense
              outlined
              label="企业证件号"
              hint="如：营业执照统一信用代码"
              v-model="betaVerifyForm.code"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            ></q-input>
            <q-input
              dense
              outlined
              label="法人微信号"
              hint="法人微信号码"
              v-model="betaVerifyForm.legal_persona_wechat"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            ></q-input>
            <q-input
              dense
              outlined
              label="法人姓名"
              hint="法人姓名，需与微信号实名一致"
              v-model="betaVerifyForm.legal_persona_name"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            ></q-input>
            <q-input
              dense
              outlined
              label="法人身份证"
              hint="法人身份证，需与微信号实名一致"
              v-model="betaVerifyForm.legal_persona_idcard"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            ></q-input>
            <q-input
              dense
              outlined
              label="第三方联系电话"
              hint="提供第三方服务的平台电话"
              v-model="betaVerifyForm.component_phone"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || '必填内容']"
            ></q-input>

            <div>
              <q-btn
                label="提交"
                unelevated
                color="primary"
                icon="r_save"
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
  createBetaMiniProgram,
  deleteLocalAuthorizer,
  getAuthorizer,
  getAuthorizers,
  getLocalAuthorizer,
  getSecretConfig,
  saveLocalAuthorizer,
} from "src/api/open-platform";
import PlatformCard from "components/SubPlatformContent";
import QRCode from "qrcodejs2";
import { verifyBetaWeApp } from "src/api/authorizer-mini-program";

export default {
  name: "ThirdPlatform",
  components: { PlatformCard },
  data: () => ({
    id: null,
    visible: false,
    breadcrumbTitle: "第三方平台",
    app_id: null,
    serve_url: null,
    notify_url: null,
    bind_url: null,
    fast_create_mp_url: null,
    access_token: "",
    domain: "",
    token: "",
    aes_key: "",
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
    showBetaVerifyDialog: false,
    currentMpAppId: null,
    betaVerifyForm: {
      enterprise_name: "",
      code: "",
      code_type: 1,
      legal_persona_wechat: "",
      legal_persona_name: "",
      component_phone: "",
      legal_persona_idcard: "",
    },
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
          this.notify_url = res.notify_url;
          this.bind_url = res.bind_url;
          this.fast_create_mp_url = res.fast_create_mp_url;
          this.token = res.token;
          this.aes_key = res.aes_key;
          this.domain = res.domain.includes("//")
            ? res.domain.split("//")[1]
            : res.domain;
          if (res.access_token) {
            this.errMsg = null;
            this.access_token = res.access_token.component_access_token;
          } else {
            this.access_token = "无效";
            this.errMsg = res.errMsg;
            this.$q.notify({
              message: res.errMsg,
              color: "negative",
            });
          }
        })
        .catch((err) => {
          this.access_token = "无效";
          this.errMsg = err.errMsg;
        });
      this.loadSubPlatforms();
    },
    gotoFastCreateMp(appId) {
      const url = `${this.fast_create_mp_url}?appId=${appId}`;
      this.$q
        .dialog({
          title: "注册提示",
          message:
            "只有已认证的公众号才可以复用已认证的公司信息，快速注册小程序。新注册的小程序自动认证，同公众号一致，无需重复认证。更多信息可参考：https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/product/Register_Mini_Programs/fast_registration_of_mini_program.html",
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk(() => {
          window.open(url, "_blank");
        });
    },
    loadSubPlatforms() {
      getLocalAuthorizer(this.id, {
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
      saveLocalAuthorizer(this.id, params).then(() => {
        this.$q.notify("同步成功");
        this.showAuthorizerDetail = false;
        this.loadSubPlatforms();
      });
    },
    onDeleteClick(item) {
      this.$q
        .dialog({
          title: "删除",
          message: "仅删除数据库内容，并非解绑，获取已授权账号重新添加",
          cancel: {
            color: "grey",
            flat: true,
          },
        })
        .onOk(() => {
          deleteLocalAuthorizer(this.id, item.id).then(() => {
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
      } else {
        this.$router.push({
          name: "subOfficialAccountIndex",
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
    onBetaVerifyClick(item) {
      console.log(item);
      this.currentMpAppId = item.app_id;
      this.showBetaVerifyDialog = true;
    },
    submitBetaVerify() {
      console.log(this.betaVerifyForm);
      verifyBetaWeApp(this.id, this.currentMpAppId, this.betaVerifyForm).then(
        () => {
          this.$q.notify("提交成功");
        }
      );
    },
  },
};
</script>

<style scoped></style>
