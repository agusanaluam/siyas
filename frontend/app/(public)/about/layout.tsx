import { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'Tentang Kami',
  description: 'Tentang Yayasan Cahaya Ayah Bunda (YCAB) - Organisasi sosial yang bergerak di bidang kerelawanan dan pemberdayaan masyarakat. Cahaya ayah bunda untuk Indonesia.',
  keywords: ['YCAB', 'tentang cahaya ayah bunda', 'yayasan', 'profil organisasi', 'cahaya ayah', 'cahaya bunda'],
  alternates: { canonical: '/about' },
}

export default function AboutLayout({ children }: { children: React.ReactNode }) {
  return <>{children}</>
}
