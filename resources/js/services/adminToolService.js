import apiClient from "./apiClient";

const adminToolService = {
    adminLaravelCommand(command) {
        return apiClient.post("/admin-run-command", command);
    },
};

export default adminToolService;

