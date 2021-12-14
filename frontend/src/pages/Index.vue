<template>
  <q-page class="flex flex-center">
    <img
      alt="Quasar logo"
      src="~assets/quasar-logo-vertical.svg"
      style="width: 200px; height: 200px"
    >
  </q-page>
</template>

<script>
import { defineComponent } from 'vue';
import { getPlatforms } from 'src/api'

export default defineComponent({
  name: 'PageIndex',
  data: () => ({
    platforms: []
  }),
  beforeMount () {
    this.initData()
  },
  methods: {
    initData () {
      getPlatforms().then(res => {
        this.platforms = res.data
      })
    }
  }
})
</script>
