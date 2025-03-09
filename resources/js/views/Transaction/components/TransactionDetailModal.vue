<template>
    <transition name="fade">
        <div class="modal-overlay" @click.self="$emit('close')">
            <transition name="scale">
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
            </transition>
        </div>
    </transition>
</template>

<script setup>
defineProps({
    transaction: Object
});
</script>

<style scoped>
/* Overlay Animation */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.4s ease-in-out;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

/* Modal Scale Animation */
.scale-enter-active {
    transition: transform 0.4s ease-out, opacity 0.4s ease-out;
}
.scale-leave-active {
    transition: transform 0.3s ease-in, opacity 0.3s ease-in;
}
.scale-enter-from {
    transform: scale(0.9);
    opacity: 0;
}
.scale-leave-to {
    transform: scale(0.9);
    opacity: 0;
}

/* Modal Styles */
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
    z-index: 1000;
}

.modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    max-width: 90%;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
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
