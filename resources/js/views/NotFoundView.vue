<template>
    <div>
        <CodeHighlight
            language="html"
            code="<div class='test'>This will be safely escaped</div>"
        />

        <CodeHighlight
            language="javascript"
            :code="jsCode"
        />
    </div>
</template>

<script setup>
import { ref } from 'vue'
import CodeHighlight from '@/components/CodeHighlight.vue'

const jsCode = ref(`const example = "<script>alert('XSS')<\\/script>"`)
</script>
