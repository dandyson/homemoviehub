<template>
    <AuthenticatedLayout>
        <section class="relative h-[35rem]">
            <vue-plyr>
            <div class="plyr__video-embed" ref="videoPlayer">
                <iframe
                src="https://www.youtube.com/watch?v=J2UK7kRNRmY"
                allowfullscreen
                allowtransparency
                allow="autoplay"
                ></iframe>
            </div>
            </vue-plyr>
        </section>
      <section class="mt-8 mx-auto max-w-screen-xl p-4 grid md:grid-cols-3 gap-6 text-white">
        <div class="video-page-description col-span-2">
          <h1 class="text-3xl font-extrabold sm:text-5xl text-white">West Burton <br>October 2023</h1>
  
          <p class="mt-4 max-w-lg text-center sm:text-xl sm:text-left text-white">
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nesciunt illo tenetur fuga ducimus numquam ea!
          </p>
        </div>
        <div class="video-page-description">
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

//   onMounted(() => {
//     this.$refs.plyr.player.on('ready', () => console.log('LETS GOOO'));
//     this.$refs.plyr.player.on('play', () => console.log('PLAYIN!'));
//   });

  </script>
  
  