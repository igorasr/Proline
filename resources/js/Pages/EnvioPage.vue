<template>
  <BaseLayout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-xl rounded-2xl">
      <h1 class="text-2xl font-bold mb-6 text-gray-800">Upload de arquivo</h1>

      <input
        type="file"
        accept="application/json"
        @change="onFile"
        class="mb-4 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
              file:rounded-md file:border-0 file:text-sm file:font-semibold
              file:bg-blue-600 file:text-white hover:file:bg-blue-700"
      />

      <button
        :disabled="!file"
        @click="sendFile"
        class="w-full py-2 px-4 rounded-md text-white font-semibold
              bg-green-600 hover:bg-green-700 disabled:bg-gray-400"
      >
        Enviar
      </button>

      <p v-if="upload" class="mt-4 text-green-700 font-medium">
        Enviado com sucesso: #{{ upload }}
      </p>
  </div>
  </BaseLayout>
</template>

<script setup>
import { ref } from 'vue'
import BaseLayout from '../Layouts/BaseLayout.vue'
import HttpClient from '../Services/HttpClient'

const file = ref(null)
const upload = ref(null)

function onFile(e) {
  file.value = e.target.files[0]
}

async function sendFile() {
  const form = new FormData()
  form.append('file', file.value)
  const { data } = await HttpClient.post('/upload', form, {
    headers: { 'Content-Type': 'multipart/form-data' }
  })
  upload.value = data.id
}
</script>
