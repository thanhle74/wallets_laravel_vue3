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
        <div class="max-w-4xl mx-auto flex">
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
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/views/layout/MainLayout.vue";
import { useSettings } from "@/composables/Setting/useSettings";
import Button from "@/components/Button.vue";
import { ref, computed, onMounted } from "vue";

const { settings, saveAllSettings, uploadImage, fetchSettings } = useSettings();
const selectedGroup = ref("general");
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
