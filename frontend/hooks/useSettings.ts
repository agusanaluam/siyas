import { useState, useCallback, useEffect } from 'react'
import { settingService, Setting } from '@/lib/api/settings'

export const useSettings = () => {
  const [setting, setSetting] = useState<Setting | null>(null)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const fetchSettings = useCallback(async () => {
    try {
      setLoading(true)
      const response = await settingService.getProfile()
      setSetting(response)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil pengaturan')
      console.error(err)
    } finally {
      setLoading(false)
    }
  }, [])

  return {
    setting,
    loading,
    error,
    fetchSettings
  }
}
