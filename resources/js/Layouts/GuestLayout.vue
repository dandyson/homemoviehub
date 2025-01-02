<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center bg-gray-100 dark:bg-gray-900 py-6 sm:py-8">
        <div>
            <Link href="/">
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
            </Link>
        </div>

        <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
             :class="customClasses">
            <slot />
        </div>
    </div>
</template>

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    customClasses: {
        type: String,
        default: ''
    }
});
</script>
