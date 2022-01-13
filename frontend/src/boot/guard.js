import { Notify } from "quasar";

// "async" is optional;
// more info on params: https://quasar.dev/quasar-cli/cli-documentation/boot-files#Anatomy-of-a-boot-file
export default async ({ router, store }) => {
  // something to do

  router.beforeEach((to, from, next) => {
    if (to.name !== "login" && to.name !== "404") {
      // 权限验证
      const token = store.getters["getToken"];
      if (token && token !== "null") {
        next();
      } else {
        Notify.create({
          message: "您需要先登录才能继续访问",
          color: "negative",
        });
        store.commit("setRedirectPath", to.path);
        next({ name: "login" });
      }
    } else {
      next();
    }
  });
};
