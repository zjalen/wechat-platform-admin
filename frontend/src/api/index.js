import { api } from "boot/axios";

export function login(params) {
  return api.post("login", params);
}

export function getPlatforms(params) {
  return api.get("platforms", { params });
}

export function getOperationLogs(params) {
  return api.get("operation-logs", { params });
}

export function getPlatform(id) {
  return api.get("platforms/" + id);
}

export function createPlatform(params) {
  return api.post("platforms", params);
}

export function updatePlatform(id, params) {
  return api.put("platforms/" + id, params);
}

export function deletePlatform(id) {
  return api.delete("platforms/" + id);
}
