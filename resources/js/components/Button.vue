<template>
    <button :class="btnClass">
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

<style scoped lang="scss">
@use "@/assets/scss/components/button";
</style>
