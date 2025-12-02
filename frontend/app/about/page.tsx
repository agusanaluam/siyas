'use client'

import { useState, useEffect } from 'react'
import LandingHeader from '@/components/landing/LandingHeader'
import LandingFooter from '@/components/landing/LandingFooter'

interface Setting {
  name: string
  description: string
  photo: string
}

export default function AboutPage() {
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

  const getImageUrl = (path: string) => {
    if (!path) return ''
    if (path.startsWith('http')) return path
    return `${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/${path}`
  }

  return (
    <main className="min-h-screen bg-white">
      <LandingHeader forceScrolledStyle={true} />
      
      {/* Hero Section */}
      <section className="pt-32 pb-16 bg-gradient-to-b from-brand-50 to-white">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
              {setting?.name || 'Yayasan'}
            </h1>
            <p className="text-lg md:text-xl text-gray-600 leading-relaxed">
              {setting?.description || 'Yayasan adalah sebuah lembaga yang bergerak di bidang sosial. Lembaga ini didirikan dengan harapan dapat memberikan sumbangih kepada bangsa dan negara terutama dalam pemberdayaan anak- anak yatim dan dhuafa yang notabene masih ada di lingkungan sekitar kita. Pendampingan dan pembinaan khususnya dalam bidang pendidikan merupakan program yang digalakkan oleh Yayasan. Dengan program tersebut kita berusaha menaikkan level kecerdasan dan keterampilan sehingga mereka dapat bangkit dari keterpurukan dan memiliki mindset baru pasca pendidikan dan pelatihan.'}
            </p>
          </div>
        </div>
      </section>

      {/* Image Section */}
      <section className="py-12">
        <div className="container mx-auto px-4">
          <div className="max-w-3xl mx-auto">
            <div className="relative rounded-2xl overflow-hidden shadow-2xl">
              {setting?.photo ? (
                <img 
                  src={getImageUrl(setting.photo)}
                  alt={setting.name}
                  className="w-auto h-[360px] object-cover"
                />
              ) : (
                <div className="w-full h-96 bg-gray-200 flex items-center justify-center">
                  <svg className="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              )}
            </div>
          </div>
        </div>
      </section>

      {/* Vision Section */}
      <section className="py-16 bg-white">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
              Visi
            </h2>
            <p className="text-lg text-gray-700 leading-relaxed">
              Menjadi lembaga filantropi internasionl yang mencerdaskan memberdayakan dan memandirikan.
            </p>
          </div>
        </div>
      </section>

      {/* Mission Section */}
      <section className="py-16 bg-gray-50">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto">
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">
              Misi
            </h2>
            <div className="space-y-4">
              <div className="flex items-start space-x-4">
                <div className="flex-shrink-0 w-8 h-8 bg-brand-600 text-white rounded-full flex items-center justify-center font-semibold">
                  1
                </div>
                <p className="text-lg text-gray-700 leading-relaxed pt-1">
                  Hadir di seluruh wilayah Indonesia dengan program pendidikan yang mencerdaskan dan berakhlakul karimah
                </p>
              </div>
              <div className="flex items-start space-x-4">
                <div className="flex-shrink-0 w-8 h-8 bg-brand-600 text-white rounded-full flex items-center justify-center font-semibold">
                  2
                </div>
                <p className="text-lg text-gray-700 leading-relaxed pt-1">
                  Memberikan program pemberdayaan ekonomi untuk kemandirian
                </p>
              </div>
              <div className="flex items-start space-x-4">
                <div className="flex-shrink-0 w-8 h-8 bg-brand-600 text-white rounded-full flex items-center justify-center font-semibold">
                  3
                </div>
                <p className="text-lg text-gray-700 leading-relaxed pt-1">
                  Memberikan program kesehatan untuk meningkatkan kualitas hidup
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Values Section */}
      <section className="py-16 bg-white">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto">
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-12 text-center">
              Nilai-Nilai Kami
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div className="text-center p-6 rounded-xl bg-brand-50 hover:shadow-lg transition-shadow">
                <div className="w-16 h-16 bg-brand-600 text-white rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-2">Pendidikan</h3>
                <p className="text-gray-600">Mencerdaskan melalui pendidikan berkualitas</p>
              </div>
              <div className="text-center p-6 rounded-xl bg-brand-50 hover:shadow-lg transition-shadow">
                <div className="w-16 h-16 bg-brand-600 text-white rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-2">Kepedulian</h3>
                <p className="text-gray-600">Peduli terhadap sesama yang membutuhkan</p>
              </div>
              <div className="text-center p-6 rounded-xl bg-brand-50 hover:shadow-lg transition-shadow">
                <div className="w-16 h-16 bg-brand-600 text-white rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-2">Pemberdayaan</h3>
                <p className="text-gray-600">Memberdayakan untuk kemandirian</p>
              </div>
            </div>
          </div>
        </div>
      </section>

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
