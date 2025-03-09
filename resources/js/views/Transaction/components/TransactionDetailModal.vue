<template>
    <div class="modal-overlay" @click.self="$emit('close')">
        <div class="modal-content">
            <h3>Transaction Details</h3>
            <p><strong>ID:</strong> {{ transaction.id }}</p>
            <p><strong>Category:</strong> {{ transaction.category.name }}</p>
            <p><strong>Wallet:</strong> {{ transaction.wallet.name }}</p>
            <p><strong>Amount:</strong> {{ transaction.amount }}</p>
            <p><strong>Date:</strong> {{ transaction.transaction_date }}</p>
            <p><strong>Description:</strong> {{ transaction.description || 'No description available' }}</p>
            <button class="btn-close" @click="$emit('close')">Close</button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    transaction: Object
});
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    max-width: 90%;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: left;
}

h3 {
    margin-top: 0;
}

.btn-close {
    margin-top: 10px;
    padding: 8px 12px;
    background: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}

.btn-close:hover {
    background: #0056b3;
}
</style>
