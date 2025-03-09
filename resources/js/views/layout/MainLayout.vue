<template>
    <div class="main-wapper">
        <Sidebar/>
        <main>
            <Topbar :title="title"/>
            <div class="page-wapper">
                <slot></slot>
            </div>
             <Footer/>
        </main>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import Sidebar from '@/views/layout/partials/Sidebar.vue';
import Footer from '@/views/layout/partials/Footer.vue';
import Topbar from '@/views/layout/partials/Topbar.vue';
import PageHeading from '@/views/layout/partials/PageHeading.vue';

defineProps({
    title: String
});
</script>
