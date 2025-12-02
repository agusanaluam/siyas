'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { campaignService } from '@/lib/api/campaign'
import { Campaign } from '@/types'

export default function CampaignListPage() {
  const router = useRouter()
  const { user, loading, isAuthenticated } = useAuth()
  const [campaigns, setCampaigns] = useState<Campaign[]>([])
  const [dataLoading, setDataLoading] = useState(true)
  const [status, setStatus] = useState<number>(0)

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated) {
      fetchCampaigns()
    }
  }, [isAuthenticated, status])

  const fetchCampaigns = async () => {
    try {
      setDataLoading(true)
      const data = await campaignService.getAll(status || undefined)
      setCampaigns(data)
    } catch (error) {
      console.error('Error fetching campaigns:', error)
    } finally {
      setDataLoading(false)
    }
  }

  const handleDelete = async (id: number) => {
    if (!confirm('Apakah Anda yakin ingin menghapus campaign ini?')) {
      return
    }

    try {
      await campaignService.delete(id)
      fetchCampaigns()
    } catch (error) {
      console.error('Error deleting campaign:', error)
      alert('Gagal menghapus campaign')
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
      month: 'short',
      year: 'numeric',
    })
  }

  const getProgress = (campaign: Campaign) => {
    if (campaign.target_amount === 0) return 0
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

  if (!isAuthenticated) {
    return null
  }

  return (
    <DashboardLayout>
      <div className="mb-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Campaign</h1>
            <p className="mt-1 text-sm text-gray-600">Kelola campaign Anda</p>
          </div>
          {(user?.level === 'administrator' || user?.level === 'root') && (
            <Link
              href="/campaign/create"
              className="btn btn-primary"
            >
              Tambah Campaign Baru
            </Link>
          )}
        </div>
      </div>

      <div className="mb-4 flex space-x-2">
        <button
          onClick={() => setStatus(0)}
          className={`px-4 py-2 rounded-lg text-sm font-medium ${
            status === 0
              ? 'bg-primary-600 text-white'
              : 'bg-white text-gray-700 hover:bg-gray-50'
          }`}
        >
          Semua
        </button>
        <button
          onClick={() => setStatus(1)}
          className={`px-4 py-2 rounded-lg text-sm font-medium ${
            status === 1
              ? 'bg-primary-600 text-white'
              : 'bg-white text-gray-700 hover:bg-gray-50'
          }`}
        >
          Pending
        </button>
        <button
          onClick={() => setStatus(2)}
          className={`px-4 py-2 rounded-lg text-sm font-medium ${
            status === 2
              ? 'bg-primary-600 text-white'
              : 'bg-white text-gray-700 hover:bg-gray-50'
          }`}
        >
          Running
        </button>
        <button
          onClick={() => setStatus(3)}
          className={`px-4 py-2 rounded-lg text-sm font-medium ${
            status === 3
              ? 'bg-primary-600 text-white'
              : 'bg-white text-gray-700 hover:bg-gray-50'
          }`}
        >
          Closed
        </button>
      </div>

      <div className="bg-white rounded-lg shadow overflow-hidden">
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-gray-200">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Campaign
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  PIC
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Start Date
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  End Date
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Target Amount
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Progress
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                {(user?.level === 'administrator' || user?.level === 'root') && (
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Action
                  </th>
                )}
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-gray-200">
              {campaigns.length === 0 ? (
                <tr>
                  <td colSpan={8} className="px-6 py-4 text-center text-gray-500">
                    Tidak ada campaign
                  </td>
                </tr>
              ) : (
                campaigns.map((campaign) => {
                  const progress = getProgress(campaign)
                  const statusInfo = getStatusLabel(campaign.status)
                  const imageUrl = campaign.images && campaign.images.length > 0
                    ? `http://localhost:8000/storage/campaign_pictures/${campaign.images[0].picture_path}`
                    : '/placeholder-image.jpg'

                  return (
                    <tr key={campaign.id} className="hover:bg-gray-50">
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex items-center">
                          <div className="flex-shrink-0 h-10 w-10">
                            <img
                              className="h-10 w-10 rounded-full object-cover"
                              src={imageUrl}
                              alt={campaign.name}
                            />
                          </div>
                          <div className="ml-4">
                            <Link
                              href={`/campaign/${campaign.id}`}
                              className="text-sm font-medium text-gray-900 hover:text-primary-600"
                            >
                              {campaign.name}
                            </Link>
                            {campaign.category && (
                              <div className="text-sm text-gray-500">{campaign.category.name}</div>
                            )}
                          </div>
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {campaign.pic || '-'}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {formatDate(campaign.start_date)}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {formatDate(campaign.end_date)}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {formatCurrency(campaign.target_amount)}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex items-center">
                          <div className="w-full bg-gray-200 rounded-full h-2 mr-2">
                            <div
                              className={`h-2 rounded-full ${
                                progress >= 100 ? 'bg-green-600' : 'bg-primary-600'
                              }`}
                              style={{ width: `${progress}%` }}
                            ></div>
                          </div>
                          <span className="text-sm text-gray-600">{progress.toFixed(0)}%</span>
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <span
                          className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-${statusInfo.color}-100 text-${statusInfo.color}-800`}
                        >
                          {statusInfo.label}
                        </span>
                      </td>
                      {(user?.level === 'administrator' || user?.level === 'root') && (
                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                          <div className="flex space-x-2">
                            <Link
                              href={`/campaign/${campaign.id}/edit`}
                              className="text-primary-600 hover:text-primary-900"
                            >
                              Edit
                            </Link>
                            <button
                              onClick={() => handleDelete(campaign.id)}
                              className="text-red-600 hover:text-red-900"
                            >
                              Hapus
                            </button>
                          </div>
                        </td>
                      )}
                    </tr>
                  )
                })
              )}
            </tbody>
          </table>
        </div>
      </div>
    </DashboardLayout>
  )
}

