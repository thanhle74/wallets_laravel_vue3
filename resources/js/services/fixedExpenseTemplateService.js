import apiClient from "./apiClient";

const fixedExpenseTemplateService = {
    getAll( params = {}) {
        return apiClient.get("/fixed-expense-template", { params });
    },
    get(id) {
        return apiClient.get(`/fixed-expense-template/${id}`);
    },
    create(data) {
        return apiClient.post("/fixed-expense-template", data);
    },
    update(id, data) {
        return apiClient.put(`/fixed-expense-template/${id}`, data);
    },
    delete(id) {
        return apiClient.delete(`/fixed-expense-template/${id}`);
    },
    massDelete(ids) {
        return apiClient.post("fixed-expense-templates/mass-delete", { ids });
    },
    assign(id, data = {}) {
        return apiClient.post(`/fixed-expense-templates/${id}/assign`, data);
    }
};

export default fixedExpenseTemplateService;
