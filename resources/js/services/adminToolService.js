import apiClient from "./apiClient";

const adminToolService = {
    adminLaravelCommand(command) {
        return apiClient.post("/admin-run-command", command);
    },
    getFactories() {
        return apiClient.get("/admin-get-factories");
    },
    getSeeders() {
        return apiClient.get("/admin-get-seeders");
    },
    runFactory(data) {
        return apiClient.post("/admin-run-factory", data);
    },
    runSeeder(data) {
        return apiClient.post("/admin-run-seeder", data);
    },
    adminListModules() {
        return apiClient.get("/admin-list-modules");
    },
    adminInstallModule(data) {
        return apiClient.post("/admin-create-module", data);
    },
    adminRunModuleCommand(data) {
        return apiClient.post("/admin-run-module-command", data);
    },
};

export default adminToolService;
