<template>
    <div class="space-y-4">
        <!-- Select Factory -->
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-5">
                <select v-model="selectedFactory" class="p-2 rounded w-full">
                    <option disabled value="">Select Factory</option>
                    <option v-for="factory in factories" :key="factory" :value="factory">{{ factory }}</option>
                </select>
            </div>
            <div class="col-span-3">
                <input v-model="factoryCount" type="number" min="1" class="p-2 rounded w-full">
            </div>
            <div class="col-span-4">
                <Button
                    @click="runFactory"
                    text="Run Factory"
                    btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple w-full"
                />
            </div>
        </div>

        <!-- Select Seeder -->
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-8">
                <select v-model="selectedSeeder" class="p-2 rounded w-full">
                    <option disabled value="">Select Seeder</option>
                    <option v-for="seeder in seeders" :key="seeder" :value="seeder">{{ seeder }}</option>
                </select>
            </div>
            <div class="col-span-4">
                <Button
                    @click="runSeeder"
                    text="Run Seeder"
                    btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple w-full"
                />
            </div>
        </div>
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-3 w-full">
                <Button
                    v-for="cmd in commands"
                    :key="cmd.command"
                    @click="runCommand(cmd.command)"
                    btnClass="w-full bg-indigo-night transition text-amethyst-purple rounded-md hover:bg-royal-purple mb-3"
                    icon="ti-terminal"
                    :text="cmd.label"
                />
            </div>

            <div class="col-span-9">
                <div class="p-3 rounded h-[400px] overflow-auto space-y-2 bg-background-body">
                    <div v-if="isLoading" class="text-button-warning-text font-medium flex items-center gap-2">
                        <i class="ti-reload animate-spin"></i> Running Command..
                    </div>
                    <div v-else>
                        <h3 class="mb-2 text-button-warning-text font-medium flex items-center gap-2 p-2">
                            <i class="ti-clipboard"></i> Execution Result:
                        </h3>
                        <pre v-if="result" :class="preClass" class="whitespace-pre-wrap text-sm p-2 rounded-md">{{ result }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAdminTool } from '@/composables/AdminTool/useAdminTool';
import Button from "@/components/Button.vue";
import {onMounted} from "vue";

const {
    result,
    isLoading,
    commands,
    preClass,
    factories,
    seeders,
    selectedFactory,
    factoryCount,
    selectedSeeder,
    fetchFactories,
    fetchSeeders,
    runCommand,
    runFactory,
    runSeeder,
} = useAdminTool();


onMounted(async () => {
    await fetchFactories();
    await fetchSeeders();
});
</script>
