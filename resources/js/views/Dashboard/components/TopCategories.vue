<template>
    <div class="top-categories">
        <h3>Top Spending Categories</h3>
        <ul>
            <li v-for="(category, index) in categories" :key="index">
                <span>{{ category.name }}</span>
                <strong>{{ category.total_spent.toLocaleString("en-US") }} đ</strong>
            </li>
        </ul>
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

<style scoped>
.top-categories {
    margin-top: 20px;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
    margin-bottom: 8px;
}
</style>
