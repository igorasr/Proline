<template>
  <header>
    <nav  class="flex justify-center max-sm:flex-col max-sm:gap-6">
      <RouterLink
          to="/app/envio"
          class="group relative rounded transition-all hover:ring-1 hover:ring-sky-500 hover:dark:ring-sky-600 px-2 py-px"
        >
         <span class="text-sky-500">/</span>Envio
      </RouterLink>
      <RouterLink
          to="/app/status"
          class="group relative rounded transition-all hover:ring-1 hover:ring-sky-500 hover:dark:ring-sky-600 px-2 py-px"
        >
         <span class="text-sky-500">/</span>Status
      </RouterLink>
    </nav>
  </header>
</template>
