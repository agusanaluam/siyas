import type { Metadata } from 'next'
import './globals.css'
import Providers from '@/components/Providers'
import JsonLd from '@/components/seo/JsonLd'

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL || 'https://cahayaayahbunda.org'

export const metadata: Metadata = {
  title: {
    default: 'Yayasan Cahaya Ayah Bunda (YCAB) - Program Sosial & Kerelawanan',
    template: '%s | Yayasan Cahaya Ayah Bunda (YCAB)',
  },
  description: 'Yayasan Cahaya Ayah Bunda (YCAB) - Platform digital untuk program sosial, donasi, dan kegiatan kerelawanan. Cahaya ayah bunda untuk masa depan generasi yang lebih baik.',
  keywords: ['YCAB', 'cahaya ayah bunda', 'ayah bunda', 'cahaya ayah', 'cahaya bunda', 'yayasan', 'donasi', 'volunteer', 'kerelawanan', 'program sosial'],
  authors: [{ name: 'Yayasan Cahaya Ayah Bunda' }],
  creator: 'Yayasan Cahaya Ayah Bunda',
  publisher: 'Yayasan Cahaya Ayah Bunda',
  metadataBase: new URL(siteUrl),
  alternates: {
    canonical: '/',
  },
  openGraph: {
    type: 'website',
    locale: 'id_ID',
    siteName: 'Yayasan Cahaya Ayah Bunda (YCAB)',
    title: 'Yayasan Cahaya Ayah Bunda (YCAB) - Program Sosial & Kerelawanan',
    description: 'Platform digital Yayasan Cahaya Ayah Bunda untuk program sosial, donasi, dan kegiatan kerelawanan.',
    url: siteUrl,
    images: [
      {
        url: '/favicon.ico',
        width: 64,
        height: 64,
        alt: 'Yayasan Cahaya Ayah Bunda',
      },
    ],
  },
  twitter: {
    card: 'summary',
    title: 'Yayasan Cahaya Ayah Bunda (YCAB)',
    description: 'Platform digital Yayasan Cahaya Ayah Bunda untuk program sosial, donasi, dan kegiatan kerelawanan.',
  },
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      'max-video-preview': -1,
      'max-image-preview': 'large',
      'max-snippet': -1,
    },
  },
  icons: {
    icon: '/favicon.ico',
    shortcut: '/favicon.ico',
    apple: '/favicon.ico',
  },
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  const midtransClientKey = process.env.NEXT_PUBLIC_MIDTRANS_CLIENT_KEY || ''
  const isProduction = process.env.NEXT_PUBLIC_MIDTRANS_IS_PRODUCTION === 'true'
  const snapScriptUrl = isProduction
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js'

  return (
    <html lang="id">
      <head>
        <script
          src={snapScriptUrl}
          data-client-key={midtransClientKey}
          async
        />
        <JsonLd
          data={{
            '@context': 'https://schema.org',
            '@type': 'Organization',
            name: 'Yayasan Cahaya Ayah Bunda',
            alternateName: ['YCAB', 'Cahaya Ayah Bunda'],
            url: siteUrl,
            description: 'Yayasan Cahaya Ayah Bunda (YCAB) - Organisasi sosial untuk program kerelawanan, donasi, dan pemberdayaan masyarakat.',
          }}
        />
      </head>
      <body>
        <Providers>{children}</Providers>
      </body>
    </html>
  )
}
