'use client'

import { useState, useEffect } from 'react'
import Image from 'next/image'
import LandingHeader from '@/components/landing/LandingHeader'
import LandingFooter from '@/components/landing/LandingFooter'
import FloatingWhatsApp from '@/components/FloatingWhatsApp'

interface Setting {
  name: string
  description: string
  photo: string
}

interface AboutSetting {
  about_content: string
  about_photo?: string
  about_service?: string[] | null
}

export default function AboutPage() {
  const [setting, setSetting] = useState<Setting | null>(null)
  const [aboutSetting, setAboutSetting] = useState<AboutSetting | null>(null)

  useEffect(() => {
    fetchSettings()
    fetchAbout()
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

  const fetchAbout = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/settings/about`)
      const data = await response.json()
      setAboutSetting(data)
    } catch (error) {
      console.error('Error fetching about:', error)
    }
  }

  const getImageUrl = (path: string) => {
    if (!path) return ''
    if (path.startsWith('http')) return path
    return `${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/${path}`
  }

  return (
    <main className="min-h-screen bg-gradient-to-b from-brand-50 via-white to-support-50">
      <LandingHeader forceScrolledStyle={true} />

      {/* Hero Section */}
      <section className="pt-32 pb-16 bg-gradient-to-b from-brand-50 via-white to-accent-50/50">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
              {setting?.name || 'Yayasan'}
            </h1>
          </div>
        </div>
      </section>

      {/* Image Section */}
      <section className="py-8">
        <div className="container mx-auto px-4">
          <div className="max-w-3xl mx-auto">
            <div className="relative rounded-2xl overflow-hidden shadow-2xl border border-brand-100 h-[360px]">
              {aboutSetting?.about_photo ? (
                <Image
                  src={getImageUrl(aboutSetting.about_photo)}
                  alt={setting?.name || 'Foto about'}
                  fill
                  className="object-cover"
                  sizes="(max-width: 768px) 100vw, 720px"
                  priority
                />
              ) : setting?.photo ? (
                <Image
                  src={getImageUrl(setting.photo)}
                  alt={setting.name || 'Foto yayasan'}
                  fill
                  className="object-cover"
                  sizes="(max-width: 768px) 100vw, 720px"
                  priority
                />
              ) : (
                <div className="w-full h-full bg-gray-200 flex items-center justify-center">
                  <svg className="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              )}
            </div>
          </div>
        </div>
      </section>

      {/* About Content */}
      <section className="py-16 bg-gradient-to-b from-brand-50/50 via-white to-support-50/60">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto">
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6 text-center">
              Tentang Kami
            </h2>
            <div
              className="prose prose-lg max-w-none text-gray-700"
              dangerouslySetInnerHTML={{ __html: aboutSetting?.about_content || '' }}
            />
          </div>
        </div>
      </section>

      {/* Services Section */}
      {aboutSetting?.about_service && aboutSetting.about_service.length > 0 && (
        <section className="py-16 bg-gradient-to-b from-white via-brand-50/40 to-support-50/40">
          <div className="container mx-auto px-4">
            <div className="max-w-4xl mx-auto">
              <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">
                Layanan Kami
              </h2>
              <div className="space-y-3">
                {aboutSetting.about_service.map((item, idx) => (
                  <div key={idx} className="flex items-start space-x-3 bg-white border border-brand-100 rounded-lg p-4 shadow-sm">
                    <div className="w-8 h-8 rounded-full bg-accent-500 text-white flex items-center justify-center font-semibold">
                      {idx + 1}
                    </div>
                    <p className="text-lg text-gray-700 leading-relaxed">{item}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>
      )}

      <LandingFooter />

      {/* Floating WhatsApp Button */}
      <FloatingWhatsApp />
    </main>
  )
}
