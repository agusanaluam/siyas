'use client'

import { HabitsSummary, UserStats } from '@/types'

interface DailyStatsProps {
  summary: HabitsSummary
  stats: UserStats | null
}

const LEVELS = [
  { name: 'Belum Mulai', min: 0, icon: '🌱' },
  { name: 'Mulai Melangkah', min: 300, icon: '🌿' },
  { name: 'Istiqomah', min: 800, icon: '🌳' },
  { name: 'Mujahid Ramadhan', min: 1500, icon: '⭐' },
  { name: 'Bintang Ramadhan', min: 2500, icon: '🏆' },
]

function getLevel(points: number) {
  let current = LEVELS[0]
  let next = LEVELS[1]
  for (let i = LEVELS.length - 1; i >= 0; i--) {
    if (points >= LEVELS[i].min) {
      current = LEVELS[i]
      next = LEVELS[i + 1] || null
      break
    }
  }
  return { current, next }
}

export default function DailyStats({ summary, stats }: DailyStatsProps) {
  const totalPoints = stats?.total_points || 0
  const daysTracked = stats?.days_tracked || 0
  const { current, next } = getLevel(totalPoints)

  const progressToNext = next
    ? Math.min(100, ((totalPoints - current.min) / (next.min - current.min)) * 100)
    : 100

  return (
    <div className="space-y-3">
      {/* Points Cards */}
      <div className="grid grid-cols-2 gap-3">
        <div className="card text-center">
          <p className="text-2xl font-bold text-ramadan-sage-dark">{summary.points_today}</p>
          <p className="text-xs text-ramadan-brown/50 dark:text-gray-400 mt-1">Poin Hari Ini</p>
        </div>
        <div className="card text-center">
          <p className="text-2xl font-bold text-ramadan-brown dark:text-white">{totalPoints}</p>
          <p className="text-xs text-ramadan-brown/50 dark:text-gray-400 mt-1">Total Poin</p>
        </div>
      </div>

      {/* Streak */}
      <div className="card flex items-center gap-3">
        <div className="relative">
          <div className="w-10 h-10 bg-ramadan-cream dark:bg-gray-700 rounded-full flex items-center justify-center text-xl">
            🔥
          </div>
          {daysTracked > 0 && (
            <div className="absolute -top-1 -right-1 w-5 h-5 bg-ramadan-gold rounded-full flex items-center justify-center">
              <span className="text-[10px] text-white font-bold">{daysTracked}</span>
            </div>
          )}
        </div>
        <div>
          <p className="font-semibold text-sm text-ramadan-brown dark:text-white">
            {daysTracked} hari berturut-turut
          </p>
          <p className="text-xs text-ramadan-brown/50 dark:text-gray-400">
            {daysTracked < 3
              ? `${3 - daysTracked} hari lagi untuk bonus +50 poin`
              : 'Terus semangat!'}
          </p>
        </div>
      </div>

      {/* Level Progress */}
      <div className="card">
        <div className="flex items-center justify-between mb-2">
          <div className="flex items-center gap-2">
            <span className="text-xl">{current.icon}</span>
            <div>
              <p className="font-semibold text-sm text-ramadan-brown dark:text-white">{current.name}</p>
              <p className="text-xs text-ramadan-brown/50 dark:text-gray-400">{totalPoints} poin</p>
            </div>
          </div>
          {next && (
            <div className="flex items-center gap-2 text-right">
              <div>
                <p className="text-xs text-ramadan-brown/50 dark:text-gray-400">Selanjutnya</p>
                <p className="font-semibold text-sm text-ramadan-brown dark:text-white">{next.name}</p>
              </div>
              <span className="text-xl">{next.icon}</span>
            </div>
          )}
        </div>
        {/* Progress bar */}
        <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
          <div
            className="bg-ramadan-sage h-2 rounded-full transition-all duration-500"
            style={{ width: `${progressToNext}%` }}
          />
        </div>
        <div className="flex justify-between mt-1">
          <span className="text-xs text-ramadan-brown/40 dark:text-gray-500">{current.min}</span>
          <span className="text-xs text-ramadan-brown/40 dark:text-gray-500">{next?.min || 'MAX'}</span>
        </div>
      </div>
    </div>
  )
}
