'use client'

import { useEffect, useState } from 'react'
import { useRouter, useParams } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { campaignService } from '@/lib/api/campaign'
import { Campaign } from '@/types'

export default function CampaignDetailsPage() {
  const router = useRouter()
  const params = useParams()
  const { user, loading, isAuthenticated } = useAuth()
  const [campaign, setCampaign] = useState<Campaign | null>(null)
  const [dataLoading, setDataLoading] = useState(true)

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated && params.id) {
      fetchCampaign()
    }
  }, [isAuthenticated, params.id])

  const fetchCampaign = async () => {
    try {
      setDataLoading(true)
      const data = await campaignService.getById(Number(params.id))
      setCampaign(data)
    } catch (error) {
      console.error('Error fetching campaign:', error)
      router.push('/campaign')
    } finally {
      setDataLoading(false)
    }
  }

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
    }).format(amount)
  }

  const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
    })
  }

  const getProgress = () => {
    if (!campaign || campaign.target_amount === 0) return 0
    return Math.min((campaign.total_amount / campaign.target_amount) * 100, 100)
  }

  const getStatusLabel = (status: number) => {
    switch (status) {
      case 1:
        return { label: 'Pending', color: 'yellow' }
      case 2:
        return { label: 'Running', color: 'green' }
      case 3:
        return { label: 'Closed', color: 'gray' }
      default:
        return { label: 'Unknown', color: 'gray' }
    }
  }

  if (loading || dataLoading) {
    return (
      <DashboardLayout>
        <div className="flex items-center justify-center h-64">
          <div className="text-center">
            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
            <p className="mt-4 text-gray-600">Memuat...</p>
          </div>
        </div>
      </DashboardLayout>
    )
  }

  if (!isAuthenticated || !campaign) {
    return null
  }

  const progress = getProgress()
  const statusInfo = getStatusLabel(campaign.status)

  return (
    <DashboardLayout>
      <div className="mb-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Detail Campaign</h1>
            <p className="mt-1 text-sm text-gray-600">Detail lengkap campaign</p>
          </div>
          <div className="flex space-x-2">
            {(user?.level === 'administrator' || user?.level === 'root') && (
              <Link href={`/campaign/${campaign.id}/edit`} className="btn btn-primary">
                Edit Campaign
              </Link>
            )}
            <Link href="/campaign" className="btn btn-secondary">
              Kembali ke List
            </Link>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2">
          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Informasi Campaign</h2>
            <dl className="space-y-4">
              <div>
                <dt className="text-sm font-medium text-gray-500">Nama Campaign</dt>
                <dd className="mt-1 text-sm text-gray-900">{campaign.name}</dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Kategori</dt>
                <dd className="mt-1 text-sm text-gray-900">
                  {campaign.category?.name || '-'}
                </dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">PIC</dt>
                <dd className="mt-1 text-sm text-gray-900">{campaign.pic || '-'}</dd>
              </div>
              {(user?.level !== 'volunteer') && (
                <>
                  <div>
                    <dt className="text-sm font-medium text-gray-500">Target Amount</dt>
                    <dd className="mt-1 text-sm text-gray-900">
                      {formatCurrency(campaign.target_amount)}
                    </dd>
                  </div>
                  <div>
                    <dt className="text-sm font-medium text-gray-500">Target Object</dt>
                    <dd className="mt-1 text-sm text-gray-900">
                      {campaign.target_object || '-'}
                    </dd>
                  </div>
                  <div>
                    <dt className="text-sm font-medium text-gray-500">Progress Amount</dt>
                    <dd className="mt-1 text-sm text-gray-900">
                      {formatCurrency(campaign.total_amount)} ({progress.toFixed(2)}%)
                    </dd>
                  </div>
                </>
              )}
              <div>
                <dt className="text-sm font-medium text-gray-500">Start - End Date</dt>
                <dd className="mt-1 text-sm text-gray-900">
                  {formatDate(campaign.start_date)} - {formatDate(campaign.end_date)}
                </dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Status</dt>
                <dd className="mt-1">
                  <span
                    className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-${statusInfo.color}-100 text-${statusInfo.color}-800`}
                  >
                    {statusInfo.label}
                  </span>
                </dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Deskripsi</dt>
                <dd className="mt-1 text-sm text-gray-900 whitespace-pre-wrap">
                  {campaign.description || '-'}
                </dd>
              </div>
            </dl>
          </div>
        </div>

        <div>
          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Gambar Campaign</h2>
            {campaign.images && campaign.images.length > 0 ? (
              <div className="space-y-4">
                {campaign.images.map((image, index) => (
                  <div key={index}>
                    <img
                      src={`http://localhost:8000/storage/campaign_pictures/${image.picture_path}`}
                      alt={`Campaign image ${index + 1}`}
                      className="w-full h-48 object-cover rounded-lg"
                    />
                  </div>
                ))}
              </div>
            ) : (
              <p className="text-sm text-gray-500">Tidak ada gambar</p>
            )}
          </div>
        </div>
      </div>
    </DashboardLayout>
  )
}

