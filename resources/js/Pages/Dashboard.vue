<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <section class="relative" :style="latestVideoBgStyle">
      <div class="absolute inset-0 bg-black/50"></div>
      <div v-if="latestVideo" class="relative mx-auto max-w-screen-xl px-4 py-16 sm:px-6 flex justify-center items-center lg:justify-start h-full lg:px-8">
        <div class="max-w-xl text-center sm:text-left max-w-xl text-center sm:text-left px-4 sm:px-0">
          <h1 class="text-2xl sm:text-3xl md:text-5xl font-extrabold text-white">
            {{ latestVideo?.title }}
          </h1>

          <p class="mt-4 max-w-lg text-center sm:text-xl sm:text-left text-white">
            {{ latestVideo?.description }}
          </p>

          <div class="mt-8 flex justify-center sm:justify-start flex-wrap gap-4 text-center">
            <Link
              :href="route('video.show', { video: latestVideo?.id })"
              class="rounded bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring active:bg-indigo-500 px-3 py-2 text-sm text-white font-medium"
            >
              <font-awesome-icon icon="fa-solid fa-video" /> <span class="ml-1">See Video</span>
            </Link>
          </div>
        </div>
      </div>
      <div v-else class="relative mx-auto max-w-screen-xl px-4 py-16 sm:px-6 flex justify-center items-center text-center h-full lg:px-8">
        <div class="max-w-xl h-full max-w-xl text-center px-4 sm:px-0 flex flex-col items-center justify-center">
          <h1 class="text-2xl sm:text-3xl md:text-5xl font-extrabold text-white">
            You don't have any videos yet.
          </h1>

        <Link :href="route('video.create')" class="relative inline-flex items-center justify-center p-0.5 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 mt-8">
            <span class="relative px-3 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                <font-awesome-icon icon="fa-solid fa-file-video" size="xl" class="me-1" /> + Add Your First Video!
            </span>
        </Link>

        </div>
      </div>
    </section>
    <div v-if="videos.length > 1">
      <CollectionLatest :videos="collectionVideos"></CollectionLatest>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import CollectionLatest from '@/Components/Collections/CollectionLatest.vue';
import { Link } from '@inertiajs/vue3';
import { ref, defineProps, reactive } from 'vue';

const { videos } = defineProps(['videos']);

const latestVideo = Object.values(videos)[0] ?? null;
const collectionVideos = ref(Object.values(videos).slice(1) || []);

const latestVideoBgStyle = ref({
  backgroundImage: `url('${latestVideo?.cover_image || ''}')`,
  backgroundSize: 'cover',
  backgroundPosition: 'center',
  backgroundRepeat: 'no-repeat',
  height: videos.length > 1 ? '80vh' : '100vh',
});

</script>
