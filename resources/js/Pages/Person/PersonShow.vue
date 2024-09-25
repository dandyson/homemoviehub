<template>
    <Head title="View" />
    <AuthenticatedLayout>

        <div class="flex flex-col items-center justify-center">
            <h2
                class="mt-14 font-semibold text-4xl md:text-6xl text-gray-800 dark:text-gray-200 leading-tight font-bebas tracking-wider text-center">
                <span>{{ person.name }} {{ person.family }}</span>
            </h2>

            <img :src="person.avatar !== null ? person.avatar : 'https://cdn.pixabay.com/photo/2021/12/17/08/27/silhouette-6875954_1280.png'" class="mt-6 w-48 h-48 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" alt="avatar">
            <div class="flex justify-center mx-6">
                <button type="button" @click="goBack" class="relative inline-flex items-center justify-center p-0.5 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 mt-8">
                    <span class="relative px-3 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                        <font-awesome-icon icon="fa-solid fa-arrow-left" size="xl" class="me-1" /> Back
                    </span>
                </button>
                <Link :href="route('person.edit', { person: person })" class="relative inline-flex items-center justify-center p-0.5 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 mt-8">
                    <span class="relative px-3 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                        <font-awesome-icon icon="fa-solid fa-pencil" size="xl" class="me-1" /> Edit
                    </span>
                </Link>
            </div>
        </div>

        <div class="flex flex-col items-center bg-gray-900 py-6 mt-2 pb-16" :class="{'min-h-screen': featuredVideos.length > 0}">
            <span
                class="mt-14 font-semibold text-3xl md:text-5xl text-gray-800 dark:text-gray-200 leading-tight font-bebas tracking-wider text-center mb-6">
                Related Videos:
            </span>
            <div v-if="featuredVideos.length > 0">
                <CollectionLatest :videos="featuredVideos"></CollectionLatest>
            </div>
            <div class="text-white" v-else>
                No videos found for this person.
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router} from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import CollectionLatest from '@/Components/Collections/CollectionLatest.vue';

const props = defineProps({
    message: {
        type: Object,
        default: () => ({}),
    },
    person: {
        type: Object,
        default: () => ({}),
    },
    videos: {
        type: Object,
        default: () => ({}),
    },
});

onMounted(() => {
  if (Object.values(props.message).length > 0) {
    Swal.fire({
      title: props.message?.type,
      text: props.message?.text,
      icon: props.message?.type?.toLowerCase(),
      timer: 2000,
      timerProgressBar: true,
      showConfirmButton: false,
    });
  }
});

const previousUrl = ref(usePage().props.previous);

const featuredVideos = ref(Object.values(props.videos) || []);
const buttonClasses = ref('me-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150');

const goBack = () => {
    router.visit(previousUrl.value, { preserveState: true });
}
</script>

