<template>
    <footer class="py-2 px-6 rounded-lg relative bg-background-section text-center mt-4">
        <div class="footer-content">
            <p>&copy; {{ year }} {{ companyName }}. {{ footerText }} {{ appVersion }}</p>
        </div>
    </footer>
</template>

<script setup>
import { config } from "@/config";
import { useSettings } from "@/composables/Setting/useSettings";
import { computed, onMounted } from "vue";

const { settings, fetchSettings } = useSettings();
const { appVersion, companyName, year, socialLinks } = config;

const footerText = computed(() => {
    const footerSetting = settings.value.find(s => s.key === "footer_text");
    return footerSetting?.value || 'Footer';
});

onMounted(async () => {
    await fetchSettings();
});
</script>
