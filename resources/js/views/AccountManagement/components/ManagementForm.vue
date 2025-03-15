<template>
    <!-- Form khi Thêm mới User -->
    <div v-if="!isEditing" class="form-container">
        <!-- Name -->
        <input v-model="user.name" placeholder="Enter name" type="text" />

        <!-- Email -->
        <input v-model="user.email" placeholder="Enter email" type="email" />

        <!-- Password -->
        <input v-model="user.password" placeholder="Enter password" type="password" />
        <input v-model="user.password_confirmation" placeholder="Confirm password" type="password" />

        <!-- Role -->
        <select v-model="user.role">
            <option :value="'admin'">Admin</option>
            <option :value="'user'">User</option>
        </select>

        <!-- Status -->
        <select v-model="user.status">
            <option :value="1">Active</option>
            <option :value="0">Disabled</option>
        </select>

        <!-- Button Add -->
        <Button btnClass="btn-info" icon="ti-plus" text="Add User" @click="$emit('create')" />
    </div>

    <!-- Form khi Chỉnh sửa User -->
    <div v-else class="form-container">
        <!-- Name -->
        <input v-model="isEditing.name" placeholder="Update name" type="text" />

        <!-- Email -->
        <input v-model="isEditing.email" placeholder="Update email" type="email" />

        <!-- Password (tùy chọn nếu muốn đổi) -->
        <input v-model="isEditing.password" placeholder="Change password (optional)" type="password" />

        <!-- Role -->
        <select v-model="isEditing.role">
            <option :value="'admin'">Admin</option>
            <option :value="'user'">User</option>
        </select>

        <!-- Status -->
        <select v-model="isEditing.status">
            <option :value="1">Active</option>
            <option :value="0">Disabled</option>
        </select>

        <!-- Button Save & Cancel -->
        <Button btnClass="btn-info" icon="ti-save" text="Save" @click="$emit('update')" />
        <Button btnClass="btn-cancel" icon="ti-close" text="Cancel" @click="$emit('cancel')" />
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import Button from "@/components/Button.vue";

// Props từ component cha
defineProps({
    user: Object, // User khi tạo mới
    isEditing: [Boolean, Object] // Khi chỉnh sửa
});

// Emits sự kiện về component cha
defineEmits(["create", "update", "cancel"]);
</script>

<style scoped>

</style>
