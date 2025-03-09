<template>
    <div class="form-container">
        <select v-model="transaction.category_id">
            <option value="" disabled>Select a category</option>
            <option v-for="(category, index) in categories" :key="index" :value="category.id">
                {{ category.name }}
            </option>
        </select>

        <select v-model="transaction.wallet_id">
            <option value="" disabled>Select a wallet</option>
            <option v-for="(wallet, index) in wallets" :key="index" :value="wallet.id">
                {{ wallet.name }}
            </option>
        </select>

        <select v-model="transaction.status">
            <option :value="1">Active</option>
            <option :value="2">Disabled</option>
        </select>
        <button class="btn-info" @click="$emit('create')">
            <i class="fas fa-plus"></i> Add Transaction
        </button>
    </div>
</template>

<script setup>
import {defineProps, defineEmits, onMounted} from "vue";
import { useCategory } from "@/composables/useCategory";
import { useWallet } from "@/composables/useWallet";

const { categories, fetchCategories,} = useCategory();
const { wallets, fetchWallets} = useWallet();

defineProps({
    transaction: Object,
});

defineEmits(["create"]);

onMounted(async () => {
    await fetchCategories();
    await fetchWallets();
});

</script>
