<template>
    <div class="col-span-6">
        <h3 class="text-lg mb-2">Recent Transactions</h3>
        <table>
            <thead>
            <tr class="bg-royal-purple text-amethyst-purple">
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
                <td class="px-2 py-1 text-xs rounded-xs" 
                    :class="transaction.is_income ? 'bg-badge-active text-color-active' : 'bg-mulberry-purple text-torch-red'">
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
