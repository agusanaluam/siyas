'use client'

import { ReactNode } from 'react'
import { AuthProvider } from '@/contexts/AuthContext'

import { Toaster } from 'react-hot-toast'

export default function Providers({ children }: { children: ReactNode }) {
  return (
    <AuthProvider>
      {children}
      <Toaster position="top-right" />
    </AuthProvider>
  )
}

