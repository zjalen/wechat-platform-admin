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
      <q-card-section class="text-h4"> 基本信息配置</q-card-section>
      <q-card-section class="q-pt-none">
        <q-list separator>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>账号名称</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label
                >{{ basicInfo.nickname_info.nickname || "空" }}
              </q-item-label>
              <q-item-label caption>
                本年度共可修改
                {{ basicInfo.nickname_info.modify_quota }} 次，已使用
                {{ basicInfo.nickname_info.modify_used_count }} 次
              </q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-btn
                flat
                dense
                label="修改"
                color="secondary"
                @click="editCard = 'name'"
              ></q-btn>
              <q-btn
                flat
                dense
                label="查询"
                color="secondary"
                @click="onGetNicknameAuditStatus"
              ></q-btn>
              <q-btn
                flat
                dense
                label="检测可用"
                color="secondary"
                @click="onCheckClick"
              ></q-btn>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>头像信息</q-item-label>
            </q-item-section>

            <q-item-section>
              <q-avatar>
                <q-img
                  :src="basicInfo.head_image_info.head_image_url"
                  alt="avatar"
                >
                </q-img>
              </q-avatar>
              <q-item-label class="q-mt-sm" caption>
                本年度共可修改
                {{ basicInfo.head_image_info.modify_quota }} 次，已使用
                {{ basicInfo.head_image_info.modify_used_count }} 次
              </q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-btn
                flat
                dense
                label="更换"
                color="secondary"
                @click="editCard = 'avatar'"
              ></q-btn>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>账号简介</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label
                >{{ basicInfo.signature_info.signature || "空" }}
              </q-item-label>
              <q-item-label caption>
                本年度修改共可修改
                {{ basicInfo.signature_info.modify_quota }} 次，已使用
                {{ basicInfo.signature_info.modify_used_count }} 次
              </q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-btn
                flat
                dense
                label="修改"
                color="secondary"
                @click="editCard = 'signature'"
              ></q-btn>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
    </q-card>
    <my-request-form-card
      v-if="editCard === 'name'"
      class="q-mt-lg"
      :form-data="nameFormData"
      title="修改名称"
      @submit="onSubmitName"
    ></my-request-form-card>
    <my-request-form-card
      v-if="editCard === 'avatar'"
      class="q-mt-lg"
      :form-data="avatarFormData"
      title="修改头像"
      @submit="onSubmitAvatar"
    ></my-request-form-card>
    <my-request-form-card
      v-if="editCard === 'signature'"
      class="q-mt-lg"
      :form-data="signatureFormData"
      title="修改简介"
      @submit="onSubmitSignature"
    ></my-request-form-card>
  </q-page>
</template>

<script>
import { useStore } from "vuex";
import { computed } from "vue";
import MyRequestFormCard from "components/MyRequestFormCard";
import {
  checkNickName,
  getNicknameAuditStatus,
  setAvatar,
  setNickName,
  setSignature,
} from "src/api/authorizer-mini-program";

export default {
  name: "BasicInformation",
  components: { MyRequestFormCard },
  setup() {
    const store = useStore();
    return {
      basicInfo: computed(() => store.state.basicInfo),
    };
  },
  data: () => ({
    showBanner: true,
    editCard: null,
    nameFormData: [
      {
        label: "新名称",
        name: "nick_name",
        value: "",
        required: true,
      },
      {
        label: "营业执照",
        name: "license",
        value: "",
        required: true,
        media: "image",
      },
      {
        label: "其他材料1",
        name: "naming_other_stuff_1",
        value: "",
        media: "image",
      },
      {
        label: "其他材料2",
        name: "naming_other_stuff_2",
        value: "",
        media: "image",
      },
      {
        label: "其他材料3",
        name: "naming_other_stuff_3",
        value: "",
        media: "image",
      },
      {
        label: "其他材料4",
        name: "naming_other_stuff_4",
        value: "",
        media: "image",
      },
      {
        label: "其他材料5",
        name: "naming_other_stuff_5",
        value: "",
        media: "image",
      },
    ],
    avatarFormData: [
      {
        label: "头像文件",
        name: "head_img_media_id",
        value: "",
        media: "image",
        required: true,
      },
      {
        label: "截图位置x1，0-1之间",
        name: "x1",
        value: "",
      },
      {
        label: "截图位置y1，0-1之间",
        name: "y1",
        value: "",
      },
      {
        label: "截图位置x2，0-1之间",
        name: "x2",
        value: "",
      },
      {
        label: "截图位置y2，0-1之间",
        name: "y2",
        value: "",
      },
    ],
    signatureFormData: [
      {
        label: "简介描述信息",
        name: "signature",
        value: "",
        required: true,
      },
    ],
  }),
  beforeMount() {
    if (this.basicInfo.account_type === 0) {
      this.nameFormData[1].label = "身份证";
      this.nameFormData[1].name = "id_card";
    }
  },
  methods: {
    onSubmitName(data) {
      setNickName(this.opId, this.appId, data)
        .then((res) => {
          if (res.errorcode === 0) {
            if (res.audit_id) {
              this.$q.dialog({
                message: JSON.stringify(res),
              });
            } else {
              this.$q.notify("设置成功，请刷新");
            }
          }
        })
        .catch((error) => {
          if (error.audit_id) {
            this.$q.dialog({
              message: "您的修改需要审核，请自行记录审核id：" + error.audit_id,
            });
          }
        });
    },
    onSubmitAvatar(data) {
      setAvatar(this.opId, this.appId, data).then(() => {
        this.$q.notify("设置成功，请刷新");
      });
    },
    onSubmitSignature(data) {
      setSignature(this.opId, this.appId, data).then(() => {
        this.$q.notify("设置成功，请刷新");
      });
    },
    onCheckClick() {
      this.$q
        .dialog({
          title: "验证名字是否可用",
          message: "请输入要验证的名字",
          prompt: {
            model: "",
            type: "text", // optional
          },
          ok: {
            label: "确定",
            unelevated: true,
          },
          cancel: {
            label: "取消",
            flat: true,
            color: "grey",
          },
          persistent: true,
        })
        .onOk((data) => {
          checkNickName(this.opId, this.appId, data).then((res) => {
            if (res.errcode === 0) {
              this.$q.dialog({
                title: "名称可用",
                message: res.wording,
              });
            }
          });
        });
    },
    onGetNicknameAuditStatus() {
      this.$q
        .dialog({
          title: "查询审核结果",
          message: "请输入要查询的 audit_id",
          prompt: {
            model: "",
            type: "text", // optional
          },
          ok: {
            label: "确定",
            unelevated: true,
          },
          cancel: {
            label: "取消",
            flat: true,
            color: "grey",
          },
          persistent: true,
        })
        .onOk((data) => {
          getNicknameAuditStatus(this.opId, this.appId, data).then((res) => {
            if (res.errcode === 0) {
              this.$q.dialog({
                title: "名称可用",
                message: res.wording,
              });
            }
          });
        });
    },
  },
};
</script>

<style scoped></style>
