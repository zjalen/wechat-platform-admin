import { api } from "boot/axios";

export function getOfficialAccountInfo(id) {
  return api.get("official-account/" + id);
}

/**
 * 更新自动回复开关状态
 * @param opId
 * @param params

 */
export function updateAutoReplyState(opId, params) {
  return api.put("official-account/" + opId, params);
}

/**
 * 查看菜单快照列表
 */
export function getMenus(opId) {
  return api.get("official-account/" + opId + "/custom-menu");
}

/**
 * 查看菜单快照详情
 */
export function getMenu(opId, id) {
  return api.get("official-account/" + opId + "/custom-menu/" + id);
}

/**
 * 存储菜单快照
 */
export function createMenu(opId, params) {
  return api.post("official-account/" + opId + "/custom-menu", params);
}

/**
 * 删除菜单快照
 */
export function deleteMenu(opId, id) {
  return api.delete("official-account/" + opId + "/custom-menu/" + id);
}

/**
 * 获取线上菜单信息
 */
export function getPublishedMenu(opId) {
  return api.get("official-account/" + opId + "/custom-menu/published/show");
}

/**
 * 获取线上菜单信息
 */
export function publishMenu(opId, params) {
  return api.post(
    "official-account/" + opId + "/custom-menu/published",
    params
  );
}

/**
 * 删除线上菜单
 * @param opId

 */
export function deletePublishedMenu(opId) {
  return api.delete(
    "official-account/" + opId + "/custom-menu/published/destroy"
  );
}

/**
 * 获取自动回复规则列表
 *
 * @param opId
 * @param params
 */
export function getAutoReplyRules(opId, params) {
  return api.get("official-account/" + opId + "/auto-reply-rules", { params });
}

/**
 * 获取自动回复规则列表
 *
 * @param opId
 * @param id
 */
export function getAutoReplyRule(opId, id) {
  return api.get("official-account/" + opId + "/auto-reply-rules/" + id);
}

/**
 * 创建自动回复规则
 * @param opId
 * @param params

 */
export function createAutoReplyRule(opId, params) {
  return api.post("official-account/" + opId + "/auto-reply-rules", params);
}

/**
 * 更新自动回复规则
 * @param opId
 * @param id
 * @param params

 */
export function updateAutoReplyRule(opId, id, params) {
  return api.put(
    "official-account/" + opId + "/auto-reply-rules/" + id,
    params
  );
}

/**
 * 更新自动回复规则
 * @param opId
 * @param id

 */
export function deleteAutoReplyRule(opId, id) {
  return api.delete("official-account/" + opId + "/auto-reply-rules/" + id);
}

export function uploadMaterial(opId, data) {
  return api.post("official-account/" + opId + "/materials", data);
}

export function getMaterials(opId, params) {
  return api.get("official-account/" + opId + "/materials", {
    params,
  });
}

export function deleteMaterial(opId, mediaId) {
  return api.delete("official-account/" + opId + "/materials/" + mediaId);
}

export function getDrafts(opId, params) {
  return api.get("official-account/" + opId + "/drafts", {
    params,
  });
}

export function deleteDraft(opId, mediaId) {
  return api.delete("official-account/" + opId + "/drafts/" + mediaId);
}

export function publishDraft(opId, mediaId) {
  return api.post("official-account/" + opId + "/articles", {
    media_id: mediaId,
  });
}

export function getArticles(opId, params) {
  return api.get("official-account/" + opId + "/articles", {
    params,
  });
}

export function deleteArticle(opId, articleId) {
  return api.delete("official-account/" + opId + "/articles/" + articleId);
}
