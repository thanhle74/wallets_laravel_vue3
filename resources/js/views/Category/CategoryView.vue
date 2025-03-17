<template>
    <MainLayout title="Category Management">
        <LoadingSpinner v-if="isLoading" message="Loading categories..."/>

        <div v-else>
            <CategoryForm
                :category="newCategory"
                :isEditing="editedCategory"
                @create="handleAddCategory"
                @update="handleUpdateCategory"
                @cancel="handleCancelUpdate"
            />
            <table id="myTable" class="table-striped">
                <thead>
                    <tr class="bg-royal-purple text-amethyst-purple">
                        <th v-if="isAdmin">ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(category, index) in categories" :key="index">
                        <td v-if="isAdmin">{{ category.id }}</td>
                        <td>{{ category.name }}</td>
                        <td><StatusBadge :status="category.status" /></td>
                        <td class="text-center">
                            <Button 
                                btnClass="bg-deep-navy text-cerulean-blue mr-1 hover:bg-midnight-blue" 
                                icon="ti-pencil" 
                                @click="editCategory(category)"
                            />
                            <Button 
                                btnClass="bg-charcoal-gray text-slate-gray hover:bg-storm-gray hover:text-lavender-gray" 
                                icon="ti-trash" 
                                @click="confirmDelete(category.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
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
import Button from "@/components/Button.vue";
import StatusBadge from "@/components/StatusBadge.vue";
import {useCategory} from "@/composables/useCategory";
import {useUserAccount} from "@/composables/Account/useUserAccount";
import {ref, onMounted, watch, nextTick} from "vue";
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

const {fetchIsAdmin} = useUserAccount();
const isAdmin = ref(false);
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
    editedCategory.value = {...category};
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
    const result = await fetchIsAdmin();
    isAdmin.value = result ?? false;
    await fetchCategories();
    initDataTable();
});
</script>
