import apiClient from "./apiClient";

const accountManagementService = {
    getAll() {
        return apiClient.get("/users");
    },
    get(id) {
        return apiClient.get(`/users/${id}`);
    },
    create(data) {
        return apiClient.post("/users", data);
    },
    update(id, data) {
        return apiClient.put(`/users/${id}`, data);
    },
    delete(id) {
        return apiClient.delete(`/users/${id}`);
    },
};

export default accountManagementService;
