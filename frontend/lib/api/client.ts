import axios, { AxiosInstance, AxiosError } from 'axios'
import Cookies from 'js-cookie'

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'

class ApiClient {
  private client: AxiosInstance

  constructor() {
    this.client = axios.create({
      baseURL: API_URL,
      headers: {
        'Accept': 'application/json',
      },
      withCredentials: true,
    })

    this.setupInterceptors()
  }

  private setupInterceptors() {
    this.client.interceptors.request.use(
      (config) => {
        const token = Cookies.get('auth_token')
        if (token) {
          config.headers.Authorization = `Bearer ${token}`
        }
        if (config.data instanceof FormData) {
          delete config.headers['Content-Type']
        } else {
          config.headers['Content-Type'] = 'application/json'
        }
        return config
      }
    )

    this.client.interceptors.response.use(
      (response) => response,
      (error: AxiosError) => {
        if (error.response?.status === 401) {
          Cookies.remove('auth_token')
          if (typeof window !== 'undefined') {
            window.location.href = '/login'
          }
        }
        return Promise.reject(error)
      }
    )
  }

  get instance(): AxiosInstance {
    return this.client
  }
}

export const apiClient = new ApiClient().instance

