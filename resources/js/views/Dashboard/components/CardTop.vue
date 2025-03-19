<template>
    <div class="card-header-dashboard">
        <div class="card-header">
            <h3 class="text-lg">Statistics</h3>
            <div class="transaction-create">
                <TransactionForm
                    :transaction="newItem"
                    @create="handleAddTransaction"
                />
            </div>
        </div>
        <div class="grid grid-cols-12 gap-2 my-15">
            <div class="col-span-3 w-full flex items-center">
                <div class="bg-mulberry-purple text-2xl rounded-(--radius-circle) text-torch-red px-4 py-3">
                    <i class="ti-stats-up"></i>
                </div>
                <div class="ml-2">
                    <p class="text-lg font-medium">{{ formatCurrency(stats.yearly) }}</p>
                    <span class="text-xs">This Year</span>
                </div>
            </div>

            <div class="col-span-3 w-full flex items-center">
                <div class="bg-indigo-night text-amethyst-purple text-2xl rounded-(--radius-circle) px-4 py-3">
                    <i class="ti-calendar"></i>
                </div>
                <div class="ml-2">
                    <p class="text-lg font-medium">{{ formatCurrency(stats.monthly) }}</p>
                    <span class="text-xs">This Month</span>
                </div>
            </div>
            <div class="col-span-3 w-full flex items-center">
                <div class="bg-deep-navy text-cerulean-blue text-2xl rounded-(--radius-circle) px-4 py-3">
                    <i class="ti-calendar"></i>
                </div>
                <div class="ml-2">
                    <p class="text-lg font-medium">{{ formatCurrency(stats.weekly) }}</p>
                    <span class="text-xs">This Week</span>
                </div>
            </div>
            <div class="col-span-3 w-full flex items-center">
                <div class="bg-badge-active text-color-active text-2xl rounded-(--radius-circle) px-4 py-3">
                    <i class="ti-timer"></i>
                </div>
                <div class="ml-2">
                    <p class="text-lg font-medium">{{ formatCurrency(stats.daily) }}</p>
                    <span class="text-xs">Today</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import dashboardService from "@/services/dashboardService.js";
import toastr from "toastr";
import TransactionForm from "@/views/Transaction/components/TransactionForm.vue";
import { useTransaction } from "@/composables/Transaction/useTransaction.js";

const {newItem, addItem,} = useTransaction();
const stats = ref({
    daily: 0,
    weekly: 0,
    monthly: 0,
    yearly: 0
});
const handleAddTransaction = async () => {
    await addItem();
};

// Hàm định dạng tiền tệ với dấu ',' mỗi 3 chữ số
const formatCurrency = (value) => {
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
        minimumFractionDigits: 2
    }).format(value);
};

const fetchStats = async () => {
    try {
        const response = await dashboardService.dashboardStats();
        stats.value = response.data.data;
    } catch (error) {
        toastr.error("Error fetching stats!");
        console.error("Error fetching stats:", error);
    }
};

onMounted(fetchStats);
</script>
