import { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'Beranda',
  description: 'Yayasan Cahaya Ayah Bunda (YCAB) - Program sosial, donasi, dan kegiatan kerelawanan. Cahaya ayah, cahaya bunda untuk masa depan generasi yang lebih baik.',
  alternates: { canonical: '/' },
}

export default function PublicLayout({ children }: { children: React.ReactNode }) {
  return <>{children}</>
}
