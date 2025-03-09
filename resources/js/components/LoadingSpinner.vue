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

<style scoped>
.loading-container {
    text-align: center;
    padding: 20px;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #ccc;
    border-top-color: #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 10px auto;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
