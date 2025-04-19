<template>
    <div class="max-w mx-auto grid grid-cols-12 font-light">
        <Sidebar/>
        <main class="col-span-10 p-8">
            <TopBar :title="title" :icon="icon" class="bg-background-section shadow min-h-[56px]"/>
            <div class="page-wapper bg-background-section p-8 rounded-lg shadow transition-all duration-500">
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
    title: String,
    icon: String,
});
</script>
