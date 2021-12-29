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
