<template>
    <MainLayout title="Wallet Management">
        <LoadingSpinner v-if="isLoading" message="Loading wallets..."/>

        <div v-else>
            <WalletForm
                :wallet="newWallet"
                :isEditing="editedWallet"
                @create="handleAddWallet"
                @update="handleUpdateWallet"
                @cancel="handleCancelUpdate"
            />

            <div class="table-responsive">
                <table id="walletTable" class="table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Balance</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th v-if="isAdmin">User</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(wallet, index) in wallets" :key="index">
                        <td>{{ wallet.id }}</td>
                        <td>{{ wallet.name }}</td>
                        <td>{{ Number(wallet.balance).toLocaleString("vi-VN") }} đ</td>
                        <td>{{ getTypeText(wallet.type)}}</td>
                        <td>
                            <span :class="getStatusClass(wallet.status)">
                              <i :class="getStatusIcon(wallet.status)"></i>
                              {{ getStatusText(wallet.status) }}
                            </span>
                        </td>
                        <td v-if="isAdmin">{{ wallet.user.name }}</td>
                        <td class="table-box-action">
                            <button class="btn-edit" @click="editWallet(wallet)">
                                <i class="ti-pencil"></i>
                            </button>
                            <button class="btn-cancel" @click="confirmDelete(wallet.id)">
                                <i class="ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <ConfirmDeleteModal
            :show="deleteConfirmId !== null"
            message="Are you sure you want to delete this wallet?"
            @confirm="handleDeleteWallet"
            @cancel="deleteConfirmId = null"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/views/layout/MainLayout.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import WalletForm from "@/views/Wallet/components/WalletForm.vue";
import { useWallet } from "@/composables/useWallet";
import { isAdminHelper } from "@/composables/helper/isAdminHelper";
import { ref, onMounted, watch, nextTick } from "vue";
import $ from 'jquery';
import 'datatables.net';

const {
    wallets,
    newWallet,
    editedWallet,
    isLoading,
    fetchWallets,
    addWallet,
    deleteWallet,
    updateWallet,
} = useWallet();

const { fetchIsAdmin } = isAdminHelper();

const isAdmin = ref(false);
const deleteConfirmId = ref(null);

const handleAddWallet = async () => {
    await addWallet();
};

const handleUpdateWallet = async () => {
    await updateWallet();
};

const handleDeleteWallet = async () => {
    if (!deleteConfirmId.value) return;

    await deleteWallet(deleteConfirmId.value);
    deleteConfirmId.value = null;
};

const handleCancelUpdate = () => {
    editedWallet.value = false;
};

const confirmDelete = (id) => {
    deleteConfirmId.value = id;
};

const editWallet = (wallet) => {
    editedWallet.value = { ...wallet };
};

const initDataTable = () => {
    nextTick(() => {
        let table = $("#walletTable");
        table.DataTable().destroy();
        table.DataTable();
    });
};

watch(wallets, (newValue) => {
    if (newValue.length) {
        initDataTable();
    }
});

onMounted(async () => {
    await fetchWallets();
    isAdmin.value = await fetchIsAdmin();
    initDataTable();
});

const getStatusClass = (status) => {
    return status === 1 ? "status-active" : "status-disabled";
};

const getStatusIcon = (status) => {
    return status === 1 ? "ti-check" : "ti-close";
};

const getStatusText = (status) => {
    return status === 1 ? "Active" : "Disabled";
};

const getTypeText = (type) => {
    if(type === 1) {
        return "Bank";
    }
    if(type === 2) {
        return "Cash";
    }
    if(type === 3) {
        return "Crypto";
    }
}
</script>
