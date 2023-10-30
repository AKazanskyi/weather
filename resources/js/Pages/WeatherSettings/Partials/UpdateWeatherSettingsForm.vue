<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';


const props = defineProps({
    user: Object,
    settings: Object
});

const form = useForm({
    pause_until: props.settings.pause_until,
    max_uv: props.settings.max_uv,
    max_pr: props.settings.max_pr,
});



const updateSettings = () => {
    form.post(route('settings.store'), {
        errorBag: 'updateSettings',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
        },
    });
};
</script>

<template>
    <FormSection @submitted="updateSettings">
        <template #title>
           Weather settings
        </template>

        <template #form>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="password" value="Do not send notifications until" />
                <TextInput
                    id="pause_until"
                    v-model="form.pause_until"
                    type="datetime-local"
                    class="mt-1 block w-full"
                />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="max_uv" value="Max UV" />
                <TextInput
                    id="max_uv"
                    v-model="form.max_uv"
                    type="number"
                    step="0.0.1"
                    class="mt-1 block w-full"
                />

            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="max_pr" value="Max Precipitation" />
                <TextInput
                    id="max_pr"
                    v-model="form.max_pr"
                    type="number"
                    class="mt-1 block w-full"
                />

            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
