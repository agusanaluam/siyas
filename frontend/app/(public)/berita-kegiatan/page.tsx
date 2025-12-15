'use client'

import { useEffect } from 'react'
import LandingHeader from '@/components/landing/LandingHeader'
import LandingFooter from '@/components/landing/LandingFooter'
import Image from 'next/image'
import Link from 'next/link'
import { BlogPost } from '@/types'
import { useBlogs } from '@/hooks/useBlog'
import { getImageUrl } from '@/lib/utils'

export default function BeritaKegiatanPage() {
  const { blogs: posts, loading, fetchBlogs } = useBlogs()

  useEffect(() => {
    fetchBlogs()
  }, [fetchBlogs])

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

  // Head news: 1 terbaru + 4 berikutnya
  const headNews = posts.slice(0, 4)
  const mainHeadNews = headNews[0]
  const sideHeadNews = headNews.slice(1, 4)

  // List berita: sisanya
  const listNews = posts.slice(4)

  return (
    <main className="min-h-screen bg-gradient-to-b from-brand-50 via-white to-support-50">
      <LandingHeader forceScrolledStyle />

      <section className="pt-28 pb-12 bg-gradient-to-b from-brand-50 via-white to-accent-50/30">
        <div className="container mx-auto px-4 md:px-[120px]">
          <div className="text-center max-w-3xl mx-auto">
            <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mt-3">Berita & Kegiatan</h1>
            <p className="text-gray-600 mt-4">
              Cerita dan aktivitas terbaru dari program dan kegiatan yayasan.
            </p>
          </div>
        </div>
      </section>

      {/* Head News Section */}
      {!loading && posts.length > 0 && (
        <section className="pb-12">
          <div className="container mx-auto px-4 md:px-[120px]">
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
              {/* Main Head News - Left */}
              {mainHeadNews && (
                <Link
                  href={`/berita-kegiatan/${mainHeadNews.id}`}
                  className="lg:col-span-2 group"
                >
                  <div className="relative h-[500px] rounded-2xl overflow-hidden shadow-lg">
                    {getPostImageUrl(mainHeadNews) ? (
                      <Image
                        src={getPostImageUrl(mainHeadNews)!}
                        alt={mainHeadNews.title}
                        fill
                        className="object-cover group-hover:scale-105 transition-transform duration-500"
                        sizes="(max-width: 1024px) 100vw, 66vw"
                      />
                    ) : (
                      <div className="w-full h-full bg-gray-200 flex items-center justify-center">
                        <svg className="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </div>
                    )}
                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent" />
                    <div className="absolute bottom-0 left-0 right-0 p-6">
                      <div className="flex items-center mb-3">
                        <div className="w-1 h-6 bg-[rgb(246,90,141)] mr-3" />
                        <span className="text-white text-sm font-semibold uppercase tracking-wide">
                          {getCategoryName(mainHeadNews)}
                        </span>
                      </div>
                      <h2 className="text-2xl md:text-3xl font-bold text-white mb-2 line-clamp-2">
                        {mainHeadNews.title}
                      </h2>
                      <p className="text-white/90 text-sm line-clamp-2">
                        {mainHeadNews.excerpt || mainHeadNews.content.replace(/<[^>]*>/g, '').substring(0, 150) + '...'}
                      </p>
                    </div>
                  </div>
                </Link>
              )}

              {/* Side Head News - Right Grid 2x2 */}
              <div className="grid grid-cols-2 lg:grid-cols-1 gap-4 lg:gap-6">
                {sideHeadNews.map((post) => (
                  <Link
                    key={post.id}
                    href={`/berita-kegiatan/${post.id}`}
                    className="group"
                  >
                    <div className="relative h-[240px] lg:h-full rounded-xl overflow-hidden shadow-md">
                      {getPostImageUrl(post) ? (
                        <Image
                          src={getPostImageUrl(post)!}
                          alt={post.title}
                          fill
                          className="object-cover group-hover:scale-105 transition-transform duration-500"
                          sizes="(max-width: 1024px) 50vw, 33vw"
                        />
                      ) : (
                        <div className="w-full h-full bg-gray-200 flex items-center justify-center">
                          <svg className="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                          </svg>
                        </div>
                      )}
                      <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent" />
                      <div className="absolute bottom-0 left-0 right-0 p-4">
                        <div className="flex items-center mb-2">
                          <div className="w-1 h-4 bg-[rgb(246,90,141)] mr-2" />
                          <span className="text-white text-xs font-semibold uppercase tracking-wide">
                            {getCategoryName(post)}
                          </span>
                        </div>
                        <h3 className="text-sm md:text-base font-bold text-white line-clamp-2">
                          {post.title}
                        </h3>
                      </div>
                    </div>
                  </Link>
                ))}
              </div>
            </div>
          </div>
        </section>
      )}

      {/* List News Section */}
      {!loading && listNews.length > 0 && (
        <section className="pb-20">
          <div className="container mx-auto px-4 md:px-[120px]">
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
              {/* Left Column - Explore More (Wider) */}
              <div className="lg:col-span-2">
                <h2 className="text-2xl font-bold text-gray-900 mb-6">Explore more</h2>
                <div className="space-y-8">
                  {listNews.map((post) => {
                    const imageUrl = getPostImageUrl(post)
                    return (
                      <Link
                        key={post.id}
                        href={`/berita-kegiatan/${post.id}`}
                        className="flex flex-col md:flex-row gap-4 group"
                      >
                        <div className="relative w-full md:w-48 h-48 flex-shrink-0 rounded-xl overflow-hidden">
                          {imageUrl ? (
                            <Image
                              src={imageUrl}
                              alt={post.title}
                              fill
                              className="object-cover group-hover:scale-105 transition-transform duration-300"
                              sizes="(max-width: 768px) 100vw, 192px"
                            />
                          ) : (
                            <div className="w-full h-full bg-gray-200 flex items-center justify-center">
                              <svg className="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                              </svg>
                            </div>
                          )}
                        </div>
                        <div className="flex-1">
                          <div className="flex items-center gap-3 mb-2">
                            <span className="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                              #{getCategoryName(post)}
                            </span>
                            <span className="text-xs text-gray-400">
                              {formatDate(post.created_at)}
                            </span>
                          </div>
                          <h3 className="text-xl font-bold text-gray-900 mb-2 group-hover:text-[rgb(246,90,141)] transition-colors line-clamp-2">
                            {post.title}
                          </h3>
                          <p className="text-gray-600 text-sm line-clamp-3 mb-3">
                            {post.excerpt || post.content.replace(/<[^>]*>/g, '').substring(0, 150) + '...'}
                          </p>
                          <div className="flex flex-wrap gap-2">
                            <span className="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                              {getCategoryName(post)}
                            </span>
                          </div>
                        </div>
                      </Link>
                    )
                  })}
                </div>
              </div>

              {/* Right Column - In case you missed it (Narrower) */}
              <div className="lg:col-span-1">
                <h2 className="text-2xl font-bold text-gray-900 mb-6">In case you missed it</h2>
                <div className="space-y-6">
                  {posts.slice(0, 3).map((post) => {
                    const imageUrl = getPostImageUrl(post)
                    return (
                      <Link
                        key={post.id}
                        href={`/berita-kegiatan/${post.id}`}
                        className="group mb-2"
                      >
                        <div className="relative w-full h-40 rounded-xl overflow-hidden">
                          {imageUrl ? (
                            <Image
                              src={imageUrl}
                              alt={post.title}
                              fill
                              className="object-cover group-hover:scale-105 transition-transform duration-300"
                              sizes="(max-width: 1024px) 100vw, 33vw"
                            />
                          ) : (
                            <div className="w-full h-full bg-gray-200 flex items-center justify-center">
                              <svg className="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                              </svg>
                            </div>
                          )}
                        </div>
                        <h3 className="text-base font-bold text-gray-900 mb-2 group-hover:text-[rgb(246,90,141)] transition-colors line-clamp-2">
                          {post.title}
                        </h3>
                        <div className="flex items-center gap-2 text-xs text-gray-400">
                          <span className="uppercase tracking-wide">{getCategoryName(post)}</span>
                          <span>•</span>
                          <span>{formatDate(post.created_at)}</span>
                        </div>
                        <hr />
                      </Link>
                    )
                  })}
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* Loading State */}
      {loading && (
        <section className="pb-20">
          <div className="container mx-auto px-4 md:px-[120px]">
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
              <div className="lg:col-span-2 h-[500px] bg-gray-200 rounded-2xl animate-pulse" />
              <div className="grid grid-cols-2 lg:grid-cols-1 gap-4">
                {[1, 2, 3, 4].map((i) => (
                  <div key={i} className="h-[240px] bg-gray-200 rounded-xl animate-pulse" />
                ))}
              </div>
            </div>
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
              <div className="lg:col-span-2 space-y-8">
                {[1, 2, 3].map((i) => (
                  <div key={i} className="flex gap-4">
                    <div className="w-48 h-48 bg-gray-200 rounded-xl animate-pulse" />
                    <div className="flex-1 space-y-3">
                      <div className="h-4 bg-gray-200 rounded w-1/4 animate-pulse" />
                      <div className="h-6 bg-gray-200 rounded w-3/4 animate-pulse" />
                      <div className="h-4 bg-gray-200 rounded animate-pulse" />
                      <div className="h-4 bg-gray-200 rounded w-5/6 animate-pulse" />
                    </div>
                  </div>
                ))}
              </div>
              <div className="space-y-6">
                {[1, 2, 3].map((i) => (
                  <div key={i} className="space-y-3">
                    <div className="h-40 bg-gray-200 rounded-xl animate-pulse" />
                    <div className="h-4 bg-gray-200 rounded animate-pulse" />
                    <div className="h-3 bg-gray-200 rounded w-2/3 animate-pulse" />
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>
      )}

      {/* Empty State */}
      {!loading && posts.length === 0 && (
        <section className="pb-20">
          <div className="container mx-auto px-4 md:px-[120px]">
            <p className="text-gray-600 text-center py-12">Belum ada berita atau kegiatan.</p>
          </div>
        </section>
      )}

      <LandingFooter />
    </main>
  )
}
