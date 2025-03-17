<script setup>
import { ref, onMounted, watch } from "vue";
import dashboardService from "@/services/dashboardService";
import { Chart as ChartJS, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from "chart.js";
import { Bar } from "vue-chartjs";

ChartJS.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend);
const months = ref(3);
const chartData = ref({ labels: [], datasets: [] });
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "top",
        },
    },
};

const fetchData = async () => {
    try {
        const response = await dashboardService.getExpensesByMonths(months.value);
        const data = response.data.data;

        chartData.value = {
            labels: data.map((item) => item.month),
            datasets: [
                {
                    label: "Chi tiêu",
                    backgroundColor: "#FF6384",
                    data: data.map((item) => item.total_expense),
                },
                {
                    label: "Thu nhập",
                    backgroundColor: "#36A2EB",
                    data: data.map((item) => item.total_income),
                },
            ],
        };
    } catch (error) {
        console.error("Error fetching expenses data", error);
    }
};

onMounted(fetchData);
watch(months, fetchData);
</script>

<template>
    <div class="chart-container">
        <div class="controls">
            <label for="months">Số tháng: </label>
            <select v-model="months" id="months" class="dropdown">
                <option :value="3">3 tháng</option>
                <option :value="6">6 tháng</option>
                <option :value="12">12 tháng</option>
            </select>
        </div>
        <div class="chart-wrapper">
            <Bar :data="chartData" :options="chartOptions" />
        </div>
    </div>
</template>
