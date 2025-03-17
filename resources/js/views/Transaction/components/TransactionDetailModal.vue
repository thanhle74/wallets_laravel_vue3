<template>
    <Modal @close="$emit('close')">
        <h3>Transaction Details: {{ transaction.id }}</h3>
        <p><strong>Description:</strong> {{ transaction.description || 'No description available' }}</p>
        <div class="box-btn">
            <Button btnClass="btn-cancel" text="Close" icon="ti-close" @click="$emit('close')"/>
        </div>
    </Modal>
</template>

<script setup>
import Button from "@/components/Button.vue";
import Modal from "@/components/Modal.vue";

defineProps({
    transaction: Object
});
</script>
