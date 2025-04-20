import apiClient from "./apiClient";

const fixedExpenseService = {
    getAll(params = {}) {
        return apiClient.get("/fixed-expense", { params });
    },
    get(id) {
        return apiClient.get(`/fixed-expense/${id}`);
    },
    create(data) {
        return apiClient.post("/fixed-expense", data);
    },
    update(id, data) {
        return apiClient.put(`/fixed-expense/${id}`, data);
    },
    delete(id) {
        return apiClient.delete(`/fixed-expense/${id}`);
    },
    massDelete(ids) {
        return apiClient.post("/fixed-expense/mass-delete", { ids });
    },
};

export default fixedExpenseService;
