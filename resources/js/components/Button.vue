<template>
    <button :class="btnClass" class="px-3 py-2">
        <i :class="icon"></i>
        {{ text }}
    </button>
</template>

<script setup>
import {defineProps} from 'vue';

defineProps({
    btnClass: String,
    icon: String,
    text: String,
});
</script>
