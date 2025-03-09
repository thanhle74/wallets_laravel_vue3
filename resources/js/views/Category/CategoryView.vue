<template>
    <MainLayout title="Category Management">
        <LoadingSpinner v-if="isLoading" message="Loading categories..." />

        <div v-else>
            <CategoryForm
                :category="newCategory"
                :isEditing="editedCategory"
                @create="handleAddCategory"
                @update="handleUpdateCategory"
                @cancel="handleCancelUpdate"
            />

            <div class="table-responsive">
                <table id="myTable" class="table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th v-if="checkAdmin">User</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(category, index) in categories" :key="index">
                        <td>{{ category.id }}</td>
                        <td>{{ category.name }}</td>
                        <td>
                                <span :class="getStatusClass(category.status)">
                                    <i :class="getStatusIcon(category.status)"></i>
                                    {{ getStatusText(category.status) }}
                                </span>
                        </td>
                        <td v-if="checkAdmin">{{ category.user.name }}</td>
                        <td class="table-box-action">
                            <button class="btn-edit" @click="editCategory(category)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-cancel" @click="confirmDelete(category.id)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <ConfirmDeleteModal
            :show="deleteConfirmId !== null"
            message="Are you sure you want to delete this category?"
            @confirm="handleDeleteCategory"
            @cancel="deleteConfirmId = null"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/views/layout/MainLayout.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import CategoryForm from "@/views/Category/components/CategoryForm.vue";
import { useCategory } from "@/composables/useCategory";
import { isAdminHelper } from "@/composables/helper/isAdminHelper";
import { ref, onMounted, watch, nextTick } from "vue";
import $ from 'jquery';
import 'datatables.net';

const {
    categories,
    isLoading,
    newCategory,
    editedCategory,
    fetchCategories,
    addCategory,
    updateCategory,
    deleteCategory
} = useCategory();

const { fetchIsAdmin } = isAdminHelper();

const checkAdmin = async () => {
    return await fetchIsAdmin();
};

const deleteConfirmId = ref(null);

const handleAddCategory = async () => {
    await addCategory();
};

const handleUpdateCategory = async () => {
    await updateCategory();
};

const handleDeleteCategory = async () => {
    if (!deleteConfirmId.value) return;

    await deleteCategory(deleteConfirmId.value);
    deleteConfirmId.value = null;
};

const handleCancelUpdate = () => {
    editedCategory.value = false;
};

const confirmDelete = (id) => {
    deleteConfirmId.value = id;
};

const editCategory = (category) => {
    editedCategory.value = { ...category };
};

const initDataTable = () => {
    nextTick(() => {
        let table = $("#myTable");
        table.DataTable().destroy();
        table.DataTable();
    });
};

watch(categories, (newValue) => {
    if (newValue.length) {
        initDataTable();
    }
});

onMounted(async () => {
    await fetchCategories();
    initDataTable();
});

const getStatusClass = (status) => {
    return status === 1 ? "status-active" : "status-disabled";
};

const getStatusIcon = (status) => {
    return status === 1 ? "fas fa-check-circle" : "fas fa-times-circle";
};

const getStatusText = (status) => {
    return status === 1 ? "Active" : "Disabled";
};
</script>
