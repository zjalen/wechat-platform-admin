<template>
  <q-page class="q-pa-lg">
    <q-card>
      <q-card-section>
        <q-list>
          <q-item>
            <q-item-section thumbnail>
              <q-item-label>服务类目</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label lines="1"
                >{{ category.first }} >
                {{ category.second }}
              </q-item-label>
            </q-item-section>

            <q-item-section side>
              <q-btn flat dense label="修改" color="secondary"></q-btn>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
export default {
  name: "MpCategory",
  data: () => ({
    category: {},
  }),
};
</script>

<style scoped></style>
