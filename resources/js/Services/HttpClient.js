
import axios from 'axios';

const baseURL = 'http://localhost:8080/api';

const axiosInstance = axios.create({
  baseURL,
  headers: {
    'Accept': 'application/json',
  },
});

async function request(method, url, data = null, config = {}) {
  try {
  const isFormData = data instanceof FormData

  const response = await axiosInstance({
    method,
    url,
    data,
    ...config,
    headers: {
      ...(isFormData ? {} : { 'Content-Type': 'application/json' }),
      ...config.headers,
    }
  })

  return {
    success: true,
    data: response.data.data || response.data,
    error: null
  }
} catch (err) {
    let message = 'Erro inesperado.'
    if (err.response?.data?.message) {
      message = err.response.data.message
    } else if (err.response?.data?.error) {
      message = err.response?.data?.error
    } else if (err.message) {
      message = err.message
    }

    return {
      success: false,
      data: null,
      error: {
        status: err.response?.status || null,
        message
      }
    }
  }
}

const HttpClient = {
  get: (url, params = {}) =>
    request('get', url, null, { params }),

  post: (url, data = {}, config = {}) =>
    request('post', url, data, config),

  patch: (url, data = {}, config = {}) =>
    request('patch', url, data, config),

  delete: (url, config = {}) =>
    request('delete', url, null, config)
}

export default HttpClient;