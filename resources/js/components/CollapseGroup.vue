<template>
    <div class="mb-4 border border-border-line rounded-lg overflow-hidden">
        <button
            @click="isOpen = !isOpen"
            class="w-full text-left px-4 py-2 bg-background-body font-medium flex justify-between items-center"
        >
            <span>{{ title }}</span>
            <i :class="isOpen ? 'ti-angle-up' : 'ti-angle-down'"></i>
        </button>
        <transition name="fade">
            <div v-if="isOpen" class="p-4 bg-background-body">
                <slot name="content" />
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    title: String,
    defaultOpen: {
        type: Boolean,
        default: false
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

const isOpen = ref(props.defaultOpen);

// Tự mở Collapse nếu có filter đang được sử dụng
watch(() => props.filters, (newFilters) => {
    const hasFilter = Object.values(newFilters).some(v => v !== null && v !== '');
    if (hasFilter) isOpen.value = true;
}, { immediate: true, deep: true });
</script>


<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: scaleY(0.95);
}
</style>
