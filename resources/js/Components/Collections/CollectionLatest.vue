<template>
    <section class="relative bg-white dark:bg-gray-800">
        <!-- Section -->
        <div class="relative mx-auto max-w-screen-xl p-4 py-12 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="(item, index) in videos" :key="index" class="relative">
                <div class="max-h-[160px] md:max-h-[200px] w-full object-cover rounded-xl relative">
                    <!-- Card Overlays -->
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-black to-black opacity-50"></div>

                    <div class="absolute inset-0 flex flex-col justify-between text-white px-4 py-6 z-10">
                        <div class="collection-button-container">
                            <p class="font-bold text-2xl">{{ item.title }}</p>
                            <p>{{ item.description.slice(0, 60) + '...' }}</p>
                        </div>
                        <div class="collection-button-container">
                            <Link :href="route('video.show', { video: item.id })"  class="rounded bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring active:bg-indigo-500 px-3 py-2 text-sm font-medium me-4">
                                <font-awesome-icon icon="fa-solid fa-video" /> <span class="ml-1">See Video</span>
                            </Link>
                        </div>
                    </div>

                    <img
                        class="max-h-[160px] md:max-h-[200px] w-full object-cover rounded-xl"
                        :src="item.cover_image"
                        alt="/"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const { videos } = defineProps(['videos']);

</script>
