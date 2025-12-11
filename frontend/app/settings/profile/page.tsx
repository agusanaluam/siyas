'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { apiClient } from '@/lib/api/client'
import { toast } from 'react-hot-toast'

interface Setting {
  id: number
  name: string
  address: string
  phone_number: string
  email?: string
  description: string
  gmaps: string
  facebook?: string
  instagram?: string
  twitter?: string
  youtube?: string
  tiktok?: string
  photo: string
}

interface DonationAccount {
  id: number
  bank_name: string
  account_number: string
  account_holder: string
}

export default function SettingsProfilePage() {
  const router = useRouter()
  const { user, loading, isAuthenticated } = useAuth()
  const [setting, setSetting] = useState<Setting | null>(null)
  const [formData, setFormData] = useState({
    name: '',
    address: '',
    phone_number: '',
    email: '',
    description: '',
    gmaps: '',
    facebook: '',
    instagram: '',
    twitter: '',
    youtube: '',
    tiktok: '',
  })
  const [photo, setPhoto] = useState<File | null>(null)
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [submitting, setSubmitting] = useState(false)
  const [dataLoading, setDataLoading] = useState(true)
  const [accounts, setAccounts] = useState<DonationAccount[]>([])
  const [accountForm, setAccountForm] = useState({
    bank_name: '',
    account_number: '',
    account_holder: '',
  })
  const [editingAccount, setEditingAccount] = useState<DonationAccount | null>(null)
  const [accountSubmitting, setAccountSubmitting] = useState(false)
  const [accountErrors, setAccountErrors] = useState<Record<string, string>>({})

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated && (user?.level === 'administrator' || user?.level === 'root')) {
      fetchSettings()
      fetchAccounts()
    }
  }, [isAuthenticated, user])

  const fetchSettings = async () => {
    try {
      setDataLoading(true)
      const response = await apiClient.get('/settings/profile')
      const data = response.data
      setSetting(data)

      setFormData({
        name: data.name || '',
        address: data.address || '',
        phone_number: data.phone_number || '',
        email: data.email || '',
        description: data.description || '',
        gmaps: data.gmaps || '',
        facebook: data.facebook || '',
        instagram: data.instagram || '',
        twitter: data.twitter || '',
        youtube: data.youtube || '',
        tiktok: data.tiktok || '',
      })
    } catch (error) {
      console.error('Error fetching settings:', error)
    } finally {
      setDataLoading(false)
    }
  }

  const fetchAccounts = async () => {
    try {
      const response = await apiClient.get('/donation-accounts')
      setAccounts(response.data)
    } catch (error) {
      console.error('Gagal mengambil rekening:', error)
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setSubmitting(true)

    try {
      const formDataToSend = new FormData()
      formDataToSend.append('_method', 'PUT')
      formDataToSend.append('name', formData.name)
      formDataToSend.append('address', formData.address)
      formDataToSend.append('phone_number', formData.phone_number)
      formDataToSend.append('email', formData.email)
      formDataToSend.append('description', formData.description)
      formDataToSend.append('gmaps', formData.gmaps)
      formDataToSend.append('facebook', formData.facebook)
      formDataToSend.append('instagram', formData.instagram)
      formDataToSend.append('twitter', formData.twitter)
      formDataToSend.append('youtube', formData.youtube)
      formDataToSend.append('tiktok', formData.tiktok)
      if (photo) {
        formDataToSend.append('photo', photo)
      }

      await apiClient.post('/settings/profile', formDataToSend, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })

      toast.success('Profile yayasan berhasil diupdate')
      fetchSettings()
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        const message = error.response?.data?.message || 'Gagal mengupdate profile yayasan'
        toast.error(message)
        setErrors({ general: message })
      }
    } finally {
      setSubmitting(false)
    }
  }

  const handleAccountSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setAccountErrors({})
    setAccountSubmitting(true)

    try {
      if (editingAccount) {
        await apiClient.post(`/donation-accounts/${editingAccount.id}`, {
          bank_name: accountForm.bank_name,
          account_number: accountForm.account_number,
          account_holder: accountForm.account_holder,
          _method: 'PUT',
        })
        toast.success('Rekening donasi berhasil diupdate')
      } else {
        await apiClient.post('/donation-accounts', accountForm)
        toast.success('Rekening donasi berhasil disimpan')
      }

      setAccountForm({ bank_name: '', account_number: '', account_holder: '' })
      setEditingAccount(null)
      fetchAccounts()
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setAccountErrors(apiErrors)
      } else {
        const message = error.response?.data?.message || 'Gagal menyimpan rekening donasi'
        toast.error(message)
        setAccountErrors({ general: message })
      }
    } finally {
      setAccountSubmitting(false)
    }
  }

  const handleDeleteAccount = async (id: number) => {
    if (!confirm('Hapus rekening ini?')) return
    try {
      await apiClient.delete(`/donation-accounts/${id}`)
      toast.success('Rekening berhasil dihapus')
      fetchAccounts()
    } catch (error) {
      console.error('Gagal menghapus rekening:', error)
      toast.error('Gagal menghapus rekening')
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

  if (!isAuthenticated || (user?.level !== 'administrator' && user?.level !== 'root')) {
    return (
      <DashboardLayout>
        <div className="text-center py-12">
          <p className="text-gray-600">Anda tidak memiliki akses ke halaman ini</p>
        </div>
      </DashboardLayout>
    )
  }

  return (
    <DashboardLayout>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-900">Profile Yayasan</h1>
        <p className="mt-1 text-sm text-gray-600">Kelola profile yayasan</p>
      </div>

      <form onSubmit={handleSubmit} className="bg-white rounded-lg shadow p-6">
        {errors.general && (
          <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            {errors.general}
          </div>
        )}

        <div className="space-y-6">
          <div>
            <label className="label">Nama Yayasan *</label>
            <input
              type="text"
              className="input"
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              required
            />
            {errors.name && <p className="mt-1 text-sm text-red-600">{errors.name}</p>}
          </div>

          <div>
            <label className="label">Alamat *</label>
            <textarea
              className="input"
              rows={3}
              value={formData.address}
              onChange={(e) => setFormData({ ...formData, address: e.target.value })}
              required
            />
            {errors.address && (
              <p className="mt-1 text-sm text-red-600">{errors.address}</p>
            )}
          </div>

          <div>
            <label className="label">Nomor Telepon *</label>
            <input
              type="text"
              className="input"
              value={formData.phone_number}
              onChange={(e) => setFormData({ ...formData, phone_number: e.target.value })}
              required
            />
            {errors.phone_number && (
              <p className="mt-1 text-sm text-red-600">{errors.phone_number}</p>
            )}
          </div>

          <div>
            <label className="label">Email</label>
            <input
              type="email"
              className="input"
              value={formData.email}
              onChange={(e) => setFormData({ ...formData, email: e.target.value })}
              placeholder="email@yayasan.org"
            />
            {errors.email && <p className="mt-1 text-sm text-red-600">{errors.email}</p>}
          </div>

          <div>
            <label className="label">Deskripsi *</label>
            <textarea
              className="input"
              rows={5}
              value={formData.description}
              onChange={(e) => setFormData({ ...formData, description: e.target.value })}
              required
            />
            {errors.description && (
              <p className="mt-1 text-sm text-red-600">{errors.description}</p>
            )}
          </div>

          <div>
            <label className="label">Google Maps Link</label>
            <input
              type="text"
              className="input"
              value={formData.gmaps}
              onChange={(e) => setFormData({ ...formData, gmaps: e.target.value })}
              placeholder="https://maps.google.com/..."
            />
            {errors.gmaps && <p className="mt-1 text-sm text-red-600">{errors.gmaps}</p>}
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label className="label">Facebook URL</label>
              <input
                type="url"
                className="input"
                value={formData.facebook}
                onChange={(e) => setFormData({ ...formData, facebook: e.target.value })}
                placeholder="https://facebook.com/..."
              />
              {errors.facebook && <p className="mt-1 text-sm text-red-600">{errors.facebook}</p>}
            </div>

            <div>
              <label className="label">Instagram URL</label>
              <input
                type="url"
                className="input"
                value={formData.instagram}
                onChange={(e) => setFormData({ ...formData, instagram: e.target.value })}
                placeholder="https://instagram.com/..."
              />
              {errors.instagram && <p className="mt-1 text-sm text-red-600">{errors.instagram}</p>}
            </div>

            <div>
              <label className="label">Twitter/X URL</label>
              <input
                type="url"
                className="input"
                value={formData.twitter}
                onChange={(e) => setFormData({ ...formData, twitter: e.target.value })}
                placeholder="https://twitter.com/..."
              />
              {errors.twitter && <p className="mt-1 text-sm text-red-600">{errors.twitter}</p>}
            </div>

            <div>
              <label className="label">YouTube URL</label>
              <input
                type="url"
                className="input"
                value={formData.youtube}
                onChange={(e) => setFormData({ ...formData, youtube: e.target.value })}
                placeholder="https://youtube.com/..."
              />
              {errors.youtube && <p className="mt-1 text-sm text-red-600">{errors.youtube}</p>}
            </div>

            <div>
              <label className="label">TikTok URL</label>
              <input
                type="url"
                className="input"
                value={formData.tiktok}
                onChange={(e) => setFormData({ ...formData, tiktok: e.target.value })}
                placeholder="https://tiktok.com/..."
              />
              {errors.tiktok && <p className="mt-1 text-sm text-red-600">{errors.tiktok}</p>}
            </div>
          </div>

          <div>
            <label className="label">Foto Yayasan</label>
            <input
              type="file"
              accept="image/*"
              onChange={(e) => setPhoto(e.target.files?.[0] || null)}
              className="input"
            />
            {setting?.photo && !photo && (
              <div className="mt-2">
                <img
                  src={`${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/${setting.photo}`}
                  alt="Current photo"
                  className="w-48 h-48 object-cover rounded-lg"
                />
              </div>
            )}
            {photo && (
              <div className="mt-2">
                <img
                  src={URL.createObjectURL(photo)}
                  alt="Preview"
                  className="w-48 h-48 object-cover rounded-lg"
                />
              </div>
            )}
            {errors.photo && <p className="mt-1 text-sm text-red-600">{errors.photo}</p>}
          </div>

          <div className="flex justify-end">
            <button type="submit" className="btn btn-primary" disabled={submitting}>
              {submitting ? 'Menyimpan...' : 'Simpan Perubahan'}
            </button>
          </div>
        </div>
      </form>

      <div className="mt-8 bg-white rounded-lg shadow p-6">
        <div className="flex items-center justify-between mb-4">
          <div>
            <h2 className="text-xl font-semibold text-gray-900">Rekening Donasi</h2>
            <p className="text-sm text-gray-600">Kelola daftar rekening donasi resmi</p>
          </div>
          {editingAccount && (
            <button
              type="button"
              className="btn bg-gray-500 text-white hover:bg-gray-600"
              onClick={() => {
                setEditingAccount(null)
                setAccountForm({ bank_name: '', account_number: '', account_holder: '' })
                setAccountErrors({})
              }}
            >
              Batalkan Edit
            </button>
          )}
        </div>

        {accountErrors.general && (
          <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            {accountErrors.general}
          </div>
        )}

        <form onSubmit={handleAccountSubmit} className="bg-gray-50 rounded-lg p-4 mb-6">
          <h3 className="text-lg font-medium mb-4">{editingAccount ? 'Edit Rekening' : 'Tambah Rekening'}</h3>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label className="label">Bank *</label>
              <input
                type="text"
                className="input"
                value={accountForm.bank_name}
                onChange={(e) => setAccountForm({ ...accountForm, bank_name: e.target.value })}
                required
              />
              {accountErrors.bank_name && <p className="mt-1 text-sm text-red-600">{accountErrors.bank_name}</p>}
            </div>
            <div>
              <label className="label">No. Rekening *</label>
              <input
                type="text"
                className="input"
                value={accountForm.account_number}
                onChange={(e) => setAccountForm({ ...accountForm, account_number: e.target.value })}
                required
              />
              {accountErrors.account_number && <p className="mt-1 text-sm text-red-600">{accountErrors.account_number}</p>}
            </div>
            <div>
              <label className="label">Atas Nama *</label>
              <input
                type="text"
                className="input"
                value={accountForm.account_holder}
                onChange={(e) => setAccountForm({ ...accountForm, account_holder: e.target.value })}
                required
              />
              {accountErrors.account_holder && <p className="mt-1 text-sm text-red-600">{accountErrors.account_holder}</p>}
            </div>
          </div>
          <div className="mt-4 flex gap-2">
            <button type="submit" className="btn btn-primary" disabled={accountSubmitting}>
              {accountSubmitting ? 'Menyimpan...' : editingAccount ? 'Update Rekening' : 'Tambah Rekening'}
            </button>
          </div>
        </form>

        {accounts.length === 0 ? (
          <p className="text-gray-600 text-sm">Belum ada rekening donasi.</p>
        ) : (
          <div className="space-y-3">
            {accounts.map((acc) => (
              <div key={acc.id} className="flex items-center justify-between border rounded-lg p-4">
                <div>
                  <p className="text-sm text-gray-500">{acc.bank_name}</p>
                  <p className="font-semibold text-gray-900">{acc.account_number}</p>
                  <p className="text-sm text-gray-700">a.n {acc.account_holder}</p>
                </div>
                <div className="flex gap-2">
                  <button
                    className="btn bg-blue-600 text-white hover:bg-blue-700"
                    onClick={() => {
                      setEditingAccount(acc)
                      setAccountForm({
                        bank_name: acc.bank_name,
                        account_number: acc.account_number,
                        account_holder: acc.account_holder,
                      })
                    }}
                  >
                    Edit
                  </button>
                  <button
                    className="btn bg-red-600 text-white hover:bg-red-700"
                    onClick={() => handleDeleteAccount(acc.id)}
                  >
                    Hapus
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </DashboardLayout>
  )
}

