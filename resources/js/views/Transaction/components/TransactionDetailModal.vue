<template>
    <Modal @close="$emit('close')">
        <h3 class="text-lg mb-2">Transaction Details: {{ transaction.id }}</h3>
        <p><strong>Description:</strong> {{ transaction.description || 'No description available' }}</p>
        <div class="mt-5 text-right">
            <Button
                btnClass="bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple"
                text="Close"
                icon="ti-close"
                @click="$emit('close')"
            />
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
