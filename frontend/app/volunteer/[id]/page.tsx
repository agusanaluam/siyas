'use client'

import { useEffect, useState } from 'react'
import { useRouter, useParams } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { volunteerService, Volunteer } from '@/lib/api/volunteer'

export default function VolunteerDetailsPage() {
  const router = useRouter()
  const params = useParams()
  const { user, loading, isAuthenticated } = useAuth()
  const [volunteer, setVolunteer] = useState<Volunteer | null>(null)
  const [dataLoading, setDataLoading] = useState(true)

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      router.push('/login')
    }
  }, [loading, isAuthenticated, router])

  useEffect(() => {
    if (isAuthenticated && params.id) {
      fetchVolunteer()
    }
  }, [isAuthenticated, params.id])

  const fetchVolunteer = async () => {
    try {
      setDataLoading(true)
      const data = await volunteerService.getById(Number(params.id))
      setVolunteer(data)
    } catch (error) {
      console.error('Error fetching volunteer:', error)
      router.push('/volunteer')
    } finally {
      setDataLoading(false)
    }
  }

  const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
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

  if (!isAuthenticated || !volunteer) {
    return null
  }

  const imageUrl = volunteer.profile_picture
    ? `http://localhost:8000/storage/${volunteer.profile_picture}`
    : 'http://localhost:8000/storage/profile_pictures/user-01.jpg'

  return (
    <DashboardLayout>
      <div className="mb-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Detail Volunteer</h1>
            <p className="mt-1 text-sm text-gray-600">Detail lengkap volunteer</p>
          </div>
          <div className="flex space-x-2">
            {(user?.level === 'administrator' || user?.level === 'root') && (
              <Link href={`/volunteer/${volunteer.id}/edit`} className="btn btn-primary">
                Edit Volunteer
              </Link>
            )}
            <Link href="/volunteer" className="btn btn-secondary">
              Kembali ke List
            </Link>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2">
          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Informasi Volunteer</h2>
            <dl className="space-y-4">
              <div>
                <dt className="text-sm font-medium text-gray-500">Nama</dt>
                <dd className="mt-1 text-sm text-gray-900">{volunteer.name}</dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Email</dt>
                <dd className="mt-1 text-sm text-gray-900">{volunteer.email}</dd>
              </div>
              {volunteer.nik && (
                <div>
                  <dt className="text-sm font-medium text-gray-500">NIK</dt>
                  <dd className="mt-1 text-sm text-gray-900">{volunteer.nik}</dd>
                </div>
              )}
              {volunteer.phone_number && (
                <div>
                  <dt className="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                  <dd className="mt-1 text-sm text-gray-900">{volunteer.phone_number}</dd>
                </div>
              )}
              {volunteer.sex && (
                <div>
                  <dt className="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                  <dd className="mt-1 text-sm text-gray-900">
                    {volunteer.sex === 'L' ? 'Laki-laki' : 'Perempuan'}
                  </dd>
                </div>
              )}
              {volunteer.birth_date && (
                <div>
                  <dt className="text-sm font-medium text-gray-500">Tanggal Lahir</dt>
                  <dd className="mt-1 text-sm text-gray-900">
                    {formatDate(volunteer.birth_date)}
                  </dd>
                </div>
              )}
              {volunteer.address && (
                <div>
                  <dt className="text-sm font-medium text-gray-500">Alamat</dt>
                  <dd className="mt-1 text-sm text-gray-900">{volunteer.address}</dd>
                </div>
              )}
              <div>
                <dt className="text-sm font-medium text-gray-500">Group</dt>
                <dd className="mt-1 text-sm text-gray-900">
                  {volunteer.group?.name || '-'}
                </dd>
              </div>
              <div>
                <dt className="text-sm font-medium text-gray-500">Status</dt>
                <dd className="mt-1">
                  <span
                    className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                      volunteer.status === 1
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800'
                    }`}
                  >
                    {volunteer.status === 1 ? 'Active' : 'Inactive'}
                  </span>
                </dd>
              </div>
              {volunteer.user?.email_verified_at && (
                <div>
                  <dt className="text-sm font-medium text-gray-500">Email Verified At</dt>
                  <dd className="mt-1 text-sm text-gray-900">
                    {formatDate(volunteer.user.email_verified_at)}
                  </dd>
                </div>
              )}
            </dl>
          </div>
        </div>

        <div>
          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Foto Profil</h2>
            <img
              src={imageUrl}
              alt={volunteer.name}
              className="w-full rounded-lg"
            />
          </div>
        </div>
      </div>
    </DashboardLayout>
  )
}

