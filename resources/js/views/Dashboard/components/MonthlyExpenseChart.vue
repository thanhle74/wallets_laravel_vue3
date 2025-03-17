<template>
    <div class="chart-container">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { Chart, registerables } from "chart.js";
import dashboardService from "@/services/dashboardService.js";

Chart.register(...registerables);

const chartCanvas = ref(null);
let chartInstance = null;

const expensesData = ref([]);
const incomeData = ref([]);
const daysOfMonth = ref([]);

const fetchChartData = async () => {
    try {
        const response = await dashboardService.getMonthlyStats();
        const data = response.data.data;

        expensesData.value = data.map(item => item.expenses);
        incomeData.value = data.map(item => item.income);
        daysOfMonth.value = data.map(item => item.date);

        renderChart();
    } catch (error) {
        console.error("Error fetching chart data:", error);
    }
};

const renderChart = () => {
    if (chartInstance) {
        chartInstance.destroy();
    }

    chartInstance = new Chart(chartCanvas.value, {
        type: "line",
        data: {
            labels: daysOfMonth.value,
            datasets: [
                {
                    label: "Expenses",
                    data: expensesData.value,
                    borderColor: "#FF5733",
                    backgroundColor: "rgba(255, 87, 51, 0.2)",
                    fill: true,
                    tension: 0.3,
                },
                {
                    label: "Income",
                    data: incomeData.value,
                    borderColor: "#28A745",
                    backgroundColor: "rgba(40, 167, 69, 0.2)",
                    fill: true,
                    tension: 0.3,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "top"
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: "Days of the Month"
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: "Amount"
                    }
                }
            }
        }
    });
};

onMounted(fetchChartData);
watch([expensesData, incomeData, daysOfMonth], renderChart);
</script>
