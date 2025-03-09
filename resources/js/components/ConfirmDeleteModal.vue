<template>
    <div v-if="show" class="delete-modal">
        <div class="delete-content">
            <p><i class="fas fa-exclamation-triangle"></i> {{ message }}</p>
            <div class="modal-bnt">
                <button class="btn-delete" @click="$emit('confirm')">
                    <i class="fas fa-check"></i> Xóa
                </button>
                <button class="btn-cancel" @click="$emit('cancel')">
                    <i class="fas fa-times"></i> Hủy
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
