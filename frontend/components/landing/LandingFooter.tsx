'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'

interface Setting {
  name: string
  description: string
  phone_number: string
  email?: string
  address: string
}

export default function LandingFooter() {
  const [setting, setSetting] = useState<Setting | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetchSettings()
  }, [])

  const fetchSettings = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/settings/profile`)
      const data = await response.json()
      setSetting(data)
    } catch (error) {
      console.error('Error fetching settings:', error)
    } finally {
      setLoading(false)
    }
  }

  return (
    <footer className="bg-[rgb(25,79,186)] text-white py-12 ">
      <div className="container mx-auto px-4 md:px-[150px]">
        {loading ? (
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div className="col-span-1 md:col-span-2 space-y-4">
              <div className="h-8 bg-gray-700 rounded w-32 animate-pulse"></div>
              <div className="space-y-2">
                <div className="h-4 bg-gray-700 rounded w-full animate-pulse"></div>
                <div className="h-4 bg-gray-700 rounded w-5/6 animate-pulse"></div>
              </div>
            </div>
            <div className="space-y-4">
              <div className="h-6 bg-gray-700 rounded w-24 animate-pulse"></div>
              <div className="space-y-2">
                {[1, 2, 3, 4].map((i) => (
                  <div key={i} className="h-4 bg-gray-700 rounded w-20 animate-pulse"></div>
                ))}
              </div>
            </div>
            <div className="space-y-4">
              <div className="h-6 bg-gray-700 rounded w-20 animate-pulse"></div>
              <div className="space-y-2">
                {[1, 2, 3].map((i) => (
                  <div key={i} className="h-4 bg-gray-700 rounded w-32 animate-pulse"></div>
                ))}
              </div>
            </div>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            {/* Brand */}
            <div className="col-span-1 md:col-span-2">
              <h3 className="text-2xl font-bold mb-4">{setting?.name || 'Yayasan'}</h3>
              <p className="text-white max-w-sm">
                {setting?.description || ''}
              </p>
            </div>

            {/* Quick Links */}
            <div>
              <h4 className="text-lg font-semibold mb-4">Tautan Cepat</h4>
              <ul className="space-y-2">
                <li><Link href="/" className="text-white hover:text-[rgb(246,90,141)] transition-colors">Beranda</Link></li>
                <li><Link href="#about" className="text-white hover:text-[rgb(246,90,141)] transition-colors">Tentang Kami</Link></li>
                <li><Link href="/program" className="text-white hover:text-[rgb(246,90,141)] transition-colors">Program</Link></li>
                <li><Link href="/rekening-donasi" className="text-white hover:text-[rgb(246,90,141)] transition-colors">Donasi</Link></li>
              </ul>
            </div>

            {/* Contact */}
            <div>
              <h4 className="text-lg font-semibold mb-4">Kontak</h4>
              <ul className="space-y-2 text-white">
                <li>{setting?.address || 'Bandung, Indonesia'}</li>
                <li>{setting?.phone_number || '+62 812-3456-7890'}</li>
                <li>{setting?.email || 'info@yayasan.org'}</li>
              </ul>
            </div>
          </div>
        )}

        <div className="border-t border-white/20 pt-8 text-center text-white text-sm">
          <p>&copy; {new Date().getFullYear()} {setting?.name || 'Yayasan'}. All rights reserved.</p>
        </div>
      </div>
    </footer>
  )
}
