<template>
    <nav class="topbar">
        <h5>{{ title }}</h5>

        <div ref="menuRef" class="user-menu" @click="toggleDropdown">
            <img class="avatar" src="https://i.pravatar.cc/40" alt="User Avatar">
            <span>{{ user.name }}</span>
            <i :class="dropdownOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>

            <!-- Dropdown -->
            <ul v-if="dropdownOpen" class="dropdown">
                <li><i class="fas fa-user"></i> Profile</li>
                <li @click="handleLogout"><i class="fas fa-sign-out-alt"></i> Logout</li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted, defineProps } from "vue";
import { useRouter } from "vue-router";
import apiClient from "@/services/apiClient";
import toastr from "toastr";

defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
});

const router = useRouter();
const user = ref({ name: "John Doe" });
const dropdownOpen = ref(false);
const menuRef = ref(null);

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

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


<style scoped>
h5 {
    color: #7367f0;
    font-size: 1.875rem;
    padding: 1rem;
}

.topbar {
    height: 50px;
    background: #2f3349;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 20px;
    margin-bottom: 2rem;
    box-shadow: 0 .125rem .5rem #1311202e;
    border-radius: .375rem;
    overflow: hidden;
}

.user-menu {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.dropdown {
    position: absolute;
    top: 50px;
    right: 0;
    list-style: none;
    padding: 10px;
    box-shadow: 0 .125rem .5rem #1311202e;
    border-radius: 5px;
    width: 150px;
    background: #2f3349;
    z-index: 999;
}

.dropdown li {
    padding: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.dropdown li:hover {
    background-color: #e1def514;
    color: #fff;
    border-radius: 5px;
}
</style>
