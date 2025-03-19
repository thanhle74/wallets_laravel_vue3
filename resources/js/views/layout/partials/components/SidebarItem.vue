<template>
    <li>
        <RouterLink :to="url" :class="computedClass" class="p-2 rounded-md text-sm flex gap-3 items-center transition
                        duration-300 ease-in-out mb-2 hover:bg-link-bg-hover hover:text-white">
            <i :class="icon"></i>
            {{ label }}
        </RouterLink>
    </li>
</template>

<script setup>
import {defineProps} from "vue";

defineProps({
    url: String,
    icon: String,
    computedClass: String,
    label: String,
});
</script>
