<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <section
            class="relative bg-[url(https://plus.unsplash.com/premium_photo-1675824592772-4d0f0daa067e?q=80&w=2938&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D)] bg-cover bg-center bg-no-repeat">
            <div class="absolute inset-0 bg-black/50">
            </div>
            <div class="relative mx-auto max-w-screen-xl px-4 py-32 sm:px-6 lg:flex lg:h-screen lg:items-center lg:px-8">
                <div class="max-w-xl text-center sm:text-left max-w-xl text-center sm:text-left px-4 sm:px-0">
                    <h1 class="text-3xl font-extrabold sm:text-5xl text-white">
                        West Burton <br>October 2023
                    </h1>

                    <p class="mt-4 max-w-lg text-center sm:text-xl sm:text-left text-white">
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nesciunt illo
                        tenetur fuga ducimus numquam ea!
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4 text-center">
                        <a href="#"
                            class="block w-full rounded bg-rose-600 px-12 py-3 text-sm font-medium text-white shadow hover:bg-rose-700 focus:outline-none focus:ring active:bg-rose-500 sm:w-auto">
                            Play
                        </a>

                        <a href="#"
                            class="block w-full rounded bg-white px-12 py-3 text-sm font-medium text-rose-600 shadow hover:text-rose-700 focus:outline-none focus:ring active:text-rose-500 sm:w-auto">
                            See Info
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
