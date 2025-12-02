'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'

interface Setting {
  name: string
  description: string
}

export default function AboutPreviewSection() {
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
    <section className="py-16 bg-white" id="about">
      <div className="container mx-auto px-4">
        <div className="flex flex-col md:flex-row items-center gap-12">
          {/* Image Side */}
          <div className="w-full md:w-1/2">
            <div className="relative rounded-2xl overflow-hidden shadow-xl">
              <img 
                src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=1600&auto=format&fit=crop" 
                alt="Tentang Kami" 
                className="w-full h-[200px] object-cover grayscale hover:grayscale-0 transition-all duration-500"
              />
              {/* Overlay Logo/Icon if needed */}
              <div className="absolute inset-0 bg-black/10"></div>
            </div>
          </div>

          {/* Content Side */}
          <div className="w-full md:w-1/2">
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
              Sekilas Tentang {setting?.name || 'Yayasan'}
            </h2>
            <p className="text-gray-600 text-lg leading-relaxed mb-8">
              {setting?.description || 'Yayasan merupakan lembaga pengelola dana Zakat, Infak, Sedekah dan Wakaf terpercaya sejak tahun 1998 yang mengkhususkan diri dalam pemberdayaan anak Yatim Dhuafa di Indonesia.'}
            </p>
            <Link 
              href="/about"
              className="inline-block bg-brand-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-brand-700 transition-colors shadow-lg shadow-brand-200"
            >
              Tentang Kami
            </Link>
          </div>
        </div>
      </div>
    </section>
  )
}
