import { ref } from "vue";
import { messageResponse } from "@/composables/support/messageResponse.js";
import toastr from "toastr";

const { handleApiResponse, handleApiError } = messageResponse();

export function useCrud(service, defaultItem = {}) {
    const items = ref([]);
    const newItem = ref({ ...defaultItem });
    const editedItem = ref(null);
    const isLoading = ref(false);

    const fetchItems = async () => {
        isLoading.value = true;
        try {
            const response = await service.getAll();
            items.value = response.data.data;
        } catch (error) {
            handleApiError(error, "Error fetching data!");
        } finally {
            isLoading.value = false;
        }
    };

    const showItem = async (id) => {
        try {
            const response = await service.get(id);
            editedItem.value = response.data.data;
        } catch (error) {
            handleApiError(error, "Error fetching item!");
        }
    };

    const addItem = async () => {
        try {
            const response = await service.create(newItem.value);
            handleApiResponse(response);
            newItem.value = { ...defaultItem };
            await fetchItems();
        } catch (error) {
            handleApiError(error);
        }
    };

    const deleteItem = async (id) => {
        try {
            const response = await service.delete(id);
            handleApiResponse(response);
            await fetchItems();
        } catch (error) {
            handleApiError(error);
        }
    };

    const updateItem = async () => {
        const requiredFields = Object.keys(defaultItem).filter(key => key !== "id" && !editedItem.value[key]?.toString().trim());

        if (requiredFields.length > 0) {
            toastr.error(`${requiredFields.join(", ")} cannot be empty!`);
            return;
        }

        try {
            const updatedData = { ...editedItem.value };
            if (!updatedData.password) delete updatedData.password;

            const response = await service.update(editedItem.value.id, updatedData);
            handleApiResponse(response);
            await fetchItems();
            editedItem.value = null;
        } catch (error) {
            handleApiError(error);
        }
    };

    const massDeleteItems = async (selectedItems) => {
        try {
            const response = await service.massDelete(selectedItems);
            handleApiResponse(response);
            await fetchItems();
        } catch (error) {
            handleApiError(error);
        }
    };

    return {
        items,
        newItem,
        editedItem,
        isLoading,
        fetchItems,
        addItem,
        showItem,
        deleteItem,
        massDeleteItems,
        updateItem,
    };
}
