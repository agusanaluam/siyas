'use client'

import { useState, useEffect } from 'react'
import { useRouter, useSearchParams } from 'next/navigation'
import Link from 'next/link'
import { authService } from '@/lib/api/auth'

export default function EmailVerificationPage() {
  const router = useRouter()
  const searchParams = useSearchParams()
  const [email, setEmail] = useState('')
  const [loading, setLoading] = useState(false)
  const [message, setMessage] = useState('')

  useEffect(() => {
    const emailParam = searchParams.get('email')
    const token = searchParams.get('token')
    
    if (emailParam) {
      setEmail(emailParam)
    }

    if (emailParam && token) {
      verifyEmail(emailParam, token)
    }
  }, [searchParams])

  const verifyEmail = async (email: string, token: string) => {
    setLoading(true)
    try {
      await authService.verifyEmail(email, token)
      setMessage('Email berhasil diverifikasi!')
      setTimeout(() => {
        router.push('/login')
      }, 2000)
    } catch (error: any) {
      setMessage(error.response?.data?.message || 'Terjadi kesalahan saat verifikasi email')
    } finally {
      setLoading(false)
    }
  }

  const handleResend = async () => {
    if (!email) return
    
    setLoading(true)
    try {
      await authService.resendVerification(email)
      setMessage('Link verifikasi telah dikirim ulang ke email Anda.')
    } catch (error: any) {
      setMessage(error.response?.data?.message || 'Terjadi kesalahan saat mengirim ulang email')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8 text-center">
        <div>
          <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Verifikasi Email Kamu
          </h2>
          <p className="mt-2 text-sm text-gray-600">
            Kami telah mengirimkan email verifikasi ke email anda: <strong>{email || 'email Anda'}</strong>. 
            Silahkan ikuti petunjuk berikutnya pada email.
          </p>
        </div>

        {message && (
          <div className={`rounded-md p-4 ${
            message.includes('berhasil') || message.includes('dikirim')
              ? 'bg-green-50 text-green-800'
              : 'bg-red-50 text-red-800'
          }`}>
            <p className="text-sm">{message}</p>
          </div>
        )}

        <div className="space-y-4">
          <div className="text-sm text-gray-600">
            <span>Tidak menerima pesan? </span>
            <button
              onClick={handleResend}
              disabled={loading || !email}
              className="font-medium text-primary-600 hover:text-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Kirim ulang Link
            </button>
          </div>

          <div>
            <Link
              href="/login"
              className="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
              Lewati Dulu
            </Link>
          </div>
        </div>
      </div>
    </div>
  )
}

