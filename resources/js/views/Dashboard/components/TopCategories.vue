<template>
    <div class="col-span-6">
        <h3 class="text-lg mb-2">Top Spending Categories</h3>
        <table>
            <thead>
            <tr class="bg-royal-purple text-amethyst-purple">
                <th>Danh mục</th>
                <th>Tổng chi tiêu</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(category, index) in categories" :key="index">
                <td>{{ category.name }}</td>
                <td><strong>{{ category.total_spent.toLocaleString("en-US") }} đ</strong></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import dashboardService from "@/services/dashboardService.js";

const categories = ref([]);

const fetchCategories = async () => {
    try {
        const response = await dashboardService.getTopCategories();
        categories.value = response.data.data;
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
};

onMounted(fetchCategories);
</script>
