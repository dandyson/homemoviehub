<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import CollectionLatest from '@/Components/Collections/CollectionLatest.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const { videos } = defineProps(['videos']);

/** TODO: This is a little hacky but it allows me to use the same data to 
 * just slice the latest Video for the hero and then the rest of the collection 
 * has all except the latest one, so the hero one does not repeat in the collection 
 * below it
 * */
const latestVideo = videos[0];
const collectionVideos = videos.slice(1);

const latestVideoBgStyle = ref({
  backgroundImage: `url('${latestVideo.cover_image}')`,
  backgroundSize: 'cover',
  backgroundPosition: 'center',
  backgroundRepeat: 'no-repeat',
});



</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <section
            class="relative h-screen" :style="latestVideoBgStyle">
            <div class="absolute inset-0 bg-black/50">
            </div>
            <div class="relative mx-auto max-w-screen-xl px-4 py-32 sm:px-6 lg:flex lg:h-screen lg:items-center lg:px-8">
                <div class="max-w-xl text-center sm:text-left max-w-xl text-center sm:text-left px-4 sm:px-0">
                    <h1 class="text-3xl font-extrabold sm:text-5xl text-white">
                        {{ latestVideo.title }}
                    </h1>

                    <p class="mt-4 max-w-lg text-center sm:text-xl sm:text-left text-white">
                        {{ latestVideo.description }}
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4 text-center">
                        <Link :href="route('video.show', { video: latestVideo.id })"  class="rounded bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring active:bg-indigo-500 px-3 py-2 text-sm text-white font-medium me-4">
                            <font-awesome-icon icon="fa-solid fa-video" /> <span class="ml-1">See Video</span>
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>

    <CollectionLatest :videos="collectionVideos"></CollectionLatest>
</template>
