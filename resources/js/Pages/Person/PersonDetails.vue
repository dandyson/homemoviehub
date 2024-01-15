<template>
    <AuthenticatedLayout>
        <div class="flex flex-col items-center justify-center mx-4 sm:mx-12 lg:mx-60 mt-16">
            <h2
                class="font-semibold text-4xl md:text-6xl text-gray-800 dark:text-gray-200 leading-tight font-bebas tracking-wider text-center">
                <span v-if="updateMode">Edit {{ person.name }}</span>
                <span v-else>Add New Person</span>
            </h2>
            <form @submit.prevent="submit" class="bg-gray-800 p-4 md:p-10 rounded my-8 w-full">
                <div>
                    <InputLabel for="name" value="Name" />

                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                        autocomplete="name" />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="avatar" value="Avatar" />

                    <AvatarUploader :avatarImage="avatarImage" @imageChanged="onImageChange"
                        @invalidFile="handleInvalidFile" />

                    <InputError class="mt-2" :message="form.errors.avatarImage" />
                </div>

                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                    {{ form.progress.percentage }}%
                </progress>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing || invalidFile }"
                        :disabled="form.processing || invalidFile">
                        Submit
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AvatarUploader from '@/Components/AvatarUploader.vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2'

const props = defineProps({
    updateMode: {
        type: Boolean,
        default: false,
    },
    person: {
        type: Object,
        default: () => ({}),
    },
});


const previousUrl = ref(usePage().props.previous);

const form = useForm({
    name: ref(props.person.name ?? ''),
});

let avatarImage = ref(props.person.avatar ?? '');
let avatarImageHasChanged = ref(false);
let invalidFile = ref(false);

const handleInvalidFile = (value) => {
    invalidFile.value = value;
};

const emit = defineEmits();

const onImageChange = (newImage) => {
    avatarImage = newImage;
    avatarImageHasChanged.value = true;
};

const submit = async () => {
    // Set default image if none chosen
    if (avatarImage === '') {
        avatarImage = 'https://images.unsplash.com/photo-1550399504-8953e1a6ac87?q=80&w=2829&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
    }

    /** TODO: I cannot get Inertia.js to use formData to send the image and other form data at the same time,
     * it messes up the other data and Laravel does not want to parse it at all. So I have done the following instead:
     * UPDATE: Send a request to a separate image upload method first and if successful, send the rest of the form data to the usual route (the redirect gets handled in the method)
     * STORE: I use axios to send the formData and get the new person.id and the success message and assign these to new variables. Then, I only send a request
     * to the uploadImnage route of the avatarImage is actually there and not default. Otherwise it will just get the defaultImage URL and store that in the db
     * as the cover imgae
     * 
     * Need to come back to this once I have worked out how to send form data with the image and parsed with Laravel successfully, then we can really make this code cleaner and shorter!
    */

    if (props.updateMode) {
        try {
            const formData = new FormData();
            formData.append('avatar', avatarImage);

            if (avatarImageHasChanged.value) {
                await axios.post(`/person/${props.person.id}/avatar-upload`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    params: {
                        person: props.person,
                    }
                });

            }

            try {
                const response = await form.put(route('person.update', props.person.id));

                Swal.fire({
                    title: response?.data?.message?.type,
                    text: response?.data?.message?.text,
                    icon: response?.data?.message?.type.toLowerCase(),
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                }).then(() => {
                    router.visit(previousUrl.value, { preserveState: true });
                });

            } catch (error) {
                // Handle error with Swal
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred',
                    icon: 'error',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                });
                console.error(error);
            }


        } catch (error) {
            console.log({ error });
            Swal.fire({
                title: "Error",
                text: `There was an issue uploading your image (${error?.response?.data?.message ? error.response.data.message : 'please try again'})`,
                icon: "error",
            });

            throw error;
        }
    } else {
        try {
            let personId;
            let message;

            // Create a separate request item for avatar only if avatarImage is blank
            const avatarImageValue = avatarImage.value !== '' ? {} : { avatar: 'default_avatar' };

            const requestData = { ...form, ...avatarImageValue };

            const response = await axios.post(route('person.store', requestData));
            personId = response.data.person.id;
            message = response.data.message;

            if (avatarImage.value !== '') {
                const formData = new FormData();
                formData.append('avatar', avatarImage);

                await axios.post(`/person/${personId}/avatar-upload`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }).then((res) => {
                    console.log(res);
                }).catch((err) => {
                    console.log(err);
                });
            }

            Swal.fire({
                title: response?.data?.message?.type,
                text: response?.data?.message?.text,
                icon: response?.data?.message?.type.toLowerCase(),
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
            }).then(() => {
                router.visit(`/person`);
            });


        } catch (error) {
            console.log({ error });
            Swal.fire({
                title: "Error",
                text: `There was an issue uploading your image (${error?.response?.data?.message ? error.response.data.message : 'please try again'})`,
                icon: "error",
            });

            throw error;
        }


    }
};

</script>
  
  