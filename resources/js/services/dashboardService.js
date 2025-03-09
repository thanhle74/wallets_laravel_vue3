import apiClient from "./apiClient";

const dashboardService = {
    dashboardStats() {
        return apiClient.get("/dashboard");
    },
    getMonthlyStats() {
        return apiClient.get("/dashboard/monthly-expenses");
    },
    getTopCategories() {
        return apiClient.get("/dashboard/top-categories");
    },
    getRecentTransactions() {
        return apiClient.get("/dashboard/recent-transactions");
    }
};

export default dashboardService;
