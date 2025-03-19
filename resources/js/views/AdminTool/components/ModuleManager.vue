<template>
    <div class="space-y-4">
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-4">
                <h3 class="text-lg mb-2">Execute Command</h3>
                <select v-model="selectedModule" class="p-2 border rounded-md w-full mb-2">
                    <option disabled value="">Select Module</option>
                    <option v-for="mod in modules" :key="mod" :value="mod">{{ mod.name }}</option>
                </select>

                <select v-model="selectedCommand" class="p-2 border rounded-md w-full mb-2">
                    <option disabled value="">Select Command</option>
                    <option v-for="cmd in moduleCommands" :key="cmd.command" :value="cmd.command">
                        {{ cmd.label }}
                    </option>
                </select>

                <Button
                    @click="executeCommand"
                    text="Run Command"
                    btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple mt-2 w-full"
                    :disabled="isLoading || !selectedModule || !selectedCommand"
                />

                <h3 class="text-lg my-2">Create New Module</h3>
                <input
                    v-model="moduleName"
                    type="text"
                    class="p-2 border rounded-md w-full mb-2"
                    placeholder="Module Name"
                />
                <Button
                    @click="createModule"
                    text="Create Module"
                    btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple mt-2 w-full"
                    :disabled="isLoading || !moduleName"
                />

                <h3 class="text-lg my-2"><i class="ti-package mr-2"></i>Modules List</h3>
                <ul class="bg-background-body p-3 rounded-md">
                    <li v-for="mod in modules" :key="mod.name" class="p-2 border-b border-border-line flex justify-between">
                        <span>{{ mod.name }}</span>
                        <span :class="mod.status === 'Enabled' ? 'text-color-active' : 'text-torch-red'">
                    {{ mod.status }}
                </span>
                    </li>
                </ul>
                <Button
                    @click="fetchModules"
                    text="Refresh Modules"
                    btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple mt-2 w-full"
                />
            </div>
            <div class="col-span-8">
                <div class="bg-background-body p-3 rounded-md h-[400px]">
                    <h3 class="mb-2 text-button-warning-text font-medium flex items-center gap-2 p-2">
                        <i class="ti-clipboard"></i> Execution Result:
                    </h3>
                    <pre v-if="result" :class="preClass" class="whitespace-pre-wrap text-sm p-2 rounded-md">{{ result }}</pre>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useModuleManager } from '@/composables/AdminTool/useModuleManager';
import {onMounted} from "vue";
import Button from "@/components/Button.vue";

const {
    modules,
    result,
    isLoading,
    moduleName,
    preClass,
    selectedModule,
    selectedCommand,
    moduleCommands,
    fetchModules,
    executeCommand,
    createModule
} = useModuleManager();

onMounted(async () => {
    await fetchModules();
});
</script>
