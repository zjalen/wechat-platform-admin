import { api } from 'boot/axios'

export function login (params) {
  return api.post('login', params)
}
export function getPlatforms (params) {
  return api.get('platforms')
}
