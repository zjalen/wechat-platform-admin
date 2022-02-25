import { api } from "boot/axios";

export function getSecretConfig(id) {
  return api.get("open-platform/" + id + "/secret-config");
}

export function getLocalAuthorizer(platformId, params) {
  return api.get("open-platform/" + platformId + "/local-authorizers", {
    params,
  });
}

export function saveLocalAuthorizer(platformId, params) {
  return api.post("open-platform/" + platformId + "/local-authorizers", params);
}

export function deleteLocalAuthorizer(platformId, id) {
  return api.delete("open-platform/" + platformId + "/local-authorizers/" + id);
}

export function getAuthorizers(id, params) {
  return api.get("open-platform/" + id + "/authorizers", params);
}

export function getAuthorizer(id, appId, query = null) {
  return api.get("open-platform/" + id + "/authorizers/" + appId, {
    params: query,
  });
}

export function createBetaMiniProgram(id, data) {
  return api.post("open-platform/" + id + "/beta-mini-program", data);
}

export function getServerDomain(id) {
  return api.get("open-platform/" + id + "/wxa-server-domain");
}

export function setServerDomain(id, data) {
  return api.put("open-platform/" + id + "/wxa-server-domain/set", data);
}

export function getWebDomain(id) {
  return api.get("open-platform/" + id + "/wxa-jump-domain");
}

export function setWebDomain(id, data) {
  return api.put("open-platform/" + id + "/wxa-jump-domain/set", data);
}

export function getWebDomainCheckFile(id) {
  return api.get("open-platform/" + id + "/domain-confirm-file");
}

export function getCode(id, params) {
  return api.get("open-platform/" + id + "/code", { params });
}
export function setCodeTemplate(id, data) {
  return api.put("open-platform/" + id + "/code/template", data);
}

export function deleteCodeTemplate(id, templateId) {
  return api.delete("open-platform/" + id + "/code/" + templateId);
}
