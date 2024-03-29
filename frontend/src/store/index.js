import { store } from "quasar/wrappers";
import { createStore } from "vuex";

import { Loading, LocalStorage, Notify } from "quasar";
import { getAuthorizer } from "src/api/open-platform";
import { getBasicInfo } from "src/api/authorizer-mini-program";
import { getPlatform } from "src/api";
import { getOfficialAccountInfo } from "src/api/official-account.js";

const saveToken = LocalStorage.getItem("token");

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Store instance.
 */

export default store((/* { ssrContext } */) => {
  return createStore({
    // enable strict mode (adds overhead!)
    // for dev mode and --debug builds only
    strict: process.env.DEBUGGING,
    state: () => ({
      token: saveToken === "null" ? null : saveToken,
      userInfo: null,
      currentOpId: null,
      currentAppId: null,
      currentOaId: null,
      currentMpId: null,
      currentSubAppId: null,
      currentAuthorizerInfo: {},
      currentPlatformInfo: {},
      redirectPath: null,
      basicInfo: {
        appid: "",
        account_type: 0,
        principal_type: 0,
        principal_name: "",
        realname_status: 0,
        wx_verify_info: {
          qualification_verify: false,
          naming_verify: false,
        },
        signature_info: {
          signature: "",
          modify_used_count: 0,
          modify_quota: 0,
        },
        head_image_info: {
          head_image_url: null,
        },
        nickname: "",
        registered_country: 0,
        nickname_info: {
          nickname: "",
          modify_used_count: 0,
          modify_quota: 0,
        },
        credential: "",
      },
    }),
    getters: {
      getToken(state) {
        return state.token;
      },
    },
    mutations: {
      setCurrentOpId(state, opId) {
        state.currentOpId = opId;
      },
      setCurrentMpId(state, mpId) {
        state.currentMpId = mpId;
      },
      setCurrentOaId(state, oaId) {
        state.currentOaId = oaId;
      },
      setCurrentAppId(state, appId) {
        state.currentAppId = appId;
      },
      setToken(state, token) {
        state.token = token;
      },
      setAuthorizerInfo(state, info) {
        state.currentAuthorizerInfo = info;
      },
      setBasicInfo(state, info) {
        state.basicInfo = info;
      },
      setCurrentPlatformInfo(state, info) {
        state.currentPlatformInfo = info;
      },
      setRedirectPath(state, path) {
        state.redirectPath = path;
      },
    },
    actions: {
      setToken({ commit }, token) {
        commit("setToken", token);
        LocalStorage.set("token", token);
      },
      loadCurrentPlatformInfo({ commit }, id) {
        getPlatform(id).then((res) => {
          commit("setCurrentPlatformInfo", res);
        });
      },
      loadAuthorizer({ commit }, { opId, appId }) {
        Loading.show();
        const timer = setTimeout(() => {
          Loading.hide();
          Notify.create({
            color: "negative",
            message: "加载平台基本信息失败",
          });
        }, 5000);
        getAuthorizer(opId, appId)
          .then((res) => {
            // getAuthorizer(opId, appId, {getCache: true}).then(res => {
            clearTimeout(timer);
            Loading.hide();
            commit("setAuthorizerInfo", res.authorizer_info);
          })
          .catch(() => {
            Loading.hide();
            clearTimeout(timer);
          });
      },
      loadSubBasicInfo({ commit }, { opId, appId }) {
        Loading.show();
        const timer = setTimeout(() => {
          Loading.hide();
          Notify.create({
            color: "negative",
            message: "加载平台基本信息失败",
          });
        }, 5000);
        getBasicInfo(opId, appId)
          .then((res) => {
            clearTimeout(timer);
            Loading.hide();
            commit("setBasicInfo", res);
          })
          .catch(() => {
            Loading.hide();
            clearTimeout(timer);
          });
      },
      loadOfficialAccountBasicInfo({ commit }, { id }) {
        Loading.show();
        const timer = setTimeout(() => {
          Loading.hide();
          Notify.create({
            color: "negative",
            message: "加载平台基本信息失败",
          });
        }, 5000);
        getOfficialAccountInfo(id)
          .then((res) => {
            clearTimeout(timer);
            Loading.hide();
            commit("setBasicInfo", res);
          })
          .catch(() => {
            Loading.hide();
            clearTimeout(timer);
          });
      },
    },
  });
});
