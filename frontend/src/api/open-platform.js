import { api } from 'boot/axios'

export function getSecretConfig (id) {
  return api.get('open-platform/' +id+ '/secret-config')
}
export function getAuthorizers (id, params) {
  return api.get('open-platform/' +id+ '/authorizers', params)
}

export function getAuthorizer (id, appId) {
  return api.get('open-platform/' +id+ '/authorizers/' + appId)
}
