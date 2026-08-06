'use client'

import { useState, useEffect } from 'react'
import { useRouter, useParams } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { volunteerService, Volunteer } from '@/lib/api/volunteer'
import { apiClient } from '@/lib/api/client'

export default function EditVolunteerPage() {
  const router = useRouter()
  const params = useParams()
  const { user, loading, isAuthenticated } = useAuth()
  const [volunteer, setVolunteer] = useState<Volunteer | null>(null)
  const [formData, setFormData] = useState({
    nik: '',
    name: '',
    phone_number: '',
    sex: '',
    birth_date: '',
    address: '',
    address_code: '',
  })
  const [provinsi, setProvinsi] = useState<any[]>([])
  const [kota, setKota] = useState<any[]>([])
  const [kecamatan, setKecamatan] = useState<any[]>([])
  const [desa, setDesa] = useState<any[]>([])
  const [profilePicture, setProfilePicture] = useState<File | null>(null)
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
      const [volunteerData, provinsiData] = await Promise.all([
        volunteerService.getById(Number(params.id)),
        apiClient.get('/location/provinsi'),
      ])

      setVolunteer(volunteerData)
      setProvinsi(provinsiData.data)

      setFormData({
        nik: volunteerData.nik || '',
        name: volunteerData.name || '',
        phone_number: volunteerData.phone_number || '',
        sex: volunteerData.sex || '',
        birth_date: volunteerData.birth_date || '',
        address: volunteerData.address || '',
        address_code: volunteerData.address_code || '',
      })

      if (volunteerData.address_code) {
        loadLocationData(volunteerData.address_code)
      }
    } catch (error) {
      console.error('Error fetching data:', error)
      router.push('/volunteer')
    } finally {
      setDataLoading(false)
    }
  }

  const loadLocationData = async (addressCode: string) => {
    if (addressCode.length >= 2) {
      const provinsiCode = addressCode.substring(0, 2)
      try {
        const kotaResponse = await apiClient.get(`/location/kota/${provinsiCode}`)
        setKota(kotaResponse.data)

        if (addressCode.length >= 5) {
          const kotaCode = addressCode.substring(0, 5)
          const kecamatanResponse = await apiClient.get(`/location/kecamatan/${kotaCode}`)
          setKecamatan(kecamatanResponse.data)

          if (addressCode.length >= 8) {
            const kecamatanCode = addressCode.substring(0, 8)
            const desaResponse = await apiClient.get(`/location/desa/${kecamatanCode}`)
            setDesa(desaResponse.data)
          }
        }
      } catch (error) {
        console.error('Error loading location data:', error)
      }
    }
  }

  const handleProvinsiChange = async (provinsiCode: string) => {
    setKota([])
    setKecamatan([])
    setDesa([])
    setFormData({ ...formData, address_code: provinsiCode })

    try {
      const response = await apiClient.get(`/location/kota/${provinsiCode}`)
      setKota(response.data)
    } catch (error) {
      console.error('Error fetching kota:', error)
    }
  }

  const handleKotaChange = async (kotaCode: string) => {
    setKecamatan([])
    setDesa([])
    setFormData({ ...formData, address_code: kotaCode })

    try {
      const response = await apiClient.get(`/location/kecamatan/${kotaCode}`)
      setKecamatan(response.data)
    } catch (error) {
      console.error('Error fetching kecamatan:', error)
    }
  }

  const handleKecamatanChange = async (kecamatanCode: string) => {
    setDesa([])
    setFormData({ ...formData, address_code: kecamatanCode })

    try {
      const response = await apiClient.get(`/location/desa/${kecamatanCode}`)
      setDesa(response.data)
    } catch (error) {
      console.error('Error fetching desa:', error)
    }
  }

  const handleDesaChange = (desaCode: string) => {
    setFormData({ ...formData, address_code: desaCode })
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setSubmitting(true)

    try {
      const formDataToSend = new FormData()
      formDataToSend.append('_method', 'PUT')
      formDataToSend.append('nik', formData.nik)
      formDataToSend.append('name', formData.name)
      formDataToSend.append('phone_number', formData.phone_number)
      formDataToSend.append('sex', formData.sex)
      formDataToSend.append('birth_date', formData.birth_date)
      formDataToSend.append('address', formData.address)
      formDataToSend.append('address_code', formData.address_code)
      if (profilePicture) {
        formDataToSend.append('profile_picture', profilePicture)
      }

      await volunteerService.update(Number(params.id), formDataToSend)
      router.push('/volunteer')
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Gagal mengupdate volunteer' })
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

  if (!isAuthenticated || !volunteer) {
    return null
  }

  const currentProvinsi = formData.address_code.substring(0, 2)
  const currentKota = formData.address_code.substring(0, 5)
  const currentKecamatan = formData.address_code.substring(0, 8)

  return (
    <DashboardLayout>
      <div className="mb-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Edit Volunteer</h1>
            <p className="mt-1 text-sm text-gray-600">Edit volunteer</p>
          </div>
          <Link href="/volunteer" className="btn btn-secondary">
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

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label className="label">NIK *</label>
            <input
              type="text"
              className="input"
              value={formData.nik}
              onChange={(e) => setFormData({ ...formData, nik: e.target.value })}
              required
            />
            {errors.nik && <p className="mt-1 text-sm text-red-600">{errors.nik}</p>}
          </div>

          <div>
            <label className="label">Nama Lengkap *</label>
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
            <label className="label">Jenis Kelamin *</label>
            <select
              className="input"
              value={formData.sex}
              onChange={(e) => setFormData({ ...formData, sex: e.target.value })}
              required
            >
              <option value="">--Pilih--</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
            {errors.sex && <p className="mt-1 text-sm text-red-600">{errors.sex}</p>}
          </div>

          <div>
            <label className="label">Tanggal Lahir *</label>
            <input
              type="date"
              className="input"
              value={formData.birth_date}
              onChange={(e) => setFormData({ ...formData, birth_date: e.target.value })}
              required
            />
            {errors.birth_date && (
              <p className="mt-1 text-sm text-red-600">{errors.birth_date}</p>
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
            <label className="label">Foto Profil</label>
            <input
              type="file"
              accept="image/*"
              onChange={(e) => setProfilePicture(e.target.files?.[0] || null)}
              className="input"
            />
            {volunteer.profile_picture && !profilePicture && (
              <div className="mt-2">
                <img
                  src={`http://localhost:8000/storage/${volunteer.profile_picture}`}
                  alt="Current profile"
                  className="w-24 h-24 object-cover rounded-full"
                />
              </div>
            )}
            {profilePicture && (
              <div className="mt-2">
                <img
                  src={URL.createObjectURL(profilePicture)}
                  alt="Preview"
                  className="w-24 h-24 object-cover rounded-full"
                />
              </div>
            )}
          </div>

          <div className="md:col-span-2">
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
            <label className="label">Provinsi *</label>
            <select
              className="input"
              value={currentProvinsi}
              onChange={(e) => handleProvinsiChange(e.target.value)}
              required
            >
              <option value="">--Pilih Provinsi--</option>
              {provinsi.map((p) => (
                <option key={p.code} value={p.code}>
                  {p.name}
                </option>
              ))}
            </select>
          </div>

          <div>
            <label className="label">Kota/Kabupaten *</label>
            <select
              className="input"
              value={currentKota}
              onChange={(e) => handleKotaChange(e.target.value)}
              required
              disabled={!currentProvinsi}
            >
              <option value="">--Pilih Kota/Kabupaten--</option>
              {kota.map((k) => (
                <option key={k.code} value={k.code}>
                  {k.name}
                </option>
              ))}
            </select>
          </div>

          <div>
            <label className="label">Kecamatan *</label>
            <select
              className="input"
              value={currentKecamatan}
              onChange={(e) => handleKecamatanChange(e.target.value)}
              required
              disabled={!currentKota}
            >
              <option value="">--Pilih Kecamatan--</option>
              {kecamatan.map((k) => (
                <option key={k.code} value={k.code}>
                  {k.name}
                </option>
              ))}
            </select>
          </div>

          <div>
            <label className="label">Desa/Kelurahan *</label>
            <select
              className="input"
              value={formData.address_code}
              onChange={(e) => handleDesaChange(e.target.value)}
              required
              disabled={!currentKecamatan}
            >
              <option value="">--Pilih Desa/Kelurahan--</option>
              {desa.map((d) => (
                <option key={d.code} value={d.code}>
                  {d.name}
                </option>
              ))}
            </select>
            {errors.address_code && (
              <p className="mt-1 text-sm text-red-600">{errors.address_code}</p>
            )}
          </div>
        </div>

        <div className="mt-6 flex justify-end">
          <button type="submit" className="btn btn-primary" disabled={submitting}>
            {submitting ? 'Menyimpan...' : 'Update Volunteer'}
          </button>
        </div>
      </form>
    </DashboardLayout>
  )
}

