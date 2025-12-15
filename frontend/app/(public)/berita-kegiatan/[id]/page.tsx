'use client'

import { useEffect, useState } from 'react'
import { useParams, useRouter } from 'next/navigation'
import { toast } from 'react-hot-toast'
import LandingHeader from '@/components/landing/LandingHeader'
import LandingFooter from '@/components/landing/LandingFooter'
import Image from 'next/image'
import Link from 'next/link'
import { BlogPost } from '@/types'
import { getImageUrl } from '@/lib/utils'

export default function BeritaDetailPage() {
  const params = useParams()
  const router = useRouter()
  const [blog, setBlog] = useState<BlogPost | null>(null)
  const [relatedPosts, setRelatedPosts] = useState<BlogPost[]>([])
  const [popularPosts, setPopularPosts] = useState<BlogPost[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    if (params.id) {
      fetchBlog()
      fetchRelatedAndPopular()
    }
  }, [params.id])

  const fetchBlog = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/blogs/${params.id}`)
      const data = await response.json()
      setBlog(data)
    } catch (error) {
      console.error('Gagal mengambil berita:', error)
      router.push('/berita-kegiatan')
    } finally {
      setLoading(false)
    }
  }

  const fetchRelatedAndPopular = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/blogs`)
      const result = await response.json()
      const allPosts = Array.isArray(result.data) ? result.data : (Array.isArray(result) ? result : [])
      
      // Related posts: same category (exclude current post)
      const currentPost = allPosts.find((p: BlogPost) => p.id === Number(params.id))
      if (currentPost?.category?.id) {
        const related = allPosts
          .filter((p: BlogPost) => 
            p.id !== Number(params.id) && 
            p.category?.id === currentPost.category.id
          )
          .slice(0, 5)
        setRelatedPosts(related)
      }
      
      // Popular posts: latest posts (exclude current post)
      const popular = allPosts
        .filter((p: BlogPost) => p.id !== Number(params.id))
        .slice(0, 5)
      setPopularPosts(popular)
    } catch (error) {
      console.error('Gagal mengambil berita terkait:', error)
    }
  }

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    })
  }

  const getPostImageUrl = (post: BlogPost) => {
    if (!post.featured_image) return null
    if (post.featured_image.startsWith('http')) return post.featured_image
    return getImageUrl(`/storage/blog_images/${post.featured_image}`)
  }

  const getCategoryName = (post: BlogPost) => {
    return post.category?.name || 'Berita'
  }

  if (loading) {
    return (
      <main className="min-h-screen bg-white">
        <LandingHeader forceScrolledStyle />
        <div className="pt-32 pb-20">
          <div className="container mx-auto px-4 md:px-[120px]">
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
              <div className="lg:col-span-2 space-y-6">
                <div className="h-96 bg-gray-200 rounded-xl animate-pulse" />
                <div className="h-8 bg-gray-200 rounded w-3/4 animate-pulse" />
                <div className="space-y-3">
                  <div className="h-4 bg-gray-200 rounded animate-pulse" />
                  <div className="h-4 bg-gray-200 rounded w-5/6 animate-pulse" />
                </div>
              </div>
              <div className="space-y-6">
                <div className="h-64 bg-gray-200 rounded-xl animate-pulse" />
                <div className="h-64 bg-gray-200 rounded-xl animate-pulse" />
              </div>
            </div>
          </div>
        </div>
        <LandingFooter />
      </main>
    )
  }

  if (!blog) {
    return null
  }

  const imageUrl = getPostImageUrl(blog)

  return (
    <main className="min-h-screen bg-white">
      <LandingHeader forceScrolledStyle />

      <section className="pt-28 pb-20">
        <div className="container mx-auto px-4 md:px-[120px]">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Main Content - Left */}
            <div className="lg:col-span-2">
              {/* Featured Image */}
              {imageUrl && (
                <div className="relative w-full h-[500px] rounded-2xl overflow-hidden mb-8 shadow-lg">
                  <Image
                    src={imageUrl}
                    alt={blog.title}
                    fill
                    className="object-cover"
                    sizes="(max-width: 1024px) 100vw, 66vw"
                    priority
                  />
                  {/* Share Button */}
                  <button 
                    onClick={() => {
                      if (navigator.share) {
                        navigator.share({
                          title: blog.title,
                          text: blog.excerpt || blog.content.replace(/<[^>]*>/g, '').substring(0, 150),
                          url: window.location.href,
                        }).catch(() => {})
                      } else {
                        navigator.clipboard.writeText(window.location.href)
                        toast.success('Link berita berhasil disalin!')
                      }
                    }}
                    className="absolute top-4 right-4 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition-colors"
                    aria-label="Share"
                  >
                    <svg className="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                  </button>
                </div>
              )}

              {/* Title */}
              <h1 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                {blog.title}
              </h1>

              {/* Meta Info */}
              <div className="flex items-center gap-4 mb-8 text-sm text-gray-600">
                <div className="flex items-center gap-2">
                  <div className="w-1 h-4 bg-[rgb(246,90,141)]" />
                  <span className="font-semibold uppercase tracking-wide">
                    {getCategoryName(blog)}
                  </span>
                </div>
                <span>•</span>
                <span>{formatDate(blog.created_at)}</span>
                {blog.creator && (
                  <>
                    <span>•</span>
                    <span>by {blog.creator.name}</span>
                  </>
                )}
              </div>

              {/* Content */}
              <div 
                className="prose prose-lg max-w-none mb-12"
                dangerouslySetInnerHTML={{ __html: blog.content }}
              />

              {/* Author Info */}
              <div className="border-t border-gray-200 pt-6">
                <div className="flex items-center gap-4">
                  {blog.creator && (
                    <>
                      <div className="w-12 h-12 bg-[rgb(246,90,141)] rounded-full flex items-center justify-center text-white font-semibold text-lg">
                        {blog.creator.name.charAt(0).toUpperCase()}
                      </div>
                      <div>
                        <p className="font-semibold text-gray-900">{blog.creator.name}</p>
                        <p className="text-sm text-gray-600">
                          {formatDate(blog.created_at)}
                        </p>
                      </div>
                    </>
                  )}
                </div>
              </div>

              {/* Related Posts */}
              {relatedPosts.length > 0 && (
                <div className="mt-12 border-t border-gray-200 pt-8">
                  <h2 className="text-2xl font-bold text-gray-900 mb-6">Berita Terkait</h2>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {relatedPosts.map((post) => {
                      const postImageUrl = getPostImageUrl(post)
                      return (
                        <Link
                          key={post.id}
                          href={`/berita-kegiatan/${post.id}`}
                          className="group"
                        >
                          <div className="relative w-full h-48 rounded-xl overflow-hidden mb-3">
                            {postImageUrl ? (
                              <Image
                                src={postImageUrl}
                                alt={post.title}
                                fill
                                className="object-cover group-hover:scale-105 transition-transform duration-300"
                                sizes="(max-width: 768px) 100vw, 50vw"
                              />
                            ) : (
                              <div className="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg className="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                              </div>
                            )}
                          </div>
                          <h3 className="text-lg font-bold text-gray-900 mb-2 group-hover:text-[rgb(246,90,141)] transition-colors line-clamp-2">
                            {post.title}
                          </h3>
                          <p className="text-sm text-gray-600 line-clamp-2">
                            {post.excerpt || post.content.replace(/<[^>]*>/g, '').substring(0, 100) + '...'}
                          </p>
                        </Link>
                      )
                    })}
                  </div>
                </div>
              )}
            </div>

            {/* Sidebar - Right */}
            <div className="lg:col-span-1">
              {/* Popular Stories */}
              {popularPosts.length > 0 && (
                <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                  <h2 className="text-xl font-bold text-gray-900 mb-6 uppercase tracking-wide">
                    Popular Stories
                  </h2>
                  <div className="space-y-4">
                    {popularPosts.map((post) => {
                      const postImageUrl = getPostImageUrl(post)
                      return (
                        <Link
                          key={post.id}
                          href={`/berita-kegiatan/${post.id}`}
                          className="flex gap-3 group"
                        >
                          <div className="relative w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden">
                            {postImageUrl ? (
                              <Image
                                src={postImageUrl}
                                alt={post.title}
                                fill
                                className="object-cover group-hover:scale-105 transition-transform duration-300"
                                sizes="80px"
                              />
                            ) : (
                              <div className="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg className="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                              </div>
                            )}
                          </div>
                          <div className="flex-1 min-w-0">
                            <h3 className="text-sm font-semibold text-gray-900 group-hover:text-[rgb(246,90,141)] transition-colors line-clamp-2">
                              {post.title}
                            </h3>
                          </div>
                        </Link>
                      )
                    })}
                  </div>
                </div>
              )}

              {/* Newsletter Subscription (Optional) */}
              <div className="bg-gradient-to-br from-brand-50 to-accent-50/30 rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 className="text-xl font-bold text-gray-900 mb-3 uppercase tracking-wide">
                  Subscribe to our newsletter
                </h2>
                <p className="text-sm text-gray-600 mb-4">
                  Dapatkan update terbaru tentang kegiatan dan program yayasan.
                </p>
                <form className="space-y-3">
                  <input
                    type="email"
                    placeholder="Email"
                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[rgb(246,90,141)] focus:border-transparent outline-none"
                  />
                  <button
                    type="submit"
                    className="w-full bg-[rgb(246,90,141)] text-white py-2 rounded-lg font-medium hover:bg-accent-600 transition-colors"
                  >
                    Subscribe
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>

      <LandingFooter />
    </main>
  )
}

