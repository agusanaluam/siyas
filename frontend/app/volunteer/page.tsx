'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { volunteerService, Volunteer } from '@/lib/api/volunteer'

export default function VolunteerListPage() {
  const router = useRouter()
  const { user, loading, isAuthenticated } = useAuth()
  const [volunteers, setVolunteers] = useState<Volunteer[]>([])
  const [dataLoading, setDataLoading] = useState(true)
  const [statusFilter, setStatusFilter] = useState<string>('all')

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated && user?.level !== 'volunteer') {
      fetchVolunteers()
    }
  }, [isAuthenticated, user, statusFilter])

  const fetchVolunteers = async () => {
    try {
      setDataLoading(true)
      const data = await volunteerService.getAll(statusFilter === 'all' ? undefined : statusFilter)
      setVolunteers(data)
    } catch (error) {
      console.error('Error fetching volunteers:', error)
    } finally {
      setDataLoading(false)
    }
  }

  const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'short',
      year: 'numeric',
    })
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

  if (!isAuthenticated || user?.level === 'volunteer') {
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
            <h1 className="text-2xl font-bold text-gray-900">Volunteer</h1>
            <p className="mt-1 text-sm text-gray-600">Kelola volunteer</p>
          </div>
          {(user?.level === 'administrator' || user?.level === 'root') && (
            <Link href="/volunteer/create" className="btn btn-primary">
              Tambah Volunteer
            </Link>
          )}
        </div>
      </div>

      <div className="mb-4 flex space-x-2">
        <button
          onClick={() => setStatusFilter('all')}
          className={`px-4 py-2 rounded-lg text-sm font-medium ${
            statusFilter === 'all'
              ? 'bg-primary-600 text-white'
              : 'bg-white text-gray-700 hover:bg-gray-50'
          }`}
        >
          Semua
        </button>
        <button
          onClick={() => setStatusFilter('1')}
          className={`px-4 py-2 rounded-lg text-sm font-medium ${
            statusFilter === '1'
              ? 'bg-primary-600 text-white'
              : 'bg-white text-gray-700 hover:bg-gray-50'
          }`}
        >
          Active
        </button>
        <button
          onClick={() => setStatusFilter('0')}
          className={`px-4 py-2 rounded-lg text-sm font-medium ${
            statusFilter === '0'
              ? 'bg-primary-600 text-white'
              : 'bg-white text-gray-700 hover:bg-gray-50'
          }`}
        >
          Inactive
        </button>
      </div>

      <div className="bg-white rounded-lg shadow overflow-hidden">
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-gray-200">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Profile
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Nama
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Email
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Group
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Verified
                </th>
                {(user?.level === 'administrator' || user?.level === 'root') && (
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Action
                  </th>
                )}
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-gray-200">
              {volunteers.length === 0 ? (
                <tr>
                  <td colSpan={7} className="px-6 py-4 text-center text-gray-500">
                    Tidak ada volunteer
                  </td>
                </tr>
              ) : (
                volunteers.map((volunteer) => {
                  const imageUrl = volunteer.profile_picture
                    ? `http://localhost:8000/storage/${volunteer.profile_picture}`
                    : 'http://localhost:8000/storage/profile_pictures/user-01.jpg'

                  return (
                    <tr key={volunteer.id} className="hover:bg-gray-50">
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex items-center">
                          <div className="flex-shrink-0 h-10 w-10">
                            <img
                              className="h-10 w-10 rounded-full object-cover"
                              src={imageUrl}
                              alt={volunteer.name}
                            />
                          </div>
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {volunteer.name}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {volunteer.email}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {volunteer.group?.name || '-'}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <span
                          className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                            volunteer.status === 1
                              ? 'bg-green-100 text-green-800'
                              : 'bg-red-100 text-red-800'
                          }`}
                        >
                          {volunteer.status === 1 ? 'Active' : 'Inactive'}
                        </span>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {volunteer.user?.email_verified_at ? (
                          formatDate(volunteer.user.email_verified_at)
                        ) : (
                          <span className="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Not Verified
                          </span>
                        )}
                      </td>
                      {(user?.level === 'administrator' || user?.level === 'root') && (
                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                          <div className="flex space-x-2">
                            <Link
                              href={`/volunteer/${volunteer.id}/edit`}
                              className="text-primary-600 hover:text-primary-900"
                            >
                              Edit
                            </Link>
                            <button
                              onClick={async () => {
                                if (confirm('Apakah Anda yakin ingin menghapus volunteer ini?')) {
                                  try {
                                    await volunteerService.delete(volunteer.id)
                                    fetchVolunteers()
                                  } catch (error) {
                                    alert('Gagal menghapus volunteer')
                                  }
                                }
                              }}
                              className="text-red-600 hover:text-red-900"
                            >
                              Hapus
                            </button>
                          </div>
                        </td>
                      )}
                    </tr>
                  )
                })
              )}
            </tbody>
          </table>
        </div>
      </div>
    </DashboardLayout>
  )
}

