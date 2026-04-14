import { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'Program',
  description: 'Program dan kampanye sosial Yayasan Cahaya Ayah Bunda (YCAB). Bergabunglah bersama ayah bunda untuk membuat perubahan positif.',
  keywords: ['YCAB', 'program cahaya ayah bunda', 'kampanye sosial', 'donasi', 'ayah bunda'],
  alternates: { canonical: '/program' },
}

export default function ProgramLayout({ children }: { children: React.ReactNode }) {
  return <>{children}</>
}
