<template>
    <AuthenticatedLayout>
        <section class="aspect-w-16 aspect-h-9 sm:aspect-h-8 md:aspect-h-7 lg:aspect-h-6 xl:aspect-h-5">
            <vue-plyr>
            <div class="plyr__video-embed" ref="videoPlayer">
                <iframe
                :src="video.youtube_url"
                allowfullscreen
                allowtransparency
                allow="autoplay"
                ></iframe>
            </div>
            </vue-plyr>
        </section>
      <section class="mt-8 mx-auto max-w-screen-xl p-4 grid md:grid-cols-3 gap-6 text-white">
        <div class="video-page-description col-span-2">
          <h1 class="text-3xl font-extrabold sm:text-5xl text-white">{{ video.title}}</h1>
  
          <p class="mt-4 max-w-lg text-center sm:text-xl sm:text-left text-white">
            {{ video.description }}
          </p>
        </div>
        <div class="video-page-description">
          <Link :href="route('video.edit', { video: video.id })" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
            <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
              <font-awesome-icon icon="fa-solid fa-pencil" class="me-2" /> Edit Details
            </span>
          </Link>
          <button @click="deleteVideo()" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-red-400 via-red-500 to-red-700 group-hover:from-red-400 group-hover:via-red-500 group-hover:to-red-700 dark:text-white focus:ring-4 focus:outline-none focus:ring-red-100 dark:focus:ring-red-400">
            <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
              <font-awesome-icon icon="fa-solid fa-trash" class="me-2" />Delete Video
            </span>
          </button>
          
          <h6>People included:</h6>
          <div class="flex flex-col">
            <strong>Dan</strong>
            <strong>Jim</strong>
            <strong>Jaz</strong>
            <strong>Mum</strong>
          </div>
        </div>
      </section>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import axios from 'axios';
  import { Link } from '@inertiajs/vue3';
  import Swal from 'sweetalert2'
  import { router } from '@inertiajs/vue3'

  const { video, message } = defineProps(['video', 'message']);

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
  
  