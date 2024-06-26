import { api } from "boot/axios";

/**
 * 更新自动回复开关状态
 * @param opId
 * @param appId
 * @param params
 * @returns {Promise<AxiosResponse<any>>}
 */
export function updateAutoReplyState(opId, appId, params) {
  return api.put("open-platform/" + opId + "/oa/" + appId, params);
}

/**
 * 查看菜单快照列表
 */
export function getMenus(opId, appId) {
  return api.get("open-platform/" + opId + "/oa/" + appId + "/custom-menu");
}

/**
 * 查看菜单快照详情
 */
export function getMenu(opId, appId, id) {
  return api.get(
    "open-platform/" + opId + "/oa/" + appId + "/custom-menu/" + id
  );
}

/**
 * 存储菜单快照
 */
export function createMenu(opId, appId, params) {
  return api.post(
    "open-platform/" + opId + "/oa/" + appId + "/custom-menu",
    params
  );
}

/**
 * 删除菜单快照
 */
export function deleteMenu(opId, appId, id) {
  return api.delete(
    "open-platform/" + opId + "/oa/" + appId + "/custom-menu/" + id
  );
}

/**
 * 获取线上菜单信息
 */
export function getPublishedMenu(opId, appId) {
  return api.get(
    "open-platform/" + opId + "/oa/" + appId + "/custom-menu/published/show"
  );
}

/**
 * 获取线上菜单信息
 */
export function publishMenu(opId, appId, params) {
  return api.post(
    "open-platform/" + opId + "/oa/" + appId + "/custom-menu/published",
    params
  );
}

/**
 * 删除线上菜单
 * @param opId
 * @param appId
 * @returns {Promise<AxiosResponse<any>>}
 */
export function deletePublishedMenu(opId, appId) {
  return api.delete(
    "open-platform/" + opId + "/oa/" + appId + "/custom-menu/published/destroy"
  );
}

/**
 * 获取自动回复规则列表
 *
 * @param opId
 * @param appId
 * @param params
 * @returns {Promise<AxiosResponse<any>>}
 */
export function getAutoReplyRules(opId, appId, params) {
  return api.get(
    "open-platform/" + opId + "/oa/" + appId + "/auto-reply-rules",
    { params }
  );
}

/**
 * 获取自动回复规则列表
 *
 * @param opId
 * @param appId
 * @param params
 * @returns {Promise<AxiosResponse<any>>}
 */
export function getAutoReplyRule(opId, appId, id) {
  return api.get(
    "open-platform/" + opId + "/oa/" + appId + "/auto-reply-rules/" + id
  );
}

/**
 * 创建自动回复规则
 * @param opId
 * @param appId
 * @param params
 * @returns {Promise<AxiosResponse<any>>}
 */
export function createAutoReplyRule(opId, appId, params) {
  return api.post(
    "open-platform/" + opId + "/oa/" + appId + "/auto-reply-rules",
    params
  );
}

/**
 * 更新自动回复规则
 * @param opId
 * @param appId
 * @param id
 * @param params
 * @returns {Promise<AxiosResponse<any>>}
 */
export function updateAutoReplyRule(opId, appId, id, params) {
  return api.put(
    "open-platform/" + opId + "/oa/" + appId + "/auto-reply-rules/" + id,
    params
  );
}

/**
 * 更新自动回复规则
 * @param opId
 * @param appId
 * @param id
 * @returns {Promise<AxiosResponse<any>>}
 */
export function deleteAutoReplyRule(opId, appId, id) {
  return api.delete(
    "open-platform/" + opId + "/oa/" + appId + "/auto-reply-rules/" + id
  );
}

export function uploadMaterial(opId, appId, data) {
  return api.post(
    "open-platform/" + opId + "/oa/" + appId + "/materials",
    data
  );
}

export function getMaterials(opId, appId, params) {
  return api.get("open-platform/" + opId + "/oa/" + appId + "/materials", {
    params,
  });
}

export function deleteMaterial(opId, appId, mediaId) {
  return api.delete(
    "open-platform/" + opId + "/oa/" + appId + "/materials/" + mediaId
  );
}

export function getDrafts(opId, appId, params) {
  return api.get("open-platform/" + opId + "/oa/" + appId + "/drafts", {
    params,
  });
}

export function deleteDraft(opId, appId, mediaId) {
  return api.delete(
    "open-platform/" + opId + "/oa/" + appId + "/drafts/" + mediaId
  );
}

export function publishDraft(opId, appId, mediaId) {
  return api.post("open-platform/" + opId + "/oa/" + appId + "/articles", {
    media_id: mediaId,
  });
}

export function getArticles(opId, appId, params) {
  return api.get("open-platform/" + opId + "/oa/" + appId + "/articles", {
    params,
  });
}

export function deleteArticle(opId, appId, articleId) {
  return api.delete(
    "open-platform/" + opId + "/oa/" + appId + "/articles/" + articleId
  );
}

export function getMiniApps(opId, appId) {
  return api.get("open-platform/" + opId + "/oa/" + appId + "/mini-apps");
}

export function bindMiniApps(opId, appId, mid) {
  return api.post("open-platform/" + opId + "/oa/" + appId + "/mini-apps/", {
    appid: mid,
  });
}

export function unbindMiniApps(opId, appId, mid) {
  return api.delete(
    "open-platform/" + opId + "/oa/" + appId + "/mini-apps/" + mid
  );
}
