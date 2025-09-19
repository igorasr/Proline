<script setup lang="ts">
import HeaderComponent from '../Components/HeaderComponent.vue';

</script>

<template>
  <div id="default-layout" class="flex flex-col gap-8 mx-auto w-2xl py-8 max-lg:w-full max-sm:pt-4">
    <HeaderComponent />
    <main>
      <slot />
    </main>
  </div>
</template>

<style lang="scss" scoped>

</style>