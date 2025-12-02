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
    }
  }

  return (
    <footer className="bg-gray-900 text-white py-12">
      <div className="container mx-auto px-4">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
          {/* Brand */}
          <div className="col-span-1 md:col-span-2">
            <h3 className="text-2xl font-bold mb-4">{setting?.name || 'Yayasan'}</h3>
            <p className="text-gray-400 max-w-sm">
              {setting?.description || 'Lembaga pengelola dana Zakat, Infak, Sedekah dan Wakaf terpercaya yang mengkhususkan diri dalam pemberdayaan anak Yatim Dhuafa.'}
            </p>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="text-lg font-semibold mb-4">Tautan Cepat</h4>
            <ul className="space-y-2">
              <li><Link href="/" className="text-gray-400 hover:text-white transition-colors">Beranda</Link></li>
              <li><Link href="#about" className="text-gray-400 hover:text-white transition-colors">Tentang Kami</Link></li>
              <li><Link href="#program" className="text-gray-400 hover:text-white transition-colors">Program</Link></li>
              <li><Link href="#donation" className="text-gray-400 hover:text-white transition-colors">Donasi</Link></li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="text-lg font-semibold mb-4">Kontak</h4>
            <ul className="space-y-2 text-gray-400">
              <li>{setting?.address || 'Bandung, Indonesia'}</li>
              <li>{setting?.phone_number || '+62 812-9267-4384'}</li>
              <li>{setting?.email || 'info@yayasan.org'}</li>
            </ul>
          </div>
        </div>

        <div className="border-t border-gray-800 pt-8 text-center text-gray-500 text-sm">
          <p>&copy; {new Date().getFullYear()} {setting?.name || 'Yayasan'}. All rights reserved.</p>
        </div>
      </div>
    </footer>
  )
}
