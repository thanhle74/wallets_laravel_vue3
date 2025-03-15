<template>
    <div v-if="!isEditing" class="form-container">
        <input v-model="category.name" placeholder="Enter name"/>
        <select v-model="category.status">
            <option :value="1">Active</option>
            <option :value="2">Disabled</option>
        </select>
        <Button btnClass="btn-info" icon="ti-plus" text="Add Category" @click="$emit('create')" />
    </div>

    <div v-if="isEditing" class="form-container">
        <input v-model="isEditing.name" placeholder="Update name"/>
        <select v-model="isEditing.status">
            <option :value="1">Active</option>
            <option :value="2">Disabled</option>
        </select>
        <Button btnClass="btn-info" icon="ti-save" text="Save" @click="$emit('update')" />
        <Button btnClass="btn-cancel" icon="ti-close" text="Cancel" @click="$emit('cancel')" />
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import Button from "@/components/Button.vue";

defineProps({
    category: Object,
    isEditing: [Boolean, Object]
});

defineEmits(["create", "update", "cancel"]);
</script>
