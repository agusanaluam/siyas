import { useState, useCallback } from 'react'
import { campaignService, campaignCategoryService } from '@/lib/api/campaign'
import { Campaign, CampaignCategory, PaginatedResponse } from '@/types'

export const useCampaigns = () => {
  const [data, setData] = useState<PaginatedResponse<Campaign> | null>(null)
  const [categories, setCategories] = useState<CampaignCategory[]>([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const fetchCampaigns = useCallback(async (params?: { 
    status?: number; 
    category_id?: number | null; 
    page?: number; 
    per_page?: number 
  }) => {
    try {
      setLoading(true)
      const response = await campaignService.getAll(params)
      setData(response)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil data campaign')
      console.error(err)
    } finally {
      setLoading(false)
    }
  }, [])

  const fetchCategories = useCallback(async () => {
    try {
      const response = await campaignCategoryService.getAll()
      setCategories(response)
    } catch (err) {
      console.error('Error fetching categories:', err)
    }
  }, [])

  return {
    data,
    categories,
    loading,
    error,
    fetchCampaigns,
    fetchCategories
  }
}

export const useCampaign = () => {
  const [campaign, setCampaign] = useState<Campaign | null>(null)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const fetchCampaign = useCallback(async (id: number) => {
    try {
      setLoading(true)
      const response = await campaignService.getById(id)
      setCampaign(response)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil detail campaign')
      console.error(err)
    } finally {
      setLoading(false)
    }
  }, [])

  return {
    campaign,
    loading,
    error,
    fetchCampaign
  }
}
