<template>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Magento Module Generator</h2>
        <div class="space-y-4">
            <input v-model="vendor" type="text" placeholder="Vendor Name" class="w-full p-2 border rounded" />
            <input v-model="module" type="text" placeholder="Module Name" class="w-full p-2 border rounded" />

            <label class="flex items-center space-x-2">
                <input type="checkbox" v-model="createTable" />
                <span>Create Table</span>
            </label>

            <label class="flex items-center space-x-2">
                <input type="checkbox" v-model="createEvent" />
                <span>Create Event</span>
            </label>

            <input v-if="createEvent" v-model="eventName" type="text" placeholder="Event Name" class="w-full p-2 border rounded" />

            <button @click="generateModule" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                Generate Module
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import generateMagentoModule from '@/utils/Magento/generateMagentoModule';

const vendor = ref('');
const module = ref('');
const createTable = ref(false);
const createEvent = ref(false);
const eventName = ref('');

const generateModule = async () => {
    if (!vendor.value || !module.value) {
        alert('Please enter Vendor and Module name.');
        return;
    }

    await generateMagentoModule(vendor.value, module.value, {
        createTable: createTable.value,
        createEvent: createEvent.value,
        eventName: eventName.value
    });
};
</script>
