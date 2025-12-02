'use client'

import { useEffect, useState } from 'react'
import { useRouter, useParams } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { blogService } from '@/lib/api/blog'
import { BlogPost } from '@/types'

export default function BlogDetailsPage() {
  const router = useRouter()
  const params = useParams()
  const { user, loading, isAuthenticated } = useAuth()
  const [blog, setBlog] = useState<BlogPost | null>(null)
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
    } catch (error) {
      console.error('Error fetching blog:', error)
      router.push('/blog')
    } finally {
      setDataLoading(false)
    }
  }

  const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    })
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

  if (!isAuthenticated || !blog) {
    return null
  }

  return (
    <DashboardLayout>
      <div className="mb-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Detail Blog</h1>
            <p className="mt-1 text-sm text-gray-600">Detail lengkap blog post</p>
          </div>
          <div className="flex space-x-2">
            {(user?.level === 'administrator' || user?.level === 'root') && (
              <Link href={`/blog/${blog.id}/edit`} className="btn btn-primary">
                Edit Blog
              </Link>
            )}
            <Link href="/blog" className="btn btn-secondary">
              Kembali ke List
            </Link>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2">
          <div className="bg-white rounded-lg shadow p-6">
            <h1 className="text-3xl font-bold text-gray-900 mb-4">{blog.title}</h1>
            
            <div className="flex items-center space-x-4 mb-6 text-sm text-gray-500">
              <span>
                {blog.published_at ? formatDate(blog.published_at) : 'Belum dipublish'}
              </span>
              <span
                className={`px-2 py-1 rounded-full text-xs font-medium ${
                  blog.status
                    ? 'bg-green-100 text-green-800'
                    : 'bg-yellow-100 text-yellow-800'
                }`}
              >
                {blog.status ? 'Published' : 'Draft'}
              </span>
            </div>

            {blog.excerpt && (
              <div className="mb-6 p-4 bg-gray-50 rounded-lg">
                <p className="text-gray-700 italic">{blog.excerpt}</p>
              </div>
            )}

            <div className="prose max-w-none">
              <div className="whitespace-pre-wrap text-gray-700">{blog.content}</div>
            </div>
          </div>
        </div>

        <div>
          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Informasi</h2>
            <dl className="space-y-4">
              <div>
                <dt className="text-sm font-medium text-gray-500">Status</dt>
                <dd className="mt-1">
                  <span
                    className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                      blog.status
                        ? 'bg-green-100 text-green-800'
                        : 'bg-yellow-100 text-yellow-800'
                    }`}
                  >
                    {blog.status ? 'Published' : 'Draft'}
                  </span>
                </dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Published At</dt>
                <dd className="mt-1 text-sm text-gray-900">
                  {blog.published_at ? formatDate(blog.published_at) : '-'}
                </dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Created At</dt>
                <dd className="mt-1 text-sm text-gray-900">{formatDate(blog.created_at)}</dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Updated At</dt>
                <dd className="mt-1 text-sm text-gray-900">{formatDate(blog.updated_at)}</dd>
              </div>
            </dl>
          </div>

          {blog.featured_image && (
            <div className="mt-6 bg-white rounded-lg shadow p-6">
              <h2 className="text-xl font-semibold text-gray-900 mb-4">Gambar Utama</h2>
              <img
                src={blog.image_url || `http://localhost:8000/storage/blog_images/${blog.featured_image}`}
                alt={blog.title}
                className="w-full rounded-lg"
              />
            </div>
          )}
        </div>
      </div>
    </DashboardLayout>
  )
}

