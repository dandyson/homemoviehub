<template>
    <GuestLayout customClasses="sm:max-w-4xl m-4">
        <Head title="Cookies Policy" />
        <div class="py-4 px-8">
            <div class="mx-auto">
                <div class="text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <h1 class="text-2xl font-bold mb-4">Cookie Policy</h1>

                    <h2 class="text-xl font-semibold mt-4 mb-2">Essential Cookies</h2>
                    <p>This website uses only essential cookies that are necessary for the website to function properly. We do not use any tracking or analytics cookies.</p>

                    <h3 class="text-lg font-semibold mt-4 mb-2">Cookies we use:</h3>
                    <ul class="list-disc pl-5">
                        <li><strong>Session Cookie:</strong> Used to maintain your login session</li>
                        <li><strong>XSRF-TOKEN:</strong> Used to prevent cross-site request forgery attacks and protect your security</li>
                    </ul>

                    <h3 class="text-lg font-semibold mt-4 mb-2">Third-party Services</h3>
                    <p>Please note that our integration with YouTube for video playback and Google Maps for location services may set their own cookies. These services are only loaded when you actively use them.</p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
</script>
