<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <!-- Tiền vào -->
        <div class="rounded bg-badge-active p-4 shadow flex items-center gap-4">
            <div class="text-color-active text-3xl">
                <i class="ti-arrow-down"></i>
            </div>
            <div>
                <p class="text-sm mb-1">Tổng tiền vào</p>
                <p class="text-color-active text-xl font-semibold">
                    {{ totalIn.toLocaleString('vi-VN') }} ₫
                </p>
            </div>
        </div>

        <!-- Tiền ra -->
        <div class="rounded bg-mulberry-purple p-4 shadow flex items-center gap-4">
            <div class="text-torch-red text-3xl">
                <i class="ti-arrow-up"></i>
            </div>
            <div>
                <p class="text-sm mb-1">Tổng tiền ra</p>
                <p class="text-torch-red text-xl font-semibold">
                    {{ totalOut.toLocaleString('vi-VN') }} ₫
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    items: Array,
});

const totalIn = computed(() =>
    props.items
        .filter(item => item.is_income === 1)  // Tiền vào
        .reduce((sum, item) => sum + parseFloat(item.amount), 0)
);

const totalOut = computed(() =>
    props.items
        .filter(item => item.is_income === 0)  // Tiền ra
        .reduce((sum, item) => sum + parseFloat(item.amount), 0)
);
</script>
