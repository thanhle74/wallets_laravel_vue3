<template>
    <MainLayout title="Wallet Management" icon="ti-wallet">
        <LoadingSpinner v-if="isLoading" message="Loading wallets..."/>

        <div v-else>
            <WalletSummary :items="items" />

            <BulkActionsWithForm
                :bulkAction="bulkAction"
                @update:bulkAction="bulkAction = $event"
                :selectedItems="selectedItems"
                :confirmMassDelete="confirmMassDelete"
                :handleBulkAction="handleBulkAction"
            >
                <template #form>
                    <WalletForm
                        :wallet="newItem"
                        :isEditing="editedItem"
                        @create="addItem"
                        @update="updateItem"
                        @cancel="handleCancelUpdate"
                    />
                </template>
            </BulkActionsWithForm>

            <DataTable>
                <template #thead>
                    <th></th>
                    <th v-if="isAdmin">ID</th>
                    <th>Name</th>
                    <th>Balance</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th v-if="isAdmin">User</th>
                    <th class="text-center">Actions</th>
                </template>
                <template #tbody>
                    <tr v-for="(wallet, index) in items" :key="index">
                        <td class="text-center"><input type="checkbox" :value="wallet.id" v-model="selectedItems" /></td>
                        <td v-if="isAdmin">{{ wallet.id }}</td>
                        <td>{{ wallet.name }}</td>
                        <td>{{ Number(wallet.balance || 0).toLocaleString("vi-VN") }} đ</td>
                        <td>{{ getTypeText(wallet.type) }}</td>
                        <td><StatusBadge :status="wallet.status"/></td>
                        <td v-if="isAdmin">{{ wallet.user.name }}</td>
                        <td class="text-center">
                            <Button
                                btnClass="bg-deep-navy text-cerulean-blue mr-1 hover:bg-midnight-blue rounded-md"
                                icon="ti-pencil"
                                @click="editItem(wallet)"
                            />
                        </td>
                    </tr>
                </template>
            </DataTable>
        </div>

        <ConfirmDeleteModal
            :show="deleteMassConfirm"
            message="Are you sure you want to delete selected wallets?"
            @confirm="handleMassDelete"
            @cancel="deleteMassConfirm = false"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/views/layout/MainLayout.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import WalletForm from "@/views/Wallet/components/WalletForm.vue";
import Button from "@/components/Button.vue";
import StatusBadge from "@/components/StatusBadge.vue";
import DataTable from "@/components/DataTable.vue";
import BulkActionsWithForm from "@/components/BulkActionsWithForm.vue";
import {useWallet} from "@/composables/Wallet/useWallet";
import { useCrudPage } from "@/composables/useCrudPage.js";
import WalletSummary from "@/views/Wallet/components/WalletSummary.vue";
const {
    items,
    newItem,
    editedItem,
    isLoading,
    addItem,
    isAdmin,
    updateItem,
    selectedItems,
    deleteMassConfirm,
    bulkAction,
    confirmMassDelete,
    handleMassDelete,
    handleBulkAction,
    handleCancelUpdate,
    editItem,
} = useCrudPage(useWallet);

const getTypeText = (type) => {
    if (type === 1) {
        return "Bank";
    }
    if (type === 2) {
        return "Cash";
    }
    if (type === 3) {
        return "Crypto";
    }
}
</script>
