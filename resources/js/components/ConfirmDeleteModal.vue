<template>
    <div v-if="show" class="delete-modal">
        <div class="delete-content">
            <p><i class="ti-alert"></i> {{ message }}</p>
            <div class="modal-bnt">
                <Button btnClass="btn-delete" icon="ti-check" text="Delete" @click="$emit('confirm')" />
                <Button btnClass="btn-cancel" icon="ti-close" text="Cancel" @click="$emit('cancel')" />
            </div>
        </div>
    </div>
</template>

<script setup>
import Button from "@/components/Button.vue";

defineProps({
    show: Boolean,
    message: {
        type: String,
        default: "Are you sure?",
    },
});
</script>
