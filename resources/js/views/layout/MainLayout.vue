<template>
    <div class="max-w-7xl mx-auto grid grid-cols-12 gap-8 font-light">
        <Sidebar/>
        <main class="col-span-9">
            <TopBar :title="title" class="bg-background-section shadow min-h-[56px]"/>
            <div class="page-wapper bg-background-section p-6 rounded-lg shadow">
                <slot></slot>
            </div>
             <Footer/>
        </main>
    </div>
</template>

<script setup>
import Sidebar from '@/views/layout/partials/Sidebar.vue';
import Footer from '@/views/layout/partials/Footer.vue';
import TopBar from '@/views/layout/partials/TopBar.vue';

defineProps({
    title: String
});
</script>
