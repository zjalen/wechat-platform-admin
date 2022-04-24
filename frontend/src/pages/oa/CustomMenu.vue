<template>
  <q-page class="q-pa-lg q-col-gutter-lg">
    <div class="col col-12">
      <q-card>
        <q-card-section class="text-h4 row flex-center">
          <div>自定义菜单</div>
          <q-space />
          <q-btn
            dense
            color="secondary"
            flat
            label="菜单快照"
            @click="loadCustomMenuList"
          ></q-btn>
        </q-card-section>
      </q-card>
    </div>

    <div class="col col-12 row q-col-gutter-lg">
      <div class="col q-ma-none col-shrink">
        <q-card
          bordered
          square
          style="width: 300px; height: 580px; background-color: white"
          class="mobile_menu_preview"
        >
          <div class="mobile_hd">{{ officialAccountName }}</div>
          <q-card-section
            v-if="orderMenu"
            class="absolute-bottom q-pa-none row bor"
            style="
              border-top: 1px solid #e7e7eb;
              border-radius: 0;
              background-color: #fafafa;
            "
          >
            <div class="flex flex-center" style="width: 43px; height: 49px">
              <q-icon size="25px" color="grey" name="r_keyboard" />
            </div>
            <draggable
              v-model="menuItems"
              tag="transition-group"
              item-key="name"
              group="menus"
              @start="
                (evt) => {
                  this.activeMenuIndex = evt.oldIndex - 1;
                }
              "
              @end="
                () => {
                  this.activeMenuIndex = null;
                }
              "
            >
              <template #item="{ element, index }">
                <q-item
                  v-if="orderMenu"
                  clickable
                  class="col q-pa-none text-center item-border-left"
                  :active="activeMenuIndex === index"
                  active-class="item-active"
                  @click="onOrderMenuClick(index)"
                >
                  <q-item-section>
                    <div class="row flex-center q-gutter-xs">
                      <q-icon name="r_menu" size="7px"></q-icon>
                      <div>{{ element.name }}</div>
                    </div>
                  </q-item-section>
                  <q-menu
                    v-if="element.sub_button && element.sub_button.length > 0"
                    fit
                    square
                    persistent
                    class="no-shadow"
                    anchor="top left"
                    self="bottom left"
                    :ref="`orderMenu` + index"
                    style="background-color: rgba(0, 0, 0, 0); z-index: 1"
                  >
                    <q-list
                      style="
                        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
                        background-color: #fafafa;
                      "
                    >
                      <draggable
                        v-model="element.sub_button"
                        tag="transition-group"
                        item-key="name"
                        group="subMenus"
                        @start="
                          (evt) => {
                            this.activeSubMenuIndex = evt.oldIndex;
                          }
                        "
                        @end="
                          () => {
                            this.activeSubMenuIndex = null;
                          }
                        "
                      >
                        <template #item="{ element, index }">
                          <q-item
                            class="q-pa-none text-center item-bordered"
                            active-class="item-active"
                            :active="activeSubMenuIndex === index"
                            clickable
                          >
                            <q-item-section>
                              <div class="row flex-center q-gutter-xs">
                                <q-icon name="r_menu" size="7px"></q-icon>
                                <div>
                                  {{ element.name }}
                                </div>
                              </div>
                            </q-item-section>
                          </q-item>
                        </template>
                      </draggable>
                    </q-list>
                  </q-menu>
                </q-item>
              </template>
            </draggable>
          </q-card-section>
          <q-card-section
            v-else
            class="absolute-bottom q-pa-none row bor"
            style="
              border-top: 1px solid #e7e7eb;
              border-radius: 0;
              background-color: #fafafa;
            "
          >
            <div class="flex flex-center" style="width: 43px; height: 49px">
              <q-icon size="25px" color="grey" name="r_keyboard" />
            </div>
            <q-item
              v-for="(element, key) in menuItems"
              :key="key"
              clickable
              v-ripple
              class="col q-pa-none item-border-left"
              active-class="item-active"
              :active="
                currentActiveMenu &&
                currentActiveMenu.level === 1 &&
                activeMenuIndex === key
              "
              @click="onMenuClick(key)"
            >
              <q-item-section>
                <div class="row flex-center q-gutter-xs">
                  <div>{{ element.name }}</div>
                </div>
              </q-item-section>
              <q-menu
                v-if="element.sub_button && element.sub_button.length > 0"
                fit
                square
                persistent
                class="no-shadow"
                anchor="top left"
                self="bottom left"
                :ref="`primaryMenu` + key"
                style="background-color: rgba(0, 0, 0, 0); z-index: 1"
              >
                <q-list
                  style="
                    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
                    background-color: #fafafa;
                  "
                >
                  <q-item
                    v-for="(subItem, subKey) in element.sub_button"
                    :key="subKey"
                    class="q-pa-none text-center item-bordered"
                    active-class="item-active"
                    :active="activeSubMenuIndex === subKey"
                    clickable
                    @click="onSubMenuClick(subKey)"
                  >
                    <q-item-section>
                      <div class="row flex-center q-gutter-xs">
                        <div>
                          {{ subItem.name }}
                        </div>
                      </div>
                    </q-item-section>
                  </q-item>
                  <q-item
                    v-if="element.sub_button.length < 5"
                    class="q-pa-none text-center item-bordered"
                    clickable
                    @click="onSubMenuClick('add')"
                  >
                    <q-item-section class="flex-center">
                      <q-icon name="r_add" size="20px"></q-icon>
                    </q-item-section>
                  </q-item>
                </q-list>
                <div
                  class="text-center"
                  style="margin-top: -16px; font-size: 20px"
                >
                  <q-icon
                    name="r_keyboard_arrow_down"
                    style="
                      color: rgba(0, 0, 0, 0.12);
                      height: 0.1em;
                      width: 0.5em;
                      border-top: 1px solid #f6f7f8;
                    "
                  ></q-icon>
                </div>
              </q-menu>
            </q-item>
            <q-item
              v-if="!loading && menuItems.length < 3"
              class="col q-pa-none text-center item-bordered"
              clickable
              @click="onMenuClick('add')"
            >
              <q-item-section class="flex-center">
                <q-icon name="r_add" size="20px"></q-icon>
              </q-item-section>
            </q-item>
          </q-card-section>
        </q-card>
      </div>
      <div class="col col-grow q-mt-none" style="z-index: 2">
        <q-card
          bordered
          square
          class="no-shadow q-px-md bg-blue-grey-1"
          style="height: 580px"
        >
          <q-card-section class="row q-px-none q-py-sm">
            <div
              v-if="currentActiveMenu"
              style="font-size: 15px"
              class="flex flex-center"
            >
              {{ currentActiveMenu.name }}
            </div>
            <div
              v-else
              style="font-size: 15px"
              class="flex text-grey flex-center"
            >
              请先选择菜单
            </div>
            <q-space />
            <q-btn
              :disable="!currentActiveMenu"
              dense
              flat
              color="secondary"
              @click="onDeleteMenu"
            >
              删除菜单
            </q-btn>
          </q-card-section>
          <q-separator />
          <q-card-section
            v-if="
              currentActiveMenu &&
              currentActiveMenu.level === 1 &&
              currentActiveMenu.sub_button.length > 0
            "
            class="q-px-none q-py-sm text-caption text-grey"
          >
            已添加子菜单，仅可设置菜单名称
          </q-card-section>
          <q-card-section v-if="currentActiveMenu" class="q-mt-sm bg-white">
            <q-form ref="form">
              <q-input
                v-model="currentActiveMenu.name"
                lazy-rules
                :rules="
                  currentActiveMenu.level === 1
                    ? formRules.menuRules
                    : formRules.subMenuRules
                "
                outlined
                dense
                :hint="`仅支持中英文和数字，字数不超过${
                  currentActiveMenu.level === 1 ? '4' : '8'
                }个汉字或${currentActiveMenu.level === 1 ? '8' : '16'}个字母`"
              >
                <template #before>
                  <div class="text-body2" style="width: 80px">
                    {{ currentActiveMenu.level === 1 ? "" : "子" }}菜单名称
                  </div>
                </template>
              </q-input>
              <q-select
                v-show="
                  (currentActiveMenu.level === 1 &&
                    currentActiveMenu.sub_button.length < 1) ||
                  currentActiveMenu.level === 2
                "
                :options="menuTypes"
                v-model="currentActiveMenu.type"
                option-label="text"
                option-value="value"
                map-options
                emit-value
                :rules="formRules.requireRules"
                dense
                outlined
                :hint="
                  currentActiveMenu.type !== 'click'
                    ? '请选择菜单类型'
                    : '点击事件类型的关键词可触发自动回复内容'
                "
              >
                <template #before>
                  <div class="text-body2" style="width: 80px">菜单类型</div>
                </template>
              </q-select>
              <div
                class="col"
                v-show="
                  ((currentActiveMenu.level === 1 &&
                    currentActiveMenu.sub_button.length < 1) ||
                    currentActiveMenu.level === 2) &&
                  currentActiveMenu.type
                "
              >
                <q-input
                  v-if="currentActiveMenu.type === 'article_id'"
                  outlined
                  readonly
                  dense
                  hint="请选择已发布的文章"
                  v-model="currentActiveMenu.article_id"
                >
                  <template #before>
                    <div class="text-body2" style="width: 80px">已发表内容</div>
                  </template>
                  <template #append>
                    <q-icon
                      name="r_search"
                      class="cursor-pointer"
                      @click="
                        type = 'article';
                        showMediaDialog = true;
                        getMediaList(type);
                      "
                    ></q-icon>
                  </template>
                </q-input>
                <div
                  v-if="currentActiveMenu.type === 'media_id'"
                  class="q-gutter-sm row"
                  style="margin-left: 80px"
                >
                  <q-radio
                    left-label
                    val="image"
                    label="图片"
                    v-model="type"
                  ></q-radio>
                  <q-radio
                    left-label
                    val="voice"
                    label="音频"
                    v-model="type"
                  ></q-radio>
                  <q-radio
                    left-label
                    val="video"
                    label="视频"
                    v-model="type"
                  ></q-radio>
                </div>
                <q-input
                  v-if="currentActiveMenu.type === 'media_id'"
                  outlined
                  readonly
                  dense
                  hint="请选择已上传的公众号素材"
                  v-model="currentActiveMenu.media_id"
                >
                  <template #before>
                    <div class="text-body2" style="width: 80px">素材选择</div>
                  </template>
                  <template #append>
                    <q-icon
                      name="r_search"
                      class="cursor-pointer"
                      @click="
                        showMediaDialog = true;
                        getMediaList(type);
                      "
                    ></q-icon>
                  </template>
                </q-input>
                <q-input
                  v-show="
                    currentActiveMenu.type === 'view' ||
                    currentActiveMenu.type === 'miniprogram'
                  "
                  v-model="currentActiveMenu.url"
                  outlined
                  dense
                  :rules="formRules.requireRules"
                >
                  <template #before>
                    <div class="text-body2" style="width: 80px">URL 链接</div>
                  </template>
                </q-input>

                <q-input
                  v-show="currentActiveMenu.type === 'miniprogram'"
                  v-model="currentActiveMenu.appid"
                  outlined
                  dense
                  :rules="formRules.requireRules"
                >
                  <template #before>
                    <div class="text-body2" style="width: 80px">
                      小程序 AppId
                    </div>
                  </template>
                </q-input>

                <q-input
                  v-show="currentActiveMenu.type === 'miniprogram'"
                  v-model="currentActiveMenu.pagepath"
                  outlined
                  dense
                  :rules="formRules.requireRules"
                >
                  <template #before>
                    <div class="text-body2" style="width: 80px">页面路径</div>
                  </template>
                </q-input>

                <q-input
                  v-show="
                    currentActiveMenu.type === 'click' ||
                    currentActiveMenu.type === 'scancode_waitmsg' ||
                    currentActiveMenu.type === 'scancode_push'
                  "
                  v-model="currentActiveMenu.key"
                  outlined
                  dense
                  :rules="formRules.requireRules"
                  :hint="
                    currentActiveMenu.type !== 'click'
                      ? '关键词 EventKey'
                      : '点击事件类型的关键词可触发自动回复内容'
                  "
                >
                  <template #before>
                    <div class="text-body2" style="width: 80px">关键词key</div>
                  </template>
                </q-input>
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </div>
    </div>
    <div class="row q-gutter-lg">
      <q-btn
        v-if="!orderMenu"
        label="菜单排序"
        outline
        color="grey"
        @click="orderMenu = true"
      ></q-btn>
      <q-btn
        v-else
        label="完成"
        outline
        color="grey"
        @click="orderMenu = false"
      ></q-btn>
      <q-btn
        label="删除"
        flat
        color="secondary"
        @click="onDeletePublishedMenu"
      ></q-btn>
      <q-space />
      <q-btn label="存储快照" color="secondary" flat @click="saveMenu"></q-btn>
      <q-btn
        label="发布到线上"
        color="primary"
        unelevated
        @click="publishMenu"
      ></q-btn>
    </div>

    <q-dialog v-model="showSnapshotList" full-width>
      <q-card class="q-mt-lg">
        <q-card-section class="text-h4 row">
          菜单快照
          <q-space />
          <q-btn flat icon="r_close" color="negative" v-close-popup></q-btn>
        </q-card-section>
        <q-card-section class="q-pa-none">
          <q-table
            bordered
            class="no-box-shadow"
            :rows="customMenuList"
            :columns="customMenuColumns"
          >
            <template #body-cell-content="props">
              <q-td>
                <json-viewer :value="props.value"></json-viewer>
              </q-td>
            </template>
            <template v-slot:body-cell-actions="props">
              <q-td class="text-center q-gutter-sm">
                <q-btn
                  unelevated
                  dense
                  color="primary"
                  @click="onChoose(props.row.content)"
                  >选择快照
                </q-btn>
                <q-btn
                  flat
                  dense
                  color="secondary"
                  @click="onDeleteCustomMenu(props.row.id)"
                  >删除
                </q-btn>
              </q-td>
            </template>
          </q-table>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="showMediaDialog" full-width>
      <q-card>
        <q-card-section class="text-h6 flex flex-center">
          <div>选择素材/文章</div>
          <q-space />
          <q-btn flat icon="r_close" color="negative" v-close-popup></q-btn>
        </q-card-section>
        <q-card-section class="row q-col-gutter-sm" style="min-height: 600px">
          <div v-if="mediaList[type].length === 0" class="col-12 text-center">
            暂无内容
          </div>
          <div v-if="type === 'article'" class="col-12 col q-gutter-sm">
            <q-card
              v-for="(media, key) in mediaList[type]"
              :key="key"
              class="row cursor-pointer bg-grey-3"
              :class="media.article_id === chosenMediaId ? 'border-chosen' : ''"
              @click="
                chosenMediaId = media.article_id;
                chosenMediaContentItems = media.content.news_item;
              "
            >
              <q-card-section>
                <q-img
                  width="80px"
                  :src="media.content.news_item[0].thumb_url"
                  :alt="media.content.news_item[0].title"
                  :ratio="1"
                ></q-img>
              </q-card-section>
              <q-card-section class="col">
                <div class="text-subtitle1">
                  {{ media.content.news_item[0].title }}
                </div>
                <div class="text-caption q-py-sm">
                  {{ media.content.news_item[0].digest }}
                </div>
                <div class="text-caption text-grey">
                  更新于：{{ formatDate(media.content.update_time) }}
                </div>
              </q-card-section>
            </q-card>
          </div>
          <div v-else class="col-12 row q-col-gutter-md">
            <view
              class="col-xs-3 col-sm-4 col-md-2 col-lg-1"
              v-for="(media, key) in mediaList[type]"
              :key="key"
            >
              <view
                class="flex flex-center q-pa-xs cursor-pointer relative-position"
                :class="media.media_id === chosenMediaId ? 'border-chosen' : ''"
              >
                <q-img
                  :src="media.url"
                  :alt="media.name"
                  class="cursor-pointer"
                  @click="chosenMediaId = media.media_id"
                ></q-img>
                <view class="absolute-bottom text-white bg-grey text-body2">
                  {{ media.name }}
                </view>
              </view>
            </view>
          </div>
        </q-card-section>
        <div class="row">
          <q-space />
          <q-pagination
            v-model="page[type]"
            :max="Math.ceil(totalCount[type] / pageSize)"
            input
            @update:model-value="getMediaList(type)"
          />
        </div>
        <q-separator />
        <q-card-section class="flex flex-center q-gutter-md">
          <q-btn
            unelevated
            color="primary"
            :disable="!chosenMediaId"
            label="确定"
            @click="onConfirmClick"
          ></q-btn>
          <q-btn
            unelevated
            label="取消"
            @click="showMediaDialog = false"
          ></q-btn>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import JsonViewer from "vue-json-viewer";
import draggable from "vuedraggable";
import {
  createMenu,
  publishMenu,
  getPublishedMenu,
  getMenus,
  deleteMenu,
  deletePublishedMenu,
  getArticles,
  getMaterials,
} from "src/api/authorizer-official-account.js";
import { getLengthOfStr } from "src/utils";
import { date } from "quasar";
export default {
  name: "CustomMenu",
  components: {
    draggable,
    JsonViewer,
  },
  data: () => ({
    officialAccountName: "",
    loading: true,
    menuId: 0,
    activeMenuIndex: null,
    activeSubMenuIndex: null,
    orderMenu: false,
    menuItems: [],
    menuTypes: [
      {
        text: "已发布文章",
        value: "article_id",
      },
      {
        text: "链接",
        value: "view",
      },
      {
        text: "点击事件",
        value: "click",
      },
      {
        text: "公众号素材",
        value: "media_id",
      },
      // {
      //   text: "微信扫码",
      //   value: "scancode_push",
      // },
      // {
      //   text: "自定义扫码",
      //   value: "scancode_waitmsg",
      // },
      {
        text: "小程序",
        value: "miniprogram",
      },
      // {
      //   text: "素材",
      //   value: "media_id",
      // },
      // {
      //   text: "图文素材",
      //   value: "view_limited",
      // },
    ],
    valid: false,
    formData: null,
    formRules: {
      menuRules: [
        (v) => !!v || "菜单名必填",
        (v) => getLengthOfStr(v) <= 8 || "字符不能多于4个中文或8个英文字母",
      ],
      subMenuRules: [
        (v) => !!v || "菜单名必填",
        (v) => getLengthOfStr(v) <= 16 || "字符不能多于8个中文或16个英文字母",
      ],
      requireRules: [(v) => !!v || "该信息必填"],
    },
    currentActiveMenu: null,
    showSnapshotList: false,
    customMenuList: [],
    customMenuColumns: [
      {
        label: "id",
        field: "id",
        align: "center",
      },
      {
        label: "内容",
        field: "content",
        name: "content",
        align: "center",
      },
      {
        label: "备注",
        field: "remark",
        align: "center",
      },
      {
        label: "时间",
        field: "created_at",
        align: "center",
      },
      {
        label: "操作",
        field: "actions",
        name: "actions",
        align: "center",
      },
    ],
    showMediaDialog: false,
    mediaList: {
      image: [],
      voice: [],
      video: [],
      article: [],
    },
    type: "article",
    page: {
      image: 1,
      voice: 1,
      video: 1,
      article: 1,
    },
    totalCount: {
      image: 0,
      voice: 0,
      video: 0,
      article: 0,
    },
    pageSize: 24,
    chosenMediaId: null,
    chosenMediaContentItems: [],
  }),
  beforeMount() {
    this.officialAccountName = this.$store.state.basicInfo.nickname;
    this.loadingMenu();
    // this.loadCustomMenuList();
  },
  methods: {
    loadCustomMenuList() {
      this.showSnapshotList = true;
      getMenus(this.opId, this.appId).then((res) => {
        this.customMenuList = res;
      });
    },
    getMediaList(type) {
      if (type === "article") {
        getArticles(this.opId, this.appId, {
          offset: (this.page[type] - 1) * this.pageSize,
          count: this.pageSize,
        }).then((res) => {
          this.mediaList[type] = res.item;
          this.totalCount[type] = res.total_count;
        });
      } else {
        getMaterials(this.opId, this.appId, {
          type: type,
          offset: (this.page[type] - 1) * this.pageSize,
          count: this.pageSize,
        }).then((res) => {
          this.mediaList[type] = res.item;
          this.totalCount[type] = res.total_count;
        });
      }
    },
    onConfirmClick() {
      if (this.type === "article") {
        this.currentActiveMenu.article_id = this.chosenMediaId;
      } else {
        this.currentActiveMenu.media_id = this.chosenMediaId;
      }
      this.showMediaDialog = false;
    },
    onChoose(content) {
      this.showSnapshotList = false;
      this.menuItems = content;
    },
    loadingMenu() {
      getPublishedMenu(this.opId, this.appId).then((response) => {
        this.loading = false;
        this.menuItems = response.menu.button;
      });
    },
    onOrderMenuClick(key) {
      this.menuItems.forEach((value, index) => {
        if (index !== key) {
          this.$refs[`orderMenu${index}`].hide();
        }
      });
    },
    onMenuClick(key) {
      this.activeSubMenuIndex = null;
      this.menuItems.forEach((value, index) => {
        if (index !== key) {
          this.$refs[`primaryMenu${index}`][0].hide();
        }
      });
      if (key === "add") {
        this.menuItems.push({
          name: "新菜单",
          type: "",
          level: 1,
          sub_button: [],
        });
        key = this.menuItems.length - 1;
      }
      this.activeMenuIndex = key;
      this.currentActiveMenu = {};
      this.currentActiveMenu = this.menuItems[key];
      this.currentActiveMenu.level = 1;
    },
    onSubMenuClick(index) {
      const key = this.activeMenuIndex;
      for (let k in this.menuItems[key]) {
        if (k !== "name" && k !== "sub_button") {
          delete this.menuItems[key][k];
        }
      }
      let subButton = this.menuItems[key].sub_button;
      if (index === "add") {
        this.menuItems[key].sub_button.push({
          name: "增加的",
          type: "",
          level: 2,
        });
        this.$refs[`primaryMenu${key}`][0].hide();
        this.$refs[`primaryMenu${key}`][0].show();
        index = this.menuItems[key].sub_button.length - 1;
      }
      this.activeSubMenuIndex = index;
      this.currentActiveMenu = {};
      this.currentActiveMenu = subButton[index];
      this.currentActiveMenu.level = 2;
    },
    saveMenu() {
      this.$q
        .dialog({
          title: "请输入备注",
          message: "您可以输入一个备注信息，方便后续查找本次存储的菜单快照。",
          prompt: {
            model: "",
            type: "text", // optional
          },
          cancel: {
            flat: true,
            color: "grey",
          },
        })
        .onOk((data) => {
          if (this.validateArray(this.menuItems) !== true) {
            this.$q.dialog({
              title: "菜单格式有误",
              message: "菜单选项不能为空，请检查菜单格式",
            });
            return;
          }
          this.formData = this.formatArray(this.menuItems);
          let params = { content: this.formData };
          params.remark = data;
          createMenu(this.opId, this.appId, params).then(() => {
            this.$q.notify("创建成功");
          });
        });
    },
    publishMenu() {
      if (this.validateArray(this.menuItems) !== true) {
        this.$q.dialog({
          title: "菜单格式有误",
          message: "菜单选项不能为空，请检查菜单格式",
        });
        return;
      }
      this.formData = this.formatArray(this.menuItems);
      let params = { content: this.formData };
      publishMenu(this.opId, this.appId, params).then(() => {
        this.$q.notify("发布成功");
      });
    },
    onDeletePublishedMenu() {
      this.$q
        .dialog({
          title: "警告操作",
          message: "删除后公众号用户将无法看到自定义菜单，确定删除吗？",
          cancel: {
            color: "grey",
            flat: true,
          },
        })
        .onOk(() => {
          deletePublishedMenu(this.opId, this.appId).then(() => {
            this.$q.notify("删除成功");
          });
        });
    },
    onDeleteCustomMenu(id) {
      this.$q
        .dialog({
          title: "是否确定删除",
          message:
            "快照删除不影响线上已发布的公众号菜单，但快照本身删除后无法撤销和找回，确定删除吗？",
          cancel: {
            color: "grey",
            flat: true,
          },
        })
        .onOk(() => {
          deleteMenu(this.opId, this.appId, id).then(() => {
            this.$q.notify("删除成功");
            this.loadCustomMenuList();
          });
        });
    },
    onDeleteMenu() {
      if (this.activeMenuIndex !== null || this.activeSubMenuIndex !== null) {
        this.$q
          .dialog({
            title: "是否确定删除",
            message:
              "一旦删除菜单，菜单和对应子菜单将删除，无法撤销和找回，确定删除吗？",
            cancel: {
              color: "grey",
              flat: true,
            },
          })
          .onOk(() => {
            this.currentActiveMenu = null;
            if (this.activeSubMenuIndex !== null) {
              this.menuItems[this.activeMenuIndex].sub_button.splice(
                this.activeSubMenuIndex,
                1
              );
              this.$refs[`primaryMenu${this.activeMenuIndex}`][0].hide();
              this.$nextTick(() => {
                this.$refs[`primaryMenu${this.activeMenuIndex}`][0].show();
              });
            } else {
              delete this.menuItems.splice(this.activeMenuIndex, 1);
            }
          });
      }
    },
    formatArray(arr) {
      arr = arr.map((obj) => {
        if (obj.level) {
          delete obj.level;
        }
        if (obj.sub_button) {
          obj.sub_button = this.formatArray(obj.sub_button);
        }
        return obj;
      });
      return arr;
    },
    validateArray(arr, level = 1) {
      for (let index in arr) {
        let obj = arr[index];
        if (level === 1) {
          this.activeMenuIndex = Number(index);
        } else {
          this.activeSubMenuIndex = Number(index);
        }
        for (let key in obj) {
          if (
            obj[key] === undefined ||
            obj[key] === null ||
            (key !== "sub_button" && obj[key].length === 0) ||
            obj[key] === ""
          ) {
            if (level === 1) {
              this.activeSubMenuIndex = null;
              this.currentActiveMenu = this.menuItems[this.activeMenuIndex];
            } else {
              this.currentActiveMenu =
                this.menuItems[this.activeMenuIndex].sub_button[index];
            }
            this.$refs[`primaryMenu${this.activeMenuIndex}`][0].show();
            this.currentActiveMenu.level = level;
            return false;
          }
        }
        if (obj.sub_button) {
          let res = this.validateArray(obj.sub_button, 2);
          if (res !== true) {
            return false;
          }
        }
        if (level === 1) {
          this.$refs[`primaryMenu${Number(index)}`][0].hide();
        }
      }
      return true;
    },
    formatDate(timestamp) {
      return date.formatDate(timestamp * 1000, "YYYY-MM-DD HH:mm:ss");
    },
  },
};
</script>

<style lang="scss" scoped>
.border-chosen {
  border: 1px solid $primary;
  box-sizing: border-box;
}
.item-border-left {
  border-left: 1px solid #e7e7eb;
  box-sizing: border-box;
}
.item-bordered {
  border: 1px solid rgba(0, 0, 0, 0.12);
  border-bottom: none;
}
.item-active {
  background-color: white;
  color: $primary;
  border: 1px solid $primary !important;
  box-sizing: border-box;
}
.mobile_menu_preview {
  width: 294px;
  background-size: contain !important;
  position: relative;
  height: 580px;
  background: url("src/assets/bg_mobile_head.png") no-repeat 0 0;
  border: 1px solid #e7e7eb;

  .mobile_hd {
    color: #fff;
    text-align: center;
    padding-top: 30px;
    font-size: 15px;
    width: auto;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    word-wrap: normal;
    margin: 0 30px;
  }
}
</style>
