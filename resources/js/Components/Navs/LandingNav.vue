<template>
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center logo-title">
                        <Link :href="route('dashboard')" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight font-bebas tracking-wider text-2xl mt-1">
                            HOMEMOVIEHUB
                        </Link>
                    </div>
                </div>

                <div class="flex items-center sm:ms-6">
                    <div class="flex">
                        <!-- Navigation Links -->
                        <div class="space-x-2 -my-px ms-10 flex">
                            <button type="button" @click="login" class="relative inline-flex items-center justify-center p-0.5 me-2 overflow-hidden text-xs sm:text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                                <span class="relative flex justify-center items-center  px-3 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                    <font-awesome-icon icon="fa-solid fa-sign-in" />
                                    <span class="hidden sm:block ms-2">Login</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { login, logout } from '../../auth.js';

</script>
