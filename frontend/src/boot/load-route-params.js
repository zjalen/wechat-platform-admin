import { boot } from "quasar/wrappers";

// "async" is optional;
// more info on params: https://v2.quasar.dev/quasar-cli/boot-files
export default boot(async ({ app }) => {
  // something to do
  const loadRouteParams = {
    data: () => ({
      opId: null,
      appId: null,
    }),
    created() {
      this.opId = this.$route.params.opId;
      this.appId = this.$route.params.appId;
    },
  };
  app.mixin(loadRouteParams);
});
