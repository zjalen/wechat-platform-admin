<template>
  <div
    class="fullscreen bg-primary text-white text-center q-pa-md flex flex-center"
  >
    <div>
      <div style="font-size: 10vh; opacity: 0.4">401</div>

      <div class="text-h2" style="opacity: 0.8">登录失效，请重新登录</div>

      <q-btn
        class="q-mt-xl"
        color="white"
        text-color="primary"
        unelevated
        to="/login"
        icon="r_lock"
        label="去登录"
      />
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "PageErrorNoAuth",
});
</script>
