<template>
    <MainLayout title="Admin Command Tool">
        <div class="w-full mx-auto">
            <div class="flex rounded-lg cursor-pointer border border-border-line">
                <div
                    v-for="(tab, index) in tabs"
                    :key="index"
                    @click="activeTab = index"
                    :class="['flex-1 text-center py-2 cursor-pointer transition rounded-lg',
                   activeTab === index ? 'bg-deep-navy text-cerulean-blue hover:bg-midnight-blue' : '']"
                >
                    {{ tab.label }}
                </div>
            </div>
            <div class="py-4">
                <component :is="tabs[activeTab].component"/>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/views/layout/MainLayout.vue';
import {ref, defineAsyncComponent, markRaw} from 'vue';

const LaravelCommands = markRaw(defineAsyncComponent(() => import('@/views/AdminTool/components/LaravelCommands.vue')));
const ModuleManager = markRaw(defineAsyncComponent(() => import('@/views/AdminTool/components/ModuleManager.vue')));

const tabs = ref([
    {label: 'Laravel Commands', component: LaravelCommands},
    {label: 'Module Manager', component: ModuleManager}
]);

const activeTab = ref(0);
</script>
