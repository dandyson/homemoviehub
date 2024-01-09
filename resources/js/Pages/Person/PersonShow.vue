<template>
    <Head title="View" />
    <AuthenticatedLayout>
        <div>
            <h2
                class="mt-16 font-semibold text-4xl md:text-6xl text-gray-800 dark:text-gray-200 leading-tight font-bebas tracking-wider text-center">
                <span>{{ person.name }}</span>
            </h2>
        </div>

        <div class="flex flex-col items-center w-screen min-h-screen bg-gray-900 py-10">

            <!-- Component Start -->
            <div class="flex flex-col mt-6 w-11/12 md:w-8/12">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                            <p v-for="(detail, index) in person" class="text-white">{{ detail }}</p>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Component End  -->

    </div>
</AuthenticatedLayout></template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    person: {
        type: Object,
        default: () => ({}),
    },
    message: {
        type: Object,
    },
});

const buttonClasses = ref('me-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150');

onMounted(() => {
  if (props.message) {
    Swal.fire({
      title: props.message?.type,
      text: props.message?.text,
      icon: props.message?.type.toLowerCase(),
      timer: 2000,
      timerProgressBar: true,
      showConfirmButton: false,
    });
  }
});

</script>
  
  