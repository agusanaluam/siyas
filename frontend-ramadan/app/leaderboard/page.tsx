'use client'

import { useEffect } from 'react'
import { useAuth } from '@/contexts/AuthContext'
import { useRamadan } from '@/hooks/useRamadan'
import RamadanHeader from '@/components/layout/RamadanHeader'
import BottomNav from '@/components/layout/BottomNav'
import LeaderboardTable from '@/components/ramadan/LeaderboardTable'

export default function LeaderboardPage() {
  const { loading: authLoading } = useAuth()
  const { leaderboard, loading, fetchLeaderboard } = useRamadan()

  useEffect(() => {
    if (!authLoading) {
      fetchLeaderboard()
    }
  }, [authLoading])

  if (authLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-pulse text-ramadan-slate text-lg">Memuat...</div>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-ramadan-snow dark:bg-gray-900 pb-20">
      <RamadanHeader />

      <main className="max-w-lg mx-auto px-4 py-4">
        <div className="text-center mb-4">
          <h1 className="text-xl font-bold text-ramadan-slate dark:text-white">Peringkat</h1>
          <p className="text-sm text-ramadan-slate/50 dark:text-gray-400 mt-1">
            Ramadhan 1447H / 2026
          </p>
        </div>

        {loading && leaderboard.length === 0 ? (
          <div className="card text-center py-8">
            <div className="animate-pulse text-ramadan-slate/50">Memuat peringkat...</div>
          </div>
        ) : (
          <LeaderboardTable entries={leaderboard} />
        )}
      </main>

      <BottomNav />
    </div>
  )
}
