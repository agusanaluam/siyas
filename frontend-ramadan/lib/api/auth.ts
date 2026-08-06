import { apiClient } from './client'
import { User, AuthResponse } from '@/types'
import Cookies from 'js-cookie'

export const authService = {
  async login(data: { email: string; password: string }): Promise<AuthResponse> {
    const response = await apiClient.post<AuthResponse>('/auth/login', data)
    const result = response.data
    if (result.token) {
      Cookies.set('auth_token', result.token, { expires: 7 })
    }
    return result
  },

  async register(data: {
    name: string
    email: string
    password: string
    password_confirmation: string
    role: string
  }): Promise<AuthResponse> {
    const response = await apiClient.post<AuthResponse>('/auth/register', data)
    const result = response.data
    if (result.token) {
      Cookies.set('auth_token', result.token, { expires: 7 })
    }
    return result
  },

  async logout(): Promise<void> {
    try {
      await apiClient.post('/auth/logout')
    } finally {
      Cookies.remove('auth_token')
    }
  },

  async getCurrentUser(): Promise<User | null> {
    try {
      const response = await apiClient.get<User>('/auth/user')
      return response.data
    } catch {
      return null
    }
  },

  isAuthenticated(): boolean {
    return !!Cookies.get('auth_token')
  },
}
