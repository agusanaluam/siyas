'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'

interface Setting {
  name: string
  description: string
}

export default function AboutPreviewSection() {
  const [setting, setSetting] = useState<Setting | null>(null)
  const [loading, setLoading] = useState(true)
  const [imageLoaded, setImageLoaded] = useState(false)

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
    <section className="py-16 bg-white" id="about">
      <div className="container mx-auto px-4">
        <div className="flex flex-col md:flex-row items-center gap-12">
          {/* Image Side */}
          <div className="w-full md:w-1/2">
            <div className="relative rounded-2xl overflow-hidden shadow-xl">
              {!imageLoaded && (
                <div className="w-full h-[200px] bg-gray-300 animate-pulse flex items-center justify-center">
                  <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-400"></div>
                </div>
              )}
              <img
                src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=1600&auto=format&fit=crop"
                alt="Tentang Kami"
                className={`w-full h-[200px] object-cover grayscale hover:grayscale-0 transition-all duration-500 ${
                  imageLoaded ? 'opacity-100' : 'opacity-0 absolute'
                }`}
                onLoad={() => setImageLoaded(true)}
                onError={() => setImageLoaded(true)}
              />
              {/* Overlay Logo/Icon if needed */}
              <div className="absolute inset-0 bg-black/10"></div>
            </div>
          </div>

          {/* Content Side */}
          <div className="w-full md:w-1/2">
            {loading ? (
              <>
                <div className="h-10 bg-gray-300 rounded w-3/4 mb-6 animate-pulse"></div>
                <div className="space-y-3 mb-8">
                  <div className="h-4 bg-gray-300 rounded animate-pulse"></div>
                  <div className="h-4 bg-gray-300 rounded w-5/6 animate-pulse"></div>
                  <div className="h-4 bg-gray-300 rounded w-4/6 animate-pulse"></div>
                </div>
                <div className="h-12 bg-gray-300 rounded w-40 animate-pulse"></div>
              </>
            ) : (
              <>
                <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                  Sekilas Tentang {setting?.name || 'Yayasan'}
                </h2>
                <p className="text-gray-600 text-lg leading-relaxed mb-8">
                  {setting?.description || ''}
                </p>
                <Link
                  href="/about"
                  className="inline-block bg-accent-500 text-white px-8 py-3 rounded-lg font-medium hover:bg-accent-600 transition-colors shadow-lg shadow-accent-200"
                >
                  Tentang Kami
                </Link>
              </>
            )}
          </div>
        </div>
      </div>
    </section>
  )
}
