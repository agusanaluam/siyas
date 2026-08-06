import { apiClient } from './client'

export interface Setting {
  name: string
  description: string
  photo: string
  email: string
  phone: string
  phone_number?: string
  address: string
  about_photo?: string
  about_content?: string
}

export const settingService = {
  async getProfile(): Promise<Setting> {
    const response = await apiClient.get<Setting>('/settings/profile')
    return response.data
  },
  
  async getAbout(): Promise<{ about_content: string; about_photo: string; about_service: string[] }> {
    const response = await apiClient.get('/settings/about')
    return response.data
  },
}
