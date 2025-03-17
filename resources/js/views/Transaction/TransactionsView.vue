<template>
    <MainLayout title="Transaction Management">
        <LoadingSpinner v-if="isLoading" message="Loading transactions..." />

        <div v-else>
            <TransactionForm
                :transaction="newTransaction"
                @create="handleAddTransaction"
            />
<!--            <TransactionFilter/>-->

                <table id="tableTransaction" class="table-striped">
                    <thead>
                        <tr class="bg-royal-purple text-amethyst-purple">
                            <th v-if="isAdmin">ID</th>
                            <th>Category</th>
                            <th>Wallet</th>
                            <th>Amount</th>
                            <th>Day</th>
                            <th v-if="isAdmin">User</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(transaction, index) in transactions" :key="index">
                            <td v-if="isAdmin">{{ transaction.id }}</td>
                            <td>{{ transaction.category.name }}</td>
                            <td>{{ transaction.wallet.name }}</td>
                            <td>
                                <StatusBadge
                                    :status="transaction.is_income"
                                    :show-icon="false"
                                    :active-text= "'+ ' + Number(transaction.amount || 0).toLocaleString('vi-VN')"
                                    :disabled-text="'- ' +Number(transaction.amount || 0).toLocaleString('vi-VN')"
                                />
                            </td>

                            <td>{{ new Date(transaction.transaction_date).toLocaleDateString("vi-VN") }}</td>
                            <td v-if="isAdmin">{{ transaction.user.name }}</td>
                            <td class="text-center">
                                <Button 
                                    btnClass="bg-deep-navy text-cerulean-blue mr-1 hover:bg-midnight-blue" 
                                    icon="ti-eye" 
                                    @click="showTransactionDetail(transaction)"
                                />
                                <Button 
                                    btnClass="bg-charcoal-gray text-slate-gray hover:bg-storm-gray hover:text-lavender-gray" 
                                    icon="ti-trash" 
                                    @click="confirmDelete(transaction.id)"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
        </div>

        <ConfirmDeleteModal
            :show="deleteConfirmId !== null"
            message="Are you sure you want to delete this transaction?"
            @confirm="handleDeleteTransaction"
            @cancel="deleteConfirmId = null"
        />

        <TransactionDetailModal
            v-if="selectedTransaction"
            :transaction="selectedTransaction"
            @close="selectedTransaction = null"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/views/layout/MainLayout.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import TransactionDetailModal from "@/views/Transaction/components/TransactionDetailModal.vue";
import StatusBadge from "@/components/StatusBadge.vue";
import {useTransaction} from "@/composables/useTransaction";
import {useUserAccount} from "@/composables/Account/useUserAccount";
import TransactionFilter from "@/views/Transaction/components/TransactionFilter.vue";
import TransactionForm from "@/views/Transaction/components/TransactionForm.vue";
import Button from "@/components/Button.vue";
import { ref, onMounted, watch, nextTick } from "vue";
import $ from 'jquery';
import 'datatables.net';

const {
    transactions,
    newTransaction,
    isLoading,
    fetchTransactions,
    addTransaction,
    deleteTransaction,
} = useTransaction();

const {fetchIsAdmin} = useUserAccount();
const isAdmin = ref(false);
const deleteConfirmId = ref(null);
const selectedTransaction = ref(null);

const showTransactionDetail = (transaction) => {
    selectedTransaction.value = transaction;
};

const handleAddTransaction = async () => {
    await addTransaction();
};

const handleDeleteTransaction = async () => {
    if (!deleteConfirmId.value) return;

    await deleteTransaction(deleteConfirmId.value);
    deleteConfirmId.value = null;
};

const confirmDelete = (id) => {
    deleteConfirmId.value = id;
};

const initDataTable = () => {
    nextTick(() => {
        let table = $("#tableTransaction");
        table.DataTable().destroy();
        table.DataTable();
    });
};

watch(transactions, (newValue) => {
    if (newValue.length) {
        initDataTable();
    }
});

onMounted(async () => {
    const result = await fetchIsAdmin();
    isAdmin.value = result ?? false;
    await fetchTransactions();
    initDataTable();
});
</script>
