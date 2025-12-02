'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import LandingHeader from '@/components/landing/LandingHeader'
import LandingFooter from '@/components/landing/LandingFooter'
import { Campaign } from '@/types'

interface Category {
  id: number
  name: string
}

export default function ProgramPage() {
  const [campaigns, setCampaigns] = useState<Campaign[]>([])
  const [categories, setCategories] = useState<Category[]>([])
  const [selectedCategory, setSelectedCategory] = useState<number | null>(null)
  const [showCategoryDropdown, setShowCategoryDropdown] = useState(false)
  const [loading, setLoading] = useState(true)
  const [currentPage, setCurrentPage] = useState(1)
  const [totalPages, setTotalPages] = useState(1)
  const [totalCampaigns, setTotalCampaigns] = useState(0)
  const perPage = 12

  useEffect(() => {
    fetchCategories()
  }, [])

  useEffect(() => {
    fetchCampaigns(currentPage)
  }, [currentPage, selectedCategory])

  const fetchCategories = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      console.log('Fetching categories from:', `${apiUrl}/campaign-categories`)
      const response = await fetch(`${apiUrl}/campaign-categories`)
      console.log('Categories response status:', response.status)
      const data = await response.json()
      console.log('Categories data:', data)
      setCategories(Array.isArray(data) ? data : data.data || [])
    } catch (error) {
      console.error('Error fetching categories:', error)
    }
  }

  const fetchCampaigns = async (page: number) => {
    try {
      setLoading(true)
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const categoryParam = selectedCategory ? `&category_id=${selectedCategory}` : ''
      const response = await fetch(`${apiUrl}/campaigns?page=${page}&per_page=${perPage}${categoryParam}`)
      const result = await response.json()
      
      if (result.data) {
        setCampaigns(result.data)
        setTotalPages(result.last_page || 1)
        setTotalCampaigns(result.total || 0)
      } else {
        setCampaigns(Array.isArray(result) ? result : [])
      }
    } catch (error) {
      console.error('Error fetching campaigns:', error)
    } finally {
      setLoading(false)
    }
  }

  const handleCategorySelect = (categoryId: number | null) => {
    setSelectedCategory(categoryId)
    setCurrentPage(1) // Reset to first page when category changes
    setShowCategoryDropdown(false)
  }

  const getImageUrl = (campaign: Campaign) => {
    if (campaign.image && campaign.image.length > 0) {
      const imagePath = campaign.image[0].picture_path
      if (imagePath.startsWith('http')) return imagePath
      return `${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/campaign_pictures/${imagePath}`
    }
    return ''
  }

  const renderPagination = () => {
    const pages = []
    const maxVisible = 5
    let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2))
    let endPage = Math.min(totalPages, startPage + maxVisible - 1)

    if (endPage - startPage < maxVisible - 1) {
      startPage = Math.max(1, endPage - maxVisible + 1)
    }

    for (let i = startPage; i <= endPage; i++) {
      pages.push(
        <button
          key={i}
          onClick={() => setCurrentPage(i)}
          className={`w-10 h-10 rounded-full font-medium transition-colors ${
            i === currentPage
              ? 'bg-brand-600 text-white'
              : 'bg-white text-gray-700 hover:bg-brand-50 border border-gray-300'
          }`}
        >
          {i}
        </button>
      )
    }

    return pages
  }

  return (
    <main className="min-h-screen bg-gray-50">
      <LandingHeader forceScrolledStyle={true} />
      
      {/* Hero Section */}
      <section className="pt-32 pb-12 bg-white">
        <div className="container mx-auto px-4">
          <div className="flex justify-between items-end mb-8">
            <div>
              <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-2">
                Program
              </h1>
              <p className="text-gray-600">
                Menampilkan {((currentPage - 1) * perPage) + 1} - {Math.min(currentPage * perPage, totalCampaigns)} dari {totalCampaigns} Campaign
              </p>
            </div>
            <div className="hidden md:block relative">
              <button 
                onClick={() => setShowCategoryDropdown(!showCategoryDropdown)}
                className="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center space-x-2"
              >
                <span>
                  {selectedCategory 
                    ? categories.find(c => c.id === selectedCategory)?.name 
                    : 'Pilih kategori'}
                </span>
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              
              {/* Dropdown Menu */}
              {showCategoryDropdown && (
                <div className="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                  <div className="py-2">
                    <button
                      onClick={() => handleCategorySelect(null)}
                      className={`w-full text-left px-4 py-2 text-sm hover:bg-brand-50 transition-colors ${
                        selectedCategory === null ? 'text-brand-600 font-medium' : 'text-gray-700'
                      }`}
                    >
                      Semua Kategori
                    </button>
                    {categories.map((category) => (
                      <button
                        key={category.id}
                        onClick={() => handleCategorySelect(category.id)}
                        className={`w-full text-left px-4 py-2 text-sm hover:bg-brand-50 transition-colors ${
                          selectedCategory === category.id ? 'text-brand-600 font-medium' : 'text-gray-700'
                        }`}
                      >
                        {category.name}
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>
          </div>

          {/* Campaign Grid */}
          {loading ? (
            <div className="flex justify-center py-20">
              <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-600"></div>
            </div>
          ) : (
            <>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                {campaigns.map((campaign) => (
                  <Link 
                    key={campaign.id} 
                    href={`/program/${campaign.id}`}
                    className="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100 group"
                  >
                    <div className="relative h-56 bg-gray-200">
                      {getImageUrl(campaign) ? (
                        <img
                          src={getImageUrl(campaign)}
                          alt={campaign.name}
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
                        {campaign.category?.name || 'Infak'}
                      </div>
                    </div>
                    
                    <div className="p-5">
                      <h3 className="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-brand-600 transition-colors">
                        {campaign.name}
                      </h3>
                      <p className="text-sm text-gray-600 line-clamp-2 mb-4">
                        {campaign.description?.replace(/<[^>]*>/g, '') || 'Tidak ada deskripsi'}
                      </p>
                    </div>
                  </Link>
                ))}
              </div>

              {/* Pagination */}
              {totalPages > 1 && (
                <div className="flex justify-center items-center space-x-2">
                  <button
                    onClick={() => setCurrentPage(Math.max(1, currentPage - 1))}
                    disabled={currentPage === 1}
                    className="w-10 h-10 rounded-full bg-white border border-gray-300 text-gray-700 hover:bg-brand-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  >
                    <svg className="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                    </svg>
                  </button>
                  
                  {renderPagination()}
                  
                  <button
                    onClick={() => setCurrentPage(Math.min(totalPages, currentPage + 1))}
                    disabled={currentPage === totalPages}
                    className="w-10 h-10 rounded-full bg-white border border-gray-300 text-gray-700 hover:bg-brand-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  >
                    <svg className="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                    </svg>
                  </button>
                </div>
              )}
            </>
          )}
        </div>
      </section>

      <LandingFooter />
    </main>
  )
}
