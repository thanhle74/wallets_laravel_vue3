<template>
    <div v-if="!isEditing" class="flex gap-4">
        <input v-model="wallet.name" placeholder="Enter name" />

        <select v-model="wallet.type">
            <option :value="1">Bank</option>
            <option :value="2">Cash</option>
            <option :value="3">Crypto</option>
        </select>

        <select v-model="wallet.status">
            <option :value="1">Active</option>
            <option :value="2">Disabled</option>
        </select>

        <Button 
            btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple" 
            icon="ti-plus" 
            text="Add Wallet" 
            @click="$emit('create')"
        />
    </div>

    <div v-if="isEditing" class="flex gap-4">
        <input v-model="isEditing.name" placeholder="Update name" />
        <select v-model="isEditing.status">
            <option :value="1">Active</option>
            <option :value="2">Disabled</option>
        </select>
        <Button 
            btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple" 
            icon="ti-save" 
            text="Save" 
            @click="$emit('update')"
        />
        <Button 
            btnClass="bg-charcoal-gray text-slate-gray hover:bg-storm-gray rounded-md hover:text-lavender-gray" 
            icon="ti-close" 
            text="Cancel" 
            @click="$emit('cancel')"
        />
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import Button from "@/components/Button.vue";

defineProps({
    wallet: Object,
    isEditing: [Boolean, Object]
});

defineEmits(["create", "update", "cancel"]);
</script>
