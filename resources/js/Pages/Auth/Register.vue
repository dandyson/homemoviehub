<template>
    <GuestLayout customClasses="sm:max-w-md">
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    :disabled="props.env !== 'local'"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    :disabled="props.env !== 'local'"
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    :disabled="props.env !== 'local'"
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    :disabled="props.env !== 'local'"
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing && props.env !== 'local'">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Swal from "sweetalert2";
import { onMounted } from 'vue';

const props = defineProps({
  env: String
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    // TODO: This is all working, but need to get a good privacy policy and TOCs before allowing users to join
    // form.post(route('register'), {
    //     onFinish: () => form.reset('password', 'password_confirmation'),
    // });
    if (props.env === 'local') {
        form.post(route('register'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'This is a demo app only',
            html: 'Please contact us at <a href="mailto:homevideohub@gmail.com">homevideohub@gmail.com</a> with your email address.<br><br>' +
                'Once we have added you to our email service, you will be able to register for an account<br><br>' +
                'Thank you for your interest!',
            showConfirmButton: true,
        });
    }
};

onMounted(() => {
  // TODO: This is all working, but need to get a good privacy policy and TOCs before allowing users to join
  if (props.env !== 'local') {
    Swal.fire({
        icon: 'warning',
        title: 'Demo Notice',
        html: 'This is a <strong>demo</strong> version of our app, so trying to sign up here will throw an error.<br><br>' +
            'To sign up and access the service, please contact us at <a href="mailto:homevideohub@gmail.com">homevideohub@gmail.com</a> with your email address.<br><br>' +
            'Once we have added you to our email service, you will be able to register for an account<br><br>' +
            'Thank you for your interest!',
        showConfirmButton: true,
    });
  }
});
</script>
