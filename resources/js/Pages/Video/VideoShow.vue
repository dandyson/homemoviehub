<template>
  <AuthenticatedLayout>
    <section class="h-[25rem] sm:h-auto">
      <vue-plyr>
        <div data-plyr-provider="youtube" :data-plyr-embed-id="video.youtube_url"></div>
      </vue-plyr>
    </section>

    <section class="mt-8 mx-auto max-w-screen-xl p-4 grid md:grid-cols-3 gap-6 text-gray-100 dark:text-white pb-20">
      <div class="video-page-description col-span-2 me-10">
        <h1 class="text-3xl font-extrabold sm:text-5xl text-gray-700 dark:text-white">{{ video.title }}</h1>
        <p class="my-4 sm:text-xl text-left text-gray-700 dark:text-white">{{ video.description }}</p>
        <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" target="_blank" :href="`https://youtube.com/watch?v=${video.youtube_url}`">
          <font-awesome-icon icon="fa-solid fa-square-arrow-up-right" class="me-2" />
          See Video on YouTube
        </a>

        <div class="video-buttons mt-5">
          <Link :href="route('video.edit', { video: video.id })" class="inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
            <span class="px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
              <font-awesome-icon icon="fa-solid fa-pencil" class="me-2" /> Edit Details
            </span>
          </Link>

          <button @click="deleteVideo" class="inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-red-400 via-red-500 to-red-700 group-hover:from-red-400 group-hover:via-red-500 group-hover:to-red-700 dark:text-white focus:ring-4 focus:outline-none focus:ring-red-100 dark:focus:ring-red-400">
            <span class="px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
              <font-awesome-icon icon="fa-solid fa-trash" class="me-2" />Delete Video
            </span>
          </button>
        </div>

        <h2 class="text-3xl mt-10 mb-5">Locations:</h2>
        <GoogleMap :markers="video.locations"></GoogleMap>

        <div v-if="video.locations.length > 0" class="mt-10">
          <h2 class="text-2xl mb-2 text-white">Location list:</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="(location, index) in video.locations" :key="index" class="flex flex-col justify-center text-white cursor-pointer bg-[#20354b] hover:bg-indigo-600 hover:border-blue-600 hover:border rounded-2xl px-8 py-2 shadow-lg transition-all relative group overflow-hidden">
              <div class="font-bold mb-1 text-2xl">
                <InputLabel for="location" value="Location" />
                <p>{{ location.location }}</p>
              </div>
              <div><InputLabel for="latitude" value="Latitude" /><p>{{ location.lat }}</p></div>
              <div><InputLabel for="longitude" value="Longitude" /><p>{{ location.lng }}</p></div>
            </div>
          </div>
        </div>
      </div>

      <div class="video-page-description md:border-l md:border-opacity-50 md:border-slate-500 md:ps-6">
        <div class="mt-4 text-gray-700 dark:text-white">
          <h4 class="text-2xl mb-3">People included:</h4>
          <div v-if="video.people && video.people.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a v-for="(person, index) in video.people" :href="route('person.show', { person: person })" :key="index" class="w-full bg-[#20354b] hover:bg-indigo-600 hover:border-blue-600 hover:border rounded-2xl px-4 py-6 shadow-lg transition-all relative group overflow-hidden">
              <div class="mx-auto">
                <img :src="person.avatar !== null ? person.avatar : 'https://cdn.pixabay.com/photo/2021/12/17/08/27/silhouette-6875954_1280.png'" class="rounded-full w-28" alt="profile picture">
              </div>
              <div class="mt-8"><h2 class="text-white text-center font-bold text-2xl tracking-wide">{{ person.name }}</h2></div>
              <div class="mt-3 text-white text-sm text-center"><span>{{ person.family }}</span></div>
            </a>
          </div>
          <div v-else><p>There is no one currently associated with this video.</p></div>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>

  
<script setup>
import { onMounted, reactive } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import { Link, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import GoogleMap from '@/Components/Maps/GoogleMap.vue';

const { video, message } = defineProps(['video', 'message']);

const locations = reactive(video.locations ?? []);

onMounted(() => {
  if (message) {
    Swal.fire({
      title: message?.type,
      text: message?.text,
      icon: message?.type.toLowerCase(),
      timer: 2000,
      timerProgressBar: true,
      showConfirmButton: false,
    });
  }
});

const deleteVideo = () => {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 border-b-4 border-red-700 hover:border-red-500 rounded",
      cancelButton: "me-2 bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-4 border-b-4 border-gray-700 hover:border-gray-500 rounded"
    },
    buttonsStyling: false
  });
  swalWithBootstrapButtons.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "No, cancel!",
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      axios.delete(`/video/${video.id}`)
        .then(() => {
          swalWithBootstrapButtons.fire({
            title: "Deleted!",
            text: "The video has been deleted.",
            icon: "success"
          }).then(() => {
            router.visit('/dashboard');
          });
        })
        .catch((error) => {
          // Handle any errors or show a notification
          console.error('Error deleting video:', error);
        })
    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire({
        title: "Cancelled",
        text: "The video is safe :)",
        icon: "error"
      });
    }
  });
}
</script>