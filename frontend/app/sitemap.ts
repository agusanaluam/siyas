import { MetadataRoute } from 'next'

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const siteUrl = process.env.NEXT_PUBLIC_SITE_URL || 'https://cahayaayahbunda.org'
  const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'

  const staticPages: MetadataRoute.Sitemap = [
    { url: siteUrl, lastModified: new Date(), changeFrequency: 'daily', priority: 1 },
    { url: `${siteUrl}/about`, lastModified: new Date(), changeFrequency: 'monthly', priority: 0.8 },
    { url: `${siteUrl}/program`, lastModified: new Date(), changeFrequency: 'weekly', priority: 0.8 },
    { url: `${siteUrl}/berita-kegiatan`, lastModified: new Date(), changeFrequency: 'daily', priority: 0.9 },
    { url: `${siteUrl}/contact`, lastModified: new Date(), changeFrequency: 'monthly', priority: 0.5 },
    { url: `${siteUrl}/rekening-donasi`, lastModified: new Date(), changeFrequency: 'monthly', priority: 0.5 },
  ]

  let blogPages: MetadataRoute.Sitemap = []
  try {
    const res = await fetch(`${apiUrl}/blogs/published`, { next: { revalidate: 3600 } })
    if (res.ok) {
      const blogs = await res.json()
      blogPages = blogs.map((blog: { id: number; slug?: string; updated_at: string }) => ({
        url: `${siteUrl}/berita-kegiatan/${blog.id}`,
        lastModified: new Date(blog.updated_at),
        changeFrequency: 'weekly' as const,
        priority: 0.7,
      }))
    }
  } catch {}

  let campaignPages: MetadataRoute.Sitemap = []
  try {
    const res = await fetch(`${apiUrl}/campaigns`, { next: { revalidate: 3600 } })
    if (res.ok) {
      const result = await res.json()
      const items = Array.isArray(result) ? result : (result.data || [])
      campaignPages = items.map((c: { id: number; updated_at?: string; created_at: string }) => ({
        url: `${siteUrl}/program/${c.id}`,
        lastModified: new Date(c.updated_at || c.created_at),
        changeFrequency: 'weekly' as const,
        priority: 0.7,
      }))
    }
  } catch {}

  return [...staticPages, ...blogPages, ...campaignPages]
}
