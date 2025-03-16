<template>
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

<script setup>
import { defineProps, defineEmits } from "vue";
import Button from "@/components/Button.vue";
import Modal from "@/components/Modal.vue";

// Props
defineProps({
    isEditing: [Boolean, Object]
});

// Emits
defineEmits(["update", "cancel"]);
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
