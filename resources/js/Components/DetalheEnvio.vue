<template>
  <div class="p-4 bg-gray-50 rounded-xl shadow-inner">
    <div class="flex justify-between items-center mb-3">
      <h2 class="text-xl font-semibold text-gray-700">Envio {{ envioId }}</h2>
      <button
        @click="$emit('close')"
        class="text-red-600 hover:text-red-800 font-medium"
      >
        Fechar
      </button>
    </div>
    <p class="text-gray-700 mb-1">Status: <span class="font-medium">{{ detalhe?.status }}</span></p>
    <p class="text-gray-700">Itens Processados: <span class="font-medium">{{ detalhe?.processed_items }}</span></p>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const props = defineProps({ envioId: String })
const detalhe = ref(null)
let intervalId

async function fetchDetalhe() {
  const { data } = await axios.get(`/api/upload/${props.envioId}`)
  detalhe.value = data
}

onMounted(() => {
  fetchDetalhe()
  intervalId = setInterval(fetchDetalhe, 5000)
})
onUnmounted(() => clearInterval(intervalId))
</script>
