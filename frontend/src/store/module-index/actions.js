// 保存变量到本地存储中
import { LocalStorage } from "quasar";

export function setToken({ commit }, token) {
  commit("setToken", token);
  LocalStorage.set("token", token);
}
