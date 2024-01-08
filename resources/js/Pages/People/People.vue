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
                                <tbody class="bg-gray-800">
                                    <tr v-for="(person, index) in people" :key="index" class="bg-black bg-opacity-20">
                                        <td class="flex px-6 py-4 whitespace-nowrap">
                                            <img class="w-5"
                                                src="https://ssl.gstatic.com/onebox/media/sports/logos/udQ6ns69PctCv143h-GeYw_48x48.png"
                                                alt="">
                                            <span class="ml-2 font-medium">{{ person.name }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ person.family }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            5
                                        </td>
                                        <td class="flex justify-center px-6 py-4 whitespace-nowrap">
                                            <Link :href="route('people.show', { person: person })" @click="closeModal" :class="buttonClasses"> <font-awesome-icon icon="fa-solid fa-eye" class="me-2" /> View </Link>
                                            <Link @click="closeModal" :class="buttonClasses"> <font-awesome-icon icon="fa-solid fa-pencil" class="me-2" /> Edit </Link>
                                            <DangerButton @click="closeModal" class="me-2"> <font-awesome-icon icon="fa-solid fa-trash" class="me-2" /> Delete </DangerButton>
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
</AuthenticatedLayout></template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    people: {
        type: Object,
        default: () => ({}),
    }
});

const buttonClasses = ref('me-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150');

</script>
  
  