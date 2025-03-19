import {ref, onMounted, watch, nextTick} from "vue";
import $ from "jquery";
import "datatables.net";
import {useUserAccount} from "@/composables/Account/useUserAccount.js";

export function useCrudPage(serviceComposable) {
    const {
        items,
        newItem,
        editedItem,
        isLoading,
        fetchItems,
        addItem,
        massDeleteItems,
        updateItem,
    } = serviceComposable();

    const selectedItems = ref([]);
    const deleteMassConfirm = ref(false);
    const bulkAction = ref("");
    const {fetchIsAdmin} = useUserAccount();
    const isAdmin = ref(false);
    const showForm = ref(false);

    const confirmMassDelete = () => {
        if (selectedItems.value.length) deleteMassConfirm.value = true;
    };

    const handleMassDelete = async () => {
        await massDeleteItems(selectedItems.value);
        deleteMassConfirm.value = false;
        selectedItems.value = [];
        await fetchItems();
    };

    const handleBulkAction = () => {
        if (bulkAction.value === "selectAll") {
            selectedItems.value = items.value.slice(0, 10).map(u => u.id);
        } else if (bulkAction.value === "unselectAll") {
            selectedItems.value = [];
        }
        bulkAction.value = "";
    };

    const handleCancelUpdate = () => {
        showForm.value = false
        editedItem.value = false;
    };

    const editItem = (item) => {
        showForm.value = true
        editedItem.value = {...item};
    };

    const initDataTable = () => {
        nextTick(() => {
            let table = $("#myTable");
            table.DataTable().destroy();
            table.DataTable();
        });
    };

    watch(items, (newValue) => {
        if (newValue.length) {
            initDataTable();
        }
    });

    onMounted(async () => {
        isAdmin.value = await fetchIsAdmin();
        await fetchItems();
        initDataTable();
    });


    return {
        items,
        newItem,
        editedItem,
        isLoading,
        fetchItems,
        isAdmin,
        addItem,
        massDeleteItems,
        updateItem,
        selectedItems,
        deleteMassConfirm,
        bulkAction,
        showForm,
        confirmMassDelete,
        handleMassDelete,
        handleBulkAction,
        handleCancelUpdate,
        editItem,
        initDataTable,
    };
}
