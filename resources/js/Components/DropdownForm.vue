<template>
  <div class="flex items-center justify-between mt-2">
    <div class="w-full">
      <div ref="dropdownRef" v-if="families && families.length > 0 && !addFamily" class="flex flex-col sm:flex-row items-start">
        <div class="relative flex-grow inline-block">
          <button @click="toggleDropdown(!isOpen)" type="button" class="flex-grow px-4 py-2 inline-flex justify-center w-full rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
            {{ familySelection ? familySelection.name : "Choose Family" }}
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>

          <div v-if="isOpen" @click="toggleDropdown(false)" class="origin-top-right absolute mt-2 w-full rounded-md shadow-lg bg-white dark:bg-gray-900 ring-1 ring-black dark:ring-gray-700 ring-opacity-5">
            <div class="py-1">
              <a @click="selectFamily(family)" v-for="family in families" :key="family.id" class="cursor-pointer block px-4 py-2 text-sm text-white hover:bg-gray-700">{{ family.name }}</a>
            </div>
          </div>
        </div>
        <button @click="addNewFamily" class="px-4 py-2 ms-0 mt-2 sm:mt-0 sm:ms-2 text-sm text-white border border-gray-700 rounded-md">+ Add New Family</button>
      </div>


      <div v-if="addFamily" class="flex flex-col justify-center sm:flex-row items-start sm:items-center">
        <input
          v-model="familySelection"
          class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"
          @input="updateModelValue"
        />
        <button @click="goBack" class="text-sm text-white border border-gray-700 px-4 py-2 rounded-md mt-2 ms-0 sm:ms-2 sm:mt-0"> Back</button>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  families: {
    type: Object,
    required: false,
  },
  modelValue: {
    type: [String, Object], // Allow both String and Object types
    required: true,
    default: null, // Set a default value (you can adjust this based on your use case)
  },
});

const emit = defineEmits(['update:modelValue']);

const addFamily = ref(false);
const familySelection = ref(props.modelValue ?? '');
const isOpen = ref(false);
const dropdownRef = ref(null);

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

defineExpose({ focus: () => dropdownRef.value?.focus() });

const handleClickOutside = (event) => {
  if (isOpen.value && dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isOpen.value = false;
  }
};

const toggleDropdown = (open) => {
  dropdownRef.value = document.getElementById('dropdown-button');
  isOpen.value = open;
};

const selectFamily = (family) => {
  familySelection.value = family;
  updateModelValue();
  toggleDropdown(false);
};

const addNewFamily = () => {
  addFamily.value = true;
  familySelection.value = null;
  toggleDropdown(false);
};

const goBack = () => {
  addFamily.value = false;
  familySelection.value = props.modelValue ?? '';
  toggleDropdown(true);
};

const updateModelValue = () => {
  emit('update:modelValue', familySelection.value);
};
</script>
