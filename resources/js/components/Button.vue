<script setup>
import { defineProps, computed } from 'vue';

// Định nghĩa các lớp cho các loại button
const buttonTypes = {
    primary: "bg-indigo-night text-amethyst-purple hover:bg-royal-purple",
    cancel: "bg-charcoal-gray text-slate-gray hover:bg-storm-gray hover:text-lavender-gray",
    secondary: "bg-gray-600 text-white hover:bg-gray-700",
    danger: "bg-mulberry-purple text-torch-red hover:bg-button-red-hover",
    success: "bg-green-600 text-white hover:bg-green-700",
    info: "bg-deep-navy text-cerulean-blue mr-1 hover:bg-midnight-blue",
    warning: "bg-button-warning-bg text-button-warning-text hover:bg-button-warning-hover-text",
};

// Nhận các prop
const props = defineProps({
    btnClass: {
        type: String,
        default: '',  // Giá trị mặc định là chuỗi rỗng
    },  // class custom bổ sung
    icon: String,
    text: String,
    type: {            // type có thể là primary, secondary, danger, etc.
        type: String,
        default: null,   // Không có giá trị mặc định, nếu không có `type` thì sẽ dùng `btnClass` thôi
    }
});

// Tạo lớp cuối cùng cho button
const finalClass = computed(() => {
    // Nếu có type, lấy lớp của type đó. Nếu không, chỉ dùng btnClass.
    if (props.type && buttonTypes[props.type]) {
        return `${buttonTypes[props.type]} ${props.btnClass}`.trim(); // Lấy lớp của type và kết hợp với btnClass
    } else {
        return props.btnClass || ''; // Nếu không có type thì chỉ dùng btnClass
    }
});
</script>

<template>
    <button :class="finalClass" class="px-3 py-2 rounded-md">
        <i :class="icon"></i>
        {{ text }}
    </button>
</template>
