import categoryService from "@/services/categoryService";
import { useCrud } from "@/composables/useCrud.js";

export function useCategory() {
    return useCrud(categoryService, {
        name: "",
        status: 1,
        user_id: null
    });
}
