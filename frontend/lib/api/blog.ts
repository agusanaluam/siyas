import { apiClient } from './client'
import { BlogPost } from '@/types'

export const blogService = {
  async getAll(): Promise<BlogPost[]> {
    const response = await apiClient.get<BlogPost[]>('/blogs')
    return response.data
  },

  async getById(id: number): Promise<BlogPost> {
    const response = await apiClient.get<BlogPost>(`/blogs/${id}`)
    return response.data
  },

  async create(data: FormData): Promise<BlogPost> {
    const response = await apiClient.post<BlogPost>('/blogs', data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data
  },

  async update(id: number, data: FormData): Promise<BlogPost> {
    const response = await apiClient.put<BlogPost>(`/blogs/${id}`, data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data
  },

  async delete(id: number): Promise<void> {
    await apiClient.delete(`/blogs/${id}`)
  },

  async uploadImage(file: File): Promise<{ url: string }> {
    const formData = new FormData()
    formData.append('image', file)
    const response = await apiClient.post<{ success: boolean; url: string }>(
      '/blogs/upload-image',
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }
    )
    return { url: response.data.url }
  },
}

