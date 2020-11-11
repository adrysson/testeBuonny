import Vue from 'vue'
import axios from 'axios'

axios.defaults.headers.common.Accept = 'application/json'
axios.defaults.baseURL = 'http://localhost:8765/v1'

Vue.prototype.$axios = axios
