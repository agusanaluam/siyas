import { MetadataRoute } from 'next'

export default function robots(): MetadataRoute.Robots {
  const siteUrl = process.env.NEXT_PUBLIC_SITE_URL || 'https://cahayaayahbunda.org'

  return {
    rules: [
      {
        userAgent: '*',
        allow: '/',
        disallow: ['/dashboard/', '/blog/', '/campaign/', '/volunteer/', '/donation/', '/settings/', '/user/'],
      },
    ],
    sitemap: `${siteUrl}/sitemap.xml`,
  }
}
