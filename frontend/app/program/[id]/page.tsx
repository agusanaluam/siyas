'use client'

import { useState, useEffect } from 'react'
import { useParams } from 'next/navigation'
import Link from 'next/link'
import LandingHeader from '@/components/landing/LandingHeader'
import LandingFooter from '@/components/landing/LandingFooter'
import { Campaign } from '@/types'

export default function CampaignDetailPage() {
  const params = useParams()
  const [campaign, setCampaign] = useState<Campaign | null>(null)
  const [relatedCampaigns, setRelatedCampaigns] = useState<Campaign[]>([])
  const [loading, setLoading] = useState(true)
  const [activeTab, setActiveTab] = useState<'detail' | 'update' | 'donatur'>('detail')
  const [selectedImageIndex, setSelectedImageIndex] = useState(0)

  useEffect(() => {
    if (params.id) {
      fetchCampaign(params.id as string)
      fetchRelatedCampaigns()
    }
  }, [params.id])

  const fetchCampaign = async (id: string) => {
    try {
      setLoading(true)
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/campaigns/${id}`)
      const data = await response.json()
      setCampaign(data)
    } catch (error) {
      console.error('Error fetching campaign:', error)
    } finally {
      setLoading(false)
    }
  }

  const fetchRelatedCampaigns = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/campaigns?per_page=3`)
      const result = await response.json()
      setRelatedCampaigns(result.data || result)
    } catch (error) {
      console.error('Error fetching related campaigns:', error)
    }
  }

  const getImageUrl = (campaign: Campaign) => {
    if (campaign.image && campaign.image.length > 0) {
      const imagePath = campaign.image[0].picture_path
      if (imagePath.startsWith('http')) return imagePath
      return `${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/campaign_pictures/${imagePath}`
    }
    return ''
  }

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(amount)
  }

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    })
  }

  const calculateDaysLeft = (endDate: string) => {
    const end = new Date(endDate)
    const now = new Date()
    const diff = end.getTime() - now.getTime()
    const days = Math.ceil(diff / (1000 * 60 * 60 * 24))
    return days > 0 ? days : 0
  }

  if (loading) {
    return (
      <main className="min-h-screen bg-white">
        <LandingHeader forceScrolledStyle={true} />
        <div className="flex justify-center items-center py-32">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-600"></div>
        </div>
        <LandingFooter />
      </main>
    )
  }

  if (!campaign) {
    return (
      <main className="min-h-screen bg-white">
        <LandingHeader forceScrolledStyle={true} />
        <div className="container mx-auto px-4 py-32 text-center">
          <h1 className="text-2xl font-bold text-gray-900">Campaign tidak ditemukan</h1>
        </div>
        <LandingFooter />
      </main>
    )
  }

  return (
    <main className="min-h-screen bg-gray-50">
      <LandingHeader forceScrolledStyle={true} />
      
      {/* Breadcrumb */}
      <section className="pt-24 pb-6 bg-white">
        <div className="container mx-auto px-4">
          <div className="flex items-center space-x-2 text-sm text-gray-600">
            <Link href="/" className="hover:text-brand-600">Beranda</Link>
            <span>/</span>
            <Link href="/program" className="hover:text-brand-600">Eksplore Donasi</Link>
            <span>/</span>
            <span className="text-gray-900">{campaign.name}</span>
          </div>
        </div>
      </section>

      {/* Campaign Header */}
      <section className="pb-12 bg-white">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Left Column - Image */}
            <div className="lg:col-span-2">
              <div className="relative rounded-xl overflow-hidden shadow-lg mb-6">
                {campaign.image && campaign.image.length > 0 ? (
                  <img
                    src={`${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/campaign_pictures/${campaign.image[selectedImageIndex].picture_path}`}
                    alt={campaign.name}
                    className="w-auto h-555 object-cover"
                  />
                ) : (
                  <div className="w-full h-96 bg-gray-200 flex items-center justify-center">
                    <svg className="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                )}
              </div>

              {/* Additional Images */}
              {campaign.image && campaign.image.length > 1 && (
                <div className="grid grid-cols-4 gap-4 mb-8">
                  {campaign.image.map((img, index) => (
                    <div 
                      key={index} 
                      className={`relative rounded-lg overflow-hidden shadow cursor-pointer transition-all ${
                        selectedImageIndex === index ? 'ring-2 ring-brand-600' : ''
                      }`}
                      onClick={() => setSelectedImageIndex(index)}
                    >
                      <img
                        src={`${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/campaign_pictures/${img.picture_path}`}
                        alt={`${campaign.name} ${index + 1}`}
                        className="w-full h-24 object-cover hover:opacity-80 transition-opacity"
                      />
                    </div>
                  ))}
                </div>
              )}
            </div>

            {/* Right Column - Campaign Info */}
            <div className="lg:col-span-1">
              <div className="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <h1 className="text-2xl font-bold text-gray-900 mb-4">
                  {campaign.name}
                </h1>

                <div className="mb-4">
                  <div className="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                    <span className="px-3 py-1 bg-brand-100 text-brand-600 rounded-full font-medium">
                      {campaign.category?.name || 'Infak'}
                    </span>
                   
                  </div>
                </div>

                <div className="mb-6">
                  <div className="flex justify-between items-baseline mb-2">
                    <span className="text-sm text-gray-600">Terkumpul</span>
                    <span className="text-sm text-gray-600">Dana Dibutuhkan</span>
                  </div>
                  <div className="flex justify-between items-baseline mb-3">
                    <span className="text-xl font-bold text-brand-600">
                      {formatCurrency(0)}
                    </span>
                    <span className="text-lg font-semibold text-gray-900">
                      {formatCurrency(campaign.target_amount)}
                    </span>
                  </div>
                  
                  {/* Progress Bar */}
                  <div className="w-full bg-gray-200 rounded-full h-2 mb-4">
                    <div className="bg-brand-600 h-2 rounded-full" style={{ width: '0%' }}></div>
                  </div>

                  <div className="flex justify-between text-sm text-gray-600">
                    <span>Open Goal</span>
                    <span>{calculateDaysLeft(campaign.end_date)} Hari Lagi</span>
                  </div>
                </div>

                <div className="mb-6">
                  <p className="text-sm text-gray-600 mb-3">Share</p>
                  <div className="flex space-x-2">
                    <button className="flex-1 bg-[#1877F2] text-white py-2 rounded-lg hover:opacity-90 transition-opacity">
                      <svg className="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                      </svg>
                    </button>
                    <button className="flex-1 bg-[#1DA1F2] text-white py-2 rounded-lg hover:opacity-90 transition-opacity">
                      <svg className="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                      </svg>
                    </button>
                    <button className="flex-1 bg-[#25D366] text-white py-2 rounded-lg hover:opacity-90 transition-opacity">
                      <svg className="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                      </svg>
                    </button>
                  </div>
                </div>

                <button className="w-full bg-brand-600 text-white py-3 rounded-lg font-semibold hover:bg-brand-700 transition-colors">
                  Donasi Sekarang
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Tabs Section */}
      <section className="py-8 bg-white border-t">
        <div className="container mx-auto px-4">
          <div className="max-w-5xl mx-auto">
            {/* Tab Navigation */}
            <div className="flex border-b border-gray-200 mb-8">
              <button
                onClick={() => setActiveTab('detail')}
                className={`px-6 py-3 font-semibold transition-colors ${
                  activeTab === 'detail'
                    ? 'text-brand-600 border-b-2 border-brand-600'
                    : 'text-gray-600 hover:text-gray-900'
                }`}
              >
                Detail
              </button>
              <button
                onClick={() => setActiveTab('update')}
                className={`px-6 py-3 font-semibold transition-colors ${
                  activeTab === 'update'
                    ? 'text-brand-600 border-b-2 border-brand-600'
                    : 'text-gray-600 hover:text-gray-900'
                }`}
              >
                Update
              </button>
              <button
                onClick={() => setActiveTab('donatur')}
                className={`px-6 py-3 font-semibold transition-colors ${
                  activeTab === 'donatur'
                    ? 'text-brand-600 border-b-2 border-brand-600'
                    : 'text-gray-600 hover:text-gray-900'
                }`}
              >
                Donatur <span className="ml-1 text-sm">0</span>
              </button>
            </div>

            {/* Tab Content */}
            <div className="prose max-w-none">
              {activeTab === 'detail' && (
                <div className="text-gray-700 leading-relaxed">
                  <div dangerouslySetInnerHTML={{ __html: campaign.description || '' }} />
                </div>
              )}
              {activeTab === 'update' && (
                <div className="text-gray-600">
                  <p>Belum ada update untuk campaign ini.</p>
                </div>
              )}
              {activeTab === 'donatur' && (
                <div className="text-gray-600">
                  <p>Belum ada donatur untuk campaign ini.</p>
                </div>
              )}
            </div>
          </div>
        </div>
      </section>

      {/* Related Programs */}
      <section className="py-16 bg-gray-50">
        <div className="container mx-auto px-4">
          <div className="flex justify-between items-center mb-8">
            <h2 className="text-2xl md:text-3xl font-bold text-gray-900">
              Program Lainnya
            </h2>
            <Link href="/program" className="text-brand-600 hover:text-brand-700 font-medium flex items-center">
              Selanjutnya
              <svg className="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
              </svg>
            </Link>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {relatedCampaigns.map((relatedCampaign) => (
              <Link
                key={relatedCampaign.id}
                href={`/program/${relatedCampaign.id}`}
                className="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100 group"
              >
                <div className="relative h-48 bg-gray-200">
                  {getImageUrl(relatedCampaign) ? (
                    <img
                      src={getImageUrl(relatedCampaign)}
                      alt={relatedCampaign.name}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                  ) : (
                    <div className="flex items-center justify-center h-full text-gray-400">
                      <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  )}
                  <div className="absolute top-4 right-4 bg-brand-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                    {relatedCampaign.category?.name || 'Infak'}
                  </div>
                </div>
                
                <div className="p-5">
                  <h3 className="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-brand-600 transition-colors">
                    {relatedCampaign.name}
                  </h3>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>

      <LandingFooter />

      {/* Floating WhatsApp Button */}
      <a
        href="https://wa.me/6281292674384"
        target="_blank"
        rel="noopener noreferrer"
        className="fixed bottom-6 right-6 bg-[#25D366] text-white p-4 rounded-full shadow-lg hover:bg-[#20bd5a] transition-colors z-50"
        aria-label="Chat on WhatsApp"
      >
        <svg className="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
          <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
        </svg>
      </a>
    </main>
  )
}
