<template>
    <div class="filter-container flex flex-wrap items-end gap-4 mb-6">
        <!-- From Date -->
        <div class="flex flex-col">
            <label for="from" class="text-sm mb-1">Từ ngày</label>
            <input
                type="date"
                id="from"
                v-model="fromDate"
            />
        </div>

        <!-- To Date -->
        <div class="flex flex-col">
            <label for="to" class="text-sm mb-1">Đến ngày</label>
            <input
                type="date"
                id="to"
                v-model="toDate"
            />
        </div>

        <!-- Category -->
        <div class="flex flex-col">
            <label class="text-sm mb-1">Danh mục</label>
            <select v-model="selectedCategory">
                <option value="">Tất cả</option>
                <option v-for="category in categoryItems" :key="category.id" :value="category.id">
                    {{ category.name }}
                </option>
            </select>
        </div>

        <!-- Wallet -->
        <div class="flex flex-col">
            <label class="text-sm mb-1">Ví</label>
            <select v-model="selectedWallet">
                <option value="">Tất cả</option>
                <option v-for="wallet in walletItems" :key="wallet.id" :value="wallet.id">
                    {{ wallet.name }}
                </option>
            </select>
        </div>

        <!-- Reset Button -->
        <button
            v-if="fromDate || toDate || selectedCategory || selectedWallet"
            @click="resetFilter"
            class="bg-deep-navy text-cerulean-blue mr-1 hover:bg-midnight-blue px-3 py-2 rounded-md"
        >
            <i class="ti-reload"></i>
            Đặt lại
        </button>

        <!-- Filter Button -->
        <Button
            btnClass="bg-button-warning-bg text-button-warning-text rounded-md transition-all duration-200"
            icon="ti-filter"
            label="Lọc"
            text="Lọc"
            @click="applyFilter"
        />
    </div>
</template>

<script setup>
import { ref } from "vue";
import Button from "@/components/Button.vue";
import { useCategory } from "@/composables/Category/useCategory.js";
import { useWallet } from "@/composables/Wallet/useWallet.js";
import { useCrudPage } from "@/composables/useCrudPage.js";

const { items: categoryItems } = useCrudPage(useCategory);
const { items: walletItems } = useCrudPage(useWallet);

const emit = defineEmits(["filter"]);

const fromDate = ref("");
const toDate = ref("");
const selectedCategory = ref("");
const selectedWallet = ref("");

const applyFilter = () => {
    emit("filter", {
        from: fromDate.value || null,
        to: toDate.value || null,
        category: selectedCategory.value || null,
        wallet: selectedWallet.value || null,
    });
};

const resetFilter = () => {
    fromDate.value = "";
    toDate.value = "";
    selectedCategory.value = "";
    selectedWallet.value = "";
    applyFilter();
};
</script>
