<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import SecretsForm from '@/Pages/Secrets/SecretsForm.vue';
import SecretsList from '@/Pages/Secrets/SecretsList.vue';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CopyText from '@/Components/CopyText.vue';

const props = defineProps({
    activeSecrets: Array,
    inactiveSecrets: Array,
    newLink: String,
});

const linkSeen = ref(false);
const showingLink = computed(() => {return !linkSeen.value && (props.newLink && props.newLink.length > 0);});

function closeModal()  {
    linkSeen.value = true
};

function onFormSubmit() {
    linkSeen.value = false
}

</script>

<template>
    <AppLayout title="Secrets">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Secrets
            </h2>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <SecretsForm @submitted="onFormSubmit"/>
                    <SecretsList :activeSecrets="props.activeSecrets" :inactiveSecrets="props.inactiveSecrets"/>
                </div>
            </div>
        </div>
    </AppLayout>
    <DialogModal :show="showingLink" @close="closeModal">
            <template #title>
                Secret Link
            </template>
            <template #content>
                <div class="flex flex-col">
                    <span>Here is your new secret link: </span>
                    <CopyText :text="props.newLink" class="mt-2"/>
                </div>
            </template>
            <template #footer>
                <PrimaryButton
                    class="ms-3"
                    type="button"
                    @click="closeModal"
                >
                    Close
                </PrimaryButton>
            </template>
    </DialogModal>
</template>
