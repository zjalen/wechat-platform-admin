export const mixinOfficialAccountParams = {
  beforeCreate() {
    const oaId = this.$route.params.oaId;
    this.$store.commit("setCurrentOaId", oaId);
  },
};
