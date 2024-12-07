<template>
    <GuestLayout customClasses="sm:max-w-4xl m-4">
        <Head title="Privacy Policy" />
        <div class="py-4 px-8">
            <div class="mx-auto">
                <div class="text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <h1 class="text-2xl font-bold mb-6">Privacy Policy</h1>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">Overview</h2>
                        <p class="mb-4">This is a demo portfolio project designed to showcase development skills. While the app is fully functional, it is not intended for production use, and sign-ups are currently disabled.</p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">Data We Collect</h2>
                        <ul class="list-disc pl-5 space-y-2">
                            <li><strong>Account Information:</strong> Name and email (via Auth0 authentication)</li>
                            <li><strong>People Profiles:</strong> Names and family surnames of people you add</li>
                            <li><strong>Images:</strong> Profile avatars and video cover images (stored on AWS S3)</li>
                            <li><strong>Video Information:</strong> Titles, descriptions, and YouTube URLs (no video files are uploaded)</li>
                            <li><strong>Location Data:</strong> Any locations you choose to add to your videos via Google Maps</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">How We Use Your Data</h2>
                        <p class="mb-4">Your data is used solely to provide the demo functionality of the app:</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Authentication and session management</li>
                            <li>Displaying your video collection</li>
                            <li>Managing people profiles</li>
                            <li>Showing video locations on maps</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">Third-Party Services</h2>
                        <ul class="list-disc pl-5 space-y-2">
                            <li><strong>Auth0:</strong> Handles user authentication</li>
                            <li><strong>YouTube:</strong> Provides video streaming functionality</li>
                            <li><strong>Google Maps:</strong> Displays video location data</li>
                            <li><strong>AWS S3:</strong> Stores uploaded images</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">Your Rights</h2>
                        <p class="mb-4">You have the right to:</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Access your personal data</li>
                            <li>Request deletion of your data</li>
                            <li>Export your data</li>
                            <li>Modify incorrect information</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">Contact</h2>
                        <p>For any privacy-related questions, please contact: <a href="mailto:homevideohub@gmail.com" class="text-blue-500 hover:text-blue-600">homevideohub@gmail.com</a></p>
                    </section>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
</script>
