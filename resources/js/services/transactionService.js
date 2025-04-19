import apiClient from "./apiClient.js";

const transactionService = {
    getAll(params = {}) {
        return apiClient.get("/transactions", { params });
    },
    get(id) {
        return apiClient.get(`/transactions/${id}`);
    },
    create(data) {
        return apiClient.post("/transactions", data);
    },
    update(id, data) {
        return apiClient.put(`/transactions/${id}`, data);
    },
    delete(id) {
        return apiClient.delete(`/transactions/${id}`);
    },
    massDelete(ids) {
        return apiClient.post("/transactions/mass-delete", { ids });
    },
};

export default transactionService;
