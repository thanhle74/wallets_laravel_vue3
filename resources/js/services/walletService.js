import apiClient from "./apiClient.js";

const walletService = {
    getAll() {
        return apiClient.get("/wallets");
    },
    get(id) {
        return apiClient.get(`/wallets/${id}`);
    },
    create(data) {
        return apiClient.post("/wallets", data);
    },
    update(id, data) {
        return apiClient.put(`/wallets/${id}`, data);
    },
    delete(id) {
        return apiClient.delete(`/wallets/${id}`);
    },
    massDelete(ids) {
        return apiClient.post("/wallets/mass-delete", { ids });
    },
};

export default walletService;
