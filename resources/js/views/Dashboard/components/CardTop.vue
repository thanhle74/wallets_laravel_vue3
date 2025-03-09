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
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-item">
                    <p>{{ formatCurrency(stats.yearly) }}</p>
                    <span>This Year</span>
                </div>
            </div>

            <div class="card">
                <div class="card-icon income month">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="card-item">
                    <p>{{ formatCurrency(stats.monthly) }}</p>
                    <span>This Month</span>
                </div>
            </div>
            <div class="card">
                <div class="card-icon income week">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="card-item">
                    <p>{{ formatCurrency(stats.weekly) }}</p>
                    <span>This Week</span>
                </div>
            </div>
            <div class="card">
                <div class="card-icon income day">
                    <i class="fas fa-calendar-day"></i>
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

<style scoped lang="scss">
.card-header-dashboard {
    display: flex;
    flex-direction: column;
    padding: 10px;
    h3{
        font-size: 1.8rem;
    }
    button{
        padding: 10px;
    }
    .card-header{
        display: flex;
        justify-content: space-between;
        margin-bottom: 4rem;
    }
    .card-content{
        display: flex;
        justify-content: space-between;
    }
    .card {
        display: flex;
        align-items: center;
        gap: 15px;
        i{
            font-size: 2rem;
        }
        p {
            font-size: 2rem;
            color: #fff;
        }
        span{
            font-size: 12px;
        }

        .card-icon {
            border-radius: 50%;
            padding: 15px;
            &.year {
                color: #ff4c51;
                background: #50374a;
            }
            &.month {
                color: #7367f0;
                background: #3a3b64;
            }
            &.week {
                color: #00bad1;
                background: #27495f;
            }
            &.day {
                color: #28c76f;
                background: #2e4b4f;
            }
        }
    }
}
</style>
