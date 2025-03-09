import { ref, onMounted } from "vue";
import transactionService from "@/services/transactionService.js";
import toastr from "toastr";

export function useTransaction() {
    const transactions = ref([]);
    const newTransaction = ref({ name: "", status: 1 });
    const editedTransaction = ref(null);
    const isLoading = ref(false);

    const fetchTransactions = async () => {
        isLoading.value = true;
        try {
            const response = await transactionService.getAll();
            transactions.value = response.data.data;
        } catch (error) {
            toastr.error("Error fetching categories!");
            console.error("Error fetching categories:", error);
        } finally {
            isLoading.value = false;
        }
    };

    const addTransaction = async () => {
        // if (!newTransaction.value.name.trim()) {
        //     toastr.error("Category name cannot be empty!");
        //     return;
        // }
        try {
            await transactionService.create(newTransaction.value);
            newTransaction.value = { name: "", status: 1 };
            await fetchTransactions();
            toastr.success("Category added successfully!");
        } catch (error) {
            toastr.error("Error adding category!");
            console.error("Error adding category:", error);
        }
    };

    const deleteTransaction = async (id) => {
        try {
            await transactionService.delete(id);
            await fetchTransactions();
            toastr.warning("Category has been deleted!");
        } catch (error) {
            toastr.error("Error deleting category!");
            console.error("Error deleting category:", error);
        }
    };

    const updateTransaction = async () => {
        // if (!editedTransaction.value.name.trim()) {
        //     toastr.error("Category name cannot be empty!");
        //     return;
        // }
        try {
            await transactionService.update(editedTransaction.value.id, editedTransaction.value);
            await fetchTransactions();
            editedTransaction.value = null;
            toastr.success("Category updated successfully!");
        } catch (error) {
            toastr.error("Error updating category!");
            console.error("Error updating category:", error);
        }
    };

    //onMounted(fetchTransactions);

    return {
        transactions,
        newTransaction,
        editedTransaction,
        isLoading,
        fetchTransactions,
        addTransaction,
        deleteTransaction,
        updateTransaction,
    };
}
