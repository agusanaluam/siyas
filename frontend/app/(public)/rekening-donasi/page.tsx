'use client'

import { useState, useEffect } from 'react'
import LandingHeader from '@/components/landing/LandingHeader'
import LandingFooter from '@/components/landing/LandingFooter'

interface Setting {
  name: string
  description: string
}

interface DonationAccount {
  id: number
  bank_name: string
  account_number: string
  account_holder: string
}

export default function RekeningDonasiPage() {
  const [setting, setSetting] = useState<Setting | null>(null)
  const [accounts, setAccounts] = useState<DonationAccount[]>([])

  useEffect(() => {
    fetchSettings()
    fetchAccounts()
  }, [])

  const fetchSettings = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/settings/profile`)
      const data = await response.json()
      setSetting(data)
    } catch (error) {
      console.error('Error fetching settings:', error)
    }
  }

  const fetchAccounts = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/donation-accounts`)
      const data = await response.json()
      setAccounts(data)
    } catch (error) {
      console.error('Error fetching accounts:', error)
    }
  }

  return (
    <main className="min-h-screen bg-gradient-to-b from-brand-50 via-white to-support-50">
      <LandingHeader forceScrolledStyle={true} />

      <section className="pt-32 pb-16">
        <div className="container mx-auto px-4 md:px-[150px]">
          <div className="flex flex-col md:flex-row items-center gap-12">
            {/* Image Side */}
            <div className="w-full md:w-1/2">
              <div className="relative rounded-2xl overflow-hidden shadow-xl">
                <img
                  src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=1600&auto=format&fit=crop"
                  alt="Rekening Donasi"
                  className="w-full h-[400px] object-cover grayscale hover:grayscale-0 transition-all duration-500"
                />
                <div className="absolute inset-0 bg-black/10"></div>
              </div>
            </div>

            {/* Content Side */}
            <div className="w-full md:w-1/2">
              <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Rekening Donasi {setting?.name || 'Yayasan'}
              </h2>
              <p className="text-gray-600 text-lg leading-relaxed mb-8">
                {setting?.description || 'Salurkan donasi terbaik anda melalui rekening resmi kami.'}
              </p>

              <div className="space-y-3">
                <h3 className="text-xl font-semibold text-gray-900">Daftar Rekening Donasi</h3>
                {accounts.length === 0 ? (
                  <p className="text-gray-600 text-sm">Belum ada rekening donasi.</p>
                ) : (
                  <div className="space-y-3">
                    {accounts.map((acc) => (
                      <div
                        key={acc.id}
                        className="border border-gray-100 rounded-xl p-3 shadow-sm bg-brand-50/40"
                      >
                        <p className="text-sm text-gray-500">{acc.bank_name}</p>
                        <p className="text-lg font-semibold text-gray-900">{acc.account_number}</p>
                        <p className="text-sm text-gray-700">a.n {acc.account_holder}</p>
                      </div>
                    ))}
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </section>

      <LandingFooter />
    </main>
  )
}
