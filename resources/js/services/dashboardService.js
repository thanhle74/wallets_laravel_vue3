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
    },
    getExpensesByMonths(months = 3) {
        return apiClient.get("/dashboard/expenses-by-months", {params: { months }});
    }
};

export default dashboardService;
