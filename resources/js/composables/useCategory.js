import { ref, onMounted } from "vue";
import categoryService from "@/services/categoryService";
import toastr from "toastr";

export function useCategory() {
    const categories = ref([]);
    const newCategory = ref({ name: "", status: 1 });
    const editedCategory = ref(null);
    const isLoading = ref(false);

    const fetchCategories = async () => {
        isLoading.value = true;
        try {
            const response = await categoryService.getAll();
            categories.value = response.data.data;
        } catch (error) {
            toastr.error("Error fetching categories!");
            console.error("Error fetching categories:", error);
        } finally {
            isLoading.value = false;
        }
    };

    const addCategory = async () => {
        if (!newCategory.value.name.trim()) {
            toastr.error("Category name cannot be empty!");
            return;
        }
        try {
            await categoryService.create(newCategory.value);
            newCategory.value = { name: "", status: 1 };
            await fetchCategories();
            toastr.success("Category added successfully!");
        } catch (error) {
            toastr.error("Error adding category!");
            console.error("Error adding category:", error);
        }
    };

    const deleteCategory = async (id) => {
        try {
            await categoryService.delete(id);
            await fetchCategories();
            toastr.warning("Category has been deleted!");
        } catch (error) {
            toastr.error("Error deleting category!");
            console.error("Error deleting category:", error);
        }
    };

    const updateCategory = async () => {
        if (!editedCategory.value.name.trim()) {
            toastr.error("Category name cannot be empty!");
            return;
        }
        try {
            await categoryService.update(editedCategory.value.id, editedCategory.value);
            await fetchCategories();
            editedCategory.value = null;
            toastr.success("Category updated successfully!");
        } catch (error) {
            toastr.error("Error updating category!");
            console.error("Error updating category:", error);
        }
    };

    //onMounted(fetchCategories);

    return {
        categories,
        newCategory,
        editedCategory,
        isLoading,
        fetchCategories,
        addCategory,
        deleteCategory,
        updateCategory,
    };
}
