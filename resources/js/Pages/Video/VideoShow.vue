<template>

    <vue-plyr>
    <div class="plyr__video-embed" ref="videoPlayer">
        <iframe
        src="https://www.youtube.com/watch?v=C0DPdy98e4c&t=5s"
        allowfullscreen
        allowtransparency
        allow="autoplay"
        ></iframe>
    </div>
    </vue-plyr>

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

    setTimeout(() => {
      alert(video.youtube_url);
    }, 3000);
    
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
  
  <style>
</style>