import { useState } from 'react'
import { toast } from 'react-hot-toast'
import { CheckoutRequest, CheckoutResponse } from '@/types/payment'

export function useGetSnapToken() {
  const [token, setToken] = useState<string | null>(null)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const getToken = async (orderData: CheckoutRequest): Promise<string | null> => {
    setLoading(true)
    setError(null)
    setToken(null)

    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'

      const response = await fetch(`${apiUrl}/checkout`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(orderData),
      })

      const data: CheckoutResponse = await response.json()

      if (!response.ok) {
        const errorMessage = data.message || data.error || 'Gagal generate snap token'
        setError(errorMessage)
        toast.error(errorMessage)
        return null
      }

      if (!data.token) {
        const errorMessage = data.message || 'Token tidak ditemukan dalam response'
        setError(errorMessage)
        toast.error(errorMessage)
        return null
      }

      setToken(data.token)
      return data.token

    } catch (err) {
      const errorMessage = err instanceof Error ? err.message : 'Terjadi kesalahan saat generate snap token'
      setError(errorMessage)
      toast.error(errorMessage)
      return null
    } finally {
      setLoading(false)
    }
  }

  return {
    token,
    loading,
    error,
    getToken,
  }
}
