import { ref, nextTick, computed } from 'vue';
import adminToolService from '@/services/adminToolService';

export function useAdminTool() {
    const result = ref('');
    const isLoading = ref(false);
    const currentCommand = ref('');

    const commands = [
        { label: 'Clear Cache', command: 'cache:clear' },
        { label: 'Clear Route Cache', command: 'route:clear' },
        { label: 'Route List', command: 'route:list' },
        { label: 'Clear Config Cache', command: 'config:clear' },
        { label: 'Clear View Cache', command: 'view:clear' },
        { label: 'Migrate Fresh & Seed', command: 'migrate:fresh --seed' },
    ];

    const factories = ref([]);
    const seeders = ref([]);
    const selectedFactory = ref('');
    const factoryCount = ref(10);
    const selectedSeeder = ref('');

    const preClass = computed(() => {
        const res = String(result.value).toLowerCase();
        return res.includes('error') || res.includes('exception')
            ? 'bg-mulberry-purple text-torch-red'
            : 'bg-badge-active text-color-active';
    });

    const fetchFactories = async () => {
        try {
            const { data } = await adminToolService.getFactories();
            factories.value = data.data;
        } catch (error) {
            console.error("Error fetching factories:", error);
        }
    };

    const fetchSeeders = async () => {
        try {
            const { data } = await adminToolService.getSeeders();
            seeders.value = data.data;
        } catch (error) {
            console.error("Error fetching seeders:", error);
        }
    };

    const runFactory = async () => {
        if (!selectedFactory.value || factoryCount.value < 1) {
            alert("Chọn Factory và nhập số lượng hợp lệ!");
            return;
        }

        isLoading.value = true;
        result.value = '';

        await nextTick();

        try {
            const { data } = await adminToolService.runFactory({
                factory: selectedFactory.value,
                count: factoryCount.value
            });
            result.value = `${data.message}\n\nOutput:\n${data.output}`;
        } catch (error) {
            result.value = `Error: ${error.response?.data?.error || error.message}`;
        } finally {
            isLoading.value = false;
        }
    };

    const runSeeder = async () => {
        if (!selectedSeeder.value) {
            alert("Chọn Seeder hợp lệ!");
            return;
        }

        isLoading.value = true;
        result.value = '';

        await nextTick();

        try {
            const { data } = await adminToolService.runSeeder({ seeder: selectedSeeder.value });
            result.value = `${data.message}\n\nOutput:\n${data.output}`;
        } catch (error) {
            result.value = `Error: ${error.response?.data?.error || error.message}`;
        } finally {
            isLoading.value = false;
        }
    };

    const runCommand = async (command) => {
        isLoading.value = true;
        currentCommand.value = command;
        result.value = '';

        await nextTick();

        try {
            const { data } = await adminToolService.adminLaravelCommand({command});
            result.value = `${data.message}\n\nOutput:\n${data.data}`;
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
        factories,
        seeders,
        selectedFactory,
        factoryCount,
        selectedSeeder,
        runCommand,
        fetchFactories,
        fetchSeeders,
        runFactory,
        runSeeder,
    };
}
