import { useState, useCallback } from 'react'
import { ramadanService } from '@/lib/api/ramadan'
import { RamadanHabit, HabitsSummary, LeaderboardEntry, UserStats, Surah, Murajaah } from '@/types'

export const useRamadan = () => {
  const [habits, setHabits] = useState<RamadanHabit[]>([])
  const [summary, setSummary] = useState<HabitsSummary>({ completed: 0, total: 0, percentage: 0, points_today: 0 })
  const [leaderboard, setLeaderboard] = useState<LeaderboardEntry[]>([])
  const [stats, setStats] = useState<UserStats | null>(null)
  const [surahList, setSurahList] = useState<Surah[]>([])
  const [murajaah, setMurajaah] = useState<Murajaah[]>([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const fetchHabits = useCallback(async (date: string) => {
    try {
      setLoading(true)
      const response = await ramadanService.getHabits(date)
      setHabits(response.habits)
      setSummary(response.summary)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil data habit')
    } finally {
      setLoading(false)
    }
  }, [])

  const saveProgress = useCallback(async (date: string, completedIds: number[]) => {
    try {
      setLoading(true)
      const result = await ramadanService.saveProgress(date, completedIds)
      setError(null)
      return result
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal menyimpan progress')
      throw err
    } finally {
      setLoading(false)
    }
  }, [])

  const fetchLeaderboard = useCallback(async () => {
    try {
      setLoading(true)
      const data = await ramadanService.getLeaderboard()
      setLeaderboard(data)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil leaderboard')
    } finally {
      setLoading(false)
    }
  }, [])

  const fetchStats = useCallback(async () => {
    try {
      setLoading(true)
      const data = await ramadanService.getStats()
      setStats(data)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil statistik')
    } finally {
      setLoading(false)
    }
  }, [])

  const fetchSurahList = useCallback(async () => {
    try {
      const data = await ramadanService.getSurahList()
      setSurahList(data)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil daftar surah')
    }
  }, [])

  const fetchMurajaah = useCallback(async () => {
    try {
      const data = await ramadanService.getMurajaah()
      setMurajaah(data)
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal mengambil data murajaah')
    }
  }, [])

  const saveMurajaah = useCallback(async (data: { surah_number: number; surah_name: string; ayah_number: number }) => {
    try {
      const result = await ramadanService.saveMurajaah(data)
      setMurajaah((prev) => [...prev, result])
      setError(null)
      return result
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal menyimpan murajaah')
      throw err
    }
  }, [])

  const deleteMurajaah = useCallback(async (id: number) => {
    try {
      await ramadanService.deleteMurajaah(id)
      setMurajaah((prev) => prev.filter((m) => m.id !== id))
      setError(null)
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal menghapus murajaah')
      throw err
    }
  }, [])

  return {
    habits,
    summary,
    leaderboard,
    stats,
    surahList,
    murajaah,
    loading,
    error,
    fetchHabits,
    saveProgress,
    fetchLeaderboard,
    fetchStats,
    fetchSurahList,
    fetchMurajaah,
    saveMurajaah,
    deleteMurajaah,
  }
}
