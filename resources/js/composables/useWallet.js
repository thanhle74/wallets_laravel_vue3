import { ref, onMounted } from "vue";
import walletService from "@/services/walletService.js";
import toastr from "toastr";

export function useWallet() {
    const wallets = ref([]);
    const newWallet = ref({ name: "", status: 1 });
    const editedWallet = ref(null);
    const isLoading = ref(false);

    const fetchWallets = async () => {
        isLoading.value = true;
        try {
            const response = await walletService.getAll();
            wallets.value = response.data.data;
        } catch (error) {
            toastr.error("Error fetching wallets!");
            console.error("Error fetching wallets:", error);
        } finally {
            isLoading.value = false;
        }
    };

    const addWallet = async () => {
        if (!newWallet.value.name.trim()) {
            toastr.error("Wallet name cannot be empty!");
            return;
        }
        try {
            await walletService.create(newWallet.value);
            newWallet.value = { name: "", status: 1 };
            await fetchWallets();
            toastr.success("Wallet added successfully!");
        } catch (error) {
            toastr.error("Error adding wallet!");
            console.error("Error adding wallet:", error);
        }
    };

    const deleteWallet = async (id) => {
        try {
            await walletService.delete(id);
            await fetchWallets();
            toastr.warning("Wallet has been deleted!");
        } catch (error) {
            toastr.error("Error deleting wallet!");
            console.error("Error deleting wallet:", error);
        }
    };

    const updateWallet = async () => {
        if (!editedWallet.value.name.trim()) {
            toastr.error("Wallet name cannot be empty!");
            return;
        }
        try {
            await walletService.update(editedWallet.value.id, editedWallet.value);
            await fetchWallets();
            editedWallet.value = null;
            toastr.success("Wallet updated successfully!");
        } catch (error) {
            toastr.error("Error updating wallet!");
            console.error("Error updating wallet:", error);
        }
    };

    // onMounted(fetchWallets);

    return {
        wallets,
        newWallet,
        editedWallet,
        isLoading,
        fetchWallets,
        addWallet,
        deleteWallet,
        updateWallet,
    };
}
