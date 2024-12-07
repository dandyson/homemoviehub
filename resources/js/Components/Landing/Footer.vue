<template>
    <footer class="bg-gray-900 text-white py-8 px-4">
        <div class="container mx-auto text-center">
            <div class="flex justify-center space-x-6 mb-4">
                <Link :href="route('cookies')" class="text-gray-300 hover:text-white transition-colors cursor-pointer">
                    Cookies
                </Link>
                <Link :href="route('privacy')" class="text-gray-300 hover:text-white transition-colors cursor-pointer">
                    Privacy Policy
                </Link>
                <Link :href="route('terms')" class="text-gray-300 hover:text-white transition-colors cursor-pointer">
                    Terms of Service
                </Link>
            </div>
            <p>&copy; 2024 HomeMovieHub. All rights reserved.</p>
        </div>
    </footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
