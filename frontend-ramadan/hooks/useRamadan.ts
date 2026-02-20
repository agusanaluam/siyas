import { useState, useCallback } from 'react'
import { ramadanService } from '@/lib/api/ramadan'
import { RamadanHabit, HabitsSummary, LeaderboardEntry, UserStats } from '@/types'

export const useRamadan = () => {
  const [habits, setHabits] = useState<RamadanHabit[]>([])
  const [summary, setSummary] = useState<HabitsSummary>({ completed: 0, total: 0, percentage: 0, points_today: 0 })
  const [leaderboard, setLeaderboard] = useState<LeaderboardEntry[]>([])
  const [stats, setStats] = useState<UserStats | null>(null)
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

  return {
    habits,
    summary,
    leaderboard,
    stats,
    loading,
    error,
    fetchHabits,
    saveProgress,
    fetchLeaderboard,
    fetchStats,
  }
}
