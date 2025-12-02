import { apiClient as api } from './client'

export interface BlogTag {
  id: number
  name: string
  status: boolean
  created_at: string
  updated_at: string
}

export interface BlogCategory {
  id: number
  name: string
  status: boolean
  created_at: string
  updated_at: string
}

export const blogTagService = {
  async getAll(): Promise<BlogTag[]> {
    const response = await api.get('/blog-tags')
    return response.data
  },

  async getById(id: number): Promise<BlogTag> {
    const response = await api.get(`/blog-tags/${id}`)
    return response.data
  },

  async create(data: { name: string; status?: boolean }): Promise<BlogTag> {
    const response = await api.post('/blog-tags', data)
    return response.data.data
  },

  async update(id: number, data: { name: string; status?: boolean }): Promise<BlogTag> {
    const response = await api.put(`/blog-tags/${id}`, data)
    return response.data.data
  },

  async delete(id: number): Promise<void> {
    await api.delete(`/blog-tags/${id}`)
  },
}

export const blogCategoryService = {
  async getAll(): Promise<BlogCategory[]> {
    const response = await api.get('/blog-categories')
    return response.data
  },

  async getById(id: number): Promise<BlogCategory> {
    const response = await api.get(`/blog-categories/${id}`)
    return response.data
  },

  async create(data: { name: string; status?: boolean }): Promise<BlogCategory> {
    const response = await api.post('/blog-categories', data)
    return response.data.data
  },

  async update(id: number, data: { name: string; status?: boolean }): Promise<BlogCategory> {
    const response = await api.put(`/blog-categories/${id}`, data)
    return response.data.data
  },

  async delete(id: number): Promise<void> {
    await api.delete(`/blog-categories/${id}`)
  },
}
