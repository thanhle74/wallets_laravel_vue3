<template>
    <div v-if="!isEditing" class="flex gap-4">
        <input v-model="fixedExpense.name" placeholder="Nhập tên" />
        <input v-model="fixedExpense.amount" type="number" placeholder="Nhập số tiền" />
        <Button
            type="primary"
            icon="ti-plus"
            text="Thêm chi phí cố định"
            @click="$emit('create')"
        />
    </div>

    <div v-if="isEditing" class="flex gap-4">
        <input v-model="isEditing.name" placeholder="Cập nhật tên" />
        <input v-model="isEditing.amount" type="number" placeholder="Cập nhật số tiền" />
        <Button
            type="primary"
            icon="ti-save"
            text="Lưu"
            @click="$emit('update')"
        />
        <Button
            type="cancel"
            icon="ti-close"
            text="Hủy"
            @click="$emit('cancel')"
        />
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import Button from "@/components/Button.vue";

defineProps({
    fixedExpense: Object,
    isEditing: [Boolean, Object]
});

defineEmits(["create", "update", "cancel"]);
</script>
