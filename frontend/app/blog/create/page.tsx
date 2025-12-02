'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { blogService } from '@/lib/api/blog'

export default function CreateBlogPage() {
  const router = useRouter()
  const { user, loading, isAuthenticated } = useAuth()
  const [formData, setFormData] = useState({
    title: '',
    content: '',
    excerpt: '',
    status: false,
    published_at: '',
  })
  const [featuredImage, setFeaturedImage] = useState<File | null>(null)
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [submitting, setSubmitting] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setSubmitting(true)

    try {
      const formDataToSend = new FormData()
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

      await blogService.create(formDataToSend)
      router.push('/blog')
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Gagal membuat blog' })
      }
    } finally {
      setSubmitting(false)
    }
  }

  if (loading) {
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

  if (!isAuthenticated || (user?.level !== 'administrator' && user?.level !== 'root')) {
    return (
      <DashboardLayout>
        <div className="text-center py-12">
          <p className="text-gray-600">Anda tidak memiliki akses ke halaman ini</p>
        </div>
      </DashboardLayout>
    )
  }

  return (
    <DashboardLayout>
      <div className="mb-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Tambah Blog Baru</h1>
            <p className="mt-1 text-sm text-gray-600">Buat blog post baru</p>
          </div>
          <Link href="/blog" className="btn btn-secondary">
            Kembali
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
              <p className="mt-1 text-xs text-gray-500">
                Centang untuk publish, kosongkan untuk draft
              </p>
            </div>

            <div>
              <label className="label">Tanggal Publish</label>
              <input
                type="datetime-local"
                className="input"
                value={formData.published_at}
                onChange={(e) => setFormData({ ...formData, published_at: e.target.value })}
              />
              <p className="mt-1 text-xs text-gray-500">
                Kosongkan jika ingin publish sekarang
              </p>
            </div>
          </div>

          <div>
            <label className="label">Ringkasan (Excerpt)</label>
            <textarea
              className="input"
              rows={3}
              value={formData.excerpt}
              onChange={(e) => setFormData({ ...formData, excerpt: e.target.value })}
              placeholder="Ringkasan singkat tentang blog ini"
            />
          </div>

          <div>
            <label className="label">Konten *</label>
            <textarea
              className="input"
              rows={10}
              value={formData.content}
              onChange={(e) => setFormData({ ...formData, content: e.target.value })}
              required
              placeholder="Tulis konten blog di sini..."
            />
            {errors.content && <p className="mt-1 text-sm text-red-600">{errors.content}</p>}
            <p className="mt-1 text-xs text-gray-500">
              Catatan: Rich text editor akan ditambahkan kemudian
            </p>
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
            {errors.featured_image && (
              <p className="mt-1 text-sm text-red-600">{errors.featured_image}</p>
            )}
          </div>

          <div className="flex justify-end space-x-4">
            <Link href="/blog" className="btn btn-secondary">
              Batal
            </Link>
            <button type="submit" className="btn btn-primary" disabled={submitting}>
              {submitting ? 'Menyimpan...' : 'Simpan Blog'}
            </button>
          </div>
        </div>
      </form>
    </DashboardLayout>
  )
}

