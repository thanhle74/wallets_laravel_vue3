import apiClient from "./apiClient";

const dashboardService = {
    dashboardStats() {
        return apiClient.get("/dashboard");
    }
};

export default dashboardService;
