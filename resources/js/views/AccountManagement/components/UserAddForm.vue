<template>
    <Modal v-if="show">
        <template v-if="!isEditing">
            <form @submit.prevent="$emit('create')" class="flex flex-col gap-4 w-80">
                <!-- Name -->
                <div>
                    <label class="block mb-1 font-medium">Name</label>
                    <div class="relative">
                        <i class="ti-user absolute left-3 top-3 text-gray-400"></i>
                        <input v-model="user.name" placeholder="Enter name" type="text" required
                            class="w-full pl-10 pr-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-1 font-medium">Email</label>
                    <div class="relative">
                        <i class="ti-email absolute left-3 top-3 text-gray-400"></i>
                        <input v-model="user.email" placeholder="Enter email" autocomplete="email" type="email" required
                            class="w-full pl-10 pr-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block mb-1 font-medium">Password</label>
                    <div class="relative">
                        <i class="ti-lock absolute left-3 top-3 text-gray-400"></i>
                        <input v-model="user.password" placeholder="Enter password" autocomplete="new-password"
                            type="password" required
                            class="w-full pl-10 pr-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block mb-1 font-medium">Confirm Password</label>
                    <div class="relative">
                        <i class="ti-lock absolute left-3 top-3 text-gray-400"></i>
                        <input v-model="user.password_confirmation" placeholder="Confirm password"
                            autocomplete="new-password" type="password" required
                            class="w-full pl-10 pr-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-50">
                        <label class="block mb-1 font-medium">Role</label>
                        <select v-model="user.role" required
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="w-50">
                        <label class="block mb-1 font-medium">Status</label>
                        <select v-model="user.status" required
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option :value="1">Active</option>
                            <option :value="0">Disabled</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-between gap-2">
                    <Button btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple"
                        icon="ti-plus" text="Add User" type="submit" />
                    <Button
                        btnClass="bg-charcoal-gray text-slate-gray rounded-md hover:bg-storm-gray hover:text-lavender-gray"
                        icon="ti-close" text="Cancel" type="button" @click="$emit('cancel')" />
                </div>
            </form>
        </template>

        <template v-else>
            <form @submit.prevent="$emit('update')" class="flex flex-col gap-4 w-80">
                <!-- Name -->
                <div>
                    <label class="block mb-1 font-medium">Name</label>
                    <div class="relative">
                        <i class="ti-user absolute left-3 top-3 text-gray-400"></i>
                        <input v-model="isEditing.name" placeholder="Update name" type="text" required
                            class="w-full pl-10 pr-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-1 font-medium">Email</label>
                    <div class="relative">
                        <i class="ti-email absolute left-3 top-3 text-gray-400"></i>
                        <input v-model="isEditing.email" placeholder="Update email" autocomplete="email" type="email"
                            required
                            class="w-full pl-10 pr-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                </div>

                <!-- Password (optional) -->
                <div>
                    <label class="block mb-1 font-medium">Password (optional)</label>
                    <div class="relative">
                        <i class="ti-lock absolute left-3 top-3 text-gray-400"></i>
                        <input v-model="isEditing.password" placeholder="Change password (optional)"
                            autocomplete="new-password" type="password"
                            class="w-full pl-10 pr-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-50">
                        <label class="block mb-1 font-medium">Role</label>
                        <select v-model="isEditing.role" required
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="w-50">
                        <label class="block mb-1 font-medium">Status</label>
                        <select v-model="isEditing.status" required
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option :value="1">Active</option>
                            <option :value="0">Disabled</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-between gap-2">
                    <Button btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple"
                        icon="ti-save" text="Save" type="submit" />
                    <Button
                        btnClass="bg-charcoal-gray text-slate-gray rounded-md hover:bg-storm-gray hover:text-lavender-gray"
                        icon="ti-close" text="Cancel" type="button" @click="$emit('cancel')" />
                </div>
            </form>
        </template>
    </Modal>
</template>

<script setup>
import { defineEmits, defineProps } from "vue";
import Button from "@/components/Button.vue";
import Modal from "@/components/Modal.vue";

defineProps({
    user: Object,
    isEditing: [Boolean, Object],
    show: Boolean
});

defineEmits(["create", "cancel", "update"]);
</script>
