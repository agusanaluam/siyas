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
    // Use POST with _method override for FormData compatibility
    const response = await apiClient.post<BlogPost>(`/blogs/${id}`, data, {
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

  async generate(data: {
    topic: string
    additional_instructions?: string
    auto_publish?: boolean
    category_id?: number
  }): Promise<{ data: BlogPost; message: string }> {
    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
    const apiKey = process.env.NEXT_PUBLIC_BLOG_GENERATE_API_KEY || ''
    const response = await fetch(`${apiUrl}/blogs/generate`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-API-Key': apiKey,
      },
      body: JSON.stringify(data),
    })
    if (!response.ok) {
      const error = await response.json().catch(() => ({}))
      throw { response: { data: error } }
    }
    return response.json()
  },
}

