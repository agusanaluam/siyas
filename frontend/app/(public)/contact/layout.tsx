import { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'Kontak',
  description: 'Hubungi Yayasan Cahaya Ayah Bunda (YCAB). Informasi kontak, alamat, dan cara berpartisipasi dalam program ayah bunda.',
  keywords: ['kontak YCAB', 'hubungi cahaya ayah bunda', 'alamat yayasan'],
  alternates: { canonical: '/contact' },
}

export default function ContactLayout({ children }: { children: React.ReactNode }) {
  return <>{children}</>
}
