<template>
    <div class="flex justify-between items-center mb-8">
        <div class="flex gap-4">
            <select
                :value="bulkAction"
                @change="onBulkActionChange"
                class="form-select w-auto"
            >
                <option value="">Bulk Actions</option>
                <option value="selectAll">Select All</option>
                <option value="unselectAll">Unselect All</option>
            </select>

            <Button
                type="danger"
                icon="ti-trash"
                :disabled="!selectedItems.length"
                @click="confirmMassDelete"
                label="Delete Selected"
            />
        </div>
        <slot name="form"></slot>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import Button from "@/components/Button.vue";

const props = defineProps({
    bulkAction: String,
    selectedItems: Array,
    confirmMassDelete: Function,
    handleBulkAction: Function,
});

const emit = defineEmits(["update:bulkAction"]);

// Xử lý khi chọn một hành động bulk
const onBulkActionChange = (event) => {
    const newValue = event.target.value;
    emit("update:bulkAction", newValue); // Phát sự kiện để cập nhật v-model
    props.handleBulkAction(); // Gọi hàm xử lý
};
</script>
