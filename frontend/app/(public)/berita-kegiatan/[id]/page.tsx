import { Metadata } from 'next'
import { serverFetch } from '@/lib/api/server'
import { BlogPost } from '@/types'
import { getImageUrl } from '@/lib/utils'
import JsonLd from '@/components/seo/JsonLd'
import BeritaDetailClient from './BeritaDetailClient'

type Props = {
  params: { id: string }
}

async function getBlog(id: string): Promise<BlogPost> {
  return serverFetch<BlogPost>(`/blogs/${id}`)
}

async function getAllBlogs(): Promise<BlogPost[]> {
  const result = await serverFetch<BlogPost[] | { data: BlogPost[] }>('/blogs')
  return Array.isArray(result) ? result : (result.data || [])
}

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  try {
    const blog = await getBlog(params.id)
    const siteUrl = process.env.NEXT_PUBLIC_SITE_URL || 'https://cahayaayahbunda.org'
    const description = blog.meta_description || blog.excerpt || blog.content.replace(/<[^>]*>/g, '').substring(0, 160)
    const imageUrl = blog.featured_image?.startsWith('http')
      ? blog.featured_image
      : blog.featured_image
        ? getImageUrl(`/storage/blog_images/${blog.featured_image}`)
        : undefined

    return {
      title: blog.title,
      description,
      keywords: blog.meta_keywords || 'YCAB, cahaya ayah bunda, berita yayasan',
      alternates: {
        canonical: `/berita-kegiatan/${params.id}`,
      },
      openGraph: {
        title: blog.title,
        description,
        type: 'article',
        publishedTime: blog.published_at || blog.created_at,
        authors: blog.creator ? [blog.creator.name] : [],
        url: `${siteUrl}/berita-kegiatan/${params.id}`,
        images: imageUrl ? [{ url: imageUrl }] : [],
      },
      twitter: {
        card: 'summary_large_image',
        title: blog.title,
        description,
        images: imageUrl ? [imageUrl] : [],
      },
    }
  } catch {
    return {
      title: 'Berita & Kegiatan',
      description: 'Berita dan kegiatan terbaru dari Yayasan Cahaya Ayah Bunda (YCAB).',
    }
  }
}

export default async function BeritaDetailPage({ params }: Props) {
  let blog: BlogPost
  let relatedPosts: BlogPost[] = []
  let popularPosts: BlogPost[] = []

  try {
    blog = await getBlog(params.id)
  } catch {
    return null
  }

  try {
    const allPosts = await getAllBlogs()
    const blogId = Number(params.id)

    if (blog.category?.id) {
      relatedPosts = allPosts
        .filter((p) => p.id !== blogId && p.category?.id === blog.category?.id)
        .slice(0, 5)
    }

    popularPosts = allPosts
      .filter((p) => p.id !== blogId)
      .slice(0, 5)
  } catch {}

  const siteUrl = process.env.NEXT_PUBLIC_SITE_URL || 'https://cahayaayahbunda.org'
  const imageUrl = blog.featured_image?.startsWith('http')
    ? blog.featured_image
    : blog.featured_image
      ? getImageUrl(`/storage/blog_images/${blog.featured_image}`)
      : undefined

  return (
    <>
      <JsonLd
        data={{
          '@context': 'https://schema.org',
          '@type': 'BlogPosting',
          headline: blog.title,
          description: blog.meta_description || blog.excerpt || '',
          datePublished: blog.published_at || blog.created_at,
          dateModified: blog.updated_at,
          url: `${siteUrl}/berita-kegiatan/${params.id}`,
          ...(imageUrl ? { image: imageUrl } : {}),
          author: blog.creator
            ? { '@type': 'Person', name: blog.creator.name }
            : undefined,
          publisher: {
            '@type': 'Organization',
            name: 'Yayasan Cahaya Ayah Bunda',
          },
        }}
      />
      <BeritaDetailClient
        blog={blog}
        relatedPosts={relatedPosts}
        popularPosts={popularPosts}
      />
    </>
  )
}
