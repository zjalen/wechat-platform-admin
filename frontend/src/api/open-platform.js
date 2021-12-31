import { api } from "boot/axios";

export function getSecretConfig(id) {
  return api.get("open-platform/" + id + "/secret-config");
}

export function getSubPlatforms(platformId, params) {
  return api.get("open-platform/" + platformId + "/sub-platforms", { params });
}

export function saveSubPlatform(platformId, params) {
  return api.post("open-platform/" + platformId + "/sub-platforms", params);
}

export function deleteSubPlatform(platformId, id) {
  return api.delete("open-platform/" + platformId + "/sub-platforms/" + id);
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
  return api.get("open-platform/" + id + "/get-server-domain");
}

export function deleteServerDomain(id, data) {
  return api.post("open-platform/" + id + "/delete-server-domain", data);
}

export function addServerDomain(id, data) {
  return api.post("open-platform/" + id + "/add-server-domain", data);
}

export function setServerDomain(id, data) {
  return api.post("open-platform/" + id + "/set-server-domain", data);
}

export function getWebDomain(id) {
  return api.get("open-platform/" + id + "/get-web-domain");
}

export function deleteWebDomain(id, data) {
  return api.post("open-platform/" + id + "/delete-web-domain", data);
}

export function addWebDomain(id, data) {
  return api.post("open-platform/" + id + "/add-web-domain", data);
}

export function setWebDomain(id, data) {
  return api.post("open-platform/" + id + "/set-web-domain", data);
}

export function getWebDomainCheckFile(id) {
  return api.post("open-platform/" + id + "/get-domain-confirm-file");
}

export function getCodeDrafts(id) {
  return api.get("open-platform/" + id + "/code-drafts");
}

export function getCodeTemplate(id, params) {
  return api.get("open-platform/" + id + "/code-template", { params });
}

export function addCodeTemplate(id, data) {
  return api.post("open-platform/" + id + "/add-template", data);
}

export function deleteCodeTemplate(id, data) {
  return api.post("open-platform/" + id + "/delete-template", data);
}
