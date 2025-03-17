<template>
    <div class="max-w-7xl mx-auto flex gap-8 font-light">
        <Sidebar/>
        <main class="flex-auto">
            <TopBar :title="title" class="bg-background-section"/>
            <div class="page-wapper bg-background-section p-6 rounded-lg">
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
