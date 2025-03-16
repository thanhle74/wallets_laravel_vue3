<template>
    <template v-if="show">
        <Modal v-if="!isEditing">
            <form @submit.prevent="$emit('create')" class="form-container">
                <!-- Name -->
                <input v-model="user.name" placeholder="Enter name" type="text" required />

                <!-- Email -->
                <input v-model="user.email" placeholder="Enter email" autocomplete="email" type="email" required />

                <!-- Password -->
                <input v-model="user.password" placeholder="Enter password" autocomplete="new-password" type="password" required />
                <input v-model="user.password_confirmation" placeholder="Confirm password" autocomplete="new-password" type="password" required />

                <!-- Role -->
                <select v-model="user.role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>

                <!-- Status -->
                <select v-model="user.status" required>
                    <option :value="1">Active</option>
                    <option :value="0">Disabled</option>
                </select>

                <!-- Action Buttons -->
                <div class="button-group">
                    <Button btnClass="btn-info" icon="ti-plus" text="Add User" type="submit" />
                    <Button btnClass="btn-cancel" icon="ti-close" text="Cancel" type="button" @click="$emit('cancel')" />
                </div>
            </form>
        </Modal>
        <Modal v-if="isEditing">
            <h1>Edit</h1>
            <form @submit.prevent="$emit('update')" class="form-container">
                <!-- Name -->
                <input v-model="isEditing.name" placeholder="Update name" type="text" required />

                <!-- Email -->
                <input v-model="isEditing.email" placeholder="Update email" autocomplete="email" type="email" required />

                <!-- Password (optional) -->
                <input v-model="isEditing.password" placeholder="Change password (optional)" autocomplete="new-password" type="password" />

                <!-- Role -->
                <select v-model="isEditing.role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>

                <!-- Status -->
                <select v-model="isEditing.status" required>
                    <option :value="1">Active</option>
                    <option :value="0">Disabled</option>
                </select>

                <!-- Action Buttons -->
                <div class="button-group">
                    <Button btnClass="btn-info" icon="ti-save" text="Save" type="submit" />
                    <Button btnClass="btn-cancel" icon="ti-close" text="Cancel" type="button" @click="$emit('cancel')" />
                </div>
            </form>
        </Modal>
    </template>
</template>

<script setup>
import { defineEmits, defineProps } from "vue";
import Button from "@/components/Button.vue";
import Modal from "@/components/Modal.vue";

defineProps({
    user: Object,
    isEditing: [Boolean, Object],
    show: Boolean
});

defineEmits(["create", "cancel", "update"]);
</script>

<style scoped>
.form-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.button-group {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}
</style>
