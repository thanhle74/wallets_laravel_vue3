import { ref, onMounted } from "vue";
import accountManagementService from "@/services/accountManagementService";
import toastr from "toastr";

export function useAccountManagement() {
    const users = ref([]);
    const newUser = ref({ name: "", status: 1 });
    const editedUser = ref(null);
    const isLoading = ref(false);

    const fetchUsers = async () => {
        isLoading.value = true;
        try {
            const response = await accountManagementService.getAll();
            users.value = response.data.data;
        } catch (error) {
            toastr.error("Error fetching users!");
            console.error("Error fetching users:", error);
        } finally {
            isLoading.value = false;
        }
    };

    const showUser = async (id) => {
        try {
            const response = await accountManagementService.get(id);
            editedUser.value = response.data.data;
        } catch (error) {
            toastr.error("Error fetching user!");
            console.error("Error fetching user:", error);
        }
    }

    const addUser = async () => {
        if (!newUser.value.name.trim()) {
            toastr.error("User name cannot be empty!");
            return;
        }
        try {
            await accountManagementService.create(newUser.value);
            newUser.value = { name: "", status: 1 };
            await fetchUsers();
            toastr.success("User added successfully!");
        } catch (error) {
            toastr.error("Error adding user!");
            console.error("Error adding user:", error);
        }
    };

    const deleteUser = async (id) => {
        try {
            await accountManagementService.delete(id);
            await fetchUsers();
            toastr.warning("User has been deleted!");
        } catch (error) {
            toastr.error("Error deleting user!");
            console.error("Error deleting user:", error);
        }
    };

    const updateUser = async () => {
        if (!editedUser.value.name.trim()) {
            toastr.error("User name cannot be empty!");
            return;
        }
        try {
            await accountManagementService.update(editedUser.value.id, editedUser.value);
            await fetchUsers();
            editedUser.value = null;
            toastr.success("User updated successfully!");
        } catch (error) {
            toastr.error("Error updating user!");
            console.error("Error updating user:", error);
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
