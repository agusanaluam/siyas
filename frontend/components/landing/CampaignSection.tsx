'use client'

import { useState, useEffect } from 'react'

import Link from 'next/link'
import { Campaign } from '@/types'
import { getImageUrl } from '@/lib/utils'

interface CampaignCardProps {
  campaign: Campaign
  formatDate: (dateString: string) => string
}

function CampaignCard({ campaign, formatDate }: CampaignCardProps) {
  const [imageLoaded, setImageLoaded] = useState(false)
  const imagePath = campaign.image && campaign.image.length > 0 ? campaign.image[0].picture_path : null
  const imageUrl = imagePath ? getImageUrl(imagePath) : null

  return (
    <div className="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100">
      <div className="relative h-48 bg-gray-200">
        {imageUrl ? (
          <>
            {!imageLoaded && (
              <div className="absolute inset-0 flex items-center justify-center">
                <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-400"></div>
              </div>
            )}
            <img
              src={imageUrl}
              alt={campaign.name}
              className={`w-full h-full object-cover transition-opacity duration-300 ${imageLoaded ? 'opacity-100' : 'opacity-0'}`}
              onLoad={() => setImageLoaded(true)}
              onError={() => setImageLoaded(true)}
            />
          </>
        ) : (
          <div className="flex items-center justify-center h-full text-gray-400">
            <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        )}
        <div className="absolute top-4 right-4 bg-[rgb(246,90,141)] text-white p-2 rounded-full">
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
        </div>
      </div>
      
      <div className="p-6">
        <h3 className="text-xl font-bold text-gray-900 mb-2 line-clamp-1">
          {campaign.name}
        </h3>
        <p className="text-sm text-gray-500 mb-3">
          {formatDate(campaign.start_date)}
        </p>
        <p className="text-gray-600 mb-4 line-clamp-2 text-sm">
          {campaign.description?.replace(/<[^>]*>/g, '') || 'Tidak ada deskripsi'}
        </p>
        <Link 
          href={`/program/${campaign.id}`}
          className="inline-block bg-accent-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-accent-600 transition-colors"
        >
          Detail Program
        </Link>
      </div>
    </div>
  )
}

export default function CampaignSection() {
  const [campaigns, setCampaigns] = useState<Campaign[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetchCampaigns()
  }, [])

  const fetchCampaigns = async () => {
    try {
      // Assuming the API supports pagination or limit, otherwise we slice the result
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/campaigns`)
      const result = await response.json()
      // Adjust based on actual API response structure
      const data = result.data || result
      setCampaigns(Array.isArray(data) ? data.slice(0, 6) : [])
    } catch (error) {
      console.error('Error fetching campaigns:', error)
    } finally {
      setLoading(false)
    }
  }

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    })
  }

  return (
    <section className="py-16 bg-gradient-to-b from-brand-50 via-white to-accent-50/40" id="program">
      <div className="container mx-auto px-4">
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Mau Berbuat Baik Apa Hari Ini?
          </h2>
          <p className="text-gray-600 max-w-2xl mx-auto">
            Bantuan sekecil apapun akan sangat berdampak bagi mereka yang membutuhkan
          </p>
        </div>

        {loading ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {[1, 2, 3, 4, 5, 6].map((i) => (
              <div key={i} className="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div className="h-48 bg-gray-300 animate-pulse"></div>
                <div className="p-6 space-y-4">
                  <div className="h-6 bg-gray-300 rounded animate-pulse"></div>
                  <div className="h-4 bg-gray-300 rounded w-1/2 animate-pulse"></div>
                  <div className="space-y-2">
                    <div className="h-4 bg-gray-300 rounded animate-pulse"></div>
                    <div className="h-4 bg-gray-300 rounded w-5/6 animate-pulse"></div>
                  </div>
                  <div className="h-10 bg-gray-300 rounded w-32 animate-pulse"></div>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {campaigns.map((campaign) => (
              <CampaignCard key={campaign.id} campaign={campaign} formatDate={formatDate} />
            ))}
          </div>
        )}
      </div>
    </section>
  )
}
