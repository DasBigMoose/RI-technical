<script setup>
const props = defineProps({
    senderName: String,
    message: String,
});

</script>

<template>
    <div class="flex flex-col p-4 border-2 bg-gray-300 border-black-500 rounded-lg">
        <span>You have a message from <span v-text="senderName"/>, please read or copy down before exiting page! Message will not be viewable again!</span>
        <span class="grow mt-2 border-2 bg-blue-300 border-blue-500 rounded-lg p-4" v-text="props.message"/>
    </div>
</template>


