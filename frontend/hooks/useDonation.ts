import { useState, useCallback } from 'react'
import { donationService } from '@/lib/api/donation'
import { Donation } from '@/types'

// Define DonationAccount interface locally or in types if needed globally
export interface DonationAccount {
  id: number
  bank_name: string
  account_number: string
  account_holder: string
}

export const useDonation = () => {
  const [donations, setDonations] = useState<Donation[]>([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const fetchDonations = useCallback(async (status?: boolean) => {
    try {
      setLoading(true)
      const response = await donationService.getAll(status)
      setDonations(response)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil data donasi')
      console.error(err)
    } finally {
      setLoading(false)
    }
  }, [])

  return {
    donations,
    loading,
    error,
    fetchDonations
  }
}

export const useDonationAccounts = () => {
  const [accounts, setAccounts] = useState<DonationAccount[]>([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const fetchAccounts = useCallback(async () => {
    try {
      setLoading(true)
      const response = await donationService.getAccounts()
      setAccounts(response)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil rekening donasi')
      console.error(err)
    } finally {
      setLoading(false)
    }
  }, [])

  return {
    accounts,
    loading,
    error,
    fetchAccounts
  }
}
