import { apiClient } from './client'

export interface Volunteer {
  id: number
  name: string
  email: string
  nik?: string
  phone_number?: string
  sex?: string
  birth_date?: string
  address?: string
  address_code?: string
  profile_picture?: string
  status: number
  group_id?: number
  group?: {
    id: number
    name: string
  }
  user?: {
    id: number
    email_verified_at: string | null
  }
}

export const volunteerService = {
  async getAll(status?: string): Promise<Volunteer[]> {
    const params = status ? { status } : {}
    const response = await apiClient.get<Volunteer[]>('/volunteers', { params })
    return response.data
  },

  async getInactive(): Promise<Volunteer[]> {
    const response = await apiClient.get<Volunteer[]>('/volunteers/inactive')
    return response.data
  },

  async getById(id: number): Promise<Volunteer> {
    const response = await apiClient.get<Volunteer>(`/volunteers/${id}`)
    return response.data
  },

  async create(data: {
    name: string
    email: string
    password: string
    password_confirmation: string
    group_id: number
  }): Promise<Volunteer> {
    const response = await apiClient.post<Volunteer>('/volunteers', data)
    return response.data
  },

  async update(id: number, data: FormData): Promise<Volunteer> {
    const response = await apiClient.post<Volunteer>(`/volunteers/${id}`, data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data
  },

  async delete(id: number): Promise<void> {
    await apiClient.delete(`/volunteers/${id}`)
  },
}

