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

export function getLocalMediaList(id, appId, params) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/local-media", {
    params,
  });
}

export function uploadTemplateFile(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/upload-template-media",
    data
  );
}

export function deleteLocalMedia(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/delete-local-media",
    data
  );
}

export function checkNickName(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/check-nick-name",
    data
  );
}

export function getNicknameAuditStatus(id, appId, auditId) {
  return api.get(
    "open-platform/" +
      id +
      "/mp/" +
      appId +
      "/nick-name-audit-status/" +
      auditId
  );
}

export function setNickName(id, appId, data) {
  return api.put("open-platform/" + id + "/mp/" + appId + "/nick-name", data);
}

export function setAvatar(id, appId, data) {
  return api.put("open-platform/" + id + "/mp/" + appId + "/avatar", data);
}

export function setSignature(id, appId, data) {
  return api.put("open-platform/" + id + "/mp/" + appId + "/signature", data);
}

export function getServerDomain(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/get-server-domain");
}

export function addServerDomain(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/add-server-domain",
    data
  );
}

export function setServerDomain(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/set-server-domain",
    data
  );
}

export function deleteServerDomain(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/delete-server-domain",
    data
  );
}

export function getWebDomain(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/get-web-domain");
}

export function addWebDomain(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/add-web-domain",
    data
  );
}

export function syncWebDomain(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/sync-web-domain",
    data
  );
}

export function setWebDomain(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/set-web-domain",
    data
  );
}

export function deleteWebDomain(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/delete-web-domain",
    data
  );
}

export function codeCommit(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/code-commit",
    data
  );
}

export function getCodePages(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/code-pages");
}

export function getCodeTestQr(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/code-test-qr", {
    responseType: "blob",
  });
}

export function codeAudit(id, appId, data) {
  return api.post("open-platform/" + id + "/mp/" + appId + "/code-audit", data);
}

export function uploadCodeAuditMedia(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/upload-code-audit-media",
    data
  );
}

export function withdrawCodeAudit(id, appId) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/code-audit-withdraw"
  );
}

export function getCodeAuditLatestStatus(id, appId) {
  return api.get(
    "open-platform/" + id + "/mp/" + appId + "/code-audit-latest-status"
  );
}

export function getCodeAuditStatus(id, appId, params) {
  return api.get(
    "open-platform/" + id + "/mp/" + appId + "/code-audit-status",
    { params }
  );
}

export function codeRelease(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/code-release",
    data
  );
}

export function codeRollbackRelease(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/code-rollback-release",
    data
  );
}

export function getCodeReleaseHistories(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/code-release-histories",
    data
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

export function getAllCategories(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/all-categories");
}

export function getCategoriesByType(id, appId, params) {
  return api.get(
    "open-platform/" + id + "/mp/" + appId + "/categories-by-type",
    { params }
  );
}

export function getCategory(id, appId) {
  return api.get("open-platform/" + id + "/mp/" + appId + "/category");
}

export function addCategory(id, appId, data) {
  return api.post("open-platform/" + id + "/mp/" + appId + "/category", data);
}

export function deleteCategory(id, appId, data) {
  return api.post(
    "open-platform/" + id + "/mp/" + appId + "/delete-category",
    data
  );
}
