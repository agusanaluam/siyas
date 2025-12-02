'use client'

import { useState, useEffect } from 'react'
import { useRouter, useParams } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import RichTextEditor from '@/components/RichTextEditor'
import { useAuth } from '@/hooks/useAuth'
import { blogService } from '@/lib/api/blog'
import { BlogPost } from '@/types'

export default function EditBlogPage() {
  const router = useRouter()
  const params = useParams()
  const { user, loading, isAuthenticated } = useAuth()
  const [blog, setBlog] = useState<BlogPost | null>(null)
  const [formData, setFormData] = useState({
    title: '',
    content: '',
    excerpt: '',
    status: false,
    published_at: '',
  })
  const [featuredImage, setFeaturedImage] = useState<File | null>(null)
  const [existingImage, setExistingImage] = useState<string | null>(null)
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [submitting, setSubmitting] = useState(false)
  const [dataLoading, setDataLoading] = useState(true)

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated && params.id) {
      fetchBlog()
    }
  }, [isAuthenticated, params.id])

  const fetchBlog = async () => {
    try {
      setDataLoading(true)
      const data = await blogService.getById(Number(params.id))
      setBlog(data)

      setFormData({
        title: data.title,
        content: data.content,
        excerpt: data.excerpt || '',
        status: data.status,
        published_at: data.published_at
          ? new Date(data.published_at).toISOString().slice(0, 16)
          : '',
      })

      if (data.featured_image) {
        setExistingImage(data.featured_image)
      }
    } catch (error) {
      console.error('Error fetching blog:', error)
      router.push('/blog')
    } finally {
      setDataLoading(false)
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setSubmitting(true)

    try {
      const formDataToSend = new FormData()
      formDataToSend.append('_method', 'PUT')
      formDataToSend.append('title', formData.title)
      formDataToSend.append('content', formData.content)
      formDataToSend.append('excerpt', formData.excerpt)
      formDataToSend.append('status', formData.status ? '1' : '0')
      if (formData.published_at) {
        formDataToSend.append('published_at', formData.published_at)
      }
      if (featuredImage) {
        formDataToSend.append('featured_image', featuredImage)
      }

      await blogService.update(Number(params.id), formDataToSend)
      router.push('/blog')
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Gagal mengupdate blog' })
      }
    } finally {
      setSubmitting(false)
    }
  }

  if (loading || dataLoading) {
    return (
      <DashboardLayout>
        <div className="flex items-center justify-center h-64">
          <div className="text-center">
            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
            <p className="mt-4 text-gray-600">Memuat...</p>
          </div>
        </div>
      </DashboardLayout>
    )
  }

  if (!isAuthenticated || !blog || (user?.level !== 'administrator' && user?.level !== 'root')) {
    return null
  }

  return (
    <DashboardLayout>
      <div className="mb-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Edit Blog</h1>
            <p className="mt-1 text-sm text-gray-600">Edit blog post</p>
          </div>
          <Link href="/blog" className="btn btn-secondary">
            Kembali ke List
          </Link>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="bg-white rounded-lg shadow p-6">
        {errors.general && (
          <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            {errors.general}
          </div>
        )}

        <div className="space-y-6">
          <div>
            <label className="label">Judul Blog *</label>
            <input
              type="text"
              className="input"
              value={formData.title}
              onChange={(e) => setFormData({ ...formData, title: e.target.value })}
              required
            />
            {errors.title && <p className="mt-1 text-sm text-red-600">{errors.title}</p>}
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label className="label">Status</label>
              <div className="flex items-center space-x-2">
                <input
                  type="checkbox"
                  checked={formData.status}
                  onChange={(e) => setFormData({ ...formData, status: e.target.checked })}
                  className="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                />
                <span className="text-sm text-gray-700">Published</span>
              </div>
            </div>

            <div>
              <label className="label">Tanggal Publish</label>
              <input
                type="datetime-local"
                className="input"
                value={formData.published_at}
                onChange={(e) => setFormData({ ...formData, published_at: e.target.value })}
              />
            </div>
          </div>

          <div>
            <label className="label">Ringkasan (Excerpt)</label>
            <textarea
              className="input"
              rows={3}
              value={formData.excerpt}
              onChange={(e) => setFormData({ ...formData, excerpt: e.target.value })}
            />
          </div>

          <div>
            <label className="label">Konten *</label>
            <RichTextEditor
              value={formData.content}
              onChange={(value) => setFormData({ ...formData, content: value })}
              placeholder="Tulis konten blog..."
            />
            {errors.content && <p className="mt-1 text-sm text-red-600">{errors.content}</p>}
          </div>

          <div>
            <label className="label">Gambar Utama</label>
            <input
              type="file"
              accept="image/*"
              onChange={(e) => setFeaturedImage(e.target.files?.[0] || null)}
              className="input"
            />
            {featuredImage && (
              <div className="mt-2">
                <img
                  src={URL.createObjectURL(featuredImage)}
                  alt="Preview"
                  className="w-48 h-48 object-cover rounded-lg"
                />
              </div>
            )}
            {existingImage && !featuredImage && (
              <div className="mt-2">
                <p className="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                <img
                  src={blog?.image_url || `http://localhost:8000/storage/blog_images/${existingImage}`}
                  alt="Current"
                  className="w-48 h-48 object-cover rounded-lg"
                />
              </div>
            )}
          </div>

          <div className="flex justify-end space-x-4">
            <Link href="/blog" className="btn btn-secondary">
              Batal
            </Link>
            <button type="submit" className="btn btn-primary" disabled={submitting}>
              {submitting ? 'Menyimpan...' : 'Update Blog'}
            </button>
          </div>
        </div>
      </form>
    </DashboardLayout>
  )
}

