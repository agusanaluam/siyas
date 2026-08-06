'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import { toast } from 'react-hot-toast'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { apiClient } from '@/lib/api/client'
import RichTextEditor from '@/components/RichTextEditor'

interface AboutSetting {
  about_content: string
  about_photo?: string
  about_service?: string[] | null
}

export default function SettingsAboutPage() {
  const router = useRouter()
  const { user, loading, isAuthenticated } = useAuth()

  const [dataLoading, setDataLoading] = useState(true)
  const [submitting, setSubmitting] = useState(false)
  const [errors, setErrors] = useState<Record<string, string>>({})

  const [aboutContent, setAboutContent] = useState('')
  const [servicesText, setServicesText] = useState('')
  const [aboutPhoto, setAboutPhoto] = useState<File | null>(null)
  const [currentData, setCurrentData] = useState<AboutSetting | null>(null)

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated && (user?.level === 'administrator' || user?.level === 'root')) {
      fetchAbout()
    }
  }, [isAuthenticated, user])

  const fetchAbout = async () => {
    try {
      setDataLoading(true)
      const response = await apiClient.get('/settings/about')
      const data: AboutSetting = response.data
      setCurrentData(data)
      setAboutContent(data.about_content || '')
      setServicesText((data.about_service || []).join('\n'))
    } catch (error) {
      console.error('Gagal mengambil data about:', error)
    } finally {
      setDataLoading(false)
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setSubmitting(true)

    try {
      const formData = new FormData()
      formData.append('_method', 'PUT')
      formData.append('about_content', aboutContent)

      const services = servicesText
        .split('\n')
        .map((item) => item.trim())
        .filter((item) => item.length > 0)

      services.forEach((service) => formData.append('about_service[]', service))

      if (aboutPhoto) {
        formData.append('about_photo', aboutPhoto)
      }

      await apiClient.post('/settings/about', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })

      toast.success('Konten about berhasil disimpan')
      setAboutPhoto(null)
      fetchAbout()
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Gagal menyimpan konten about' })
      }
    } finally {
      setSubmitting(false)
    }
  }

  const getImageUrl = (path: string) => {
    if (path.startsWith('http')) return path
    return `${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/${path}`
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
        <h1 className="text-2xl font-bold text-gray-900">Pengaturan About</h1>
        <p className="mt-1 text-sm text-gray-600">Kelola konten About, foto, dan layanan</p>
      </div>

      <form onSubmit={handleSubmit} className="bg-white rounded-lg shadow p-6 space-y-6">
        {errors.general && (
          <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            {errors.general}
          </div>
        )}

        <div>
          <label className="label">Konten About *</label>
          <RichTextEditor
            value={aboutContent}
            onChange={setAboutContent}
            placeholder="Tulis deskripsi about..."
          />
          {errors.about_content && <p className="mt-1 text-sm text-red-600">{errors.about_content}</p>}
        </div>

        <div>
          <label className="label">Layanan (satu baris satu layanan)</label>
          <textarea
            className="input"
            rows={4}
            value={servicesText}
            onChange={(e) => setServicesText(e.target.value)}
            placeholder="Contoh:\nProgram pendidikan\nKesehatan masyarakat"
          />
          {errors['about_service.0'] && <p className="mt-1 text-sm text-red-600">{errors['about_service.0']}</p>}
        </div>

        <div>
          <label className="label">Foto About</label>
          <input
            type="file"
            accept="image/*"
            onChange={(e) => setAboutPhoto(e.target.files?.[0] || null)}
            className="input"
          />

          {currentData?.about_photo && !aboutPhoto && (
            <div className="mt-2">
              <img
                src={getImageUrl(currentData.about_photo)}
                alt="Foto about"
                className="w-48 h-48 object-cover rounded-lg"
              />
            </div>
          )}

          {aboutPhoto && (
            <div className="mt-2">
              <img
                src={URL.createObjectURL(aboutPhoto)}
                alt="Preview"
                className="w-48 h-48 object-cover rounded-lg"
              />
            </div>
          )}

          {errors.about_photo && <p className="mt-1 text-sm text-red-600">{errors.about_photo}</p>}
        </div>

        <div className="flex justify-end">
          <button type="submit" className="btn btn-primary" disabled={submitting}>
            {submitting ? 'Menyimpan...' : 'Simpan Perubahan'}
          </button>
        </div>
      </form>
    </DashboardLayout>
  )
}

