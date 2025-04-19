<template>
    <MainLayout title="Category Management">
        <LoadingSpinner v-if="isLoading" message="Loading categories..." />

        <div v-else>
            <BulkActionsWithForm
                :bulkAction="bulkAction"
                @update:bulkAction="bulkAction = $event"
                :selectedItems="selectedItems"
                :confirmMassDelete="confirmMassDelete"
                :handleBulkAction="handleBulkAction"
            >
                <template #form>
                    <CategoryForm
                        :category="newItem"
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
                    <th>Status</th>
                    <th v-if="isAdmin">User</th>
                    <th class="text-center">Actions</th>
                </template>
                <template #tbody>
                    <tr v-for="category in items" :key="category.id">
                        <td class="text-center"><input type="checkbox" :value="category.id" v-model="selectedItems" /></td>
                        <td v-if="isAdmin">{{ category.id }}</td>
                        <td>{{ category.name }}</td>
                        <td><StatusBadge :status="category.status" /></td>
                        <td v-if="isAdmin">{{ category.user.name }}</td>
                        <td class="text-center">
                            <Button
                                btnClass="bg-deep-navy text-cerulean-blue mr-1 hover:bg-midnight-blue rounded-md"
                                icon="ti-pencil"
                                @click="editItem(category)"
                            />
                        </td>
                    </tr>
                </template>
            </DataTable>
        </div>

        <ConfirmDeleteModal
            :show="deleteMassConfirm"
            message="Are you sure you want to delete selected categories?"
            @confirm="handleMassDelete"
            @cancel="deleteMassConfirm = false"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/views/layout/MainLayout.vue";
import LoadingSpinner from "@/components/LoadingSpinner.vue";
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import CategoryForm from "@/views/Category/components/CategoryForm.vue";
import Button from "@/components/Button.vue";
import StatusBadge from "@/components/StatusBadge.vue";
import BulkActionsWithForm from "@/components/BulkActionsWithForm.vue";
import DataTable from "@/components/DataTable.vue";
import { useCategory } from "@/composables/Category/useCategory.js";
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
    confirmMassDelete,
    handleMassDelete,
    handleCancelUpdate,
    handleBulkAction,
    editItem,
} = useCrudPage(useCategory);
</script>
