import transactionService from "@/services/transactionService.js";
import { useCrud } from "@/composables/useCrud.js";

export function useTransaction() {
    return useCrud(transactionService, {});
}
