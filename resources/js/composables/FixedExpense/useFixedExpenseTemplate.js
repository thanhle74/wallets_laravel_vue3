import { useCrud } from "@/composables/useCrud.js";
import fixedExpenseTemplateService from "@/services/fixedExpenseTemplateService.js";

export function useFixedExpenseTemplate() {
    return useCrud(fixedExpenseTemplateService, {});
}
