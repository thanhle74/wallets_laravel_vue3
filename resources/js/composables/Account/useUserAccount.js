import apiClient from "@/services/apiClient";
import toastr from "toastr";

export function useUserAccount(router) {
    const fetchIsAdmin = async () => {
        try {
            const response = await apiClient.get("/is-admin");
            return response.data.data.is_admin;
        } catch (error) {
            console.error("Lỗi khi kiểm tra admin:", error);
        }
    };

    const logout = async () => {
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

    return { fetchIsAdmin, logout };
}
