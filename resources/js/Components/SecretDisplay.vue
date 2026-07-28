<script setup>
import CopyText from "@/Components/CopyText.vue"
const props = defineProps({
    secret: Object,
});

</script>
<template>
    <div class="w-full flex flex-row px-6 py-4 gap-2">
        <span class="w-2/5" v-text="props.secret.message"/>
        <CopyText class="w-3/5" :text="props.secret.generated_link"/>
        <span class="w-1/6 text-end" v-text="props.secret.expires_at"/>
    </div>
</template>
