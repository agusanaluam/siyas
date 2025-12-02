'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { apiClient } from '@/lib/api/client'

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

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated && (user?.level === 'administrator' || user?.level === 'root')) {
      fetchSettings()
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

      alert('Profile yayasan berhasil diupdate')
      fetchSettings()
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Gagal mengupdate profile yayasan' })
      }
    } finally {
      setSubmitting(false)
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
    </DashboardLayout>
  )
}

