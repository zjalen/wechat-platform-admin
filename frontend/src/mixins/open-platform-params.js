export const mixinOpenPlatformParams = {
  beforeCreate() {
    const opId = this.$route.params.opId;
    const appId = this.$route.params.appId;
    this.$store.commit("setCurrentOpId", opId);
    this.$store.commit("setCurrentAppId", appId);
  },
};
