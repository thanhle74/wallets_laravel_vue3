<template>
    <MainLayout title="Transaction Management">
        <LoadingSpinner v-if="isLoading" message="Loading transactions..."/>

        <div v-else>
            <BulkActionsWithForm
                :bulkAction="bulkAction"
                @update:bulkAction="bulkAction = $event"
                :selectedItems="selectedItems"
                :confirmMassDelete="confirmMassDelete"
                :handleBulkAction="handleBulkAction"
            >
                <template #form>
                    <TransactionForm
                        :transaction="newItem"
                        @create="addItem"
                    />
                </template>
            </BulkActionsWithForm>

            <TransactionFilter @filter="handleFilter"/>

            <div
                v-if="filters.from || filters.to || filters.category || filters.wallet"
                class="flex items-center gap-2 p-3 rounded-lg bg-background-body text-amethyst-purple text-sm mb-4"
            >
                <i class="ti-filter text-base"></i>
                <div>
                    <p class="mb-0">
                        <span class="font-medium">Đã lọc</span>
                        <span v-if="filters.from"> từ <strong>{{ new Date(filters.from).toLocaleDateString('vi-VN') }}</strong></span>
                        <span v-if="filters.to"> đến <strong>{{ new Date(filters.to).toLocaleDateString('vi-VN') }}</strong></span>,
                        <span v-if="filters.category"> danh mục <strong>{{ selectedCategoryName }}</strong></span>
                        <span v-if="filters.wallet"> ví <strong>{{ selectedWalletName }}</strong></span>.
                        tổng cộng <strong>{{ items.length }}</strong> kết quả.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Tiền vào -->
                <div class="rounded bg-badge-active p-4 shadow flex items-center gap-4">
                    <div class="text-color-active text-3xl">
                        <i class="ti-arrow-down"></i>
                    </div>
                    <div>
                        <p class="text-sm mb-1">Tổng tiền vào</p>
                        <p class="text-color-active text-xl font-semibold">
                            {{ totalIn.toLocaleString('vi-VN') }} ₫
                        </p>
                    </div>
                </div>

                <!-- Tiền ra -->
                <div class="rounded bg-mulberry-purple p-4 shadow flex items-center gap-4">
                    <div class="text-torch-red text-3xl">
                        <i class="ti-arrow-up"></i>
                    </div>
                    <div>
                        <p class="text-sm mb-1">Tổng tiền ra</p>
                        <p class="text-torch-red text-xl font-semibold">
                            {{ totalOut.toLocaleString('vi-VN') }} ₫
                        </p>
                    </div>
                </div>
            </div>

            <DataTable>
                <template #thead>
                    <th></th>
                    <th v-if="isAdmin">ID</th>
                    <th>Category</th>
                    <th>Wallet</th>
                    <th>Amount</th>
                    <th>Day</th>
                    <th v-if="isAdmin">User</th>
                    <th class="text-center">Actions</th>
                </template>
                <template #tbody>
                    <tr v-for="(transaction, index) in items" :key="index">
                        <td class="text-center"><input type="checkbox" :value="transaction.id" v-model="selectedItems"/>
                        </td>
                        <td v-if="isAdmin">{{ transaction.id }}</td>
                        <td>{{ transaction.category.name }}</td>
                        <td>{{ transaction.wallet.name }}</td>
                        <td>
                            <StatusBadge
                                :status="transaction.is_income"
                                :show-icon="false"
                                :active-text="'+ ' + Number(transaction.amount || 0).toLocaleString('vi-VN')"
                                :disabled-text="'- ' +Number(transaction.amount || 0).toLocaleString('vi-VN')"
                            />
                        </td>

                        <td>{{ new Date(transaction.transaction_date).toLocaleDateString("vi-VN") }}</td>
                        <td v-if="isAdmin">{{ transaction.user.name }}</td>
                        <td class="text-center">
                            <Button
                                btnClass="bg-deep-navy text-cerulean-blue mr-1 hover:bg-midnight-blue rounded-md"
                                icon="ti-eye"
                                @click="showTransactionDetail(transaction)"
                            />
                        </td>
                    </tr>
                </template>
            </DataTable>
        </div>

        <TransactionDetailModal
            v-if="selectedTransaction"
            :transaction="selectedTransaction"
            @close="selectedTransaction = null"
        />
        <ConfirmDeleteModal
            :show="deleteMassConfirm"
            message="Are you sure you want to delete selected transactions?"
            @confirm="handleMassDelete"
            @cancel="deleteMassConfirm = false"
        />
    </MainLayout>
</template>

<script setup>
import {useTransaction} from "@/composables/Transaction/useTransaction.js";
import {useCrudPage} from "@/composables/useCrudPage.js";
import {ref, computed} from "vue";
import MainLayout from '@/views/layout/MainLayout.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import TransactionDetailModal from "@/views/Transaction/components/TransactionDetailModal.vue";
import StatusBadge from "@/components/StatusBadge.vue";
import TransactionForm from "@/views/Transaction/components/TransactionForm.vue";
import Button from "@/components/Button.vue";
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import BulkActionsWithForm from "@/components/BulkActionsWithForm.vue";
import DataTable from "@/components/DataTable.vue";
import TransactionFilter from "@/views/Transaction/components/TransactionFilter.vue";
import { useCategory } from "@/composables/Category/useCategory.js";
import { useWallet } from "@/composables/Wallet/useWallet.js";

const {
    items,
    newItem,
    isLoading,
    addItem,
    isAdmin,
    selectedItems,
    deleteMassConfirm,
    bulkAction,
    fetchItems,
    confirmMassDelete,
    handleMassDelete,
    handleBulkAction
} = useCrudPage(useTransaction);

const selectedTransaction = ref(null);
const showTransactionDetail = (transaction) => {
    selectedTransaction.value = transaction;
};

const filters = ref({});

const handleFilter = (filterData) => {
    filters.value = filterData;
    fetchFilteredTransactions();
};

const fetchFilteredTransactions = async () => {
    const params = {};

    if (filters.value.from) {
        params.from = filters.value.from;
    }

    if (filters.value.to) {
        params.to = filters.value.to;
    }

    if (filters.value.category) {
        params.category = filters.value.category;
    }

    if (filters.value.wallet) {
        params.wallet = filters.value.wallet;
    }

    await fetchItems(params);
};

const totalIn = computed(() =>
    items.value
        .filter(item => item.is_income === 1)  // Tiền vào
        .reduce((sum, item) => sum + parseFloat(item.amount), 0)
);

const totalOut = computed(() =>
    items.value
        .filter(item => item.is_income === 0)  // Tiền ra
        .reduce((sum, item) => sum + parseFloat(item.amount), 0)
);

const { items: categoryItems } = useCrudPage(useCategory);
const selectedCategoryName = computed(() => {
    if (!filters.value.category) return null;
    const category = categoryItems.value.find(cat => cat.id === filters.value.category);
    return category ? category.name : filters.value.category;
});

const { items: walletItems } = useCrudPage(useWallet);
const selectedWalletName = computed(() => {
    if (!filters.value.wallet) return null;
    const wallet = walletItems.value.find(cat => cat.id === filters.value.wallet);
    return wallet ? wallet.name : filters.value.wallet;
});

</script>
