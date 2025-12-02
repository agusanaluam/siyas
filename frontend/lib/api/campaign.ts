import { apiClient } from './client'
import { Campaign, CampaignCategory } from '@/types'

export const campaignService = {
  async getAll(status?: number): Promise<Campaign[]> {
    const params = status ? { status } : {}
    const response = await apiClient.get<Campaign[]>('/campaigns', { params })
    return response.data
  },

  async getPending(): Promise<Campaign[]> {
    const response = await apiClient.get<Campaign[]>('/campaigns/pending')
    return response.data
  },

  async getRunning(): Promise<Campaign[]> {
    const response = await apiClient.get<Campaign[]>('/campaigns/running')
    return response.data
  },

  async getClosed(): Promise<Campaign[]> {
    const response = await apiClient.get<Campaign[]>('/campaigns/closed')
    return response.data
  },

  async getById(id: number): Promise<Campaign> {
    const response = await apiClient.get<Campaign>(`/campaigns/${id}`)
    return response.data
  },

  async create(data: FormData): Promise<Campaign> {
    const response = await apiClient.post<Campaign>('/campaigns', data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data
  },

  async update(id: number, data: FormData): Promise<Campaign> {
    const response = await apiClient.post<Campaign>(`/campaigns/${id}`, data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data
  },

  async delete(id: number): Promise<void> {
    await apiClient.delete(`/campaigns/${id}`)
  },
}

export const campaignCategoryService = {
  async getAll(): Promise<CampaignCategory[]> {
    const response = await apiClient.get<CampaignCategory[]>('/campaign-categories')
    return response.data
  },

  async getById(id: number): Promise<CampaignCategory> {
    const response = await apiClient.get<CampaignCategory>(`/campaign-categories/${id}`)
    return response.data
  },

  async create(data: { name: string; pic: string }): Promise<CampaignCategory> {
    const response = await apiClient.post<CampaignCategory>('/campaign-categories', data)
    return response.data
  },

  async update(id: number, data: { name: string; pic: string }): Promise<CampaignCategory> {
    const response = await apiClient.put<CampaignCategory>(`/campaign-categories/${id}`, data)
    return response.data
  },

  async delete(id: number): Promise<void> {
    await apiClient.delete(`/campaign-categories/${id}`)
  },
}

