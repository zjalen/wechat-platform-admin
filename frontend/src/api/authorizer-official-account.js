import { api } from "boot/axios";

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

export function deletePublishedMenu(opId, appId) {
  return api.delete(
    "open-platform/" + opId + "/oa/" + appId + "/custom-menu/published/destroy"
  );
}
