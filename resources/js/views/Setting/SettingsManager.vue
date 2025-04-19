<template>
    <MainLayout title="Settings Manager">
        <div class="mb-6 text-right">
            <Button
                btnClass="bg-deep-navy text-cerulean-blue rounded-md hover:bg-midnight-blue"
                icon="ti-save"
                text="Save"
                @click="saveAllSettings"
            />
        </div>
        <div class="max-w mx-auto flex">
            <div class="w-1/4 border-r border-border-line pr-4">
                <ul>
                    <li
                        v-for="group in groups"
                        :key="group"
                        @click="selectedGroup = group"
                        class="p-2 rounded-md text-sm flex gap-3 items-center transition
                        duration-300 ease-in-out mb-2 hover:bg-link-bg-hover hover:text-white cursor-pointer"
                        :class="{'bg-royal-purple text-amethyst-purple': selectedGroup === group}"
                    >
                        <i class="ti-control-record"></i>
                        {{ capitalizeFirstLetter(group) }}
                    </li>
                </ul>
            </div>

            <!-- Settings Content -->
            <div class="w-3/4 pl-4">
                <div v-for="setting in filteredSettings" :key="setting.id" class="mb-4">
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label>{{ setting.label }}</label>

                        <!-- Text Input -->
                        <input
                            v-if="setting.type === 'text'"
                            v-model="setting.value"
                            type="text"
                            class="border p-2 rounded w-full col-span-2"
                        />

                        <!-- Timezone Dropdown -->
                        <select
                            v-if="setting.type === 'timezone'"
                            v-model="setting.value"
                            class="border p-2 rounded w-full col-span-2"
                        >
                            <option
                                v-for="tz in timezones"
                                :key="tz.value"
                                :value="tz.value"
                            >
                                {{ tz.label }}
                            </option>
                        </select>

                        <!-- Image Upload -->
                        <div v-if="setting.type === 'image'" class="flex items-center gap-4 col-span-2">
                            <input type="file" @change="(e) => uploadImage(e, setting)" class="border p-2 rounded" />
                            <img v-if="setting.value" :src="setting.value" class="w-20 h-20 rounded">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb-4">
            <Button
                btnClass="bg-blue-600 text-white rounded hover:bg-blue-700"
                icon="ti-plus"
                text="Add New Setting"
                @click="showAddSettingForm = !showAddSettingForm"
            />
        </div>
        <div v-if="showAddSettingForm" class="mb-8 p-4 rounded">
            <h2 class="text-lg font-semibold mb-4">Add New Setting</h2>

            <!-- Toggle chọn Group hoặc thêm mới -->
            <div class="flex items-center gap-2 mb-4">
                <label class="text-sm font-medium">Use new group?</label>
                <input type="checkbox" v-model="isCustomGroup" class="form-checkbox h-4 w-4 text-blue-600" />
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- Group -->
                <template v-if="isCustomGroup">
                    <input
                        v-model="newSetting.group"
                        type="text"
                        placeholder="New Group"
                        class="border p-2 rounded w-full"
                    />
                </template>
                <template v-else>
                    <select v-model="newSetting.group" class="border p-2 rounded w-full">
                        <option disabled value="">-- Select Group --</option>
                        <option v-for="group in groups" :key="group" :value="group">
                            {{ capitalizeFirstLetter(group) }}
                        </option>
                    </select>
                </template>

                <!-- Key -->
                <input v-model="newSetting.key" type="text" placeholder="Key" class="border p-2 rounded w-full" />

                <!-- Label -->
                <input v-model="newSetting.label" type="text" placeholder="Label" class="border p-2 rounded w-full" />

                <!-- Value -->
                <input v-model="newSetting.value" type="text" placeholder="Value" class="border p-2 rounded w-full" />

                <!-- Type -->
                <select v-model="newSetting.type" class="border p-2 rounded w-full col-span-2">
                    <option value="text">Text</option>
                    <option value="image">Image</option>
                    <option value="timezone">Timezone</option>
                </select>
            </div>

            <div class="text-right">
                <Button
                    btnClass="bg-green-600 text-white rounded hover:bg-green-700"
                    icon="ti-plus"
                    text="Add Setting"
                    @click="createSetting(newSetting)"
                />
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/views/layout/MainLayout.vue";
import { useSettings } from "@/composables/Setting/useSettings";
import Button from "@/components/Button.vue";
import { ref, computed, onMounted } from "vue";

const showAddSettingForm = ref(false);
const isCustomGroup = ref(false);
const { settings, saveAllSettings, uploadImage, fetchSettings, createSetting } = useSettings();
const selectedGroup = ref("general");
const newSetting = ref({
    group: "",
    key: "",
    label: "",
    value: "",
    type: "text",
});

const groups = computed(() => {
    return settings.value.length > 0
        ? [...new Set(settings.value.map(setting => setting.group))]
        : [];
});

const filteredSettings = computed(() => {
    return settings.value.length > 0
        ? settings.value.filter(setting => setting.group === selectedGroup.value)
        : [];
});
const timezones = ref([
    { value: "UTC", label: "UTC (GMT+0)" },
    { value: "Asia/Ho_Chi_Minh", label: "Asia/Ho_Chi_Minh (GMT+7)" },
    { value: "America/New_York", label: "America/New_York (GMT-5)" },
    { value: "Europe/London", label: "Europe/London (GMT+0)" },
    { value: "Asia/Tokyo", label: "Asia/Tokyo (GMT+9)" },
    { value: "Australia/Sydney", label: "Australia/Sydney (GMT+11)" },
]);
const capitalizeFirstLetter = (str) => {
    return str.charAt(0).toUpperCase() + str.slice(1);
};

onMounted(async () => {
    await fetchSettings();
});
</script>
