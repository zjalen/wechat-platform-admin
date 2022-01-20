import { api } from "boot/axios";

export function getLocalResources(appId, params) {
  return api.get("platforms/" + appId + "/resources", {
    params,
  });
}

export function deleteLocalResource(appId, data) {
  return api.post("platforms/" + appId + "/resources/delete", data);
}
