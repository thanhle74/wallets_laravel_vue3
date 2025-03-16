<template>
    <MainLayout title="Account Management" v-if="isAdmin">
        <LoadingSpinner v-if="isLoading" message="Loading users..." />

        <div v-else>
            <Button
                btnClass="btn-info"
                icon="ti-plus"
                text="Add User"
                @click="showForm = true"
            />
            <div class="d-flex justify-between items-center mb-3">
                <select v-model="bulkAction" @change="handleBulkAction" class="form-select w-auto">
                    <option value="">Bulk Actions</option>
                    <option value="selectAll">Select All</option>
                    <option value="unselectAll">Unselect All</option>
                </select>

                <Button
                    btnClass="btn-danger"
                    icon="ti-trash"
                    :disabled="!selectedUsers.length"
                    @click="confirmMassDelete"
                    label="Delete Selected"
                />
            </div>
            <div class="table-responsive">
                <table id="userTable" class="table-striped">
                    <thead>
                    <tr>
                        <th></th>
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
                        <td>
                            <input type="checkbox" :value="user.id" v-model="selectedUsers" />
                        </td>
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.role }}</td>
                        <td><StatusBadge :status="user.status" /></td>
                        <td class="table-box-action">
                            <Button btnClass="btn-edit" icon="ti-pencil" @click="editUser(user)" />
                            <Button btnClass="btn-cancel" icon="ti-trash" @click="confirmDelete(user.id)" />
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <UserAddForm
            :user="newUser"
            :show="showForm"
            :isEditing="editedUser"
            @create="handleUser"
            @update="handleUpdateUser"
            @cancel="handleCancelUpdate"
        />
        <ConfirmDeleteModal
            :show="deleteMassConfirm"
            message="Are you sure you want to delete selected users?"
            @confirm="handleMassDelete"
            @cancel="deleteMassConfirm = false"
        />
        <ConfirmDeleteModal
            :show="deleteConfirmId !== null"
            message="Are you sure you want to delete this user?"
            @confirm="handleDeleteUser"
            @cancel="deleteConfirmId = null"
        />
    </MainLayout>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from "vue";
import $ from 'jquery';
import 'datatables.net';

import MainLayout from '@/views/layout/MainLayout.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import ConfirmDeleteModal from "@/components/ConfirmDeleteModal.vue";
import Button from "@/components/Button.vue";
import StatusBadge from "@/components/StatusBadge.vue";
import UserAddForm from "@/views/AccountManagement/components/UserAddForm.vue";
// import UserEditForm from "@/views/AccountManagement/components/UserEditForm.vue";
import { useAccountManagement } from "@/composables/Account/useAccountManagement";
import { isAdminHelper } from "@/composables/helper/isAdminHelper";

const {
    users,
    newUser,
    editedUser,
    isLoading,
    fetchUsers,
    addUser,
    deleteUser,
    updateUser,
    massDeleteUsers,
} = useAccountManagement();

const { fetchIsAdmin } = isAdminHelper();
const isAdmin = ref(false);
const deleteConfirmId = ref(null);
const deleteMassConfirm = ref(false);
const selectedUsers = ref([]);
const bulkAction = ref("");
const showForm = ref(false);

const handleUser = async () => {
    await addUser();
    handleCancelUpdate();
};
const handleUpdateUser = async () => {
    await updateUser()
    handleCancelUpdate();
};
const handleCancelUpdate = () => {
    showForm.value = false
    editedUser.value = false
};
const confirmDelete = (id) => (deleteConfirmId.value = id);
const editUser = (user) => {
    showForm.value = true
    editedUser.value = { ...user }
};

const handleDeleteUser = async () => {
    if (!deleteConfirmId.value) return;
    await deleteUser(deleteConfirmId.value);
    deleteConfirmId.value = null;
    await fetchUsers();
};

const confirmMassDelete = () => {
    if (selectedUsers.value.length) deleteMassConfirm.value = true;
};

const handleMassDelete = async () => {
    await massDeleteUsers(selectedUsers.value);
    deleteMassConfirm.value = false;
    selectedUsers.value = [];
    await fetchUsers();
};

const handleBulkAction = () => {
    if (bulkAction.value === "selectAll") {
        selectedUsers.value = users.value.slice(0, 10).map(u => u.id);
    } else if (bulkAction.value === "unselectAll") {
        selectedUsers.value = [];
    }
    bulkAction.value = "";
};

const initDataTable = () => {
    nextTick(() => {
        let table = $("#userTable");
        if ( $.fn.DataTable.isDataTable(table) ) {
            table.DataTable().destroy();
        }
        table.DataTable();
    });
};

watch(users, (newValue) => {
    if (newValue.length) initDataTable();
});

onMounted(async () => {
    await fetchUsers();
    isAdmin.value = await fetchIsAdmin();
    initDataTable();
});
</script>
