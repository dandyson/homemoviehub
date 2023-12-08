<template>
  <div v-if="coverImage || selectedImage" class="w-80 h-60 overflow-hidden rounded-md mt-1">
      <img v-if="selectedImage" :src="selectedImage" alt="Selected Cover Image" class="w-full h-full object-cover rounded-md" />
      <img v-else-if="coverImage !== ''" :src="coverImage" alt="Cover Image" class="w-full h-full object-cover rounded-md" />
  </div>
  <div v-else class="w-80 h-60 overflow-hidden rounded-md mt-1">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/Placeholder_view_vector.svg/681px-Placeholder_view_vector.svg.png" alt="Cover Image" class="w-full h-full object-cover rounded-md w-80 h-60 overflow-hidden rounded-md mt-1" />
  </div>
  <div class="mt-1">
    <input
      type="file"
      @change="onFileChange"
      :class="{ 'is-invalid': invalidFile }"
    />
    <p class="text-red-600 border-2 border-red-900 animated fadeIn mt-4" v-if="invalidFile">
      {{ invalidFileMessage }}
    </p>
  </div>
</template>

<script setup>
import { ref, defineEmits, defineProps } from 'vue';

const selectedImage = ref('');
const invalidFile = ref(false);
const invalidFileMessage = ref('');
const { coverImage } = defineProps(['coverImage']);
const emit = defineEmits(['imageChanged', 'invalidFile']);

const onFileChange = (e) => {
  const file = e.target.files[0];

  if (file) {
    // Validation
    const allowedFormats = ['image/jpeg', 'image/jpg', 'image/png'];
    const maxSizeKB = 10000;

    if (!allowedFormats.includes(file.type)) {
      invalidFile.value = true;
      invalidFileMessage.value = 'Invalid file format. Please select a .jpeg, .jpg, or .png file.';
    } else if (file.size > maxSizeKB * 1024) {
      invalidFile.value = true;
      invalidFileMessage.value = 'File size exceeds the maximum allowed size.';
    } else {
      invalidFile.value = false;
      invalidFileMessage.value = '';
    }

    // Generate preview
    const reader = new FileReader();
    reader.onload = (e) => {
      selectedImage.value = e.target.result;
      emit('imageChanged', file);
    };

    reader.readAsDataURL(file);

    console.log(invalidFile.value);

    if (invalidFile.value) {
      emit('invalidFile', true);
    } else {
      emit('invalidFile', false);
    }
  }
};
</script>
