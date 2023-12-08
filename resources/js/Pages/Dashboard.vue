<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import CollectionLatest from '@/Components/Collections/CollectionLatest.vue';
import { Link } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';

const { videos } = defineProps(['videos']);

const latestVideo = ref(videos.length > 0 ? videos[0] : null);
const collectionVideos = ref(videos.slice(1));

const latestVideoBgStyle = ref({
  backgroundImage: `url('${latestVideo.value?.cover_image || ''}')`,
  backgroundSize: 'cover',
  backgroundPosition: 'center',
  backgroundRepeat: 'no-repeat',
});
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <section class="relative lg:h-[45rem]" :style="latestVideoBgStyle">
      <div class="absolute inset-0 bg-black/50"></div>
      <div class="relative mx-auto max-w-screen-xl px-4 py-16 sm:px-6 flex justify-center items-center lg:justify-start h-[30rem] lg:h-full lg:px-8">
        <div v-if="latestVideo" class="max-w-xl text-center sm:text-left max-w-xl text-center sm:text-left px-4 sm:px-0">
          <h1 class="text-2xl sm:text-3xl md:text-5xl font-extrabold text-white">
            {{ latestVideo?.title }}
          </h1>

          <p class="mt-4 max-w-lg text-center sm:text-xl sm:text-left text-white">
            {{ latestVideo?.description }}
          </p>

          <div class="mt-8 flex justify-center sm:justify-start flex-wrap gap-4 text-center">
            <Link
              :href="route('video.show', { video: latestVideo?.id })"
              class="rounded bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring active:bg-indigo-500 px-3 py-2 text-sm text-white font-medium me-4"
            >
              <font-awesome-icon icon="fa-solid fa-video" /> <span class="ml-1">See Video</span>
            </Link>
          </div>
        </div>
        <div v-else class="max-w-xl text-center sm:text-left max-w-xl text-center sm:text-left px-4 sm:px-0">
          <h1 class="text-2xl sm:text-3xl md:text-5xl font-extrabold text-white">
            Your latest video will go here
          </h1>

          <p class="mt-4 max-w-lg text-center sm:text-xl sm:text-left text-white">
            Please click 'Add Video' in the Navigation to add your first video!
          </p>

        </div>
      </div>
    </section>
  </AuthenticatedLayout>

  <div v-if="videos.value">
    <CollectionLatest :videos="collectionVideos.value"></CollectionLatest>
</div>
</template>
