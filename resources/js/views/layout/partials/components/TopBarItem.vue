<template>
    <li class="p-2 text-sm mb-1 rounded-md transition duration-300 ease-in-out hover:bg-link-bg-hover hover:text-white">
        <i :class="icon"></i>
        {{ text }}
    </li>
</template>

<script setup>
import {defineProps} from 'vue';

defineProps({
    icon: String,
    text: String,
});
</script>
