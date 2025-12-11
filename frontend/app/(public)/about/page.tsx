'use client'

import { useState, useEffect } from 'react'
import Image from 'next/image'
import LandingHeader from '@/components/landing/LandingHeader'
import LandingFooter from '@/components/landing/LandingFooter'

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
      <a
        href="https://wa.me/6281292674384"
        target="_blank"
        rel="noopener noreferrer"
        className="fixed bottom-6 right-6 bg-[#25D366] text-white p-4 rounded-full shadow-lg hover:bg-[#20bd5a] transition-colors z-50 animate-bounce"
        aria-label="Chat on WhatsApp"
      >
        <svg className="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
          <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
        </svg>
      </a>
    </main>
  )
}
