'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import { BlogPost } from '@/types'

export default function NewsSection() {
  const [posts, setPosts] = useState<BlogPost[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetchPosts()
  }, [])

  const fetchPosts = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/blogs`)
      const result = await response.json()
      const data = result.data || result
      // Filter for 'realisasi' category if possible, or just take latest 3
      // Assuming backend returns all or paginated
      const filtered = Array.isArray(data) 
        ? data.filter((post: any) => post.category?.toLowerCase().includes('realisasi') || true).slice(0, 3)
        : []
      setPosts(filtered)
    } catch (error) {
      console.error('Error fetching posts:', error)
    } finally {
      setLoading(false)
    }
  }

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    })
  }

  return (
    <section className="py-16 bg-white" id="news">
      <div className="container mx-auto px-4">
        <div className="flex justify-between items-end mb-12">
          <div>
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
              Berita dan Kegiatan
            </h2>
            <p className="text-gray-600">
              Aktivitas kami dalam menebar kebaikan
            </p>
          </div>
          <Link 
            href="/blog" 
            className="hidden md:inline-block bg-brand-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-brand-700 transition-colors"
          >
            Lihat Semua
          </Link>
        </div>

        {loading ? (
          <div className="flex justify-center py-12">
            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-600"></div>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {posts.map((post) => (
              <div key={post.id} className="group">
                <div className="relative h-64 rounded-xl overflow-hidden mb-6">
                  {post.featured_image ? (
                    <img
                      src={`${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/blog_images/${post.featured_image}`}
                      alt={post.title}
                      className="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                    />
                  ) : (
                    <div className="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                      <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  )}
                </div>
                
                <h3 className="text-xl font-bold text-gray-900 mb-2 group-hover:text-brand-600 transition-colors line-clamp-2">
                  {post.title}
                </h3>
                <p className="text-sm text-gray-500 mb-3">
                  {formatDate(post.created_at)}
                </p>
                <p className="text-gray-600 mb-4 line-clamp-3 text-sm">
                  {post.excerpt || post.content.replace(/<[^>]*>/g, '').substring(0, 100) + '...'}
                </p>
                <Link 
                  href={`/blog/${post.id}`}
                  className="inline-block bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-700 transition-colors"
                >
                  Lanjutkan membaca
                </Link>
              </div>
            ))}
          </div>
        )}

        <div className="mt-8 text-center md:hidden">
          <Link 
            href="/blog" 
            className="inline-block bg-brand-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-brand-700 transition-colors"
          >
            Lihat Semua
          </Link>
        </div>
      </div>
    </section>
  )
}
