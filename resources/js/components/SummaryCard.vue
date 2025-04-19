<template>
    <div :class="['rounded p-4 flex items-center gap-4 border border-border-line', bgColor]">
        <div :class="[iconColor, 'text-3xl']">
            <i :class="icon"></i>
        </div>
        <div>
            <p class="text-sm mb-1">{{ label }}</p>
            <p :class="['text-xl']">
                {{ amount.toLocaleString('vi-VN') }} ₫
            </p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    label: String,
    icon: String,
    amount: Number,
    bgColor: String,
    iconColor: String,
});
</script>
