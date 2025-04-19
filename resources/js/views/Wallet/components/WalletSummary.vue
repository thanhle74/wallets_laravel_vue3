<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <SummaryCard
            label="Tổng Số Dư"
            icon="ti-pie-chart"
            :amount="totalAll"
            iconColor="text-button-warning-text"
        />
        <SummaryCard
            label="Tiền Mặt"
            icon="ti-money"
            :amount="totalCash"
            iconColor="text-color-active"
        />
        <SummaryCard
            label="Ngân Hàng"
            icon="ti-credit-card"
            :amount="totalBank"
            iconColor="text-cerulean-blue"
        />
        <SummaryCard
            label="Tiền Ảo"
            icon="ti-shield"
            :amount="totalCrypto"
            iconColor="text-background-primary"
        />
    </div>
</template>

<script setup>
import { computed } from 'vue';
import SummaryCard from '@/components/SummaryCard.vue';

const props = defineProps({
    items: {
        type: Array,
        required: true
    }
});

const totalCash = computed(() =>
    props.items
        .filter(w => w.type === 2)
        .reduce((sum, w) => sum + parseFloat(w.balance || 0), 0)
);

const totalBank = computed(() =>
    props.items
        .filter(w => w.type === 1)
        .reduce((sum, w) => sum + parseFloat(w.balance || 0), 0)
);

const totalCrypto = computed(() =>
    props.items
        .filter(w => w.type === 3)
        .reduce((sum, w) => sum + parseFloat(w.balance || 0), 0)
);

const totalAll = computed(() =>
    props.items.reduce((sum, w) => sum + parseFloat(w.balance || 0), 0)
);
</script>
