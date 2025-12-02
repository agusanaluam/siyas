'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { apiClient } from '@/lib/api/client'

interface HeroSlide {
  id: number
  title: string
  description: string
  image: string
  order: number
}

interface Partner {
  id: number
  name: string
  image: string
  order: number
}

export default function SettingsLayoutPage() {
  const router = useRouter()
  const { user, loading, isAuthenticated } = useAuth()
  const [slides, setSlides] = useState<HeroSlide[]>([])
  const [partners, setPartners] = useState<Partner[]>([])
  const [dataLoading, setDataLoading] = useState(true)

  // Form states
  const [slideForm, setSlideForm] = useState({ title: '', description: '' })
  const [slideImage, setSlideImage] = useState<File | null>(null)
  const [partnerForm, setPartnerForm] = useState({ name: '' })
  const [partnerImage, setPartnerImage] = useState<File | null>(null)
  const [editingSlide, setEditingSlide] = useState<HeroSlide | null>(null)

  const [submittingSlide, setSubmittingSlide] = useState(false)
  const [submittingPartner, setSubmittingPartner] = useState(false)

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated && (user?.level === 'administrator' || user?.level === 'root')) {
      fetchData()
    }
  }, [isAuthenticated, user])

  const fetchData = async () => {
    try {
      setDataLoading(true)
      const [slidesRes, partnersRes] = await Promise.all([
        apiClient.get('/hero-slides'),
        apiClient.get('/partners')
      ])
      setSlides(slidesRes.data)
      setPartners(partnersRes.data)
    } catch (error) {
      console.error('Error fetching data:', error)
    } finally {
      setDataLoading(false)
    }
  }

  const handleSlideSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setSubmittingSlide(true)
    try {
      const formData = new FormData()
      formData.append('title', slideForm.title)
      formData.append('description', slideForm.description)
      if (slideImage) {
        formData.append('image', slideImage)
      }

      if (editingSlide) {
        await apiClient.post(`/hero-slides/${editingSlide.id}`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })
      } else {
        if (!slideImage) return alert('Image is required')
        await apiClient.post('/hero-slides', formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })
      }

      setSlideForm({ title: '', description: '' })
      setSlideImage(null)
      setEditingSlide(null)
      fetchData()
    } catch (error) {
      console.error('Error saving slide:', error)
      alert('Failed to save slide')
    } finally {
      setSubmittingSlide(false)
    }
  }

  const handleDeleteSlide = async (id: number) => {
    if (!confirm('Are you sure you want to delete this slide?')) return
    try {
      await apiClient.delete(`/hero-slides/${id}`)
      fetchData()
    } catch (error) {
      console.error('Error deleting slide:', error)
      alert('Failed to delete slide')
    }
  }

  const handlePartnerSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    if (!partnerImage) return alert('Image is required')
    
    setSubmittingPartner(true)
    try {
      const formData = new FormData()
      formData.append('name', partnerForm.name)
      formData.append('image', partnerImage)

      await apiClient.post('/partners', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })

      setPartnerForm({ name: '' })
      setPartnerImage(null)
      fetchData()
    } catch (error) {
      console.error('Error saving partner:', error)
      alert('Failed to save partner')
    } finally {
      setSubmittingPartner(false)
    }
  }

  const handleDeletePartner = async (id: number) => {
    if (!confirm('Are you sure you want to delete this partner?')) return
    try {
      await apiClient.delete(`/partners/${id}`)
      fetchData()
    } catch (error) {
      console.error('Error deleting partner:', error)
      alert('Failed to delete partner')
    }
  }

  const getImageUrl = (path: string) => {
    if (path.startsWith('http')) return path
    return `${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}${path}`
  }

  if (loading || dataLoading) {
    return (
      <DashboardLayout>
        <div className="flex items-center justify-center h-64">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
        </div>
      </DashboardLayout>
    )
  }

  return (
    <DashboardLayout>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-900">Layout Settings</h1>
        <p className="mt-1 text-sm text-gray-600">Manage landing page content</p>
      </div>

      <div className="space-y-8">
        {/* Hero Slides Section */}
        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-xl font-semibold mb-4">Hero Slides</h2>
          
          <form onSubmit={handleSlideSubmit} className="mb-8 p-4 bg-gray-50 rounded-lg">
            <h3 className="text-lg font-medium mb-4">{editingSlide ? 'Edit Slide' : 'Add New Slide'}</h3>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="label">Title</label>
                <input
                  type="text"
                  className="input"
                  value={slideForm.title}
                  onChange={e => setSlideForm({...slideForm, title: e.target.value})}
                />
              </div>
              <div>
                <label className="label">Description</label>
                <input
                  type="text"
                  className="input"
                  value={slideForm.description}
                  onChange={e => setSlideForm({...slideForm, description: e.target.value})}
                />
              </div>
              <div className="md:col-span-2">
                <label className="label">Image {editingSlide && '(Leave empty to keep current)'}</label>
                <input
                  type="file"
                  accept="image/*"
                  className="input"
                  onChange={e => setSlideImage(e.target.files?.[0] || null)}
                />
              </div>
            </div>
            <div className="mt-4 flex gap-2">
              <button type="submit" className="btn btn-primary" disabled={submittingSlide}>
                {submittingSlide ? 'Saving...' : (editingSlide ? 'Update Slide' : 'Add Slide')}
              </button>
              {editingSlide && (
                <button 
                  type="button" 
                  className="btn bg-gray-500 text-white hover:bg-gray-600"
                  onClick={() => {
                    setEditingSlide(null)
                    setSlideForm({ title: '', description: '' })
                    setSlideImage(null)
                  }}
                >
                  Cancel
                </button>
              )}
            </div>
          </form>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {slides.map(slide => (
              <div key={slide.id} className="border rounded-lg overflow-hidden relative group">
                <img 
                  src={getImageUrl(slide.image)} 
                  alt={slide.title} 
                  className="w-full h-48 object-cover"
                />
                <div className="p-4">
                  <h4 className="font-bold">{slide.title}</h4>
                  <p className="text-sm text-gray-600">{slide.description}</p>
                </div>
                <div className="absolute top-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button 
                    onClick={() => {
                      setEditingSlide(slide)
                      setSlideForm({ title: slide.title, description: slide.description })
                    }}
                    className="p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700"
                  >
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                  </button>
                  <button 
                    onClick={() => handleDeleteSlide(slide.id)}
                    className="p-2 bg-red-600 text-white rounded-full hover:bg-red-700"
                  >
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Partners Section */}
        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-xl font-semibold mb-4">Partners</h2>
          
          <form onSubmit={handlePartnerSubmit} className="mb-8 p-4 bg-gray-50 rounded-lg">
            <h3 className="text-lg font-medium mb-4">Add New Partner</h3>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="label">Partner Name</label>
                <input
                  type="text"
                  className="input"
                  value={partnerForm.name}
                  onChange={e => setPartnerForm({...partnerForm, name: e.target.value})}
                  required
                />
              </div>
              <div>
                <label className="label">Logo Image</label>
                <input
                  type="file"
                  accept="image/*"
                  className="input"
                  onChange={e => setPartnerImage(e.target.files?.[0] || null)}
                  required
                />
              </div>
            </div>
            <div className="mt-4">
              <button type="submit" className="btn btn-primary" disabled={submittingPartner}>
                {submittingPartner ? 'Saving...' : 'Add Partner'}
              </button>
            </div>
          </form>

          <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
            {partners.map(partner => (
              <div key={partner.id} className="border rounded-lg p-4 relative group flex flex-col items-center">
                <img 
                  src={getImageUrl(partner.image)} 
                  alt={partner.name} 
                  className="h-16 object-contain mb-2"
                />
                <p className="font-medium text-center">{partner.name}</p>
                <button 
                  onClick={() => handleDeletePartner(partner.id)}
                  className="absolute top-2 right-2 p-1 bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-700"
                >
                  <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
              </div>
            ))}
          </div>
        </div>
      </div>
    </DashboardLayout>
  )
}
