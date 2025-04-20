<template>
    <div class="flex items-center justify-center h-screen">
        <div class="w-full max-w-md bg-background-section border border-border-line shadow-lg rounded-lg p-6">
            <div class="w-24 h-24 mx-auto rounded-full overflow-hidden">
                <img :src="imageUrl" alt="Logo" class="w-full h-full object-cover">
            </div>

            <form class="space-y-4 mt-4" @submit.prevent="handleLogin">
                <div class="flex items-center bg-background-body rounded-md px-3 py-2">
                    <i class="ti-user text-gray-500 mr-2"></i>
                    <input
                        type="email"
                        v-model="loginForm.email"
                        autocomplete="username"
                        placeholder="Email"
                        required
                        class="w-full bg-transparent outline-none"
                    />
                </div>

                <div class="flex items-center bg-background-body rounded-md px-3 py-2">
                    <i class="ti-lock text-gray-500 mr-2"></i>
                    <input
                        type="password"
                        v-model="loginForm.password"
                        autocomplete="current-password"
                        placeholder="Password"
                        required
                        class="w-full bg-transparent outline-none"
                    />
                </div>

                <Button
                    btnClass="w-full bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple py-2 transition"
                    icon="ti-unlock"
                    text="Login"
                    type="submit"
                />
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from '@/services/apiClient';
import toastr from 'toastr';
import imageUrl from '@/assets/images/logo.jpg';
import Button from "@/components/Button.vue";

const loginForm = ref({
    email: '',
    password: '',
});

const router = useRouter();
const isLoading = ref(false);

const handleLogin = async () => {
    isLoading.value = true;
    try {
        const response = await apiClient.post('/login', {
            email: loginForm.value.email,
            password: loginForm.value.password,
        });

        console.log("Login response:", response.data);
        localStorage.setItem("token", response.data.token);
        toastr.success("Login successful!");

        await router.push('/dashboard');
    } catch (error) {
        toastr.error("Invalid email or password!");
        console.error("Login error:", error);
    } finally {
        isLoading.value = false;
    }
};
</script>
