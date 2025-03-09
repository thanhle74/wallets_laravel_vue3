<template>
    <div v-if="!isEditing" class="form-container">
        <input v-model="category.name" placeholder="Enter name"/>
        <select v-model="category.status">
            <option :value="1">Active</option>
            <option :value="2">Disabled</option>
        </select>
        <button class="btn-info" @click="$emit('create')">
            <i class="ti-plus"></i>
            Add Category
        </button>
    </div>

    <div v-if="isEditing" class="form-container">
        <input v-model="isEditing.name" placeholder="Update name"/>
        <select v-model="isEditing.status">
            <option :value="1">Active</option>
            <option :value="2">Disabled</option>
        </select>
        <button class="btn-info" @click="$emit('update')">
            <i class="ti-save"></i>
            Save
        </button>
        <button class="btn-cancel" @click="$emit('cancel')">
            <i class="ti-close"></i>
            Cancel
        </button>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

defineProps({
    category: Object,
    isEditing: [Boolean, Object]
});

defineEmits(["create", "update", "cancel"]);
</script>
