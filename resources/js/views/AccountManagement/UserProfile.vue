<template>
    <MainLayout title="Profile">
        <div class="max-w mx-auto flex">
            <div class="w-1/4 border-r border-border-line pr-6">
                <div class="mb-6">
                    <h2 class="text-xl mb-4">Personal Information</h2>
                    <div class="flex items-center gap-4">
                        <img v-if="user.avatar" :src="user.avatar" alt="Avatar"
                            class="w-24 h-24 rounded-full border border-border-line object-cover" />
                        <div>
                            <p class="text-lg font-semibold">{{ user.name }}</p>
                            <p class="text-gray-600">{{ user.email }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl mb-4">Change Password</h2>
                    <form @submit.prevent="changePassword" class="space-y-3">
                        <input
                            type="text"
                            name="username"
                            :value="user.email"
                            autocomplete="username"
                            class="hidden"
                        />

                        <div class="flex items-center gap-2">
                            <i class="ti-lock"></i>
                            <label class="block text-sm font-medium">Current Password</label>
                        </div>
                        <input v-model="passwords.current_password" type="password" required autocomplete="current-password"
                            class="border p-2 rounded w-full" />

                        <div class="flex items-center gap-2">
                            <i class="ti-key"></i>
                            <label class="block text-sm font-medium">New Password</label>
                        </div>
                        <input v-model="passwords.new_password" type="password" required autocomplete="new-password"
                            class="border p-2 rounded w-full" />

                        <div class="flex items-center gap-2">
                            <i class="ti-check"></i>
                            <label class="block text-sm font-medium">Confirm Password</label>
                        </div>
                        <input v-model="passwords.new_password_confirmation" type="password" required autocomplete="new-password"
                            class="border p-2 rounded w-full" />

                        <Button
                            btnClass="bg-mulberry-purple text-torch-red rounded-md hover:bg-button-red-hover px-3 py-2 w-full"
                            icon="ti-exchange-vertical" text="Change Password" type="submit" />
                    </form>
                </div>
            </div>

            <!-- Right Column: Edit Information -->
            <div class="w-2/3 pl-6">
                <h2 class="text-xl mb-4">Edit Information</h2>
                <form @submit.prevent="updateProfile" class="space-y-4">
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label class="text-sm font-medium flex items-center gap-2">
                            <i class="ti-user"></i> Full Name
                        </label>
                        <input v-model="user.name" type="text" required class="border p-2 rounded col-span-2 w-full" />
                    </div>

                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label class="text-sm font-medium flex items-center gap-2">
                            <i class="ti-email"></i> Email
                        </label>
                        <input v-model="user.email" type="email" required
                            class="border p-2 rounded col-span-2 w-full" />
                    </div>
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label class="text-sm font-medium flex items-center gap-2">
                            <i class="ti-image"></i> Profile Picture
                        </label>
                        <div class="col-span-2 flex items-center gap-4">
                            <input type="file" @change="handleFileUpload" class="border p-2 rounded w-full" />
                            <img v-if="user.avatar" :src="user.avatar"
                                class="w-16 h-16 rounded border border-border-line object-cover" />
                        </div>
                    </div>
                    <Button
                        btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple px-3 py-2 w-full"
                        icon="ti-save" text="Update Information" type="submit" />
                </form>

            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/views/layout/MainLayout.vue";
import { useUserProfile } from "@/composables/Account/useUserProfile";
import Button from "@/components/Button.vue";

const {
    user,
    passwords,
    handleFileUpload,
    updateProfile,
    changePassword
} = useUserProfile();
</script>
