'use client'

import { useState, useEffect } from 'react'
import Image from 'next/image'
import Link from 'next/link'
import { BlogPost } from '@/types'

interface NewsCardProps {
  post: BlogPost
  formatDate: (dateString: string) => string
}

function NewsCard({ post, formatDate }: NewsCardProps) {
  const [imageLoaded, setImageLoaded] = useState(false)
  const imageUrl = post.featured_image
    ? `${(process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api').replace('/api', '')}/storage/blog_images/${post.featured_image}`
    : null

  return (
    <div className="group">
      <div className="relative h-64 rounded-xl overflow-hidden mb-6">
        {imageUrl ? (
          <>
            {!imageLoaded && (
              <div className="absolute inset-0 flex items-center justify-center bg-gray-200">
                <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-400"></div>
              </div>
            )}
            <Image
              src={imageUrl}
              alt={post.title}
              fill
              className={`object-cover transform group-hover:scale-110 transition-transform duration-500 transition-opacity duration-300 ${
                imageLoaded ? 'opacity-100' : 'opacity-0'
              }`}
              sizes="(max-width: 768px) 100vw, 33vw"
              onLoad={() => setImageLoaded(true)}
              onError={() => setImageLoaded(true)}
            />
          </>
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
        {post.excerpt || post.content.replace(/<[^>]*>/g, '').substring(0, 25) + '...'}
      </p>
      <Link 
        href={`/berita-kegiatan/${post.id}`}
        className="inline-block bg-[rgb(246,90,141)] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-accent-600 transition-colors"
      >
        Lanjutkan membaca
      </Link>
    </div>
  )
}

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
        ? data.filter((post: any) => post.category?.name?.toLowerCase().includes('realisasi') || true).slice(0, 4)
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
    <section className="py-16 bg-gradient-to-b from-white via-brand-50/40 to-accent-50/40" id="news">
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
            className="hidden md:inline-block bg-[rgb(246,90,141)] text-white px-6 py-2 rounded-lg font-medium hover:bg-accent-600 transition-colors"
          >
            Lihat Semua
          </Link>
        </div>

        {loading ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {[1, 2, 3, 4].map((i) => (
              <div key={i}>
                <div className="h-64 bg-gray-300 rounded-xl mb-6 animate-pulse"></div>
                <div className="space-y-4">
                  <div className="h-6 bg-gray-300 rounded animate-pulse"></div>
                  <div className="h-4 bg-gray-300 rounded w-1/2 animate-pulse"></div>
                  <div className="space-y-2">
                    <div className="h-4 bg-gray-300 rounded animate-pulse"></div>
                    <div className="h-4 bg-gray-300 rounded w-5/6 animate-pulse"></div>
                    <div className="h-4 bg-gray-300 rounded w-4/6 animate-pulse"></div>
                  </div>
                  <div className="h-10 bg-gray-300 rounded w-32 animate-pulse"></div>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {posts.map((post) => (
              <NewsCard key={post.id} post={post} formatDate={formatDate} />
            ))}
          </div>
        )}

        <div className="mt-8 text-center md:hidden">
          <Link 
            href="/blog" 
            className="inline-block bg-accent-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-accent-600 transition-colors"
          >
            Lihat Semua
          </Link>
        </div>
      </div>
    </section>
  )
}
