<!-- src/components/LoadingSpinner.vue -->
<template>
    <div class="loading-container">
        <div class="spinner"></div>
        <p>{{ message }}</p>
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

<style scoped lang="scss">
@use "@/assets/scss/components/loading-spinner";
</style>
