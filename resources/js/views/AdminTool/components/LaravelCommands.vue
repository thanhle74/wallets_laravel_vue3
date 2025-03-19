<template>
    <div class="space-y-4">
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
                <div class="p-3 rounded h-[400px] overflow-auto space-y-2 bg-background-body ">
                    <div v-if="isLoading" class="text-button-warning-text font-medium flex items-center gap-2">
                        <i class="ti-reload animate-spin"></i> Running Command..
                    </div>
                    <div v-else>
                        <h3 v-if="!isLoading" class="mb-2 text-button-warning-text font-medium flex items-center gap-2 p-2">
                            <i class="ti-clipboard"></i> Result:
                        </h3>
                        <pre
                            v-if="result"
                            :class="preClass"
                            class="whitespace-pre-wrap text-sm p-2 rounded-md"
                        >{{ result }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAdminTool } from '@/composables/AdminTool/useAdminTool';
import Button from "@/components/Button.vue";

const { result, isLoading, commands, preClass, runCommand } = useAdminTool();
</script>
