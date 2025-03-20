<template>
    <aside class="col-span-3 bg-background-section p-6 min-h-screen shadow">
        <div class="sidebar">
            <div class="rounded-(--radius-circle) overflow-hidden w-25 mx-auto h-25">
                <RouterLink to="/dashboard">
                    <img :src="logoUrl" alt="Sample Image">
                </RouterLink>
            </div>
            <div class="sidebar-actions flex items-center justify-center gap-5 p-5">
                <RouterLink to="/profile" class="text-xs link-profile">
                    <i class="ti-user"></i>
                </RouterLink>
                <Button btnClass="text-xs" icon="ti-power-off" @click="handleLogout" />
            </div>
            <nav class="pt-5 border-t border-border-line">
                <ul>
                    <SidebarItem url="/dashboard" icon="ti-dashboard" label="Dashboard"/>
                    <li>
                        <div
                            class="p-2 rounded-md mb-2 text-sm flex gap-3 items-center justify-between transition duration-300
                            ease-in-out cursor-pointer hover:bg-link-bg-hover hover:text-white"
                            @click="toggleDropdown"
                        >
                            <span class="flex gap-3">
                                <i class="ti-money"></i>
                                Finance
                            </span>
                            <i :class="isDropdownOpen ? 'ti-angle-down rotate-180' : 'ti-angle-down'"></i>
                        </div>
                        <ul v-if="isDropdownOpen" class="ml-5 border-l border-border-line pl-4 mt-2">
                            <SidebarItem url="/transaction" icon="ti-receipt" label="Transactions"/>
                            <SidebarItem url="/category" icon="ti-tag" label="Categories"/>
                            <SidebarItem url="/wallet" icon="ti-wallet" label="Wallets"/>
                        </ul>
                    </li>

                    <SidebarItem url="/users" icon="ti-user" label="Users" v-if="isAdmin"/>
                    <SidebarItem url="/admin-tool" icon="ti-panel" label="Tools" v-if="isAdmin"/>
                    <SidebarItem url="/settings" icon="ti-settings" label="Settings" v-if="isAdmin"/>
                </ul>
            </nav>
        </div>
    </aside>
</template>
<script setup>
// import imageUrl from '@/assets/images/logo.jpg';
import { useUserAccount } from "@/composables/Account/useUserAccount";
import { useRouter, useRoute } from "vue-router";
import { onMounted, ref, watch, computed } from "vue";
import { useSettings } from "@/composables/Setting/useSettings";
import Button from "@/components/Button.vue";
import SidebarItem from "@/views/layout/partials/components/SidebarItem.vue";

const route = useRoute();
const { fetchIsAdmin } = useUserAccount();
const isAdmin = ref(false);
const isDropdownOpen = ref(false);
const router = useRouter();
const { logout } = useUserAccount(router);
const { settings, fetchSettings } = useSettings();

const logoUrl = computed(() => {
    const logoSetting = settings.value.find(s => s.key === "logo");
    return logoSetting?.value || '';
});

const checkCurrentRoute = () => {
    const transactionRoutes = ["/transaction", "/category", "/wallet"];
    isDropdownOpen.value = transactionRoutes.includes(route.path);
};

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

watch(route, checkCurrentRoute, { immediate: true });

const handleLogout = async () => {
    await logout();
};

onMounted(async () => {
    const result = await fetchIsAdmin();
    isAdmin.value = result ?? false;
    checkCurrentRoute();
    await fetchSettings();
});
</script>
