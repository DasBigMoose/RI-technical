<script setup>
async function copyToClipboard() {
    await navigator.clipboard.writeText(props.text);
}
const props = defineProps({
    text: String,
    class: String,
});
</script>

<template>
    <span title="Click to copy to clipboard" :class="props.class" class="cursor-pointer" @click="copyToClipboard" v-text="props.text"/>
</template>
