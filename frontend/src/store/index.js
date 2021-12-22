import { store } from 'quasar/wrappers'
import { createStore } from 'vuex'

// import index from './module-index'
import { Loading, LocalStorage, Notify } from 'quasar'
import { getAuthorizer } from 'src/api/open-platform'

const saveToken = LocalStorage.getItem('token')

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Store instance.
 */

export default store((/* { ssrContext } */) => {
  return createStore({
    // modules: {
    //   index
    // },

    // enable strict mode (adds overhead!)
    // for dev mode and --debug builds only
    strict: process.env.DEBUGGING,
    state: () => ({
      token: saveToken,
      userInfo: null,
      currentOpId: null,
      currentSubAppId:null,
      currentAuthorizerInfo: {},
    }),
    getters: {
      getToken (state) {
        return state.token
      }
    },
    mutations: {
      setToken (state, token) {
        state.token = token
      },
      setAuthorizerInfo (state, info) {
        state.currentAuthorizerInfo = info
      }
    },
    actions: {
      setToken ({commit}, token) {
        commit('setToken', token)
        LocalStorage.set('token', token)
      },
      loadAuthorizer ({commit}, {opId, appId}) {
        Loading.show()
        const timer = setTimeout(() => {
          Loading.hide()
          Notify.create({
            color: 'negative',
            message: '加载平台基本信息失败'
          })
        }, 5000)
        getAuthorizer(opId, appId, {getCache: true}).then(res => {
          clearTimeout(timer)
          Loading.hide()
          commit('setAuthorizerInfo', res.authorizer_info)
        }).catch(() => {
          Loading.hide()
          clearTimeout(timer)
        })
      }
    }
  })
})
