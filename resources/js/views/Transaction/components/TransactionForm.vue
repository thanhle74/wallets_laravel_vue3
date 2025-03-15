<template>
    <div class="transaction-create">
        <Button btnClass="btn-info" text="Add Transaction" icon="ti-plus" @click="isOpen = true"/>
    </div>

    <div v-if="isOpen" class="offcanvas-overlay" @click="closeOffcanvas"></div>

    <div class="offcanvas-container" :class="{ 'open': isOpen }">
        <div class="offcanvas-header">
            <h3>Add Transaction</h3>
            <Button btnClass="btn-close" icon="ti-close" @click="closeOffcanvas"/>
        </div>

        <div class="offcanvas-body">
            <div class="form-group">
                <label for="income">Income</label>
                <select id="income" v-model="transaction.is_income">
                    <option :value="0">No</option>
                    <option :value="1">Yes</option>
                </select>
            </div>

            <!-- Amount -->
            <div class="form-group">
                <label for="amount">Amount</label>
                <input id="amount" v-model="transaction.amount" placeholder="Enter Amount" />
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" v-model="transaction.category_id">
                    <option value="" selected>Select a category</option>
                    <option
                        v-for="(category, index) in categories.filter(c => c.status === 1)"
                        :key="index"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <!-- Wallet -->
            <div class="form-group">
                <label for="wallet">Wallet</label>
                <select id="wallet" v-model="transaction.wallet_id">
                    <option value="" selected>Select a wallet</option>
                    <option
                        v-for="(wallet, index) in wallets.filter(w => w.status === 1)"
                        :key="index"
                        :value="wallet.id"
                    >
                        {{ wallet.name }} ---- {{ Number(wallet.balance).toLocaleString("vi-VN") }} đ
                    </option>
                </select>
            </div>

            <!-- Transaction Date -->
            <div class="form-group">
                <label for="transaction_date">Date</label>
                <VueDatePicker id="transaction_date" v-model="transaction.transaction_date" utc />
            </div>

            <div class="form-group">
                <label for="description">Note</label>
                <textarea id="description" v-model="transaction.description" placeholder="Enter note" rows="5" />
            </div>

            <Button btnClass="btn-info" text="Add Transaction" icon="ti-plus" @click="submitTransaction"/>
        </div>
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

<style scoped>

.transaction-create {
    text-align: right;
    .btn-info {
        width: auto;
    }
}

h3{
    font-weight: 500;
    font-size: 2rem;
}

.btn-info{
    width: 100%;
    padding: 10px;
}

.offcanvas-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10;
}

/* Offcanvas Container */
.offcanvas-container {
    position: fixed;
    top: 0;
    right: -400px; /* Ban đầu ẩn đi */
    width: 350px;
    height: 100vh;
    background: #2f3349;
    box-shadow: -2px 0px 10px rgba(0, 0, 0, 0.2);
    padding: 20px;
    transition: right 0.3s ease-in-out;
    z-index: 20;
}

.offcanvas-container.open {
    right: 0;
}

.offcanvas-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 10px;
}

/* Form Group */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-bottom: 15px;
}

.form-group label {
    padding-bottom: 10px;
}

.form-group input,
.form-group select {
    border-radius: 5px;
}

</style>
