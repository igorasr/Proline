<template>
  <BaseLayout>
    <div class="relative max-w-2xl mx-auto p-6">
        <!-- Cabeçalho fixo -->
        <div
          class="sticky top-0 z-10 bg-white/80 backdrop-blur-md
                text-2xl font-bold text-gray-800 px-4 py-3 border-b border-gray-200"
        >
          Status de Envios
        </div>

        <div
          class="mt-4 max-h-[calc(100vh-8rem)] overflow-y-auto
                divide-y divide-gray-200 rounded-lg border border-gray-100"
        >
          <div
            v-for="item in envios"
            :key="item.id"
            class="px-4"
          >
            <div
              class="flex items-center justify-between py-4
                    hover:bg-gray-50 transition-colors"
            >
              <div class="flex flex-col">
                <span class="text-sm text-gray-500">Upload</span>
                <div class="flex items-center gap-2">
                  <strong class="text-lg text-gray-800">#{{ item.id }}</strong>
                  <StatusComponent :status="item.status" />
                </div>
              </div>

              <div class="flex items-center gap-2">
                <button
                  class="cursor-pointer"
                  @click="toggleSelected(item.id)"
              >
                <Library class="size-4 text-sm text-gray-500 "/>
              </button>
              <button
                class="cursor-pointer"
                @click="reprocessar(item.id)"
              >
                <RefreshCcw class="size-4 text-sm text-gray-500"/>
              </button>
              </div>
            </div>

            <transition name="accordion">
              <div
                v-if="selectedId === item.id"
                class="bg-gray-50 rounded-md p-4 mb-4 text-gray-700"
              >
                <p><strong>Status:</strong> {{ item.status }}</p>
                <p><strong>Itens Processados:</strong> {{ item.processed_items }}</p>
                <p><strong>Data</strong> {{ new Date(item.created_at).toLocaleString() }}</p>
              </div>
            </transition>
          </div>
        </div>
      </div>
  </BaseLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import HttpClient from '../Services/HttpClient'
import BaseLayout from '../Layouts/BaseLayout.vue'
import StatusComponent from '../Components/StatusComponent.vue'
import { Library, RefreshCcw } from 'lucide-vue-next'

const envios = ref([])
const selectedId = ref(null)
const detalhe = ref(null)
let intervalId

async function fetchUploads() {
  const { data } = await HttpClient.get('/upload')
  envios.value = data
}

function toggleSelected(id) {
  selectedId.value = selectedId.value === id ? null : id
}

async function reprocessar(id) {
  await HttpClient.post(`/upload/${id}/reprocess`)
  fetchUploads()
}

onMounted(() => {
  fetchUploads()
  intervalId = setInterval(fetchUploads, 5000)
})
onUnmounted(() => clearInterval(intervalId))
</script>

<style scoped>
/* animação simples de acordeão */
.accordion-enter-active,
.accordion-leave-active {
  transition: max-height 0.25s;
}
.accordion-enter-from,
.accordion-leave-to {
  max-height: 0;
  opacity: 0;
}
.accordion-enter-to,
.accordion-leave-from {
  max-height: 500px; /* valor suficiente para o conteúdo */
  opacity: 1;
}
</style>
