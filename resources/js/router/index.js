import { createRouter, createWebHistory } from 'vue-router';
import { useUserAccount } from "@/composables/Account/useUserAccount";
import { useSettings } from "@/composables/Setting/useSettings";
import DashboardView from '@/views/Dashboard/DashboardView.vue';
import CategoryView from '@/views/Category/CategoryView.vue';
import WalletView from "@/views/Wallet/WalletView.vue";
import LoginView from '@/views/LoginView.vue';
import TransactionsView from "@/views/Transaction/TransactionsView.vue";
import AccountManagement from "@/views/AccountManagement/AccountManagement.vue";
import NotFoundView from "@/views/NotFoundView.vue";
import ModuleMagento from "@/views/ModuleMagento/ModuleMagento.vue";
import AdminTool from "@/views/AdminTool/AdminTool.vue";
import SettingsManager from "@/views/Setting/SettingsManager.vue";
import UserProfile from "@/views/AccountManagement/UserProfile.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        { path: '/', name: 'login', component: LoginView, meta: { title: 'Login' } },
        { path: '/dashboard', name: 'dashboard', component: DashboardView, meta: { title: 'Dashboard' } },
        { path: '/transaction', name: 'transaction', component: TransactionsView, meta: { title: 'Transactions' } },
        { path: '/category', name: 'category', component: CategoryView, meta: { title: 'Categories' } },
        { path: '/wallet', name: 'wallet', component: WalletView, meta: { title: 'Wallet' } },
        { path: '/users', name: 'Users', component: AccountManagement, meta: { title: 'User Management' } },
        { path: '/modules', name: 'ModuleMagento', component: ModuleMagento, meta: { title: 'Magento Modules' } },
        { path: '/admin-tool', name: 'admin-tool', component: AdminTool, meta: { title: 'Admin Tools' } },
        { path: '/settings', name: 'settings', component: SettingsManager, meta: { title: 'Settings' } },
        { path: '/profile', name: 'profile', component: UserProfile, meta: { title: 'Profile' } },
        { path: '/:pathMatch(.*)*', component: NotFoundView, meta: { title: 'Page Not Found' } },
    ],
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { top: 0 };
        }
    },
});

router.beforeEach(async (to, from, next) => {
    const { checkAuth } = useUserAccount(router);
    const { settings, fetchSettings } = useSettings();

    await fetchSettings();

    const siteTitle = () => {
        const titleSetting = settings.value.find(s => s.key === "site_title");
        return titleSetting?.value || 'Thành Aloha';
    };

    const faviconUrl = () => {
        const faviconSetting = settings.value.find(s => s.key === "favicon");
        return faviconSetting?.value || '/favicon.ico';
    };

    document.title = to.meta.title ? `${to.meta.title} | ${siteTitle()}` : siteTitle();

    const updateFavicon = (url) => {
        let link = document.querySelector("link[rel~='icon']");
        if (!link) {
            link = document.createElement("link");
            link.rel = "icon";
            document.head.appendChild(link);
        }
        link.href = url;
    };

    updateFavicon(faviconUrl());

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
