<template>
  <q-card style="width: 80vw">
    <q-card-section class="row text-subtitle1">
      {{ title }}
      <q-space />
      <q-btn flat color="negative" icon="r_close" v-close-popup></q-btn>
    </q-card-section>
    <q-separator />
    <q-card-section>
      <json-viewer :value="data"></json-viewer>
    </q-card-section>
  </q-card>
</template>

<script>
import JsonViewer from "vue-json-viewer";

export default {
  name: "JsonCard",
  components: { JsonViewer },
  props: {
    title: { type: String, default: () => "结果展示" },
    data: Object,
  },
};
</script>

<style scoped></style>
