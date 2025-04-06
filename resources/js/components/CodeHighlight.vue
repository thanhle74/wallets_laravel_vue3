<template>
    <div class="code-container">
        <button class="copy-btn" @click="copyCode">Copy</button>
        <pre><code ref="codeEl" :class="language"></code></pre>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import hljs from 'highlight.js/lib/core'
import javascript from 'highlight.js/lib/languages/javascript'
import xml from 'highlight.js/lib/languages/xml'
import 'highlight.js/styles/github-dark.css'

// Register languages
hljs.registerLanguage('javascript', javascript)
hljs.registerLanguage('html', xml)

const props = defineProps({
    language: {
        type: String,
        default: 'javascript'
    },
    code: {
        type: String,
        default: ''
    }
})

const codeEl = ref(null)

// Escape HTML entities
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;')
}

onMounted(() => {
    updateCode()
})

watch(() => props.code, updateCode)

function updateCode() {
    if (codeEl.value) {
        // Escape the code before setting it
        codeEl.value.innerHTML = escapeHtml(props.code)
        hljs.highlightElement(codeEl.value)
    }
}

function copyCode() {
    if (codeEl.value) {
        navigator.clipboard.writeText(codeEl.value.textContent)
    }
}
</script>

<style>
.code-container {
    position: relative;
    margin: 1em 0;
}

.copy-btn {
    position: absolute;
    right: 8px;
    top: 8px;
    background: #2d2d2d;
    color: white;
    border: none;
    padding: 4px 8px;
    border-radius: 4px;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.copy-btn:hover {
    opacity: 1;
}

pre {
    margin: 0;
    padding: 1em;
    overflow: auto;
    border-radius: 4px;
    background: #0d1117;
}

code {
    font-family: 'Fira Code', monospace;
    font-size: 0.9em;
}
</style>
