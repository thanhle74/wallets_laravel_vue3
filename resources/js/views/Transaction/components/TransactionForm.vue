<template>
    <!-- Nút mở form -->
    <div class="text-right mb-4">
        <Button 
            btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple" 
            text="Add Transaction" 
            icon="ti-plus" 
            @click="isOpen = true"
        />
    </div>

    <!-- Overlay -->
    <div 
        v-if="isOpen" 
        class="fixed inset-0 bg-modal-overlay bg-opacity-50 z-40 transition-opacity duration-300"
        @click="closeOffcanvas"
    ></div>

    <!-- Offcanvas Form -->
    <div 
        class="fixed top-0 right-0 w-full max-w-md h-full bg-background-body shadow-lg z-50 transform transition-transform duration-300"
        :class="{ 'translate-x-0': isOpen, 'translate-x-full': !isOpen }"
    >
        <!-- Header -->
        <div class="flex justify-between items-center p-4 border-b border-border-line">
            <h3 class="text-lg">Add Transaction</h3>
            <Button 
                btnClass="text-gray-500 hover:text-red-500 transition"
                icon="ti-close" 
                @click="closeOffcanvas"
            />
        </div>

        <!-- Body -->
        <form class="p-4 space-y-4 overflow-y-auto h-[calc(100%-65px)]" @submit.prevent="submitTransaction">
            <div>
                <label for="income" class="block font-medium mb-1">Income</label>
                <select 
                    id="income" 
                    v-model="transaction.is_income"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option :value="0">No</option>
                    <option :value="1">Yes</option>
                </select>
            </div>

            <div>
                <label for="amount" class="block font-medium mb-1">Amount</label>
                <input 
                    id="amount" 
                    v-model="transaction.amount" 
                    placeholder="Enter Amount" 
                    type="number"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>

            <div>
                <label for="category" class="block font-medium mb-1">Category</label>
                <select 
                    id="category" 
                    v-model="transaction.category_id"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="" disabled>Select a category</option>
                    <option
                        v-for="(category, index) in categories.filter(c => c.status === 1)"
                        :key="index"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <div>
                <label for="wallet" class="block font-medium mb-1">Wallet</label>
                <select 
                    id="wallet" 
                    v-model="transaction.wallet_id"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="" disabled>Select a wallet</option>
                    <option
                        v-for="(wallet, index) in wallets.filter(w => w.status === 1)"
                        :key="index"
                        :value="wallet.id"
                    >
                        {{ wallet.name }} ---- {{ Number(wallet.balance).toLocaleString("vi-VN") }} đ
                    </option>
                </select>
            </div>

            <div>
                <label for="transaction_date" class="block font-medium mb-1">Date</label>
                <VueDatePicker 
                    id="transaction_date"
                    v-model="transaction.transaction_date"
                    utc
                    class="w-full rounded-md"
                />
            </div>

            <div>
                <label for="description" class="block font-medium mb-1">Note</label>
                <textarea 
                    id="description" 
                    v-model="transaction.description" 
                    placeholder="Enter note" 
                    rows="4"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                ></textarea>
            </div>

            <div class="text-right">
                <Button 
                    btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple" 
                    text="Add Transaction" 
                    icon="ti-plus"
                    type="submit"
                />
            </div>
        </form>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, onMounted, ref } from "vue";
import { useCategory } from "@/composables/useCategory";
import { useWallet } from "@/composables/Wallet/useWallet";
import VueDatePicker from "@vuepic/vue-datepicker";
import Button from "@/components/Button.vue";
import "@vuepic/vue-datepicker/dist/main.css";

const { categories, fetchCategories } = useCategory();
const { wallets, fetchWallets } = useWallet();
const isOpen = ref(false);

defineProps({
    transaction: Object,
});

const emit = defineEmits(["create"]);

onMounted(async () => {
    await fetchCategories();
    await fetchWallets();
});

const closeOffcanvas = () => {
    isOpen.value = false;
};

const submitTransaction = () => {
    emit("create");
    closeOffcanvas();
};
</script>
