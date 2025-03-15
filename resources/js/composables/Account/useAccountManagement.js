import { ref } from "vue";
import accountManagementService from "@/services/accountManagementService";
import { messageResponse } from "@/composables/support/messageResponse.js";
import toastr from "toastr";

const { handleApiResponse, handleApiError } = messageResponse();

export function useAccountManagement() {
    const users = ref([]);
    const newUser = ref({ name: "", email: "", password: "",password_confirmation: "", role: "user", status: 1 });
    const editedUser = ref(null);
    const isLoading = ref(false);

    const fetchUsers = async () => {
        isLoading.value = true;
        try {
            const response = await accountManagementService.getAll();
            users.value = response.data.data;
        } catch (error) {
            handleApiError(error, "Error fetching users!");
        } finally {
            isLoading.value = false;
        }
    };

    const showUser = async (id) => {
        try {
            const response = await accountManagementService.get(id);
            editedUser.value = response.data.data;
        } catch (error) {
            handleApiError(error, "Error fetching user!");
        }
    };

    const addUser = async () => {
        if (!newUser.value.name.trim() || !newUser.value.email.trim() || !newUser.value.password.trim()) {
            toastr.error("Name, Email, and Password cannot be empty!");
            return;
        }

        if (newUser.value.password !== newUser.value.password_confirmation) {
            toastr.error("Password and confirmation do not match!");
            return;
        }

        try {
            const response = await accountManagementService.create(newUser.value);
            handleApiResponse(response);
            newUser.value = { name: "", email: "", password: "", password_confirmation: "", role: "user", status: 1 }; // reset form
            await fetchUsers();
        } catch (error) {
            handleApiError(error);
        }
    };

    const deleteUser = async (id) => {
        try {
            const response = await accountManagementService.delete(id);
            handleApiResponse(response);
            await fetchUsers();
        } catch (error) {
            handleApiError(error);
        }
    };

    const updateUser = async () => {
        if (!editedUser.value.name.trim() || !editedUser.value.email.trim()) {
            toastr.error("Name and Email cannot be empty!");
            return;
        }

        try {
            const updatedData = { ...editedUser.value };
            if (!updatedData.password) {
                delete updatedData.password;
            }

            const response = await accountManagementService.update(editedUser.value.id, updatedData);
            handleApiResponse(response);
            await fetchUsers();
            editedUser.value = null;
        } catch (error) {
            handleApiError(error);
        }
    };

    return {
        users,
        newUser,
        editedUser,
        isLoading,
        fetchUsers,
        addUser,
        showUser,
        deleteUser,
        updateUser,
    };
}
