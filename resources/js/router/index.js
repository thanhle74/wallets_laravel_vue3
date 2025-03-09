import { createRouter, createWebHistory } from 'vue-router';
import DashboardView from '@/views/Dashboard/DashboardView.vue';
import CategoryView from '@/views/Category/CategoryView.vue';
import WalletView from "@/views/Wallet/WalletView.vue";
import LoginView from '@/views/LoginView.vue';
import TransactionsView from "@/views/Transaction/TransactionsView.vue";
import NotFoundView from "@/views/NotFoundView.vue";
import apiClient from "@/services/apiClient";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        { path: '/', name: 'login', component: LoginView },
        { path: '/dashboard', name: 'dashboard', component: DashboardView },
        { path: '/transaction', name: 'transaction', component: TransactionsView },
        { path: '/category', name: 'category', component: CategoryView },
        { path: '/wallet', name: 'wallet', component: WalletView },
        { path: '/:pathMatch(.*)*', component: NotFoundView }
    ],
});

async function checkAuth() {
    try {
        const response = await apiClient.get('/auth/check');
        return response.status === 200;
    } catch (error) {
        return false;
    }
}

router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem("token");
    const isAuthenticated = token ? await checkAuth() : false;

    if (to.name !== "login" && !isAuthenticated) {
        localStorage.removeItem("token");
        next({ name: "login" });
    } else {
        next();
    }
});

export default router;
