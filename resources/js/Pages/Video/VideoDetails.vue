<template>
    <AuthenticatedLayout>
        <div class="h-screen flex flex-col items-center justify-center mx-4 sm:mx-12 lg:mx-60">
            <h2 class="font-semibold text-4xl md:text-6xl text-gray-800 dark:text-gray-200 leading-tight font-bebas tracking-wider text-center">
                <span v-if="edit">Edit Video</span>
                <span v-else>Add New Video</span>
            </h2>
        <form @submit.prevent="submit" class="bg-gray-700 p-4 md:p-10 rounded mt-8 w-full">
            <div>
                <InputLabel for="title" value="Title" />

                <TextInput
                    id="title"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.title"
                    required
                    autofocus
                    autocomplete="title"
                />

                <InputError class="mt-2" :message="form.errors.title" />
            </div>

            <div class="mt-4">
                <InputLabel for="description" value="Description" />

                <TextArea
                    id="description"
                    type="description"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div class="mt-4">
                <InputLabel for="youtube_url" value="YouTube Link" />

                <TextInput
                    id="youtube_url"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.youtube_url"
                    required
                    autofocus
                    autocomplete="youtube_url"
                />
                <InputError class="mt-2" :message="form.errors.youtube_url" />
            </div>

            <div class="mt-4">
                <InputLabel for="cover_image" value="Cover Image" />

                <FileUpload
                    id="cover_image"
                    type="file"
                    class="mt-1 block w-full"
                    v-model="coverImage"
                    @change="handleCoverImageChange"
                    autocomplete="cover-image"
                />

                <InputError class="mt-2" :message="form.errors.coverImage" />
            </div>

            <!-- <div class="mt-4">
                <InputLabel for="featured_users" value="Featured Users" />

                <Dropdown
                    id="featured_users"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.featured_users"
                    required
                    autocomplete="featured_users"
                />

                <InputError class="mt-2" :message="form.errors.cover_image" />
            </div> -->

            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
            {{ form.progress.percentage }}%
            </progress>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Submit
                </PrimaryButton>
            </div>
        </form>
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import FileUpload from '@/Components/FileUpload.vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
  edit: {
    type: Boolean,
    default: false,
  },
  video: {
    type: Object,
    default: () => ({}),
  },
});

const form = useForm({
  title: ref(props.video.title ?? ''),
  description: ref(props.video.description?? ''),
  youtube_url: ref(props.video.youtube_url ?? ''),
  featured_users: ref(''),
});

let coverImage = ref('');

const handleCoverImageChange = (event) => {
    coverImage = event.target.files[0];
};

const submit = () => {
    debugger;
    const imageUpload = 'none';

    if (coverImage !== '') {
        const formData = new FormData();
        formData.append('cover_image', coverImage);

        axios.post(`/api/video/${props.video.id}/cover-image-upload`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }).then(() => imageUpload = 'success')
          .catch((error) => {
            imageUpload = 'fail';
            console.log({ error });
            return;
          });
    }
    if (imageUpload === 'none' || imageUpload === 'success') {
        if (props.edit) {
            form.put(route('video.update', props.video.id));
        } else {
            form.post(route('video.store'));
        }
    } else {
        swalWithBootstrapButtons.fire({
          title: "Error",
          text: "There was an issue uploading your image, please try again",
          icon: "error"
        });
    }


};
</script>
  
  