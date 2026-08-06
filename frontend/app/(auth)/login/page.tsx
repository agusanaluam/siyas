'use client'

import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import Image from 'next/image'
import { useAuth } from '@/hooks/useAuth'
import { useSettings } from '@/hooks/useSettings'
import { getImageUrl } from '@/lib/utils'
import { ApiError } from '@/lib/api/auth'

export default function LoginPage() {
  const router = useRouter()
  const { login } = useAuth()
  const { setting, fetchSettings } = useSettings()
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [rememberMe, setRememberMe] = useState(false)
  const [showPassword, setShowPassword] = useState(false)
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [loading, setLoading] = useState(false)

  const [loginRole, setLoginRole] = useState<'relawan' | 'donatur'>('donatur')

  useEffect(() => {
    fetchSettings()
  }, [fetchSettings])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setLoading(true)

    try {
      await login(email, password)
      router.push('/dashboard')
    } catch (error: any) {
      if (error.response?.data?.errors) {
        setErrors(error.response.data.errors)
      } else if (error.response?.data?.message) {
        setErrors({ email: error.response.data.message })
      } else {
        setErrors({ email: 'Terjadi kesalahan saat login' })
      }
    } finally {
      setLoading(false)
    }
  }

  const logoUrl = setting?.photo
    ? getImageUrl(setting.photo)
    : null

  return (
    <div className="min-h-screen flex flex-col items-center justify-center bg-white relative overflow-hidden">
      {/* Back to Home Button */}
      <Link
        href="/"
        className="absolute top-6 left-6 z-20 flex items-center text-gray-600 hover:text-primary-600 transition-colors"
      >
        <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Landing Page
      </Link>

      {/* Background wavy shapes */}
      <div className="absolute bottom-0 left-0 right-0 h-64 overflow-hidden pointer-events-none">
        <div className="absolute bottom-0 left-0 w-96 h-96 rounded-full opacity-20 blur-3xl transform -translate-x-1/4 translate-y-1/4" style={{ backgroundColor: '#1C63E3' }}></div>
        <div className="absolute bottom-0 right-0 w-96 h-96 rounded-full opacity-20 blur-3xl transform translate-x-1/4 translate-y-1/4" style={{ backgroundColor: '#5B8FE8' }}></div>
      </div>

      {/* Logo */}
      <div className="mb-8 relative z-10 text-center">
        <div className="flex flex-col items-center justify-center gap-4">
          {logoUrl ? (
            <div className="relative w-20 h-20">
              <Image
                src={logoUrl}
                alt={setting?.name || 'Logo'}
                className="object-contain"
                fill
                sizes="80px"
              />
            </div>
          ) : (
            <div className="w-20 h-20 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
          )}
          <h1 className="text-2xl font-bold text-gray-900">
            {setting?.name || 'SIYAS'}
          </h1>
        </div>
      </div>

      {/* Login Card */}
      <div className="w-full max-w-md px-6 relative z-10">
        <div className="bg-white rounded-2xl shadow-lg p-8">
          <h2 className="text-3xl font-bold text-gray-900 mb-2">Masuk</h2>
          <p className="text-sm text-gray-600 mb-8">
            Akses panel SIYAS menggunakan email dan kata sandi Anda.
          </p>

          <div className="flex rounded-md shadow-sm mb-6" role="group">
            <button
              type="button"
              onClick={() => setLoginRole('relawan')}
              className={`flex-1 px-4 py-2 text-sm font-medium text-center border rounded-l-lg transition-colors ${loginRole === 'relawan'
                  ? 'bg-primary-600 text-white border-primary-600'
                  : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                }`}
            >
              Relawan & Staf
            </button>
            <button
              type="button"
              onClick={() => setLoginRole('donatur')}
              className={`flex-1 px-4 py-2 text-sm font-medium text-center border rounded-r-lg transition-colors ${loginRole === 'donatur'
                  ? 'bg-primary-600 text-white border-primary-600'
                  : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                }`}
            >
              Donatur
            </button>
          </div>

          <form onSubmit={handleSubmit} className="space-y-6">
            {/* Email Input */}
            <div>
              <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-2">
                Email <span className="text-red-500">*</span>
              </label>
              <div className="relative">
                <input
                  id="email"
                  name="email"
                  type="email"
                  autoComplete="email"
                  required
                  className="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent text-sm"
                  style={{ '--tw-ring-color': '#1C63E3' } as React.CSSProperties}
                  onFocus={(e) => e.currentTarget.style.boxShadow = '0 0 0 2px #1C63E3'}
                  onBlur={(e) => e.currentTarget.style.boxShadow = ''}
                  placeholder="Masukkan email Anda"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                />
                <div className="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                  <svg className="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
              </div>
              {errors.email && (
                <p className="mt-1 text-sm text-red-600">{errors.email}</p>
              )}
            </div>

            {/* Password Input */}
            <div>
              <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-2">
                Kata Sandi <span className="text-red-500">*</span>
              </label>
              <div className="relative">
                <input
                  id="password"
                  name="password"
                  type={showPassword ? 'text' : 'password'}
                  autoComplete="current-password"
                  required
                  className="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent text-sm"
                  style={{ '--tw-ring-color': '#1C63E3' } as React.CSSProperties}
                  onFocus={(e) => e.currentTarget.style.boxShadow = '0 0 0 2px #1C63E3'}
                  onBlur={(e) => e.currentTarget.style.boxShadow = ''}
                  placeholder="Masukkan kata sandi Anda"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600"
                >
                  {showPassword ? (
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                  ) : (
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  )}
                </button>
              </div>
              {errors.password && (
                <p className="mt-1 text-sm text-red-600">{errors.password}</p>
              )}
            </div>

            {/* Remember Me & Forgot Password */}
            <div className="flex items-center justify-between">
              <div className="flex items-center">
                <input
                  id="remember-me"
                  name="remember-me"
                  type="checkbox"
                  checked={rememberMe}
                  onChange={(e) => setRememberMe(e.target.checked)}
                  className="h-4 w-4 border-gray-300 rounded focus:ring-2"
                  style={{ accentColor: '#1C63E3' } as React.CSSProperties}
                />
                <label htmlFor="remember-me" className="ml-2 block text-sm text-gray-700">
                  Ingat saya
                </label>
              </div>
              <Link href="/forgot-password" className="text-sm font-medium hover:opacity-80 transition-opacity" style={{ color: '#1C63E3' }}>
                Lupa kata sandi?
              </Link>
            </div>

            {/* Sign In Button */}
            <div>
              <button
                type="submit"
                disabled={loading}
                className="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-sm font-medium text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                style={{ backgroundColor: '#1C63E3', '--tw-ring-color': '#1C63E3' } as React.CSSProperties}
              >
                {loading ? 'Memproses...' : 'Masuk'}
              </button>
            </div>

            {/* Create Account Link */}
            <div className="text-center text-sm text-gray-600">
              Baru di platform kami?{' '}
              <Link href="/register" className="font-semibold text-gray-900 hover:opacity-80 transition-opacity" style={{ '--hover-color': '#1C63E3' } as React.CSSProperties} onMouseEnter={(e) => e.currentTarget.style.color = '#1C63E3'} onMouseLeave={(e) => e.currentTarget.style.color = '#111827'}>
                Buat akun
              </Link>
            </div>
          </form>
        </div>
      </div>

      {/* Footer */}
      <div className="mt-8 text-center text-sm text-gray-500 relative z-10">
        Copyright © {new Date().getFullYear()} SIYAS
      </div>
    </div>
  )
}

