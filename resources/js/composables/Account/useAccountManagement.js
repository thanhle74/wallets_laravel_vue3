import accountManagementService from "@/services/accountManagementService";
import { useCrud } from "@/composables/useCrud.js";

export function useAccountManagement() {
    return useCrud(accountManagementService, {});
}
