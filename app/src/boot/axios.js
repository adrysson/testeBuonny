import Vue from 'vue'
import axios from 'axios'
import { Loading, Notify } from 'quasar'

axios.defaults.headers.common.Accept = 'application/json'
axios.defaults.baseURL = 'http://localhost:8765/v1'

// interceptors
axios.interceptors.request.use((config) => {
  Loading.show({
    delay: 400
  })
  return config
})

axios.interceptors.response.use((response) => {
  Loading.hide()
  return response
}, (error) => {
  Loading.hide()
  Notify.create({
    message: error.response.statusText,
    color: 'negative',
    position: 'top'
  })
  return Promise.reject(error)
})

Vue.prototype.$axios = axios
