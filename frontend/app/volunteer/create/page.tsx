'use client'

import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { volunteerService } from '@/lib/api/volunteer'
import { apiClient } from '@/lib/api/client'

interface Group {
  id: number
  name: string
}

export default function CreateVolunteerPage() {
  const router = useRouter()
  const { user, loading, isAuthenticated } = useAuth()
  const [groups, setGroups] = useState<Group[]>([])
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    group_id: '',
  })
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [submitting, setSubmitting] = useState(false)

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated && (user?.level === 'administrator' || user?.level === 'root')) {
      fetchGroups()
    }
  }, [isAuthenticated, user])

  const fetchGroups = async () => {
    try {
      const response = await apiClient.get('/groups')
      setGroups(response.data)
    } catch (error) {
      console.error('Error fetching groups:', error)
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})

    if (formData.password !== formData.password_confirmation) {
      setErrors({ password_confirmation: 'Password dan konfirmasi password tidak sama' })
      return
    }

    setSubmitting(true)

    try {
      await volunteerService.create({
        name: formData.name,
        email: formData.email,
        password: formData.password,
        password_confirmation: formData.password_confirmation,
        group_id: Number(formData.group_id),
      })
      router.push('/volunteer')
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Gagal membuat volunteer' })
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
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Tambah Volunteer</h1>
            <p className="mt-1 text-sm text-gray-600">Buat volunteer baru</p>
          </div>
          <Link href="/volunteer" className="btn btn-secondary">
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
              <label className="label">Group *</label>
              <select
                className="input"
                value={formData.group_id}
                onChange={(e) => setFormData({ ...formData, group_id: e.target.value })}
                required
              >
                <option value="">--Pilih Group--</option>
                {groups.map((group) => (
                  <option key={group.id} value={group.id}>
                    {group.name}
                  </option>
                ))}
              </select>
              {errors.group_id && (
                <p className="mt-1 text-sm text-red-600">{errors.group_id}</p>
              )}
            </div>

            <div>
              <label className="label">Nama Lengkap *</label>
              <input
                type="text"
                className="input"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                required
                minLength={5}
              />
              {errors.name && <p className="mt-1 text-sm text-red-600">{errors.name}</p>}
            </div>

            <div>
              <label className="label">Email *</label>
              <input
                type="email"
                className="input"
                value={formData.email}
                onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                required
              />
              {errors.email && <p className="mt-1 text-sm text-red-600">{errors.email}</p>}
            </div>

            <div>
              <label className="label">Password *</label>
              <input
                type="password"
                className="input"
                value={formData.password}
                onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                required
                minLength={8}
              />
              {errors.password && (
                <p className="mt-1 text-sm text-red-600">{errors.password}</p>
              )}
            </div>

            <div>
              <label className="label">Konfirmasi Password *</label>
              <input
                type="password"
                className="input"
                value={formData.password_confirmation}
                onChange={(e) =>
                  setFormData({ ...formData, password_confirmation: e.target.value })
                }
                required
                minLength={8}
              />
              {errors.password_confirmation && (
                <p className="mt-1 text-sm text-red-600">{errors.password_confirmation}</p>
              )}
            </div>
          </div>

          <div className="flex justify-end space-x-4">
            <Link href="/volunteer" className="btn btn-secondary">
              Batal
            </Link>
            <button type="submit" className="btn btn-primary" disabled={submitting}>
              {submitting ? 'Menyimpan...' : 'Simpan Volunteer'}
            </button>
          </div>
        </div>
      </form>
    </DashboardLayout>
  )
}

