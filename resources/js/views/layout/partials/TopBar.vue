<template>
    <nav class="flex items-center justify-between py-2 px-6 mb-8 rounded-lg relative">
        <h5 class="text-lg">{{ title }}</h5>

        <div ref="menuRef" class="flex items-center gap-2 cursor-pointer" @click="toggleDropdown">
            <div class="rounded-(--radius-circle) overflow-hidden mx-auto">
                <img class="w-10 h-10 object-cover" v-if="user.avatar" :src="user.avatar" alt="User Avatar">
            </div>
            <span class="text-sm">{{ user.name }}</span>
            <i :class="dropdownOpen ? 'ti-angle-up' : 'ti-angle-down'"></i>

            <ul v-if="dropdownOpen" class="absolute top-full bg-background-section right-0 mt-2 py-2 px-4 w-50 rounded-md shadow-sm">
                <TopBarItem icon="ti-user" text="Profile" @click="handleProfile"/>
                <TopBarItem icon="ti-power-off" text="Logout" @click="handleLogout" />
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted, defineProps } from "vue";
import TopBarItem from "@/views/layout/partials/components/TopBarItem.vue";
import {useUserAccount} from "@/composables/Account/useUserAccount.js";
import { useRouter } from "vue-router";
import { useUserProfile } from '@/composables/Account/useUserProfile';

defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
});

const dropdownOpen = ref(false);
const menuRef = ref(null);
const router = useRouter();
const { logout } = useUserAccount(router);
const { user } = useUserProfile();

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const handleLogout = async () => {
    await logout();
};

const handleProfile = () => {
    router.push({ name: 'profile' });
};

const handleClickOutside = (event) => {
    if (menuRef.value && !menuRef.value.contains(event.target)) {
        dropdownOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>
