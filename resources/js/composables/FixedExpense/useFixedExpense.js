import fixedExpenseService from "@/services/fixedExpenseService.js";
import { useCrud } from "@/composables/useCrud.js";

export function useFixedExpense() {
    return useCrud(fixedExpenseService, {});
}
