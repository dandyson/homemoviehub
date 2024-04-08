<template>
    <div class="mx-auto w-full rounded-xl bg-neutral-100 p-6">
        <div class="flex w-full flex-col items-center space-y-6 py-12 text-center md:py-32">
            <div class="flex max-w-xl flex-col space-y-2">
                <h1 class="text-4xl font-semibold leading-snug tracking-tight md:text-5xl">
                    Sign up today.
                </h1>
                <p class="text-gray-600">
                    Get started with a free account on our platform
                </p>
            </div>
            <div class="max-4xl flex flex-col space-y-4">
                <div>
                    <!-- Action button -->
                    <Link :href="route('register')"
                        class="relative inline-flex items-center justify-center p-0.5 me-2 overflow-hidden text-lg font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                    <span class="relative flex justify-center items-center px-3 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                        <font-awesome-icon icon="fa-solid fa-rocket" /> <span class="ml-1"></span>
                        <span class="ms-1">Get Started</span>
                    </span>
                    </Link>
                </div>
                <p class="text-gray-600">
                    Already have an account?
                    <Link :href="route('login')" class="font-medium text-gray-900 hover:text-gray-500">
                    Log in
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

</script>