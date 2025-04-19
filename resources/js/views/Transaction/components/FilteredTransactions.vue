<template>
    <!-- Đoạn lọc -->
    <div
        v-if="filters.from || filters.to || filters.category || filters.wallet"
        class="flex items-center gap-2 p-3 rounded-lg bg-background-body text-sm mb-4"
    >
        <i class="ti-filter text-base"></i>
        <div>
            <p class="mb-0">
                <span class="font-medium">Đã lọc</span>
                <span v-if="filters.from">
                        từ <strong class="text-amethyst-purple">{{ new Date(filters.from).toLocaleDateString('vi-VN') }}</strong>
                    </span>
                <span v-if="filters.to">
                        đến <strong class="text-amethyst-purple">{{ new Date(filters.to).toLocaleDateString('vi-VN') }}</strong>
                    </span>
                <span v-if="filters.category">,
                        danh mục <strong class="text-amethyst-purple">{{ selectedCategoryName }}</strong>
                    </span>
                <span v-if="filters.wallet">,
                        ví <strong class="text-amethyst-purple">{{ selectedWalletName }}</strong>
                    </span>.
                Tổng cộng <strong class="text-amethyst-purple bg-indigo-night p-1 rounded-md">{{ items.length }}</strong> kết quả.
            </p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    filters: Object,
    items: Array,
    selectedCategoryName: String,
    selectedWalletName: String,
});
</script>
