<template>
    <Head title="People" />
    <AuthenticatedLayout>
        <div>
            <h2
                class="mt-16 font-semibold text-4xl md:text-6xl text-gray-800 dark:text-gray-200 leading-tight font-bebas tracking-wider text-center">
                <span>People</span>
            </h2>
        </div>

        <div class="flex flex-col items-center w-screen min-h-screen bg-gray-900 py-10">

            <!-- Component Start -->
            <div class="flex flex-col mt-6 w-11/12 md:w-8/12">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="w-full flex justify-start">
                            <Link :href="route('person.create')" class="relative inline-flex items-center justify-center p-0.5 me-2 mb-4 overflow-hidden text-xs font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                                <span class="relative px-2 py-1.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                    <font-awesome-icon icon="fa-solid fa-file-video" size="xl" class="me-1" /> + Add Person
                                </span>
                            </Link>
                        </div>
                        <div class="shadow overflow-hidden sm:rounded-lg">
                            <table class="min-w-full text-sm text-gray-400">
                                <thead class="bg-gray-800 text-xs uppercase font-medium">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left tracking-wider">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left tracking-wider">
                                            Family
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left tracking-wider">
                                            Videos
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
  
                                <tbody v-if="people && people.length > 0" class="bg-gray-800">
                                    <tr v-for="(person, index) in people" :key="index" class="bg-black bg-opacity-20">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex">
                                                <img class="w-5 rounded-full ring-2 ring-gray-300 dark:ring-gray-500"
                                                    :src="person.avatar !== null ? person.avatar : 'https://cdn.pixabay.com/photo/2021/12/17/08/27/silhouette-6875954_1280.png'"
                                                    alt="">
                                                <span class="ml-3 font-medium">{{ person.name }}</span>
                                            </div>
                                        </td>
                                        <td v-if="person.family" class="px-6 py-4 whitespace-nowrap">
                                            {{ person.family.name }}
                                        </td>
                                        <td v-else class="px-6 py-4 whitespace-nowrap">
                                            -
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ person.videos_count }}
                                        </td>
                                        <td class="flex justify-center px-6 py-4 whitespace-nowrap">
                                            <Link :href="route('person.show', { person: person })" :class="buttonClasses"> <font-awesome-icon icon="fa-solid fa-eye" class="me-2" /> View </Link>
                                            <Link :href="route('person.edit', { person: person })" :class="buttonClasses"> <font-awesome-icon icon="fa-solid fa-pencil" class="me-2" /> Edit </Link>
                                            <DangerButton @click="deletePerson(person)" class="me-2"> <font-awesome-icon icon="fa-solid fa-trash" class="me-2" /> Delete </DangerButton>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else class="bg-gray-800">
                                    <tr class="bg-black bg-opacity-20">
                                        <td colspan="4" class="text-center py-5">
                                            No People have been added
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Component End  -->
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    people: {
        type: Object,
        default: () => ({}),
    }
});

const buttonClasses = ref('me-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150');

const deletePerson = (person) => {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 border-b-4 border-red-700 hover:border-red-500 rounded",
      cancelButton: "me-2 bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-4 border-b-4 border-gray-700 hover:border-gray-500 rounded"
    },
    buttonsStyling: false
  });
  swalWithBootstrapButtons.fire({
    title: "Are you sure?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete person!",
    cancelButtonText: "No, cancel!",
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      axios.delete(`/person/${person.id}`)
        .then(() => {
          swalWithBootstrapButtons.fire({
            title: "Deleted!",
            text: "This person has been removed.",
            icon: "success"
          }).then(() => {
            location.reload();
          });
        })
        .catch((error) => {
          // Handle any errors or show a notification
          console.error('Error deleting person:', error);
        })
    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire({
        title: "Cancelled",
        text: "The person is safe :)",
        icon: "error"
      });
    }
  });
}
</script>
  
  