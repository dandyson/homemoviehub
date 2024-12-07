<template>
    <GuestLayout customClasses="sm:max-w-4xl m-4">
        <Head title="Terms of Service" />
        <div class="py-4 px-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h1 class="text-2xl font-bold mb-6">Terms of Service</h1>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">1. Demo Nature of the Service</h2>
                        <p class="mb-4">HomeMovieHub is a demonstration project created for portfolio purposes. While fully functional, it is not intended for production use. Access is limited to authorized demo accounts only.</p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">2. Account Usage</h2>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Demo accounts are provided for evaluation purposes only</li>
                            <li>You are responsible for maintaining the confidentiality of your demo credentials</li>
                            <li>You agree not to use the service for storing sensitive or confidential information</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">3. Content Guidelines</h2>
                        <p class="mb-4">When using the demo account, you agree not to:</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Upload inappropriate or offensive content</li>
                            <li>Add personal information of real individuals without their consent</li>
                            <li>Use the service for any illegal purposes</li>
                            <li>Attempt to circumvent the service's technical limitations</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">4. Service Limitations</h2>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>No video files are stored - only YouTube links are supported</li>
                            <li>Image uploads are limited to avatars and video covers</li>
                            <li>The service may be modified or terminated at any time</li>
                            <li>No guarantee of service availability or data persistence</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">5. Third-Party Services</h2>
                        <p class="mb-4">By using this service, you also agree to the terms of service of:</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Auth0 (Authentication)</li>
                            <li>YouTube (Video streaming)</li>
                            <li>Google Maps (Location services)</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">6. Changes to Terms</h2>
                        <p>We reserve the right to modify these terms at any time. Continued use of the service constitutes acceptance of the modified terms.</p>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold mb-3">7. Contact</h2>
                        <p>For questions about these terms, please contact: <a href="mailto:homevideohub@gmail.com" class="text-blue-500 hover:text-blue-600">homevideohub@gmail.com</a></p>
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
