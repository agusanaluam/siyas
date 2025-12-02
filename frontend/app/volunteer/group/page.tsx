'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { apiClient } from '@/lib/api/client'

interface Group {
  id: number
  name: string
  description?: string
}

export default function VolunteerGroupPage() {
  const router = useRouter()
  const { user, loading, isAuthenticated } = useAuth()
  const [groups, setGroups] = useState<Group[]>([])
  const [dataLoading, setDataLoading] = useState(true)
  const [showModal, setShowModal] = useState(false)
  const [editingGroup, setEditingGroup] = useState<Group | null>(null)
  const [formData, setFormData] = useState({ name: '', description: '' })
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
      setDataLoading(true)
      const response = await apiClient.get('/groups')
      setGroups(response.data)
    } catch (error) {
      console.error('Error fetching groups:', error)
    } finally {
      setDataLoading(false)
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setSubmitting(true)

    try {
      if (editingGroup) {
        await apiClient.put(`/groups/${editingGroup.id}`, formData)
      } else {
        await apiClient.post('/groups', formData)
      }
      setShowModal(false)
      setEditingGroup(null)
      setFormData({ name: '', description: '' })
      fetchGroups()
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Gagal menyimpan group' })
      }
    } finally {
      setSubmitting(false)
    }
  }

  const handleEdit = (group: Group) => {
    setEditingGroup(group)
    setFormData({ name: group.name, description: group.description || '' })
    setShowModal(true)
  }

  const handleDelete = async (id: number) => {
    if (!confirm('Apakah Anda yakin ingin menghapus group ini?')) {
      return
    }

    try {
      await apiClient.delete(`/groups/${id}`)
      fetchGroups()
    } catch (error) {
      console.error('Error deleting group:', error)
      alert('Gagal menghapus group')
    }
  }

  const openModal = () => {
    setEditingGroup(null)
    setFormData({ name: '', description: '' })
    setErrors({})
    setShowModal(true)
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
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Group Volunteer</h1>
            <p className="mt-1 text-sm text-gray-600">Kelola group volunteer</p>
          </div>
          <button onClick={openModal} className="btn btn-primary">
            Tambah Group
          </button>
        </div>
      </div>

      <div className="bg-white rounded-lg shadow overflow-hidden">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nama Group
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Deskripsi
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Action
              </th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {groups.length === 0 ? (
              <tr>
                <td colSpan={3} className="px-6 py-4 text-center text-gray-500">
                  Tidak ada group
                </td>
              </tr>
            ) : (
              groups.map((group) => (
                <tr key={group.id} className="hover:bg-gray-50">
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {group.name}
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-900">
                    {group.description || '-'}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div className="flex space-x-2">
                      <button
                        onClick={() => handleEdit(group)}
                        className="text-primary-600 hover:text-primary-900"
                      >
                        Edit
                      </button>
                      <button
                        onClick={() => handleDelete(group.id)}
                        className="text-red-600 hover:text-red-900"
                      >
                        Hapus
                      </button>
                    </div>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {showModal && (
        <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
          <div className="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div className="mt-3">
              <h3 className="text-lg font-medium text-gray-900 mb-4">
                {editingGroup ? 'Edit Group' : 'Tambah Group'}
              </h3>
              <form onSubmit={handleSubmit}>
                {errors.general && (
                  <div className="mb-4 p-2 bg-red-50 border border-red-200 rounded text-red-800 text-sm">
                    {errors.general}
                  </div>
                )}
                <div className="mb-4">
                  <label className="label">Nama Group *</label>
                  <input
                    type="text"
                    className="input"
                    value={formData.name}
                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                    required
                  />
                  {errors.name && (
                    <p className="mt-1 text-sm text-red-600">{errors.name}</p>
                  )}
                </div>
                <div className="mb-4">
                  <label className="label">Deskripsi</label>
                  <textarea
                    className="input"
                    rows={3}
                    value={formData.description}
                    onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                  />
                </div>
                <div className="flex justify-end space-x-2">
                  <button
                    type="button"
                    onClick={() => {
                      setShowModal(false)
                      setEditingGroup(null)
                      setFormData({ name: '', description: '' })
                    }}
                    className="btn btn-secondary"
                  >
                    Batal
                  </button>
                  <button type="submit" className="btn btn-primary" disabled={submitting}>
                    {submitting ? 'Menyimpan...' : 'Simpan'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}
    </DashboardLayout>
  )
}

