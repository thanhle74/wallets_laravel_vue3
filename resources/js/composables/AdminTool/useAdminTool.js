import { ref, nextTick, computed } from 'vue';
import adminToolService from '@/services/adminToolService';

export function useAdminTool() {
    const result = ref('');
    const isLoading = ref(false);
    const currentCommand = ref('');

    const commands = [
        { label: 'Clear Cache', command: 'cache:clear' },
        { label: 'Clear Route Cache', command: 'route:clear' },
        { label: 'Clear Config Cache', command: 'config:clear' },
        { label: 'Clear View Cache', command: 'view:clear' },
        { label: 'Migrate Fresh & Seed', command: 'migrate:fresh --seed' },
    ];

    const preClass = computed(() => {
        const res = String(result.value).toLowerCase();
        return res.includes('error') || res.includes('exception')
            ? 'bg-mulberry-purple text-torch-red'
            : 'bg-badge-active text-color-active';
    });

    const runCommand = async (command) => {
        //if (!confirm(`Bạn chắc chắn muốn chạy: ${command}?`)) return;

        isLoading.value = true;
        currentCommand.value = command;
        result.value = '';

        await nextTick();

        try {
            const { data } = await adminToolService.adminLaravelCommand({command});
            result.value = `${data.message}\n\nOutput:\n${data.output}`;
        } catch (error) {
            result.value = `Error: ${error.response?.data?.error || error.message}`;
        } finally {
            isLoading.value = false;
            currentCommand.value = '';
        }
    };

    return {
        result,
        isLoading,
        commands,
        preClass,
        runCommand,
    };
}
