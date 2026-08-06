'use client'

import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { donationService } from '@/lib/api/donation'
import { campaignService } from '@/lib/api/campaign'
import { Campaign } from '@/types'

interface DonationItem {
  campaign_id: string
  amount: string
}

export default function CreateDonationPage() {
  const router = useRouter()
  const { user, loading, isAuthenticated } = useAuth()
  const [campaigns, setCampaigns] = useState<Campaign[]>([])
  const [formData, setFormData] = useState({
    liq_number: '',
    donatur_name: '',
    donatur_phone: '',
    donatur_address: '',
    payment_method: 'cash',
    trans_date: new Date().toISOString().split('T')[0],
    reference_code: '',
    description: '',
  })
  const [donationItems, setDonationItems] = useState<DonationItem[]>([
    { campaign_id: '', amount: '' },
  ])
  const [referencePicture, setReferencePicture] = useState<File | null>(null)
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [submitting, setSubmitting] = useState(false)

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated) {
      fetchCampaigns()
    }
  }, [isAuthenticated])

  const fetchCampaigns = async () => {
    try {
      const data = await campaignService.getRunning()
      setCampaigns(data)
    } catch (error) {
      console.error('Error fetching campaigns:', error)
    }
  }

  const addDonationItem = () => {
    setDonationItems([...donationItems, { campaign_id: '', amount: '' }])
  }

  const removeDonationItem = (index: number) => {
    if (donationItems.length > 1) {
      setDonationItems(donationItems.filter((_, i) => i !== index))
    }
  }

  const updateDonationItem = (index: number, field: keyof DonationItem, value: string) => {
    const updated = [...donationItems]
    updated[index] = { ...updated[index], [field]: value }
    setDonationItems(updated)
  }

  const calculateTotal = () => {
    return donationItems.reduce((sum, item) => {
      return sum + (parseFloat(item.amount) || 0)
    }, 0)
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})

    // Validation
    if (donationItems.some((item) => !item.campaign_id || !item.amount)) {
      setErrors({ general: 'Semua campaign dan amount harus diisi' })
      return
    }

    setSubmitting(true)

    try {
      const formDataToSend = new FormData()
      formDataToSend.append('liq_number', formData.liq_number)
      formDataToSend.append('donatur_name', formData.donatur_name)
      formDataToSend.append('donatur_phone', formData.donatur_phone)
      formDataToSend.append('donatur_address', formData.donatur_address)
      formDataToSend.append('payment_method', formData.payment_method)
      formDataToSend.append('trans_date', formData.trans_date)
      formDataToSend.append('reference_code', formData.reference_code)
      formDataToSend.append('description', formData.description)
      formDataToSend.append('total_amount', calculateTotal().toString())

      donationItems.forEach((item) => {
        formDataToSend.append('campaign_id[]', item.campaign_id)
        formDataToSend.append('amount[]', item.amount)
      })

      if (referencePicture) {
        formDataToSend.append('reference_picture', referencePicture)
      }

      await donationService.create(formDataToSend)
      router.push('/donation')
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Gagal membuat donation' })
      }
    } finally {
      setSubmitting(false)
    }
  }

  if (loading) {
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
            <h1 className="text-2xl font-bold text-gray-900">Buat Donation</h1>
            <p className="mt-1 text-sm text-gray-600">Buat donation baru</p>
          </div>
          <Link href="/donation" className="btn btn-secondary">
            Kembali
          </Link>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="bg-white rounded-lg shadow p-6">
        {errors.general && (
          <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            {errors.general}
          </div>
        )}

        <div className="space-y-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label className="label">LIQ Number *</label>
              <input
                type="text"
                className="input"
                value={formData.liq_number}
                onChange={(e) => setFormData({ ...formData, liq_number: e.target.value })}
                required
              />
              {errors.liq_number && (
                <p className="mt-1 text-sm text-red-600">{errors.liq_number}</p>
              )}
            </div>

            <div>
              <label className="label">Tanggal Transaksi *</label>
              <input
                type="date"
                className="input"
                value={formData.trans_date}
                onChange={(e) => setFormData({ ...formData, trans_date: e.target.value })}
                required
              />
              {errors.trans_date && (
                <p className="mt-1 text-sm text-red-600">{errors.trans_date}</p>
              )}
            </div>

            <div>
              <label className="label">Nama Donatur *</label>
              <input
                type="text"
                className="input"
                value={formData.donatur_name}
                onChange={(e) => setFormData({ ...formData, donatur_name: e.target.value })}
                required
              />
              {errors.donatur_name && (
                <p className="mt-1 text-sm text-red-600">{errors.donatur_name}</p>
              )}
            </div>

            <div>
              <label className="label">Nomor Telepon Donatur *</label>
              <input
                type="text"
                className="input"
                value={formData.donatur_phone}
                onChange={(e) => setFormData({ ...formData, donatur_phone: e.target.value })}
                required
              />
              {errors.donatur_phone && (
                <p className="mt-1 text-sm text-red-600">{errors.donatur_phone}</p>
              )}
            </div>

            <div>
              <label className="label">Alamat Donatur</label>
              <textarea
                className="input"
                rows={2}
                value={formData.donatur_address}
                onChange={(e) => setFormData({ ...formData, donatur_address: e.target.value })}
              />
            </div>

            <div>
              <label className="label">Metode Pembayaran *</label>
              <select
                className="input"
                value={formData.payment_method}
                onChange={(e) => setFormData({ ...formData, payment_method: e.target.value })}
                required
              >
                <option value="cash">Cash</option>
                <option value="transfer">Transfer</option>
              </select>
            </div>

            {formData.payment_method === 'transfer' && (
              <>
                <div>
                  <label className="label">Reference Code</label>
                  <input
                    type="text"
                    className="input"
                    value={formData.reference_code}
                    onChange={(e) => setFormData({ ...formData, reference_code: e.target.value })}
                  />
                </div>

                <div>
                  <label className="label">Bukti Transfer</label>
                  <input
                    type="file"
                    accept="image/*"
                    onChange={(e) => setReferencePicture(e.target.files?.[0] || null)}
                    className="input"
                  />
                  {referencePicture && (
                    <div className="mt-2">
                      <img
                        src={URL.createObjectURL(referencePicture)}
                        alt="Preview"
                        className="w-48 h-48 object-cover rounded-lg"
                      />
                    </div>
                  )}
                </div>
              </>
            )}
          </div>

          <div>
            <label className="label">Deskripsi</label>
            <textarea
              className="input"
              rows={3}
              value={formData.description}
              onChange={(e) => setFormData({ ...formData, description: e.target.value })}
            />
          </div>

          <div>
            <div className="flex justify-between items-center mb-4">
              <label className="label">Detail Donation *</label>
              <button
                type="button"
                onClick={addDonationItem}
                className="btn btn-secondary btn-sm"
              >
                Tambah Campaign
              </button>
            </div>

            <div className="space-y-4">
              {donationItems.map((item, index) => (
                <div key={index} className="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border rounded-lg">
                  <div>
                    <label className="label">Campaign *</label>
                    <select
                      className="input"
                      value={item.campaign_id}
                      onChange={(e) => updateDonationItem(index, 'campaign_id', e.target.value)}
                      required
                    >
                      <option value="">--Pilih Campaign--</option>
                      {campaigns.map((campaign) => (
                        <option key={campaign.id} value={campaign.id}>
                          {campaign.name}
                        </option>
                      ))}
                    </select>
                  </div>
                  <div>
                    <label className="label">Amount *</label>
                    <input
                      type="number"
                      className="input"
                      value={item.amount}
                      onChange={(e) => updateDonationItem(index, 'amount', e.target.value)}
                      required
                      min="1"
                    />
                  </div>
                  <div className="flex items-end">
                    {donationItems.length > 1 && (
                      <button
                        type="button"
                        onClick={() => removeDonationItem(index)}
                        className="btn btn-danger btn-sm w-full"
                      >
                        Hapus
                      </button>
                    )}
                  </div>
                </div>
              ))}
            </div>

            <div className="mt-4 p-4 bg-gray-50 rounded-lg">
              <div className="flex justify-between items-center">
                <span className="text-lg font-semibold">Total Amount:</span>
                <span className="text-lg font-bold text-primary-600">
                  {new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                  }).format(calculateTotal())}
                </span>
              </div>
            </div>
          </div>

          <div className="flex justify-end space-x-4">
            <Link href="/donation" className="btn btn-secondary">
              Batal
            </Link>
            <button type="submit" className="btn btn-primary" disabled={submitting}>
              {submitting ? 'Menyimpan...' : 'Simpan Donation'}
            </button>
          </div>
        </div>
      </form>
    </DashboardLayout>
  )
}

