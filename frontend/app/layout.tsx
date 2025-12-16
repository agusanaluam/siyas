import type { Metadata } from 'next'
import './globals.css'
import Providers from '@/components/Providers'

export const metadata: Metadata = {
  title: 'SIYAS - YCAB Volunteer Dashboard',
  description: 'Sistem Informasi Yayasan Cahaya Ayah Bunda',
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
      </head>
      <body>
        <Providers>{children}</Providers>
      </body>
    </html>
  )
}

