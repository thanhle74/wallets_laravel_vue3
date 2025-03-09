<template>
    <div v-if="show" class="delete-modal">
        <div class="delete-content">
            <p><i class="ti-alert"></i> {{ message }}</p>
            <div class="modal-bnt">
                <button class="btn-delete" @click="$emit('confirm')">
                    <i class="ti-check"></i> Delete
                </button>
                <button class="btn-cancel" @click="$emit('cancel')">
                    <i class="ti-close"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: Boolean,
    message: {
        type: String,
        default: "Bạn có chắc chắn muốn xóa?",
    },
});
</script>
