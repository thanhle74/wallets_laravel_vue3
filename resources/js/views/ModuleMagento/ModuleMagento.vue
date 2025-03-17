<template>
    <MainLayout>
        <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Magento Module Generator</h2>
            <div class="space-y-4">
                <input v-model="vendor" type="text" placeholder="Vendor Name" class="w-full p-2 border rounded" />
                <input v-model="module" type="text" placeholder="Module Name" class="w-full p-2 border rounded" />
                <button @click="generateModule" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                    Generate Module
                </button>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/views/layout/MainLayout.vue';
import { ref } from 'vue';
import axios from 'axios';

const vendor = ref('');
const module = ref('');

const generateModule = async () => {
    try {
        const response = await axios.post('/api/modulemagento', {
            vendor: vendor.value,
            module: module.value
        }, { responseType: 'blob' }); // Nhận file dạng blob (zip)

        // Tải file về
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `${vendor.value}_${module.value}.zip`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.error('Failed to generate module:', error);
    }
};
</script>
