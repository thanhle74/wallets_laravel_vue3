import { ref, computed, nextTick } from 'vue';
import adminToolService from '@/services/adminToolService';

export function useModuleManager() {
    const modules = ref([]);
    const result = ref('');
    const isLoading = ref(false);
    const moduleName = ref('');
    const selectedModule = ref('');
    const selectedCommand = ref('');

    const moduleCommands = [
        { label: 'Enable Module', command: 'enable' },
        { label: 'Disable Module', command: 'disable' },
        { label: 'Migrate Module', command: 'migrate' },
        { label: 'Seed Module', command: 'seed' },
    ];

    const preClass = computed(() => {
        const res = String(result.value).toLowerCase();
        return res.includes('error') || res.includes('exception')
            ? 'bg-mulberry-purple text-torch-red'
            : 'bg-badge-active text-color-active';
    });

    const fetchModules = async () => {
        isLoading.value = true;
        try {
            const { data } = await adminToolService.adminListModules();
            modules.value = data.data;
        } catch (error) {
            result.value = `Error: ${error.response?.data?.error || error.message}`;
        } finally {
            isLoading.value = false;
        }
    };

    const executeCommand = async () => {
        if (!selectedCommand.value || !selectedModule.value) return;
        isLoading.value = true;
        try {
            const { data } = await adminToolService.adminRunModuleCommand({
                command: selectedCommand.value,
                module: selectedModule.value.name,
            });
            result.value = data.message + '\n' + (data.output?.join('\n') || '');
            await fetchModules();
        } catch (error) {
            result.value = `Error: ${error.response?.data?.error || error.message}`;
        } finally {
            isLoading.value = false;
        }
    };


    const createModule = async () => {
        if (!moduleName.value) return;
        isLoading.value = true;
        try {
            const { data } = await adminToolService.adminInstallModule({ name: moduleName.value });
            result.value = data.message + '\n' + (data.output?.join('\n') || '');
            moduleName.value = '';
            await fetchModules();
        } catch (error) {
            result.value = `Error: ${error.response?.data?.error || error.message}`;
        } finally {
            isLoading.value = false;
        }
    };

    return {
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
        createModule,
    };
}
