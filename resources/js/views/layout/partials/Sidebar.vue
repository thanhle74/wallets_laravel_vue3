<template>
    <aside>
        <div class="sidebar">
            <div class="logo">
                <RouterLink to="/dashboard">
                    <img :src="imageUrl" alt="Sample Image">
                </RouterLink>
            </div>
            <div class="sidebar-actions">
                <button class="btn-setting">
                    <i class="ti-settings"></i>
                </button>
                <button class="btn-logout" @click="handleLogout">
                    <i class="ti-power-off"></i>
                </button>
            </div>
            <nav>
                <ul>
                    <li>
                        <RouterLink to="/dashboard">
                            <i class="ti-dashboard"></i>
                            Dashboard
                        </RouterLink>
                    </li>
                    <li>
                        <RouterLink to="/transaction">
                            <i class="ti-receipt"></i>
                            Transaction
                        </RouterLink>
                    </li>
                    <li>
                        <RouterLink to="/category">
                            <i class="ti-tag"></i>
                            Category
                        </RouterLink>
                    </li>
                    <li>
                        <RouterLink to="/wallet">
                            <i class="ti-wallet"></i>
                            Wallet
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
import { useRouter } from "vue-router";

const router = useRouter();
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
</script>

<style scoped>
.logo {
    width: 50%;
    position: relative;
    border-radius: 50%;
    overflow: hidden;
    text-align: center;
    margin: auto auto 2rem;
}

nav {
    border-top: 1px solid #343f5b;
    padding-top: 2rem;
}
.sidebar {
    padding: 15px;
    li {
        margin-bottom: 1rem;
        a {
            display: block;
            padding: 12px 15px;
            transition: all .3s ease;
            border-radius: 5px;
            &:hover {
                background-color: #e1def514;
                color: #fff;
                border-radius: 5px;
            }
            &.router-link-active {
                background-color: #685dd8;
                color: #fff;
            }
        }
    }

    i {
        margin-right: 1rem;
        font-size: 1.4rem;
    }
}

.sidebar-actions {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 2rem 0;
}

.btn-setting, .btn-logout {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.4rem;
    color: #b2b9bf;
    transition: color 0.3s;
}

.btn-setting:hover, .btn-logout:hover {
    color: #685dd8;
}
</style>
