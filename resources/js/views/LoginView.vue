<template>
    <div class="main-wrapper">
        <div class="login-container">
            <div class="login-box">
                <div class="logo">
                    <img :src="imageUrl" alt="Sample Image">
                </div>
                <form class="login-form" @submit.prevent="handleLogin">
                    <div class="input-group">
                        <i class="ti-user"></i>
                        <input type="email" v-model="loginForm.email" autocomplete="username" placeholder="Email" required />
                    </div>

                    <div class="input-group">
                        <i class="ti-lock"></i>
                        <input type="password" v-model="loginForm.password" autocomplete="current-password" placeholder="Password" required />
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="ti-unlock"></i> Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from '@/services/apiClient';
import toastr from 'toastr';
import imageUrl from '@/assets/images/logo.jpg';

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

<style scoped lang="scss">
@use "@/assets/scss/views/login";
</style>
