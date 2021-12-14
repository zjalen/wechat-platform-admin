import { LocalStorage } from 'quasar'

const saveToken = LocalStorage.getItem('token')

export default function () {
  return {
    token: saveToken,
    userInfo: null
  }
}
