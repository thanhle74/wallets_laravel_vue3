<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <SummaryCard
            label="Chi phí tháng qua"
            :amount="monthTotal"
            icon="ti-credit-card"
            iconColor="text-color-active"
        />
        <SummaryCard
            label="Chi phí tuần này"
            :amount="weekTotal"
            icon="ti-credit-card"
            iconColor="text-cerulean-blue"
        />
        <SummaryCard
            label="Chi phí hôm qua"
            :amount="yesterdayTotal"
            icon="ti-wallet"
            iconColor="text-amethyst-purple"
        />
        <SummaryCard
            label="Chi phí hôm nay"
            :amount="todayTotal"
            icon="ti-wallet"
            iconColor="text-torch-red"
        />
    </div>
</template>

<script setup>
import { computed } from "vue";
import SummaryCard from "@/components/SummaryCard.vue";
import {useTransaction} from "@/composables/Transaction/useTransaction.js";
import {useCrudPage} from "@/composables/useCrudPage.js";
const {
    items
} = useCrudPage(useTransaction);

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
});

const todayTotal = computed(() => {
    const today = new Date().setHours(0, 0, 0, 0);
    return props.items
        .filter(item => item.is_income === 0 && new Date(item.transaction_date).setHours(0, 0, 0, 0) === today)
        .reduce((sum, item) => sum + parseFloat(item.amount), 0);
});

const yesterdayTotal = computed(() => {
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    yesterday.setHours(0, 0, 0, 0);
    const target = yesterday.getTime();

    return props.items
        .filter(item => {
            if (item.is_income !== 0) return false;
            const itemDate = new Date(item.transaction_date);
            itemDate.setHours(0, 0, 0, 0);
            return itemDate.getTime() === target;
        })
        .reduce((sum, item) => sum + parseFloat(item.amount), 0);
});

const weekTotal = computed(() => {
    const startOfWeek = new Date();
    startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay());
    startOfWeek.setHours(0, 0, 0, 0);
    return props.items
        .filter(item => item.is_income === 0 && new Date(item.transaction_date).setHours(0, 0, 0, 0) >= startOfWeek)
        .reduce((sum, item) => sum + parseFloat(item.amount), 0);
});

const monthTotal = computed(() => {
    const startOfMonth = new Date();
    startOfMonth.setDate(1);
    startOfMonth.setHours(0, 0, 0, 0);
    return props.items
        .filter(item => item.is_income === 0 && new Date(item.transaction_date).setHours(0, 0, 0, 0) >= startOfMonth)
        .reduce((sum, item) => sum + parseFloat(item.amount), 0);
});
</script>
