<template>
    <span :class="statusClass">
        <i v-if="showIcon" :class="statusIcon" class="me-1"></i>
        {{ statusText }}
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: [Number, String],
        required: true
    },
    activeText: {
        type: String,
        default: 'Active'
    },
    disabledText: {
        type: String,
        default: 'Disabled'
    },
    showIcon: {
        type: Boolean,
        default: true
    }
});

const statusClass = computed(() => {
    return props.status === 1 ? 'status-active' : 'status-disabled';
});

const statusIcon = computed(() => {
    return props.status === 1 ? 'ti-check' : 'ti-close';
});

const statusText = computed(() => {
    return props.status === 1 ? props.activeText : props.disabledText;
});
</script>

<style scoped lang="scss">
@use "@/assets/scss/components/status-badge";
</style>
