<template>
    <MainLayout title="Account Management">
        <LoadingSpinner v-if="isLoading" message="Loading categories..."/>

        <div v-else>
            <ManagementForm
                :user="newUser"
                :isEditing="editedUser"
                @create="handleUser"
                @update="handleUpdateUser"
                @cancel="handleCancelUpdate"
            />
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
                        <td><StatusBadge :status="user.status" /></td>
                        <td class="table-box-action">
                            <Button btnClass="btn-edit" icon="ti-pencil" @click="editUser(user)"/>
                            <Button btnClass="btn-cancel" icon="ti-trash" @click="confirmDelete(user.id)"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <ConfirmDeleteModal
            :show="deleteConfirmId !== null"
            message="Are you sure you want to delete this user?"
            @confirm="handleDeleteUser"
            @cancel="deleteConfirmId = null"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/views/layout/MainLayout.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import Button from "@/components/Button.vue";
import StatusBadge from "@/components/StatusBadge.vue";
import ManagementForm from "@/views/AccountManagement/components/ManagementForm.vue";
import {useAccountManagement} from "@/composables/Account/useAccountManagement";
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

const editUser = (category) => {
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
