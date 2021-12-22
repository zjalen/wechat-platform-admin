import { store } from 'quasar/wrappers'
import { createStore } from 'vuex'

// import index from './module-index'
import { LocalStorage } from 'quasar'

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
      currentOpenPlatformId: null,
      currentSubPlatformId: null,
    }),
    getters: {
      getToken (state) {
        return state.token
      }
    },
    mutations: {
      setToken (state, token) {
        state.token = token
      }
    },
    actions: {
      setToken ({commit}, token) {
        commit('setToken', token)
        LocalStorage.set('token', token)
      }
    }
  })
})
