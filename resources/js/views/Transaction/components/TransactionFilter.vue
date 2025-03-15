<template>
    <div class="filter-container">
        <VueDatePicker v-model="dateRange" range :preset-dates="presetDates" />

        <select v-model="selectedCategory">
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
            </option>
        </select>

        <select v-model="selectedWallet">
            <option value="">All Wallets</option>
            <option v-for="wallet in wallets" :key="wallet.id" :value="wallet.id">
                {{ wallet.name }}
            </option>
        </select>

        <button @click="applyFilter">Filter</button>
    </div>
</template>

<script setup>
import {ref, onMounted} from "vue";
import {endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths} from "date-fns";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import {useCategory} from "@/composables/useCategory";
import {useWallet} from "@/composables/Wallet/useWallet";

const dateRange = ref(null);
const selectedCategory = ref("");
const selectedWallet = ref("");
const {categories, fetchCategories} = useCategory();
const {wallets, fetchWallets} = useWallet();

const presetDates = ref([
    {label: "Today", value: [new Date(), new Date()]},
    {label: "This month", value: [startOfMonth(new Date()), endOfMonth(new Date())]},
    {label: "Last month", value: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))]},
    {label: "This year", value: [startOfYear(new Date()), endOfYear(new Date())]},
]);

const emit = defineEmits(["filter"]);

const applyFilter = () => {
    emit("filter", {
        dateRange: dateRange.value,
        categoryId: selectedCategory.value,
        walletId: selectedWallet.value,
    });
};

onMounted(() => {
    fetchCategories();
    fetchWallets();
});
</script>

<style scoped>
.filter-container {
    display: flex;
    gap: 10px;
    align-items: center;
}
</style>
