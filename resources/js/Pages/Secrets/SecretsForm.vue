<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DateInput from '@/Components/DateInput.vue';


const form = useForm({
    message: "",
    expires_at: "",
    _method: 'POST',
});

const emit = defineEmits(['submitted'])

function submit() {
    form.post(route('secrets.generate'));
    form.reset();
    emit("submitted");
};

function formatToday() {
  let today = new Date();
  let year = today.getFullYear();
  let month = ('0' + (today.getMonth() + 1)).slice(-2);
  let day = ('0' + today.getDate()).slice(-2);
  return year + '-' + month + '-' + day;
}

</script>


<template>
    <form @submit.prevent="submit">
        <div>
            <InputLabel for="message" value="Message" />
            <TextInput
                    id="message"
                    v-model="form.message"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    />
            <InputError class="mt-2" :message="form.errors.message" />
        </div>
        <div class="mt-4">
            <InputLabel for="expires_at" value="Expiry" />
            <DateInput
                    id="expires_at"
                    v-model="form.expires_at",
                    :min="formatToday()"
                    type="date"
                    class="mt-1 block w-full"
                    />
            <InputError class="mt-2" :message="form.errors.expires_at" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Generate Secret Link
            </PrimaryButton>
        </div>
    </form>
</template>

