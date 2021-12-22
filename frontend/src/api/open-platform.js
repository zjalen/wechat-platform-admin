import { api } from 'boot/axios'

export function getSecretConfig (id) {
  return api.get('open-platform/' + id + '/secret-config')
}

export function getSubPlatforms (platformId, params) {
  return api.get('open-platform/' + platformId + '/sub-platforms', { params })
}

export function getSubPlatform (platformId, id) {
  return api.get('open-platform/' + platformId + '/sub-platforms/' + id)
}

export function saveSubPlatform (platformId, params) {
  return api.post('open-platform/' + platformId + '/sub-platforms', params)
}

export function deleteSubPlatform (platformId, id) {
  return api.delete('open-platform/' + platformId + '/sub-platforms/' + id)
}

export function getAuthorizers (id, params) {
  return api.get('open-platform/' + id + '/authorizers', params)
}

export function getAuthorizer (id, appId, query = null) {
  return api.get('open-platform/' + id + '/authorizers/' + appId, { params: query })
}
