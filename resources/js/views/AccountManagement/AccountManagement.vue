<template>
    <MainLayout title="Account Management" v-if="isAdmin">
        <LoadingSpinner v-if="isLoading" message="Loading users..." />

        <div v-else>
            <BulkActionsWithForm
                :bulkAction="bulkAction"
                @update:bulkAction="bulkAction = $event"
                :selectedItems="selectedItems"
                :confirmMassDelete="confirmMassDelete"
                :handleBulkAction="handleBulkAction"
            >
                <template #form>
                    <Button
                        btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple"
                        icon="ti-plus"
                        text="Add User"
                        @click="showForm = true" />
                </template>
            </BulkActionsWithForm>
            <DataTable>
                <template #thead>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </template>
                <template #tbody>
                    <tr v-for="(user, index) in items" :key="index">
                        <td>
                            <input type="checkbox" :value="user.id" v-model="selectedItems" />
                        </td>
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.role }}</td>
                        <td>
                            <StatusBadge :status="user.status" />
                        </td>
                        <td class="text-center">
                            <Button
                                btnClass="bg-deep-navy text-cerulean-blue mr-1 hover:bg-midnight-blue"
                                icon="ti-pencil"
                                @click="editItem(user)"
                            />
                        </td>
                    </tr>
                </template>
            </DataTable>
        </div>
        <UserAddForm
            :user="newItem"
            :show="showForm"
            :isEditing="editedItem"
            @create="addItem"
            @update="updateItem"
            @cancel="handleCancelUpdate" />
        <ConfirmDeleteModal
            :show="deleteMassConfirm"
            message="Are you sure you want to delete selected users?"
            @confirm="handleMassDelete" @cancel="deleteMassConfirm = false"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/views/layout/MainLayout.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import Button from "@/components/Button.vue";
import StatusBadge from "@/components/StatusBadge.vue";
import UserAddForm from "@/views/AccountManagement/components/UserAddForm.vue";
import DataTable from "@/components/DataTable.vue";
import BulkActionsWithForm from "@/components/BulkActionsWithForm.vue";
import { useAccountManagement } from "@/composables/Account/useAccountManagement";
import { useCrudPage } from "@/composables/useCrudPage.js";

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
    showForm,
    confirmMassDelete,
    handleMassDelete,
    handleBulkAction,
    handleCancelUpdate,
    editItem,
} = useCrudPage(useAccountManagement);
</script>
