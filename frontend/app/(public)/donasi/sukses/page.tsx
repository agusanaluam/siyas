'use client'

import { useState, useEffect, Suspense } from 'react'
import { useSearchParams, useRouter } from 'next/navigation'
import Link from 'next/link'
import Image from 'next/image'
import LandingHeader from '@/components/landing/LandingHeader'
import LandingFooter from '@/components/landing/LandingFooter'
import FloatingWhatsApp from '@/components/FloatingWhatsApp'
import { Donation } from '@/types'

function DonationSuccessContent() {
  const searchParams = useSearchParams()
  const router = useRouter()
  const [donation, setDonation] = useState<Donation | null>(null)
  const [loading, setLoading] = useState(true)
  const orderId = searchParams.get('order_id')

  useEffect(() => {
    if (orderId) {
      fetchDonation(orderId)
    } else {
      setLoading(false)
    }
  }, [orderId])

  const fetchDonation = async (liqNumber: string) => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/donations/public/${liqNumber}`)

      if (response.ok) {
        const data = await response.json()
        setDonation(data)
      }
    } catch (error) {
      console.error('Error fetching donation:', error)
    } finally {
      setLoading(false)
    }
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
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    })
  }

  const getStatusBadge = (status: string) => {
    const statusMap: Record<string, { text: string; color: string }> = {
      'paid': { text: 'Berhasil', color: 'bg-green-100 text-green-800' },
      'pending': { text: 'Menunggu Pembayaran', color: 'bg-yellow-100 text-yellow-800' },
      'failed': { text: 'Gagal', color: 'bg-red-100 text-red-800' },
    }

    const statusInfo = statusMap[status] || statusMap['pending']
    return (
      <span className={`px-3 py-1 rounded-full text-sm font-medium ${statusInfo.color}`}>
        {statusInfo.text}
      </span>
    )
  }

  if (loading) {
    return (
      <main className="min-h-screen bg-gray-50">
        <LandingHeader forceScrolledStyle={true} />
        <div className="flex justify-center items-center py-32">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-600"></div>
        </div>
        <LandingFooter />
      </main>
    )
  }

  return (
    <main className="min-h-screen bg-gray-50">
      <LandingHeader forceScrolledStyle={true} />

      <section className="pt-24 pb-16">
        <div className="container mx-auto px-4 md:px-[150px]">
          <div className="max-w-2xl mx-auto">
            {/* Success Icon */}
            <div className="text-center mb-8">
              <div className="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                <svg className="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <h1 className="text-3xl font-bold text-gray-900 mb-2">
                Terima Kasih Atas Donasi Anda
              </h1>
              <p className="text-gray-600">
                Donasi Anda telah berhasil direkam
              </p>
            </div>

            {/* Donation Card */}
            {donation ? (
              <div className="bg-white rounded-xl shadow-lg p-6 md:p-8 mb-6">
                <div className="flex items-center justify-between mb-6">
                  <h2 className="text-xl font-bold text-gray-900">Detail Donasi</h2>
                  {donation.payment_status && getStatusBadge(donation.payment_status)}
                </div>

                <div className="space-y-4">
                  <div className="flex justify-between items-center py-3 border-b border-gray-100">
                    <span className="text-gray-600">Nomor Transaksi</span>
                    <span className="font-semibold text-gray-900">{donation.liq_number}</span>
                  </div>

                  <div className="flex justify-between items-center py-3 border-b border-gray-100">
                    <span className="text-gray-600">Nama Donatur</span>
                    <span className="font-semibold text-gray-900">{donation.donatur_name}</span>
                  </div>

                  <div className="flex justify-between items-center py-3 border-b border-gray-100">
                    <span className="text-gray-600">Jumlah Donasi</span>
                    <span className="font-bold text-xl text-brand-600">
                      {formatCurrency(donation.total_amount)}
                    </span>
                  </div>

                  <div className="flex justify-between items-center py-3 border-b border-gray-100">
                    <span className="text-gray-600">Tanggal</span>
                    <span className="font-semibold text-gray-900">
                      {formatDate(donation.trans_date)}
                    </span>
                  </div>

                  {donation.description && (
                    <div className="py-3 border-b border-gray-100">
                      <span className="text-gray-600 block mb-2">Doa/Dukungan</span>
                      <p className="text-gray-900 italic">{donation.description}</p>
                    </div>
                  )}

                  {donation.detail && donation.detail.length > 0 && (
                    <div className="py-3">
                      <span className="text-gray-600 block mb-2">Program Donasi</span>
                      {donation.detail.map((detail, index) => (
                        <div key={index} className="flex justify-between items-center mb-2">
                          <span className="text-gray-900">
                            {detail.campaign?.name || 'Program Donasi'}
                          </span>
                          <span className="font-semibold text-gray-900">
                            {formatCurrency(detail.amount)}
                          </span>
                        </div>
                      ))}
                    </div>
                  )}
                </div>

                {donation.payment_status === 'pending' && (
                  <div className="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p className="text-sm text-yellow-800">
                      <strong>Pembayaran sedang menunggu konfirmasi.</strong> Silakan selesaikan pembayaran melalui link yang telah dikirimkan.
                    </p>
                  </div>
                )}

                {donation.payment_status === 'paid' && (
                  <div className="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p className="text-sm text-green-800">
                      <strong>Pembayaran berhasil!</strong> Terima kasih atas donasi Anda. Kami akan menggunakan donasi ini untuk membantu program yang sedang berjalan.
                    </p>
                  </div>
                )}

                {donation.payment_status === 'failed' && (
                  <div className="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p className="text-sm text-red-800">
                      <strong>Pembayaran gagal.</strong> Silakan coba lagi atau hubungi kami jika masalah berlanjut.
                    </p>
                  </div>
                )}
              </div>
            ) : (
              <div className="bg-white rounded-xl shadow-lg p-6 md:p-8 text-center">
                <p className="text-gray-600 mb-4">
                  {orderId ? 'Data donasi tidak ditemukan' : 'Parameter order_id tidak ditemukan'}
                </p>
                <Link
                  href="/program"
                  className="inline-block px-6 py-3 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors"
                >
                  Kembali ke Program Donasi
                </Link>
              </div>
            )}

            {/* Action Buttons */}
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link
                href="/program"
                className="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center"
              >
                Lihat Program Lainnya
              </Link>
              <Link
                href="/"
                className="px-6 py-3 bg-[rgb(246,90,141)] text-white rounded-lg hover:bg-accent-600 transition-colors text-center"
              >
                Kembali ke Beranda
              </Link>
            </div>
          </div>
        </div>
      </section>

      <LandingFooter />
      <FloatingWhatsApp />
    </main>
  )
}

export default function DonationSuccessPage() {
  return (
    <Suspense fallback={
      <main className="min-h-screen bg-gray-50">
        <LandingHeader forceScrolledStyle={true} />
        <div className="flex justify-center items-center py-32">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-600"></div>
        </div>
        <LandingFooter />
      </main>
    }>
      <DonationSuccessContent />
    </Suspense>
  )
}
