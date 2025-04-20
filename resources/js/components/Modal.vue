<template>
    <div class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center bg-modal-overlay">
        <div :class = "['bg-background-section p-6 rounded-lg', customClass]">
            <slot/>
        </div>
    </div>
</template>
<script setup>
defineProps({
    customClass: String,
});
</script>
