import apiClient from "./apiClient.js";

const settingService = {
    getAll() {
        return apiClient.get("/settings");
    },
    saveAll(settings) {
        return apiClient.put("/settings", { settings });
    },
    uploadImage(settingId, image) {
        const formData = new FormData();
        formData.append("image", image);

        return apiClient.post(`/settings/${settingId}/upload`, formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });
    },
};

export default settingService;