<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

defineProps<{
    title?: string;
    description?: string;
    backgroundImage?: string;
    sideImage?: string;
}>();
</script>

<template>
    <div
    class="min-h-screen w-full bg-cover bg-center bg-no-repeat flex items-center justify-center p-6"
    :style="{ backgroundImage: `url(${backgroundImage})` }"
  >
    <div
      class=" w-full max-w-3xl bg-white/60 backdrop-blur-md rounded-xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2"
    >
 
      <div class="hidden md:block">
        <img
          :src="sideImage"
          class="h-full w-full object-cover"
        />
      </div>

       
      <div class="p-6 flex flex-col justify-center">
        <img 
         src="/images/attendance-logo.png"
         class="h-20 w-75 pb-6 ps-10"
        >
        <h1 class="text-lg font-bold mb-2">{{ title }}</h1>
        <p class="text-sm text-gray-600 mb-8">{{ description }}</p>

        <slot />
      </div>
    </div>
  </div>
</template>
