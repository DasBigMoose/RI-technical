<script setup>
import SecretDisplay from '@/Components/SecretDisplay.vue'
const props = defineProps({
    activeSecrets: Array,
    inactiveSecrets: Array,
});

</script>

<template>
    <div v-if="props.activeSecrets && props.activeSecrets.length > 0">
        <h1 class="font-bold text-xl">Active Secrets</h1>
        <div class="w-full flex flex-row px-6 py-4 gap-2">
            <span class="w-2/5">Message</span>
            <span class="w-3/5">Link</span>
            <span class="w-1/6 text-end">Expires At</span>
        </div>
        <div v-for="secret in props.activeSecrets">
            <SecretDisplay :secret="secret"/>
        </div>
    </div>

    <div v-if="props.inactiveSecrets && props.inactiveSecrets.length > 0">
        <h1 class="font-bold text-xl">Inactive Secrets</h1>
        <div class="w-full flex flex-row px-6 py-4 gap-2">
            <span class="w-2/5">Message</span>
            <span class="w-3/5">Link</span>
            <span class="w-1/6 text-end">Expires At</span>
        </div>
        <div v-for="secret in props.inactiveSecrets">
            <SecretDisplay :secret="secret"/>
        </div>
    </div>
</template>

