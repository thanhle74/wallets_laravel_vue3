<template>
    <div class="form-group">
        <label :for="id">{{ label }}</label>
        <slot/>
    </div>
</template>

<script setup>
defineProps({
    label: String,
    id: String
});
</script>
