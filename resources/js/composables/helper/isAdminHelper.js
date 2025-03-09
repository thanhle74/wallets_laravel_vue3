import apiClient from "@/services/apiClient";

export function isAdminHelper() {
    const fetchIsAdmin = async () => {
        try {
            const response = await apiClient.get("/is-admin")
            return  response.data.data.is_admin;
        } catch (error) {
            console.error("Lỗi khi kiểm tra admin:", error);
        }
    };

    return {fetchIsAdmin};
}
