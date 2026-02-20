'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import { useAuth } from '@/contexts/AuthContext'
import toast from 'react-hot-toast'

export default function LoginPage() {
  const router = useRouter()
  const { login } = useAuth()
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [loading, setLoading] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    try {
      await login(email, password)
      toast.success('Berhasil masuk!')
      router.push('/dashboard')
    } catch (err: any) {
      toast.error(err?.response?.data?.message || 'Email atau password salah')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen bg-diamond-pattern flex flex-col items-center justify-center px-4 py-8">
      {/* Logo & Title */}
      <div className="text-center mb-8">
        <div className="w-20 h-20 mx-auto mb-4 bg-ramadan-ice rounded-full flex items-center justify-center shadow-sm">
          <span className="text-4xl">🌙</span>
        </div>
        <h1 className="text-2xl font-bold text-ramadan-slate">Mutaba&apos;ah Ramadhan</h1>
        <p className="text-ramadan-slate/60 mt-1">Habit Builder untuk kebiasaan baik</p>
      </div>

      {/* Tab Toggle */}
      <div className="flex w-full max-w-sm mb-6">
        <div className="flex-1 py-3 text-center font-semibold text-ramadan-slate border-2 border-ramadan-slate/30 rounded-xl bg-white">
          Masuk
        </div>
        <Link
          href="/register"
          className="flex-1 py-3 text-center font-semibold text-ramadan-slate/50 hover:text-ramadan-slate transition-colors"
        >
          Daftar
        </Link>
      </div>

      {/* Form Card */}
      <div className="w-full max-w-sm bg-white rounded-2xl p-6 shadow-sm">
        <form onSubmit={handleSubmit} className="space-y-5">
          <div>
            <label className="block text-sm font-semibold text-ramadan-slate mb-2">Email</label>
            <input
              type="email"
              placeholder="nama@email.com"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="input-field"
              required
            />
          </div>

          <div>
            <label className="block text-sm font-semibold text-ramadan-slate mb-2">Password</label>
            <input
              type="password"
              placeholder="Masukkan password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="input-field"
              required
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="btn-primary disabled:opacity-50"
          >
            {loading ? 'Memproses...' : 'Masuk'}
          </button>
        </form>

        <p className="text-center text-sm text-ramadan-slate/50 mt-4">
          Belum punya akun? Klik &quot;Daftar&quot; di atas.
        </p>
      </div>

      {/* Footer */}
      <p className="text-ramadan-blue text-sm mt-8 font-medium">
        Ramadhan 1447H / 2026
      </p>
    </div>
  )
}
