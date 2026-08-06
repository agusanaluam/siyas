'use client'

import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { campaignService, campaignCategoryService } from '@/lib/api/campaign'
import { CampaignCategory } from '@/types'
import { compressImage } from '@/lib/imageCompression'
import RichTextEditor from '@/components/RichTextEditor'

export default function CreateCampaignPage() {
  const router = useRouter()
  const { user, loading, isAuthenticated } = useAuth()
  const [categories, setCategories] = useState<CampaignCategory[]>([])
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
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [submitting, setSubmitting] = useState(false)
  const [processingImages, setProcessingImages] = useState(false)

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated) {
      fetchCategories()
    }
  }, [isAuthenticated])

  const fetchCategories = async () => {
    try {
      const data = await campaignCategoryService.getAll()
      setCategories(data)
    } catch (error) {
      console.error('Error fetching categories:', error)
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setSubmitting(true)

    try {
      const formDataToSend = new FormData()
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

      await campaignService.create(formDataToSend)
      router.push('/campaign')
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Gagal membuat campaign' })
      }
    } finally {
      setSubmitting(false)
    }
  }

  const handleImageChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files) {
      setProcessingImages(true)
      const files = Array.from(e.target.files)
      const compressedFiles: File[] = []

      try {
        for (const file of files) {
          if (file.type.startsWith('image/')) {
            const compressed = await compressImage(file)
            compressedFiles.push(compressed)
          } else {
            compressedFiles.push(file)
          }
        }
        setImages((prev) => [...prev, ...compressedFiles])
      } catch (error) {
        console.error('Error compressing images:', error)
        // Fallback to original files if compression fails
        setImages((prev) => [...prev, ...files])
      } finally {
        setProcessingImages(false)
        // Reset input value so same file can be selected again if needed
        e.target.value = ''
      }
    }
  }

  const handleRemoveImage = (index: number) => {
    setImages(images.filter((_, i) => i !== index))
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
            <h1 className="text-2xl font-bold text-gray-900">Tambah Campaign Baru</h1>
            <p className="mt-1 text-sm text-gray-600">Buat campaign baru</p>
          </div>
          <Link href="/campaign" className="btn btn-secondary">
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
          </div>

          <div>
            <label className="label">Deskripsi *</label>
            <RichTextEditor
              value={formData.description}
              onChange={(value) => setFormData({ ...formData, description: value })}
              placeholder="Masukkan deskripsi campaign..."
            />
            {errors.description && (
              <p className="mt-1 text-sm text-red-600">{errors.description}</p>
            )}
          </div>

          <div>
            <label className="label mb-2">Gambar Campaign *</label>
            <div className="border rounded-lg p-4 bg-gray-50">
              <div className="flex flex-wrap gap-4">
                {/* Add Image Button */}
                <div className="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:border-primary-500 hover:bg-white transition-colors relative bg-white">
                  <input
                    type="file"
                    multiple
                    accept="image/*"
                    onChange={handleImageChange}
                    className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                    // Only required if no images are selected yet
                    required={images.length === 0}
                  />
                  <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                  </svg>
                  <span className="text-xs text-gray-500 mt-1">Add Images</span>
                </div>

                {/* Image Previews */}
                {images.map((image, index) => (
                  <div key={index} className="relative w-24 h-24 border rounded-lg overflow-hidden group bg-white shadow-sm">
                    <img
                      src={URL.createObjectURL(image)}
                      alt={`Preview ${index + 1}`}
                      className="w-full h-full object-cover"
                    />
                    <button
                      type="button"
                      onClick={() => handleRemoveImage(index)}
                      className="absolute top-1 right-1 bg-red-500 text-white rounded-full p-0.5 hover:bg-red-600 transition-colors shadow-sm"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" className="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fillRule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clipRule="evenodd" />
                      </svg>
                    </button>
                  </div>
                ))}
              </div>
            </div>
            {errors.campaign_picture && (
              <p className="mt-1 text-sm text-red-600">{errors.campaign_picture}</p>
            )}
          </div>

          <div className="flex justify-end space-x-4">
            <Link href="/campaign" className="btn btn-secondary">
              Batal
            </Link>
            <button type="submit" className="btn btn-primary" disabled={submitting || processingImages}>
              {submitting ? 'Menyimpan...' : processingImages ? 'Memproses Gambar...' : 'Simpan Campaign'}
            </button>
          </div>
        </div>
      </form>
    </DashboardLayout>
  )
}

