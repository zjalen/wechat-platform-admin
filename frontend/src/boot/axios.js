import { boot } from "quasar/wrappers";
import axios from "axios";
import { Dialog, Notify } from "quasar";

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)

const api = axios.create({
  baseURL: "api",
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 10000, // request timeout
});

export default boot(({ app, router, store }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api

  // request interceptor
  api.interceptors.request.use(
    (config) => {
      // do something before request is sent
      if (store.getters["getToken"]) {
        config.headers["Authorization"] = "Bearer " + store.getters["getToken"];
      }
      return config;
    },
    (error) => {
      // do something with request error
      console.log(error); // for debug
      return Promise.reject(error);
    }
  );

  // response interceptor
  api.interceptors.response.use(
    (response) => {
      if (Object.prototype.hasOwnProperty.call(response.data, "errcode")) {
        if (response.data.errcode !== 0) {
          Notify.create({
            color: "negative",
            message: response.data.errmsg,
            timeout: 5000,
          });
          return Promise.reject(response.data.data);
        }
      }
      return response.data.data;
    },
    (error) => {
      let message = Object.prototype.hasOwnProperty.call(
        error.response.data,
        "errMsg"
      )
        ? error.response.data.errMsg
        : error.response.statusText;
      if (error.response.status === 401) {
        message = "身份验证失败，请重新登录";
        Dialog.create({
          title: "提示",
          class: "negative",
          message: message,
          persistent: true,
        }).onOk(() => {
          store.dispatch("setToken", null);
          router.replace({ path: "/login" });
        });
      }
      Notify.create({
        color: "negative",
        message: message,
        timeout: 5000,
      });

      return Promise.reject(error.response.data);
    }
  );

  app.config.globalProperties.$axios = axios;
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api;
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
});

export { api };
