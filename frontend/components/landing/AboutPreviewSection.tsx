'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import Image from 'next/image'

import { Setting, settingService } from '@/lib/api/settings'
import { getImageUrl } from '@/lib/utils'

export default function AboutPreviewSection() {
  const [setting, setSetting] = useState<Setting | null>(null)
  const [loading, setLoading] = useState(true)
  const [imageLoaded, setImageLoaded] = useState(false)

  useEffect(() => {
    fetchSettings()
  }, [])

  const fetchSettings = async () => {
    try {
      const data = await settingService.getProfile()
      setSetting(data)
    } catch (error) {
      console.error('Error fetching settings:', error)
    } finally {
      setLoading(false)
    }
  }

  // Construct image URL
  const aboutPhoto = setting?.about_photo
  const imageUrl = aboutPhoto ? getImageUrl(aboutPhoto) : null

  return (
    <section className="py-16 bg-white" id="about">
      <div className="container mx-auto px-4">
        <div className="flex flex-col md:flex-row items-center gap-12">
          {/* Image Side */}
          <div className="w-full md:w-1/2">
            <div className="relative rounded-2xl overflow-hidden shadow-xl aspect-video bg-gray-100">
              {!imageLoaded && (
                <div className="absolute inset-0 flex items-center justify-center bg-gray-200 animate-pulse z-10">
                  <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-400"></div>
                </div>
              )}
              {imageUrl ? (
                <Image
                  src={imageUrl}
                  alt="Tentang Kami"
                  className={`object-cover transition-opacity duration-500 ${
                    imageLoaded ? 'opacity-100' : 'opacity-0'
                  }`}
                  onLoadingComplete={() => setImageLoaded(true)}
                  fill
                  sizes="(max-width: 768px) 100vw, 600px"
                />
              ) : (
                <div className="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                    <svg className="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
              )}
              {/* Overlay if needed */}
              <div className="absolute inset-0 bg-black/5 pointer-events-none"></div>
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
                <div className="text-gray-600 text-lg leading-relaxed mb-8 prose">
                   {/* Handle specific shortening of about_content or description if needed, or just display description which is short */}
                  {setting?.description || ''}
                </div>
                <Link
                  href="/about"
                  className="inline-block bg-primary-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-primary-700 transition-colors shadow-lg shadow-primary-200"
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
