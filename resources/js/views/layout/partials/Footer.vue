<template>
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; {{ year }} {{ companyName }}. All rights reserved. Version: {{ appVersion }}</p>
        </div>
    </footer>
</template>

<script setup>
import { config } from "@/config";

const { appVersion, companyName, year, socialLinks } = config;
</script>

<style scoped>
.footer {
    background-color: #2f3349;
    color: #b2b9bf;
    padding: 15px;
    text-align: center;
    font-size: 1.2rem;
    margin-top: 2rem;
    box-shadow: 0 0.125rem 0.5rem rgba(19, 17, 32, 0.1803921569);
    border-radius: 0.375rem;
    overflow: hidden;
}

.footer-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}
</style>
