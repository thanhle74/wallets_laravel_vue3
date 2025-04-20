<template>
    <Modal @close="$emit('close')" customClass="min-w-5xl">
        <BulkActionsWithForm
            :bulkAction="bulkAction"
            @update:bulkAction="bulkAction = $event"
            :selectedItems="selectedItems"
            :confirmMassDelete="confirmMassDelete"
            :handleBulkAction="handleBulkAction"
        >
            <template #form>
                <FixedExpenseForm
                    :fixedExpense="newItem"
                    :isEditing="editedItem"
                    @create="addItem"
                    @update="updateItem"
                    @cancel="handleCancelUpdate"
                />
            </template>
        </BulkActionsWithForm>

        <!-- Phần tử bao bọc bảng để cuộn -->
        <div class="overflow-y-auto max-h-[400px]">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr class="bg-royal-purple text-amethyst-purple">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8">
                        <input type="checkbox" @change="toggleSelectAll" :checked="selectedItems.length === items.length && items.length > 0" />
                    </th>
                    <th v-if="isAdmin">ID</th>
                    <th>Tên mẫu</th>
                    <th>Số tiền</th>
                    <th class="text-center">Gán vào tháng</th>
                    <th v-if="isAdmin">Người dùng</th>
                    <th class="text-center">Thao tác</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <tr v-for="template in items" :key="template.id">
                    <td class="whitespace-nowrap text-center">
                        <input type="checkbox" :value="template.id" v-model="selectedItems" />
                    </td>
                    <td v-if="isAdmin">{{ template.id }}</td>
                    <td>{{ template.name }}</td>
                    <td>{{ formatCurrency(template.amount) }}</td>
                    <td class="whitespace-nowrap">
                        <div class="text-center space-x-2">
                            <input
                                type="month"
                                v-model="assignedMonths[template.id]"
                                :min="minSelectableMonth"
                                class="bg-gray-800 text-gray-400 cursor-not-allowed"
                                disabled
                            />
                            <Button icon="ti-arrow-right" type="warning" @click="assignTemplateToMonth(template)" :disabled="isAssigning" text="Gán" />
                        </div>
                    </td>
                    <td v-if="isAdmin">{{ template.user.name }}</td>
                    <td class="text-center">
                        <Button type="info" icon="ti-pencil" @click="editItem(template)" tooltip="Chỉnh sửa mẫu" />
                    </td>
                </tr>
                <tr v-if="items.length === 0">
                    <td :colspan="isAdmin ? 7 : 6" class="text-center text-sm text-gray-500">Không có mẫu nào</td>
                </tr>
                </tbody>
            </table>
        </div>

        <ConfirmDeleteModal
            :show="deleteMassConfirm"
            message="Bạn có chắc chắn muốn xóa các mẫu đã chọn?"
            @confirm="handleMassDelete"
            @cancel="deleteMassConfirm = false"
        />

        <div class="mt-5 flex justify-between items-center">
            <div class="text-sm text-gray-500">Hiển thị {{ items.length }} mẫu chi phí cố định</div>
            <Button btnClass="bg-gray-600 text-white hover:bg-gray-700 rounded-md" text="Đóng" icon="ti-close" @click="$emit('close')" />
        </div>
    </Modal>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import dayjs from 'dayjs';
import Modal from '@/components/Modal.vue';
import Button from '@/components/Button.vue';
import ConfirmDeleteModal from '@/components/ConfirmDeleteModal.vue';
import BulkActionsWithForm from '@/components/BulkActionsWithForm.vue';
import { useCrudPage } from '@/composables/useCrudPage.js';
import { useFixedExpenseTemplate } from '@/composables/FixedExpense/useFixedExpenseTemplate.js';
import { useHandlerFixedExpense } from '@/composables/FixedExpense/useHandlerFixedExpense.js';
import FixedExpenseForm from '@/views/FixedExpense/components/FixedExpenseTemplateForm.vue';

// Props
const props = defineProps({
    filterMonth: {
        type: String,
        default: () => dayjs().format('YYYY-MM'),
    },
    parentItems: Array,
});

// Emits
const emit = defineEmits(['close', 'assigned']);

// Composables
const { assignTemplate } = useHandlerFixedExpense(emit);
const {
    items,
    newItem,
    editedItem,
    addItem,
    isAdmin,
    updateItem,
    selectedItems,
    deleteMassConfirm,
    bulkAction,
    confirmMassDelete,
    handleMassDelete,
    handleCancelUpdate,
    handleBulkAction,
    editItem,
    fetchItems,
} = useCrudPage(useFixedExpenseTemplate);

// State
const isAssigning = ref(false);
const initialized = ref(false);
const assignedMonths = ref({});

// Computed Properties
const formattedMonth = computed(() => dayjs(props.filterMonth).format('MM-YYYY'));
const minSelectableMonth = computed(() => dayjs().format('YYYY-MM'));

// Methods
const fetchTemplates = async () => {
    try {
        await fetchItems({ month: formattedMonth.value });
        if (!initialized.value) {
            initializeAssignedMonths();
            initialized.value = true;
        }
    } catch (error) {
        console.error('Error fetching templates:', error);
    }
};

const initializeAssignedMonths = () => {
    items.value.forEach(template => {
        assignedMonths.value[template.id] = props.filterMonth || dayjs().format('YYYY-MM');
    });
};

const assignTemplateToMonth = async (template) => {
    if (!assignedMonths.value[template.id]) {
        alert('Please select a valid month');
        return;
    }

    isAssigning.value = true;
    try {
        // Gọi hàm assign
        const success = await assignTemplate(template, assignedMonths.value[template.id]);

        if (success) {
            emit('assigned', assignedMonths.value[template.id]);

            // Chỉ xóa nếu assign thành công
            items.value = items.value.filter(item => item.id !== template.id);
        }

        // Hoặc reload lại dữ liệu từ API
        // fetchTemplates();  // Uncomment nếu bạn muốn reload lại toàn bộ dữ liệu

    } catch (error) {
        console.error('Error assigning template:', error);
    } finally {
        isAssigning.value = false;
    }
};

const toggleSelectAll = (event) => {
    if (event.target.checked) {
        selectedItems.value = items.value.map(item => item.id);
    } else {
        selectedItems.value = [];
    }
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
    }).format(amount);
};

// Lifecycle Hooks
onMounted(() => {
    fetchTemplates();
    initialized.value = true;
});

// Watchers
watch(() => props.filterMonth, (newMonth) => {
    if (newMonth && initialized.value) {
        fetchTemplates();
    }
});

watch(
    () => props.parentItems,
    () => {
        fetchTemplates();
    },
    { deep: true }
);

watch(items, (newItems) => {
    // Only update assigned months for new items
    newItems.forEach(template => {
        if (!assignedMonths.value[template.id]) {
            assignedMonths.value[template.id] = props.filterMonth || dayjs().format('YYYY-MM');
        }
    });
}, { deep: true });
</script>
