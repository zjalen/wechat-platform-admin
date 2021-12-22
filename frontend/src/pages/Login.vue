<template>
  <q-page class="flex flex-center">
    <q-card class="login-frame" bordered>
      <q-card-section class="text-h6">登录</q-card-section>
      <q-card-section>
        <q-form
          @submit="onSubmit"
        >
          <q-input
            v-model="formData.email"
            label="输入你的邮箱"
            lazy-rules
            :rules="[ val => val && val.length > 0 || '邮箱不能为空']"
          />

          <q-input
            v-model="formData.password"
            label="输入你的密码"
            type="password"
            lazy-rules
            :rules="[ val => val && val.length > 0 || '密码不能为空']"
          />

          <div>
            <q-btn class="btn-login" label="登录" type="submit" unelevated color="primary" />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { login } from 'src/api'

export default {
  name: 'Login',
  data: () => ({
    formData: {
      email: '',
      password: '',
    }
  }),
  methods: {
    onSubmit () {
      login(this.formData).then(res => {
        const token = res.data
        this.$store.dispatch('setToken', token)
        this.$router.replace('/')
      })
    },
  },
}
</script>

<style scoped lang="scss">
.login-frame {
  width: 340px;
  box-shadow: $shadow-0;

  &:hover {
    box-shadow: $shadow-10;
    border: 0;
  }
}

.btn-login {
  width: 120px;
}
</style>
