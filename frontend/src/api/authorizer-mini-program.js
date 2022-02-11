import { api } from "boot/axios";

export function getBasicInfo(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/basic-info");
}

export function getTesters(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/testers");
}

export function bindTester(id, appId, data) {
  return api.post("open-platform/" + id + "/mp/" + appId + "/testers", data);
}

export function unBindTester(id, appId, userSlug, params = null) {
  return api.delete(
    "open-platform/" + id + "/mp/" + appId + "/testers/" + userSlug,
    { params }
  );
}

export function uploadTemplateFile(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/upload-template-media",
    data
  );
}

export function checkNickName(id, appId, name) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/nick-name/" + name);
}

export function getNicknameAuditStatus(id, appId, auditId) {
  return api.get(
    "open-platform/" +
      id +
      "/mp/" +
      appId +
      "/nick-name/audit-status/" +
      auditId
  );
}

export function setNickName(id, appId, data) {
  return api.put(
    "open-platform/" + id + "/mp/" + appId + "/nick-name/update",
    data
  );
}

export function setAvatar(id, appId, data) {
  return api.put(
    "open-platform/" + id + "/mp/" + appId + "/avatar/update",
    data
  );
}

export function setSignature(id, appId, data) {
  return api.put(
    "open-platform/" + id + "/mp/" + appId + "/signature/update",
    data
  );
}

export function getServerDomain(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/server-domain");
}

export function addServerDomain(id, appId, data) {
  return api.put(
    "open-platform/" + id + "/mp/" + appId + "/server-domain/add",
    data
  );
}

export function setServerDomain(id, appId, data) {
  return api.put(
    "open-platform/" + id + "/mp/" + appId + "/server-domain/set",
    data
  );
}

export function deleteServerDomain(id, appId, data) {
  return api.put(
    "open-platform/" + id + "/mp/" + appId + "/server-domain/delete",
    data
  );
}

export function getWebDomain(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/webview-domain");
}

export function addWebDomain(id, appId, data) {
  return api.put(
    "open-platform/" + id + "/mp/" + appId + "/webview-domain/add",
    data
  );
}

export function syncWebDomain(id, appId, data) {
  return api.put(
    "open-platform/" + id + "/mp/" + appId + "/webview-domain/sync",
    data
  );
}

export function setWebDomain(id, appId, data) {
  return api.put(
    "open-platform/" + id + "/mp/" + appId + "/webview-domain/set",
    data
  );
}

export function deleteWebDomain(id, appId, data) {
  return api.put(
    "open-platform/" + id + "/mp/" + appId + "/webview-domain/delete",
    data
  );
}

export function codeCommit(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/code/commit",
    data
  );
}

export function getCodePages(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/code/pages");
}

export function getCodeTestQr(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/code/test-qr", {
    responseType: "blob",
  });
}

export function codeAudit(id, appId, data) {
  return api.post("open-platform/" + id + "/mp/" + appId + "/code/audit", data);
}

export function uploadCodeAuditMedia(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/code/upload-audit-media",
    data
  );
}

export function withdrawCodeAudit(id, appId) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/code/audit-withdraw"
  );
}

export function getCodeAuditStatus(id, appId, params) {
  return api.get(
    "open-platform/" + id + "/mp/" + appId + "/code/audit-status",
    { params }
  );
}

export function codeRelease(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/code/release",
    data
  );
}

export function codeRollbackRelease(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/code/revert",
    data
  );
}

export function getCodeReleaseHistories(id, appId, data) {
  return api.get(
    "open-platform/" + id + "/mp/" + appId + "/code/release-history-versions",
    data
  );
}

export function getGrayReleaseInfo(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/code/gray-release");
}

export function grayRelease(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/code/gray-release",
    data
  );
}

export function revertGrayRelease(id, appId) {
  return api.delete(
    "open-platform/" + id + "/mp/" + appId + "/code/gray-release"
  );
}

export function getPrivacySetting(id, appId, params) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/privacy-setting", {
    params,
  });
}

export function setPrivacySetting(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/privacy-setting",
    data
  );
}

export function getCategories(id, appId, params) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/categories", {
    params,
  });
}

export function getCategory(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/categories/show");
}

export function addCategory(id, appId, data) {
  return api.post("open-platform/" + id + "/mp/" + appId + "/categories", data);
}

export function deleteCategory(id, appId, data) {
  return api.put(
    "open-platform/" + id + "/mp/" + appId + "/categories/delete",
    data
  );
}
