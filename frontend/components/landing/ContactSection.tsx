'use client'

import { useState, useEffect } from 'react'


interface Setting {
  phone_number: string
  address: string
  gmaps: string
  email?: string // Assuming email might be added or I'll use a default
}

interface Partner {
  id: number
  name: string
  image: string
}

export default function ContactSection() {
  const [setting, setSetting] = useState<Setting | null>(null)
  const [partners, setPartners] = useState<Partner[]>([])
  const [loading, setLoading] = useState(true)
  const [partnersLoading, setPartnersLoading] = useState(true)

  useEffect(() => {
    fetchSettings()
    fetchPartners()
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

  const fetchPartners = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/partners`)
      const data = await response.json()
      setPartners(data)
    } catch (error) {
      console.error('Error fetching partners:', error)
    } finally {
      setPartnersLoading(false)
    }
  }

  const getImageUrl = (path: string) => {
    if (path.startsWith('http')) return path
    return `${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}${path}`
  }

  const getMapEmbedUrl = (url: string | null | undefined): string | null => {
    if (!url) return null

    // Jika sudah format embed, gunakan langsung
    if (url.includes('/maps/embed')) {
      return url
    }

    // Jika URL Google Maps biasa, return null untuk menampilkan fallback
    // Admin harus memasukkan embed URL yang benar
    return null
  }

  return (
    <section className="py-16 bg-gradient-to-b from-brand-50 via-white to-support-50" id="contact">
      <div className="container mx-auto px-4">
        <div className="flex flex-col md:flex-row gap-12 mb-20">
          {/* Contact Info */}
          <div className="w-full md:w-1/2">
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
              Hubungi Kami
            </h2>
            <p className="text-gray-600 mb-8 text-lg">
              Mari berikan perubahan dalam kehidupan mereka yang membutuhkan dengan bantuan terkuatmu.
            </p>

            {loading ? (
              <div className="space-y-6">
                {[1, 2, 3].map((i) => (
                  <div key={i} className="flex items-center space-x-4">
                    <div className="w-10 h-10 bg-gray-300 rounded-full animate-pulse flex-shrink-0"></div>
                    <div className="h-6 bg-gray-300 rounded w-48 animate-pulse"></div>
                  </div>
                ))}
              </div>
            ) : (
              <div className="space-y-6">
                <div className="flex items-center space-x-4">
                  <div className="w-10 h-10 bg-brand-600 rounded-full flex items-center justify-center text-white flex-shrink-0">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                  </div>
                  <span className="text-gray-700 text-lg">{setting?.phone_number || '+62 812-3456-7890'}</span>
                </div>

                <div className="flex items-center space-x-4">
                  <div className="w-10 h-10 bg-brand-600 rounded-full flex items-center justify-center text-white flex-shrink-0">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <span className="text-gray-700 text-lg">{setting?.email || 'email@gmail.com'}</span>
                </div>

                <div className="flex items-start space-x-4">
                  <div className="w-10 h-10 bg-brand-600 rounded-full flex items-center justify-center text-white flex-shrink-0 mt-1">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </div>
                  <span className="text-gray-700 text-lg max-w-md">
                    {setting?.address || 'Alamat'}
                  </span>
                </div>
              </div>
            )}
          </div>

          {/* Map */}
          <div className="w-full md:w-1/2">
            <div className="bg-white p-2 rounded-xl shadow-lg h-[400px] relative">
              {setting?.gmaps && getMapEmbedUrl(setting.gmaps) ? (
                <iframe
                  src={getMapEmbedUrl(setting.gmaps)!}
                  width="100%"
                  height="100%"
                  style={{ border: 0, borderRadius: '0.75rem' }}
                  allowFullScreen
                  loading="lazy"
                  title="Peta Lokasi"
                ></iframe>
              ) : (
                <div className="w-full h-full flex flex-col items-center justify-center bg-gray-100 rounded-lg p-4">
                  <svg className="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <p className="text-gray-600 text-center mb-4">Peta tidak tersedia</p>
                  {setting?.gmaps && (
                    <a
                      href={setting.gmaps}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-brand-600 hover:text-brand-700 underline"
                    >
                      Buka di Google Maps
                    </a>
                  )}
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Partners */}
        <div className="text-center">
          <p className="text-gray-500 mb-4">Mitra Kami</p>
          <h3 className="text-2xl md:text-3xl font-bold text-gray-900 mb-12">
            Garda Terdepan Dalam Kebaikan
          </h3>
          {partnersLoading ? (
            <div className="flex flex-wrap justify-center items-center gap-8 md:gap-16">
              {[1, 2, 3].map((i) => (
                <div key={i} className="h-12 md:h-16 w-24 md:w-32 bg-gray-300 rounded animate-pulse"></div>
              ))}
            </div>
          ) : (
            <div className="flex flex-wrap justify-center items-center gap-8 md:gap-16">
              {partners.length > 0 ? (
                partners.map(partner => (
                  <div key={partner.id} className="relative h-12 md:h-16 w-32 md:w-40">
                    <img
                      src={getImageUrl(partner.image)}
                      alt={partner.name}
                      className="w-full h-full object-contain"
                    />
                  </div>
                ))
              ) : (
                <>
                  {['bni.png', 'bsi.png', 'its.png'].map((file) => (
                    <div key={file} className="relative h-12 md:h-16 w-32 md:w-40">
                      <img
                        src={`${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/partners/${file}`}
                        alt={file.split('.')[0]}
                        className="w-full h-full object-contain"
                      />
                    </div>
                  ))}
                </>
              )}
            </div>
          )}
        </div>
      </div>
    </section>
  )
}
