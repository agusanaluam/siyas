import { apiClient } from './client'
import { Donation } from '@/types'

export const donationService = {
  async getAll(status?: boolean): Promise<Donation[]> {
    const params = status !== undefined ? { status: status ? 1 : 0 } : {}
    const response = await apiClient.get<Donation[]>('/donations', { params })
    return response.data
  },

  async getById(id: number): Promise<Donation> {
    const response = await apiClient.get<Donation>(`/donations/${id}`)
    return response.data
  },

  async getHistory(): Promise<Donation[]> {
    const response = await apiClient.get<Donation[]>('/donations/history')
    return response.data
  },

  async getTransfer(): Promise<Donation[]> {
    const response = await apiClient.get<Donation[]>('/donations/transfer')
    return response.data
  },

  async create(data: FormData): Promise<Donation> {
    const response = await apiClient.post<Donation>('/donations', data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data
  },

  async update(id: number, data: FormData): Promise<Donation> {
    const response = await apiClient.post<Donation>(`/donations/${id}`, data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data
  },

  async approve(id: number): Promise<Donation> {
    const response = await apiClient.patch<Donation>(`/donations/${id}/approve`)
    return response.data
  },

  async delete(id: number): Promise<void> {
    await apiClient.delete(`/donations/${id}`)
  },
}

