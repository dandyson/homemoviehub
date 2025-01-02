<template>
  <input type="file" @change="handleChange" ref="fileInput" />
</template>

<script setup>
import { ref, defineProps, defineEmits, defineExpose } from 'vue';

const fileInput = ref(null);

const handleChange = () => {
  const file = fileInput.value.files[0];
  emit('update:modelValue', file);
};

defineExpose({ fileInput, handleChange });
const { modelValue } = defineProps(['modelValue']);
const emit = defineEmits();
</script>
