import { apiClient } from './client'
import Cookies from 'js-cookie'

export interface LoginCredentials {
  email: string
  password: string
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
  phone_number?: string
  group_id?: number
}

export interface AuthResponse {
  user: {
    id: number
    name: string
    email: string
    level: string
    email_verified_at: string | null
  }
  token: string
}

export interface ApiError {
  message: string
  errors?: Record<string, string[]>
}

export const authService = {
  async login(credentials: LoginCredentials): Promise<AuthResponse> {
    const response = await apiClient.post<AuthResponse>('/auth/login', credentials)
    if (response.data.token) {
      Cookies.set('auth_token', response.data.token, { expires: 7 })
    }
    return response.data
  },

  async register(data: RegisterData): Promise<AuthResponse> {
    const response = await apiClient.post<AuthResponse>('/auth/register', data)
    if (response.data.token) {
      Cookies.set('auth_token', response.data.token, { expires: 7 })
    }
    return response.data
  },

  async logout(): Promise<void> {
    try {
      await apiClient.post('/auth/logout')
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      Cookies.remove('auth_token')
      if (typeof window !== 'undefined') {
        window.location.href = '/login'
      }
    }
  },

  async getCurrentUser(): Promise<AuthResponse['user'] | null> {
    try {
      const response = await apiClient.get<{ user: AuthResponse['user'] }>('/auth/user')
      return response.data.user
    } catch (error) {
      return null
    }
  },

  async forgotPassword(email: string): Promise<{ message: string }> {
    const response = await apiClient.post<{ message: string }>('/auth/forgot-password', { email })
    return response.data
  },

  async resetPassword(data: {
    email: string
    token: string
    password: string
    password_confirmation: string
  }): Promise<{ message: string }> {
    const response = await apiClient.post<{ message: string }>('/auth/reset-password', data)
    return response.data
  },

  async verifyEmail(email: string, token: string): Promise<{ message: string }> {
    const response = await apiClient.get<{ message: string }>('/auth/verify-email', {
      params: { email, token },
    })
    return response.data
  },

  async resendVerification(email: string): Promise<{ message: string }> {
    const response = await apiClient.post<{ message: string }>('/auth/resend-verification', { email })
    return response.data
  },

  isAuthenticated(): boolean {
    return !!Cookies.get('auth_token')
  },
}

