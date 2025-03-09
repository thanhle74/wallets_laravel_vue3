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
.logo {
    width: 20%;
    position: relative;
    border-radius: 50%;
    overflow: hidden;
    text-align: center;
    margin: auto auto 2rem;
}

.main-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Hộp login */
.login-container {
    width: 100%;
    max-width: 400px;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    background: #2f3349;
}

/* Tiêu đề */
.login-title {
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
}

/* Ô nhập */
.input-group {
    display: flex;
    align-items: center;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #343f5b;
    color: #b2b9bf;
}

.input-group {
    i {
        margin-right: 10px;
        color: #7f8c8d;
    }

    input {
        border: none;
        outline: none;
        background: transparent;
        flex: 1;
        font-size: 16px;
    }
}

/* Nút login */
.btn-login {
    width: 100%;
    padding: 10px;
    background: #3a3b64;
    color: #7367f0;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
    transition: 0.3s;
    &:hover {
        background: #3f3f71;
    }
}
</style>
