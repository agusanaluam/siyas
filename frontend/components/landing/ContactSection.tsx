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
    }
  }

  const getImageUrl = (path: string) => {
    if (path.startsWith('http')) return path
    return `${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}${path}`
  }

  return (
    <section className="py-16 bg-gray-50" id="contact">
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
            
            <div className="space-y-6">
              <div className="flex items-center space-x-4">
                <div className="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white flex-shrink-0">
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </div>
                <span className="text-gray-700 text-lg">{setting?.phone_number || '+62 812-9267-4384'}</span>
              </div>

              <div className="flex items-center space-x-4">
                <div className="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white flex-shrink-0">
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <span className="text-gray-700 text-lg">{setting?.email || 'yayasan@gmail.com'}</span>
              </div>

              <div className="flex items-start space-x-4">
                <div className="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white flex-shrink-0 mt-1">
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <span className="text-gray-700 text-lg max-w-md">
                  {setting?.address || 'Komp. Vijayakusuma jl. Kusuma indah blok B14 no 36, RT 06 RW 17. Kel. Cipadung, Kec. Cibiru Kota Bandung.'}
                </span>
              </div>
            </div>
          </div>

          {/* Map */}
          <div className="w-full md:w-1/2">
            <div className="bg-white p-2 rounded-xl shadow-lg h-[400px]">
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.687915886963!2d107.71372631477296!3d-6.927863994994468!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68c32a7e555555%3A0x5555555555555555!2sBandung!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid" 
                width="100%" 
                height="100%" 
                style={{ border: 0, borderRadius: '0.75rem' }} 
                allowFullScreen 
                loading="lazy"
              ></iframe>
            </div>
          </div>
        </div>

        {/* Partners */}
        <div className="text-center">
          <p className="text-gray-500 mb-4">Mitra Kami</p>
          <h3 className="text-2xl md:text-3xl font-bold text-gray-900 mb-12">
            Garda Terdepan Dalam Kebaikan
          </h3>
          <div className="flex flex-wrap justify-center items-center gap-8 md:gap-16">
            {partners.length > 0 ? (
              partners.map(partner => (
                <img 
                  key={partner.id}
                  src={getImageUrl(partner.image)} 
                  alt={partner.name} 
                  className="h-12 md:h-16 object-contain" 
                  title={partner.name}
                />
              ))
            ) : (
              <>
                <img 
                  src={`${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/partners/bni.png`} 
                  alt="BNI" 
                  className="h-12 md:h-16 object-contain" 
                />
                <img 
                  src={`${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/partners/bsi.png`} 
                  alt="BSI" 
                  className="h-12 md:h-16 object-contain" 
                />
                <img 
                  src={`${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/partners/its.png`} 
                  alt="IT's" 
                  className="h-12 md:h-16 object-contain" 
                />
              </>
            )}
          </div>
        </div>
      </div>
    </section>
  )
}
