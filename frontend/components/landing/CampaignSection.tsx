'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import { Campaign } from '@/types'

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
    <section className="py-16 bg-gray-50" id="program">
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
          <div className="flex justify-center py-12">
            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-600"></div>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {campaigns.map((campaign) => (
              <div key={campaign.id} className="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100">
                <div className="relative h-48 bg-gray-200">
                  {campaign.image && campaign.image.length > 0 ? (
                    <img
                      src={`${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/campaign_pictures/${campaign.image[0].picture_path}`}
                      alt={campaign.name}
                      className="w-full h-full object-cover"
                    />
                  ) : (
                    <div className="flex items-center justify-center h-full text-gray-400">
                      <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  )}
                  <div className="absolute top-4 right-4 bg-brand-500 text-white p-2 rounded-full">
                     {/* Icon placeholder */}
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
                    href={`/campaign/${campaign.id}`}
                    className="inline-block bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-700 transition-colors"
                  >
                    Detail Program
                  </Link>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </section>
  )
}
