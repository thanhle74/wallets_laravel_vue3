<template>
    <div v-if="show" class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center bg-modal-overlay">
        <div class="bg-background-section p-6 rounded-lg">
            <p class="text-torch-red"><i class="ti-alert"></i> {{ message }}</p>
            <div class="mt-5 flex gap-2 items-center justify-center">
                <Button 
                    btnClass="bg-mulberry-purple text-torch-red rounded-md hover:bg-button-red-hover" 
                    icon="ti-check" 
                    text="Delete" 
                    @click="$emit('confirm')" 
                />
                <Button 
                    btnClass="bg-charcoal-gray text-slate-gray rounded-md hover:bg-storm-gray hover:text-lavender-gray" 
                    icon="ti-close" 
                    text="Cancel" 
                    @click="$emit('cancel')" 
                />
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
