import { apiClient } from './client'

export interface Setting {
  name: string
  description: string
  photo: string
  email: string
  phone: string
  address: string
}

export const settingService = {
  async getProfile(): Promise<Setting> {
    const response = await apiClient.get<Setting>('/settings/profile')
    return response.data
  },
}
