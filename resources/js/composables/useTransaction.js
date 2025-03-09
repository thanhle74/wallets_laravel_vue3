import { ref, onMounted } from "vue";
import transactionService from "@/services/transactionService.js";
import toastr from "toastr";

export function useTransaction() {
    const transactions = ref([]);
    const newTransaction = ref({ amount: "", status: 1 });
    const editedTransaction = ref(null);
    const isLoading = ref(false);

    const fetchTransactions = async () => {
        isLoading.value = true;
        try {
            const response = await transactionService.getAll();
            transactions.value = response.data.data;
        } catch (error) {
            toastr.error("Error fetching transactions!");
            console.error("Error fetching transactions:", error);
        } finally {
            isLoading.value = false;
        }
    };

    const addTransaction = async () => {
        if (!newTransaction.value.amount.trim()) {
            toastr.error("Transaction amount cannot be empty!");
            return;
        }
        try {
            await transactionService.create(newTransaction.value);
            newTransaction.value = { amount: "", status: 1 };
            await fetchTransactions();
            toastr.success("Transaction added successfully!");
        } catch (error) {
            toastr.error("Error adding transaction!");
            console.error("Error adding transaction:", error);
        }
    };

    const deleteTransaction = async (id) => {
        try {
            await transactionService.delete(id);
            await fetchTransactions();
            toastr.warning("Transaction has been deleted!");
        } catch (error) {
            toastr.error("Error deleting transaction!");
            console.error("Error deleting transaction:", error);
        }
    };

    const updateTransaction = async () => {
        if (!editedTransaction.value.amount.trim()) {
            toastr.error("Transaction amount cannot be empty!");
            return;
        }
        try {
            await transactionService.update(editedTransaction.value.id, editedTransaction.value);
            await fetchTransactions();
            editedTransaction.value = null;
            toastr.success("Transaction updated successfully!");
        } catch (error) {
            toastr.error("Error updating transaction!");
            console.error("Error updating transaction:", error);
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
