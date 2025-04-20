<template>
    <MainLayout title="Quản lý chi phí cố định" icon="ti-wallet">
        <LoadingSpinner v-if="isLoading" message="Đang tải ví..."/>

        <div v-else>
            <BulkActionsWithForm
                :bulkAction="bulkAction"
                @update:bulkAction="bulkAction = $event"
                :selectedItems="selectedItems"
                :confirmMassDelete="confirmMassDelete"
                :handleBulkAction="handleBulkAction"
            >
                <template #form>
                    <!-- Nút Hiển thị Mẫu -->
                    <Button
                        type="primary"
                        icon="ti-plus"
                        text="Hiển thị Mẫu"
                        @click="showTemplateModal = true"
                    />
                </template>
            </BulkActionsWithForm>

            <div class="flex justify-start items-center mb-4 space-x-2">
                <label for="filterMonth" class="mr-2">Chọn tháng:</label>
                <input
                    id="filterMonth"
                    type="month"
                    v-model="filterMonth"
                    @change="fetchItemsWithMonth"
                />
            </div>

            <!-- Bảng dữ liệu -->
            <DataTable>
                <template #thead>
                    <th></th>
                    <th v-if="isAdmin">ID</th>
                    <th>Tên</th>
                    <th>Số tiền</th>
                    <th>Tháng</th>
                    <th>Đã trừ</th>
                    <th v-if="isAdmin">Người dùng</th>
                    <th class="text-center">Thao tác</th>
                </template>
                <template #tbody>
                    <tr v-for="(fixedExpense, index) in items" :key="index">
                        <td class="text-center">
                            <input type="checkbox" :value="fixedExpense.id" v-model="selectedItems" />
                        </td>
                        <td v-if="isAdmin">{{ fixedExpense.id }}</td>
                        <td>{{ fixedExpense.name }}</td>
                        <td>{{ fixedExpense.amount }}</td>
                        <td>{{ fixedExpense.month }}</td>
                        <td>{{ fixedExpense.is_deducted }}</td>
                        <td v-if="isAdmin">{{ fixedExpense.user.name }}</td>
                        <td class="text-center">
                            <Button
                                type="info"
                                icon="ti-pencil"
                                @click="editItem(fixedExpense)"
                            />
                        </td>
                    </tr>
                </template>
            </DataTable>
        </div>

        <!-- Modal xác nhận xóa -->
        <ConfirmDeleteModal
            :show="deleteMassConfirm"
            message="Bạn có chắc chắn muốn xóa các ví đã chọn không?"
            @confirm="handleMassDelete"
            @cancel="deleteMassConfirm = false"
        />

        <!-- Component Mẫu Chi Phí Cố Định -->
        <FixedExpenseTemplate
            v-show="showTemplateModal"
            @close="showTemplateModal = false"
            @assigned="handleAfterAssign"
            :filterMonth="filterMonth"
            @updateFilterMonth="updateFilterMonth"
            :parentItems="items"
        />
    </MainLayout>
</template>
<script setup>
import MainLayout from '@/views/layout/MainLayout.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import Button from "@/components/Button.vue";
import DataTable from "@/components/DataTable.vue";
import BulkActionsWithForm from "@/components/BulkActionsWithForm.vue";
import FixedExpenseTemplate from "@/views/FixedExpense/FixedExpenseTemplate.vue";
import dayjs from 'dayjs';
import { ref, onMounted, watch } from 'vue';
import { useFixedExpense } from "@/composables/FixedExpense/useFixedExpense.js";
import { useCrudPage } from "@/composables/useCrudPage.js";

const {
    items, // Danh sách dữ liệu
    isLoading, // Trạng thái đang tải
    isAdmin, // Có phải admin không
    selectedItems, // Các mục đã chọn
    deleteMassConfirm, // Hiển thị modal xác nhận xóa hàng loạt
    bulkAction, // Hành động hàng loạt đang chọn
    confirmMassDelete, // Xác nhận xóa hàng loạt
    handleMassDelete, // Hàm xử lý xóa hàng loạt
    handleBulkAction, // Hàm xử lý hành động hàng loạt
    editItem, // Hàm sửa item
    fetchItems, // Hàm lấy dữ liệu
} = useCrudPage(useFixedExpense);

// Biến filter tháng
const filterMonth = ref(dayjs().format('YYYY-MM'));

// Hàm lấy dữ liệu theo tháng đã chọn
const fetchItemsWithMonth = async () => {
    const formattedMonth = dayjs(filterMonth.value).format('MM-YYYY'); // Chuyển về định dạng MM-YYYY
    await fetchItems({ month: formattedMonth });
};

// Gọi lấy dữ liệu khi component mount
onMounted(fetchItemsWithMonth);

// Theo dõi filterMonth, mỗi khi đổi thì gọi lại dữ liệu
watch(filterMonth, () => {
    fetchItemsWithMonth();
});

// Điều khiển modal hiển thị Template
const showTemplateModal = ref(false);

// Khi assign xong từ template, load lại dữ liệu
const handleAfterAssign = async () => {
    await fetchItemsWithMonth();
}

// Cập nhật tháng lọc từ component con
const updateFilterMonth = (newMonth) => {
    filterMonth.value = newMonth;
}
</script>
