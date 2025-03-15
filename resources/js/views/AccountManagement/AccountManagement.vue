<template>
    <MainLayout title="Account Management">
        <LoadingSpinner v-if="isLoading" message="Loading categories..."/>

        <div v-else>
<!--            <CategoryForm-->
<!--                :category="newCategory"-->
<!--                :isEditing="editedCategory"-->
<!--                @create="handleAddCategory"-->
<!--                @update="handleUpdateCategory"-->
<!--                @cancel="handleCancelUpdate"-->
<!--            />-->

            <div class="table-responsive">
                <table id="userTable" class="table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(user, index) in users" :key="index">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.role }}</td>
                        <td>
                            <span :class="getStatusClass(user.status)">
                                <i :class="getStatusIcon(user.status)"></i>
                                {{ getStatusText(user.status) }}
                            </span>
                        </td>
                        <td class="table-box-action">
                            <button class="btn-edit" @click="editCategory(user)">
                                <i class="ti-pencil"></i>
                            </button>
                            <button class="btn-cancel" @click="confirmDelete(user.id)">
                                <i class="ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

<!--        <ConfirmDeleteModal-->
<!--            :show="deleteConfirmId !== null"-->
<!--            message="Are you sure you want to delete this category?"-->
<!--            @confirm="handleDeleteCategory"-->
<!--            @cancel="deleteConfirmId = null"-->
<!--        />-->
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/views/layout/MainLayout.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import CategoryForm from "@/views/Category/components/CategoryForm.vue";
import {useAccountManagement} from "@/composables/useAccountManagement";
import {isAdminHelper} from "@/composables/helper/isAdminHelper";
import {ref, onMounted, watch, nextTick} from "vue";
import $ from 'jquery';
import 'datatables.net';

const {
    users,
    newUser,
    editedUser,
    isLoading,
    fetchUsers,
    addUser,
    deleteUser,
    updateUser,
} = useAccountManagement();

const {fetchIsAdmin} = isAdminHelper();

const isAdmin = ref(false);
const deleteConfirmId = ref(null);

const handleUser = async () => {
    await addUser();
};

const handleUpdateUser= async () => {
    await updateUser();
};

const handleDeleteUser = async () => {
    if (!deleteConfirmId.value) return;

    await deleteUser(deleteConfirmId.value);
    deleteConfirmId.value = null;
};

const handleCancelUpdate = () => {
    editedUser.value = false;
};

const confirmDelete = (id) => {
    deleteConfirmId.value = id;
};

const editCategory = (category) => {
    editedUser.value = {...category};
};

const initDataTable = () => {
    nextTick(() => {
        let table = $("#userTable");
        table.DataTable().destroy();
        table.DataTable();
    });
};

watch(users, (newValue) => {
    if (newValue.length) {
        initDataTable();
    }
});

onMounted(async () => {
    await fetchUsers();
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
</script>
