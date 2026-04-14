import { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'Berita & Kegiatan',
  description: 'Berita dan kegiatan terbaru dari Yayasan Cahaya Ayah Bunda (YCAB). Ikuti cerita inspiratif dan update program cahaya ayah bunda.',
  keywords: ['berita YCAB', 'kegiatan cahaya ayah bunda', 'artikel yayasan', 'berita ayah bunda'],
  alternates: { canonical: '/berita-kegiatan' },
}

export default function BeritaKegiatanLayout({ children }: { children: React.ReactNode }) {
  return <>{children}</>
}
