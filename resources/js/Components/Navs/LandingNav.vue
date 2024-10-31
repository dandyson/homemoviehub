<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import DashboardNav from '@/Components/Navs/DashboardNav.vue';
import { login, logout } from '../../auth.js';

const showingNavigationDropdown = ref(false);

</script>

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
                        <!-- Action buttons -->
                        <button type="button" @click="login" class="relative inline-flex items-center justify-center p-0.5 me-2 overflow-hidden text-xs sm:text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                            <span class="relative flex justify-center items-center  px-3 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                <font-awesome-icon icon="fa-solid fa-sign-in" />
                                <span class="hidden sm:block ms-2">Login</span>
                            </span>
                        </button>
                        <!-- <Link :href="route('register')" class="relative inline-flex items-center justify-center p-0.5 me-2 overflow-hidden text-xs sm:text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                            <span class="relative flex justify-center items-center  px-3 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                <font-awesome-icon icon="fa-solid fa-rocket" />
                                <span class="hidden sm:block ms-2">Get Started</span>
                            </span>
                        </Link> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div
        :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
        class="sm:hidden"
    >
        <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                Dashboard
            </ResponsiveNavLink>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <Link :href="route('video.create')" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                    <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                        <font-awesome-icon icon="fa-solid fa-file-video" size="xl" class="me-2" /> + Add Video
                    </span>
                </Link>
                <!-- <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                    {{ $page.props.auth.user.name }}
                </div> -->
                <!-- <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div> -->
            </div>

            <div class="mt-3 space-y-1">
                <!-- <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink> -->
                <ResponsiveNavLink :href="route('person.index')"> People </ResponsiveNavLink>
                <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                    Log Out
                </ResponsiveNavLink>
            </div>
        </div>
    </div>
</nav>
</template>
