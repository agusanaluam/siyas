import type { Metadata } from 'next'
import './globals.css'
import Providers from '@/components/Providers'

export const metadata: Metadata = {
  title: "Mutaba'ah Ramadhan - Habit Builder",
  description: 'Habit Builder untuk kebiasaan baik selama Ramadhan 1447H / 2026',
  icons: {
    icon: '/favicon.ico',
  },
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="id" suppressHydrationWarning>
      <body className="min-h-screen bg-diamond-pattern">
        <Providers>{children}</Providers>
      </body>
    </html>
  )
}
