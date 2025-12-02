'use client'

import { useState, useEffect } from 'react'
import { useRouter, useParams } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { campaignService, campaignCategoryService } from '@/lib/api/campaign'
import { Campaign, CampaignCategory } from '@/types'

export default function EditCampaignPage() {
  const router = useRouter()
  const params = useParams()
  const { user, loading, isAuthenticated } = useAuth()
  const [categories, setCategories] = useState<CampaignCategory[]>([])
  const [campaign, setCampaign] = useState<Campaign | null>(null)
  const [formData, setFormData] = useState({
    name: '',
    category_id: '',
    start_date: '',
    end_date: '',
    description: '',
    pic: '',
    target_amount: '',
    target_object: '',
    close_type: '1',
  })
  const [images, setImages] = useState<File[]>([])
  const [existingImages, setExistingImages] = useState<string[]>([])
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [submitting, setSubmitting] = useState(false)
  const [dataLoading, setDataLoading] = useState(true)

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated && params.id) {
      fetchData()
    }
  }, [isAuthenticated, params.id])

  const fetchData = async () => {
    try {
      setDataLoading(true)
      const [campaignData, categoriesData] = await Promise.all([
        campaignService.getById(Number(params.id)),
        campaignCategoryService.getAll(),
      ])

      setCampaign(campaignData)
      setCategories(categoriesData)

      setFormData({
        name: campaignData.name,
        category_id: campaignData.category_id.toString(),
        start_date: campaignData.start_date.split(' ')[0],
        end_date: campaignData.end_date.split(' ')[0],
        description: campaignData.description || '',
        pic: campaignData.pic || '',
        target_amount: campaignData.target_amount.toString(),
        target_object: campaignData.target_object?.toString() || '',
        close_type: campaignData.close_type.toString(),
      })

      if (campaignData.images) {
        setExistingImages(campaignData.images.map((img) => img.picture_path))
      }
    } catch (error) {
      console.error('Error fetching data:', error)
      router.push('/campaign')
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
      formDataToSend.append('category_id', formData.category_id)
      formDataToSend.append('start_date', formData.start_date)
      formDataToSend.append('end_date', formData.end_date)
      formDataToSend.append('description', formData.description)
      formDataToSend.append('pic', formData.pic)
      formDataToSend.append('target_amount', formData.target_amount)
      formDataToSend.append('target_object', formData.target_object || '0')
      formDataToSend.append('close_type', formData.close_type)

      images.forEach((image) => {
        formDataToSend.append('campaign_picture[]', image)
      })

      await campaignService.update(Number(params.id), formDataToSend)
      router.push('/campaign')
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Gagal mengupdate campaign' })
      }
    } finally {
      setSubmitting(false)
    }
  }

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files) {
      setImages(Array.from(e.target.files))
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

  return (
    <DashboardLayout>
      <div className="mb-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Edit Campaign</h1>
            <p className="mt-1 text-sm text-gray-600">Edit campaign</p>
          </div>
          <Link href="/campaign" className="btn btn-secondary">
            Kembali ke List
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
              <label className="label">Nama Campaign *</label>
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
              <label className="label">Kategori *</label>
              <select
                className="input"
                value={formData.category_id}
                onChange={(e) => setFormData({ ...formData, category_id: e.target.value })}
                required
              >
                <option value="">--Pilih Kategori--</option>
                {categories.map((category) => (
                  <option key={category.id} value={category.id}>
                    {category.name}
                  </option>
                ))}
              </select>
              {errors.category_id && (
                <p className="mt-1 text-sm text-red-600">{errors.category_id}</p>
              )}
            </div>

            <div>
              <label className="label">PIC *</label>
              <input
                type="text"
                className="input"
                value={formData.pic}
                onChange={(e) => setFormData({ ...formData, pic: e.target.value })}
                required
              />
              {errors.pic && <p className="mt-1 text-sm text-red-600">{errors.pic}</p>}
            </div>

            <div>
              <label className="label">Tanggal Mulai *</label>
              <input
                type="date"
                className="input"
                value={formData.start_date}
                onChange={(e) => setFormData({ ...formData, start_date: e.target.value })}
                required
              />
              {errors.start_date && (
                <p className="mt-1 text-sm text-red-600">{errors.start_date}</p>
              )}
            </div>

            <div>
              <label className="label">Tanggal Selesai *</label>
              <input
                type="date"
                className="input"
                value={formData.end_date}
                onChange={(e) => setFormData({ ...formData, end_date: e.target.value })}
                required
              />
              {errors.end_date && (
                <p className="mt-1 text-sm text-red-600">{errors.end_date}</p>
              )}
            </div>

            <div>
              <label className="label">Target Amount *</label>
              <input
                type="number"
                className="input"
                value={formData.target_amount}
                onChange={(e) => setFormData({ ...formData, target_amount: e.target.value })}
                required
                min="0"
              />
              {errors.target_amount && (
                <p className="mt-1 text-sm text-red-600">{errors.target_amount}</p>
              )}
            </div>

            <div>
              <label className="label">Target Object (penerima manfaat)</label>
              <input
                type="number"
                className="input"
                value={formData.target_object}
                onChange={(e) => setFormData({ ...formData, target_object: e.target.value })}
                min="0"
              />
            </div>

            <div>
              <label className="label">Close Type *</label>
              <select
                className="input"
                value={formData.close_type}
                onChange={(e) => setFormData({ ...formData, close_type: e.target.value })}
                required
              >
                <option value="1">End Date</option>
                <option value="2">Target Amount</option>
              </select>
            </div>
          </div>

          <div>
            <label className="label">Deskripsi *</label>
            <textarea
              className="input"
              rows={4}
              value={formData.description}
              onChange={(e) => setFormData({ ...formData, description: e.target.value })}
              required
            />
            {errors.description && (
              <p className="mt-1 text-sm text-red-600">{errors.description}</p>
            )}
          </div>

          <div>
            <label className="label">Gambar Campaign (opsional, untuk menambah gambar baru)</label>
            <input
              type="file"
              multiple
              accept="image/*"
              onChange={handleImageChange}
              className="input"
            />
            {errors.campaign_picture && (
              <p className="mt-1 text-sm text-red-600">{errors.campaign_picture}</p>
            )}
            {images.length > 0 && (
              <div className="mt-2 grid grid-cols-4 gap-4">
                {images.map((image, index) => (
                  <div key={index} className="relative">
                    <img
                      src={URL.createObjectURL(image)}
                      alt={`Preview ${index + 1}`}
                      className="w-full h-32 object-cover rounded-lg"
                    />
                  </div>
                ))}
              </div>
            )}
            {existingImages.length > 0 && (
              <div className="mt-4">
                <p className="text-sm font-medium text-gray-700 mb-2">Gambar yang sudah ada:</p>
                <div className="grid grid-cols-4 gap-4">
                  {existingImages.map((img, index) => (
                    <div key={index} className="relative">
                      <img
                        src={`http://localhost:8000/storage/campaign_pictures/${img}`}
                        alt={`Existing ${index + 1}`}
                        className="w-full h-32 object-cover rounded-lg"
                      />
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>

          <div className="flex justify-end space-x-4">
            <Link href="/campaign" className="btn btn-secondary">
              Batal
            </Link>
            <button type="submit" className="btn btn-primary" disabled={submitting}>
              {submitting ? 'Menyimpan...' : 'Update Campaign'}
            </button>
          </div>
        </div>
      </form>
    </DashboardLayout>
  )
}

