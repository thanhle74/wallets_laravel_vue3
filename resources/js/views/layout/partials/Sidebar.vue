<template>
    <aside class="w-3xs bg-background-section p-6 min-h-screen">
        <div class="sidebar">
            <div class="rounded-(--radius-circle) overflow-hidden w-25 mx-auto">
                <RouterLink to="/dashboard">
                    <img :src="imageUrl" alt="Sample Image">
                </RouterLink>
            </div>
            <div class="sidebar-actions flex items-center justify-center gap-5 p-5">
                <Button btnClass="btn-setting text-xs" icon="ti-settings" />
                <Button btnClass="btn-logout text-xs" icon="ti-power-off" @click="handleLogout" />
            </div>
            <nav class="pt-5 border-t border-border-line">
                <ul>
                    <li v-for="(item, index) in filteredMenuItems" :key="index" class="mb-3">
                        <RouterLink :to="item.to" class="p-2 rounded-md text-sm flex gap-3 items-center transition duration-300 ease-in-out hover:bg-link-bg-hover hover:text-white">
                            <i :class="item.icon"></i>
                            {{ item.label }}
                        </RouterLink>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>

<script setup>
import imageUrl from '@/assets/images/logo.jpg';
import toastr from "toastr";
import apiClient from "@/services/apiClient";
import { useUserAccount } from "@/composables/Account/useUserAccount";
import { useRouter } from "vue-router";
import { onMounted, ref, computed } from "vue";
import Button from "@/components/Button.vue";

const router = useRouter();
const { fetchIsAdmin } = useUserAccount();
const isAdmin = ref(false);

const menuItems = [
    { label: 'Dashboard', icon: 'ti-dashboard', to: '/dashboard' },
    { label: 'Transaction', icon: 'ti-receipt', to: '/transaction' },
    { label: 'Category', icon: 'ti-tag', to: '/category' },
    { label: 'Wallet', icon: 'ti-wallet', to: '/wallet' },
    { label: 'Users', icon: 'ti-user', to: '/users', adminOnly: true }
];

const filteredMenuItems = computed(() => {
    return menuItems.filter(item => !item.adminOnly || isAdmin.value);
});

const handleLogout = async () => {
    try {
        const response = await apiClient.post('/logout');
        localStorage.removeItem("token");
        toastr.success(response.data.message);
        await router.push('/');
    } catch (error) {
        toastr.error("Logout failed!");
        console.error("Logout error:", error);
    }
};

onMounted(async () => {
    const result = await fetchIsAdmin();
    isAdmin.value = result ?? false;
});
</script>
