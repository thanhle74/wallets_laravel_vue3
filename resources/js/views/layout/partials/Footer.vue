<template>
    <footer class="py-2 px-6 rounded-lg relative bg-background-section text-center mt-4">
        <div class="footer-content">
            <p>&copy; {{ year }} {{ companyName }}. All rights reserved. Version: {{ appVersion }}</p>
        </div>
    </footer>
</template>

<script setup>
import { config } from "@/config";

const { appVersion, companyName, year, socialLinks } = config;
</script>
