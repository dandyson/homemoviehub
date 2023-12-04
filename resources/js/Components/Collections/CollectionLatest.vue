<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const media = ref([
    {
      text: "Spain October 2022",
      subtitle: "Family Tip to Alicante in October 2022",
      image:
        "https://images.pexels.com/photos/17216084/pexels-photo-17216084/free-photo-of-nourriture-tasse-fruit-panier.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1",
    },
    {
      text: "title",
      subtitle: "subtitle",
      image:
        "https://images.pexels.com/photos/17216084/pexels-photo-17216084/free-photo-of-nourriture-tasse-fruit-panier.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1",
    },
    {
      text: "title",
      subtitle: "subtitle",
      image:
        "https://images.pexels.com/photos/1624487/pexels-photo-1624487.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1",
    },
    {
      text: "title",
      subtitle: "subtitle",
      image:
        "https://images.pexels.com/photos/1624487/pexels-photo-1624487.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1",
    },
    {
      text: "title",
      subtitle: "subtitle",
      image:
        "https://images.pexels.com/photos/1624487/pexels-photo-1624487.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1",
    },
    {
      text: "title",
      subtitle: "subtitle",
      image:
        "https://images.pexels.com/photos/1624487/pexels-photo-1624487.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1",
    },
]);
</script>

<template>
    <section class="relative bg-white dark:bg-gray-600">
        <!-- Section -->
        <div class="absolute inset-0 bg-black/50">
        </div>
        <div class="relative mx-auto max-w-screen-xl p-4 py-12 grid md:grid-cols-3 gap-6">
            <div v-for="(item, index) in media" :key="index" class="relative">
                <div class="max-h-[160px] md:max-h-[200px] w-full object-cover rounded-xl relative">
                    <!-- Card Overlays -->
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-black to-black opacity-50"></div>
                    
                    <div class="absolute inset-0 flex flex-col justify-between text-white px-4 py-6 z-10">
                        <div class="collection-button-container">
                            <p class="font-bold text-2xl">{{ item.text }}</p>
                            <p>{{ item.subtitle }}</p>
                        </div>
                        <div class="collection-button-container">
                            <a href="#" class="rounded bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring active:bg-indigo-500 px-3 py-2 text-sm font-medium me-4">
                                <font-awesome-icon icon="fa-solid fa-play" /> <span class="ml-1">Play</span>
                            </a>
                            <Link :href="route('video-details')" class="rounded bg-white px-3 py-2 text-sm font-medium text-indigo-600 shadow hover:text-indigo-700 focus:outline-none focus:ring active:text-indigo-500 sm:w-auto">
                                <font-awesome-icon icon="fa-solid fa-circle-info" /> <span class="ml-1">Details</span>
                            </Link>
                        </div>
                    </div>

                    <img
                        class="max-h-[160px] md:max-h-[200px] w-full object-cover rounded-xl"
                        :src="item.image"
                        alt="/"
                    />
                </div>
            </div>
        </div>
        
    </section>
</template>