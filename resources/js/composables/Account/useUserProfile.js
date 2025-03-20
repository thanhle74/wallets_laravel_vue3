import { ref, onMounted } from "vue";
import { messageResponse } from "@/utils/messageResponse";
import apiClient from '@/services/apiClient';

export function useUserProfile() {
    const user = ref({ name: "", email: "", avatar: "" });
    const selectedFile = ref(null);
    const passwords = ref({ current_password: "", new_password: "", new_password_confirmation: "" });

    const { handleApiResponse, handleApiError } = messageResponse();

    const fetchUser = async () => {
        try {
            const response = await apiClient.get('/user');
            if (response.data.data && response.data.data.user) {
                user.value = response.data.data.user;
            }
        } catch (error) {
            handleApiError(error, "Could not fetch user profile.");
        }
    };

    const handleFileUpload = (event) => {
        selectedFile.value = event.target.files[0];
    };

    const updateProfile = async () => {
        try {
            const formData = new FormData();
            formData.append("name", user.value.name);
            formData.append("email", user.value.email);
            if (selectedFile.value) {
                formData.append("avatar", selectedFile.value);
            }

            const response = await apiClient.post('/user/update-profile', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            if (response.data.data && response.data.data.user) {
                user.value = response.data.data.user;
            }

            handleApiResponse(response);
            await fetchUser();
        } catch (error) {
            handleApiError(error, "Update profile failed.");
        }
    };

    const changePassword = async () => {
        try {
            const response = await apiClient.post('/user/change-password', passwords.value);
            passwords.value = { current_password: '', new_password: '', new_password_confirmation: '' };
            handleApiResponse(response);
        } catch (error) {
            handleApiError(error, "Change password failed.");
        }
    };    

    onMounted(fetchUser);

    return {
        user,
        selectedFile,
        passwords,
        fetchUser,
        handleFileUpload,
        updateProfile,
        changePassword
    };
}
