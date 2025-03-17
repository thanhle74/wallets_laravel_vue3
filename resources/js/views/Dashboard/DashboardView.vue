<template>
	<MainLayout>
        <CardTop/>
        <MonthlyExpenseChart />
<!--        <CompareMonthsChart/>-->
        <div class="dashboard-box">
            <TopCategories/>
            <RecentTransactions/>
        </div>
	</MainLayout>
</template>

<script setup>
import MainLayout from '@/views/layout/MainLayout.vue';
import CardTop from "@/views/Dashboard/components/CardTop.vue";
import MonthlyExpenseChart from "@/views/Dashboard/components/MonthlyExpenseChart.vue";
import TopCategories from "@/views/Dashboard/components/TopCategories.vue";
import RecentTransactions from "@/views/Dashboard/components/RecentTransactions.vue";
import CompareMonthsChart from "@/views/Dashboard/components/CompareMonthsChart.vue";

</script>
