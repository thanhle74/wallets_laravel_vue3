import { ref } from "vue";
import walletService from "@/services/walletService.js";
import toastr from "toastr";

// Hàm lấy message từ API response
const getApiMessage = (response, defaultMessage = "Operation successful!") => {
    return response?.data?.message || defaultMessage;
};

// Hàm xử lý response thành công
const handleApiResponse = (response) => {
    const message = getApiMessage(response, "Success!");
    toastr.success(message);
};

// Hàm xử lý lỗi từ API
const handleApiError = (error, defaultMessage = "Something went wrong!") => {
    const message = error.response?.data?.message || defaultMessage;
    toastr.error(message);
    console.error("API Error:", message, error);
};

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
            handleApiError(error, "Error fetching wallets!");
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
            const response = await walletService.create(newWallet.value);
            handleApiResponse(response);
            newWallet.value = { name: "", status: 1 };
            await fetchWallets();
        } catch (error) {
            handleApiError(error);
        }
    };

    const deleteWallet = async (id) => {
        try {
            const response = await walletService.delete(id);
            handleApiResponse(response);
            await fetchWallets();
        } catch (error) {
            handleApiError(error);
        }
    };

    const updateWallet = async () => {
        if (!editedWallet.value.name.trim()) {
            toastr.error("Wallet name cannot be empty!");
            return;
        }
        try {
            const response = await walletService.update(editedWallet.value.id, editedWallet.value);
            handleApiResponse(response);
            await fetchWallets();
            editedWallet.value = null;
        } catch (error) {
            handleApiError(error);
        }
    };

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
