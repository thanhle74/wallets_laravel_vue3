<template>
    <div class="card-header-dashboard">
        <div class="card-header">
            <h3>Statistics</h3>
            <div class="transaction-create">
                <TransactionForm
                    :transaction="newTransaction"
                    @create="handleAddTransaction"
                />
            </div>
        </div>
        <div class="card-content">
            <div class="card">
                <div class="card-icon income year">
                    <i class="ti-stats-up"></i>
                </div>
                <div class="card-item">
                    <p>{{ formatCurrency(stats.yearly) }}</p>
                    <span>This Year</span>
                </div>
            </div>

            <div class="card">
                <div class="card-icon income month">
                    <i class="ti-calendar"></i>
                </div>
                <div class="card-item">
                    <p>{{ formatCurrency(stats.monthly) }}</p>
                    <span>This Month</span>
                </div>
            </div>
            <div class="card">
                <div class="card-icon income week">
                    <i class="ti-calendar"></i> <!-- hoặc ti-agenda -->
                </div>
                <div class="card-item">
                    <p>{{ formatCurrency(stats.weekly) }}</p>
                    <span>This Week</span>
                </div>
            </div>
            <div class="card">
                <div class="card-icon income day">
                    <i class="ti-timer"></i> <!-- hoặc ti-time -->
                </div>
                <div class="card-item">
                    <p>{{ formatCurrency(stats.daily) }}</p>
                    <span>Today</span>
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
import { useTransaction } from "@/composables/useTransaction";

const {
    newTransaction,
    addTransaction,
} = useTransaction();

const stats = ref({
    daily: 0,
    weekly: 0,
    monthly: 0,
    yearly: 0
});

const handleAddTransaction = async () => {
    await addTransaction();
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
