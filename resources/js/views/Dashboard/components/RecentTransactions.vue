<template>
    <div class="recent-transactions">
        <h3>Recent Transactions</h3>
        <table>
            <thead>
            <tr>
                <th>Ví</th>
                <th>Danh mục</th>
                <th>Số tiền</th>
                <th>Ngày</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(transaction, index) in transactions" :key="index">
                <td>{{ transaction.wallet_name }}</td>
                <td>{{ transaction.category_name || 'Unknown' }}</td>
                <td :class="transaction.is_income ? 'income' : 'expense'">
                    {{ transaction.is_income ? '+' : '-' }}
                    {{ Number(transaction.amount).toLocaleString("vi-VN") }} đ
                </td>
                <td>{{ new Date(transaction.transaction_date).toLocaleDateString("vi-VN") }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import dashboardService from "@/services/dashboardService.js";

const transactions = ref([]);

const fetchTransactions = async () => {
    try {
        const response = await dashboardService.getRecentTransactions();
        transactions.value = response.data.data;
    } catch (error) {
        console.error("Error fetching transactions:", error);
    }
};

onMounted(fetchTransactions);
</script>
