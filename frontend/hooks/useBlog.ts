import { useState, useCallback } from 'react'
import { blogService } from '@/lib/api/blog'
import { BlogPost, BlogCategory } from '@/types'

export const useBlogs = () => {
  const [blogs, setBlogs] = useState<BlogPost[]>([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const fetchBlogs = useCallback(async () => {
    try {
      setLoading(true)
      const response = await blogService.getAll()
      setBlogs(response)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil berita')
      console.error(err)
    } finally {
      setLoading(false)
    }
  }, [])

  return {
    blogs,
    loading,
    error,
    fetchBlogs
  }
}

export const useBlog = () => {
  const [blog, setBlog] = useState<BlogPost | null>(null)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const fetchBlog = useCallback(async (id: number) => {
    try {
      setLoading(true)
      const response = await blogService.getById(id)
      setBlog(response)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil detail berita')
      console.error(err)
    } finally {
      setLoading(false)
    }
  }, [])

  return {
    blog,
    loading,
    error,
    fetchBlog
  }
}
