'use client'

import { useEffect, useState } from 'react'
import { useRouter, useParams } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { donationService } from '@/lib/api/donation'
import { Donation } from '@/types'

export default function DonationDetailsPage() {
  const router = useRouter()
  const params = useParams()
  const { user, loading, isAuthenticated } = useAuth()
  const [donation, setDonation] = useState<Donation | null>(null)
  const [dataLoading, setDataLoading] = useState(true)

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated && params.id) {
      fetchDonation()
    }
  }, [isAuthenticated, params.id])

  const fetchDonation = async () => {
    try {
      setDataLoading(true)
      const data = await donationService.getById(Number(params.id))
      setDonation(data)
    } catch (error) {
      console.error('Error fetching donation:', error)
      router.push('/donation')
    } finally {
      setDataLoading(false)
    }
  }

  const handleApprove = async () => {
    if (!confirm('Apakah Anda yakin ingin menyetujui donation ini?')) {
      return
    }

    try {
      await donationService.approve(Number(params.id))
      alert('Donation berhasil disetujui')
      fetchDonation()
    } catch (error: any) {
      alert(error.response?.data?.message || 'Gagal menyetujui donation')
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

  if (!isAuthenticated || !donation) {
    return null
 }

  return (
    <DashboardLayout>
      <div className="mb-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Detail Donation</h1>
            <p className="mt-1 text-sm text-gray-600">Detail lengkap donation</p>
          </div>
          <div className="flex space-x-2">
            {(user?.level === 'administrator' || user?.level === 'root') && donation.status !== 'Completed' && (
              <button onClick={handleApprove} className="btn btn-primary">
                Approve Donation
              </button>
            )}
            <Link href="/donation" className="btn btn-secondary">
              Kembali ke List
            </Link>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2">
          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Informasi Donation</h2>
            <dl className="space-y-4">
              <div>
                <dt className="text-sm font-medium text-gray-500">LIQ Number</dt>
                <dd className="mt-1 text-sm text-gray-900">{donation.liq_number}</dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Nama Donatur</dt>
                <dd className="mt-1 text-sm text-gray-900">{donation.donatur_name}</dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                <dd className="mt-1 text-sm text-gray-900">{donation.donatur_phone}</dd>
              </div>
              {donation.donatur_address && (
                <div>
                  <dt className="text-sm font-medium text-gray-500">Alamat</dt>
                  <dd className="mt-1 text-sm text-gray-900">{donation.donatur_address}</dd>
                </div>
              )}
              <div>
                <dt className="text-sm font-medium text-gray-500">Tanggal Transaksi</dt>
                <dd className="mt-1 text-sm text-gray-900">{formatDate(donation.trans_date)}</dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Metode Pembayaran</dt>
                <dd className="mt-1">
                  <span
                    className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                      donation.via_transfer
                        ? 'bg-blue-100 text-blue-800'
                        : 'bg-gray-100 text-gray-800'
                    }`}
                  >
                    {donation.via_transfer ? 'Transfer' : 'Cash'}
                  </span>
                </dd>
              </div>
              {donation.via_transfer && donation.reference_code && (
                <div>
                  <dt className="text-sm font-medium text-gray-500">Reference Code</dt>
                  <dd className="mt-1 text-sm text-gray-900">{donation.reference_code}</dd>
                </div>
              )}
              <div>
                <dt className="text-sm font-medium text-gray-500">Total Amount</dt>
                <dd className="mt-1 text-lg font-bold text-primary-600">
                  {formatCurrency(donation.total_amount)}
                </dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Status</dt>
                <dd className="mt-1">
                  <span
                    className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                      donation.status === 'Completed'
                        ? 'bg-green-100 text-green-800'
                        : donation.status === 'Approved'
                        ? 'bg-blue-100 text-blue-800'
                        : 'bg-yellow-100 text-yellow-800'
                    }`}
                  >
                    {donation.status}
                  </span>
                </dd>
              </div>
              {donation.description && (
                <div>
                  <dt className="text-sm font-medium text-gray-500">Deskripsi</dt>
                  <dd className="mt-1 text-sm text-gray-900 whitespace-pre-wrap">
                    {donation.description}
                  </dd>
                </div>
              )}
            </dl>
          </div>

          {donation.detail && donation.detail.length > 0 && (
            <div className="mt-6 bg-white rounded-lg shadow p-6">
              <h2 className="text-xl font-semibold text-gray-900 mb-4">Detail Campaign</h2>
              <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-200">
                  <thead className="bg-gray-50">
                    <tr>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Campaign
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Amount
                      </th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {donation.detail.map((detail) => (
                      <tr key={detail.id}>
                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          {detail.campaign?.name || '-'}
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          {formatCurrency(detail.amount)}
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          )}
        </div>

        <div>
          {donation.via_transfer && donation.reference_picture && (
            <div className="bg-white rounded-lg shadow p-6">
              <h2 className="text-xl font-semibold text-gray-900 mb-4">Bukti Transfer</h2>
              <img
                src={`http://localhost:8000/storage/${donation.reference_picture}`}
                alt="Bukti Transfer"
                className="w-full rounded-lg"
              />
            </div>
          )}
        </div>
      </div>
    </DashboardLayout>
  )
}

