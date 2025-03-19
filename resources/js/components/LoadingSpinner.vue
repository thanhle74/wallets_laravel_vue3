<template>
    <div class="flex flex-col items-center justify-center space-y-2">
        <div class="w-8 h-8 border-4 border-gray-300 border-t-blue-500 rounded-full animate-spin"></div>
        <p class="text-gray-600 text-sm">{{ message }}</p>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
    message: {
        type: String,
        default: 'Loading...',
    },
});
</script>
