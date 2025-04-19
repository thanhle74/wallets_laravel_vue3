import { ref } from "vue";
import settingService from "@/services/settingService";
import { messageResponse } from "@/utils/messageResponse";

export function useSettings() {
    const settings = ref([]);
    const { handleApiResponse, handleApiError } = messageResponse();

    const fetchSettings = async () => {
        try {
            const response = await settingService.getAll();
            settings.value = response.data.data;
        } catch (error) {
            handleApiError(error, "Failed to fetch settings.");
        }
    };

    const saveAllSettings = async () => {
        try {
            const response = await settingService.saveAll(settings.value);
            handleApiResponse(response);
        } catch (error) {
            handleApiError(error, "Failed to save settings.");
        }
    };

    const uploadImage = async (event, setting) => {
        const file = event.target.files[0];
        if (!file) return;

        try {
            const response = await settingService.uploadImage(setting.id, file);
            setting.value = response.data.data.path;
            handleApiResponse(response);
        } catch (error) {
            handleApiError(error, "Failed to upload image.");
        }
    };

    const createSetting = async (setting) => {
        try {
            const response = await settingService.createSetting(setting);
            handleApiResponse(response);
            Object.assign(setting, {
                group: '',
                key: '',
                label: '',
                value: '',
                type: 'text',
            });
            await fetchSettings();
        } catch (error) {
            handleApiError(error, "Failed to create setting.");
        }
    };

    return {
        settings,
        fetchSettings,
        saveAllSettings,
        createSetting,
        uploadImage,
    };
}
