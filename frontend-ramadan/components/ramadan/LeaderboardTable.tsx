'use client'

import { LeaderboardEntry } from '@/types'

interface LeaderboardTableProps {
  entries: LeaderboardEntry[]
}

function getMedalIcon(rank: number): string | null {
  switch (rank) {
    case 1: return '🥇'
    case 2: return '🥈'
    case 3: return '🥉'
    default: return null
  }
}

export default function LeaderboardTable({ entries }: LeaderboardTableProps) {
  const top3 = entries.slice(0, 3)
  const rest = entries.slice(3)

  return (
    <div className="space-y-4">
      {/* Top 3 Podium */}
      {top3.length > 0 && (
        <div className="flex items-end justify-center gap-3 py-4">
          {/* Second Place */}
          {top3.length > 1 && (
            <div className="flex flex-col items-center">
              <div className="w-14 h-14 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mb-2">
                <span className="text-2xl">🥈</span>
              </div>
              <p className={`text-xs font-semibold text-center max-w-[80px] truncate ${
                top3[1].is_current_user ? 'text-ramadan-blue-dark' : 'text-ramadan-slate dark:text-white'
              }`}>
                {top3[1].name}
              </p>
              <p className="text-xs text-ramadan-slate/50 dark:text-gray-400">{top3[1].total_points} poin</p>
              <div className="w-20 h-16 bg-gray-200 dark:bg-gray-700 rounded-t-xl mt-2 flex items-center justify-center">
                <span className="text-lg font-bold text-gray-500 dark:text-gray-400">2</span>
              </div>
            </div>
          )}

          {/* First Place */}
          {top3.length > 0 && (
            <div className="flex flex-col items-center -mt-4">
              <div className="w-16 h-16 bg-ramadan-gold/20 border-2 border-ramadan-gold rounded-full flex items-center justify-center mb-2">
                <span className="text-3xl">🥇</span>
              </div>
              <p className={`text-sm font-bold text-center max-w-[80px] truncate ${
                top3[0].is_current_user ? 'text-ramadan-blue-dark' : 'text-ramadan-slate dark:text-white'
              }`}>
                {top3[0].name}
              </p>
              <p className="text-xs text-ramadan-slate/50 dark:text-gray-400">{top3[0].total_points} poin</p>
              <div className="w-20 h-24 bg-ramadan-gold/20 dark:bg-ramadan-gold/10 rounded-t-xl mt-2 flex items-center justify-center">
                <span className="text-xl font-bold text-ramadan-gold">1</span>
              </div>
            </div>
          )}

          {/* Third Place */}
          {top3.length > 2 && (
            <div className="flex flex-col items-center">
              <div className="w-14 h-14 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mb-2">
                <span className="text-2xl">🥉</span>
              </div>
              <p className={`text-xs font-semibold text-center max-w-[80px] truncate ${
                top3[2].is_current_user ? 'text-ramadan-blue-dark' : 'text-ramadan-slate dark:text-white'
              }`}>
                {top3[2].name}
              </p>
              <p className="text-xs text-ramadan-slate/50 dark:text-gray-400">{top3[2].total_points} poin</p>
              <div className="w-20 h-12 bg-amber-100 dark:bg-amber-900/20 rounded-t-xl mt-2 flex items-center justify-center">
                <span className="text-lg font-bold text-amber-600 dark:text-amber-400">3</span>
              </div>
            </div>
          )}
        </div>
      )}

      {/* Rest of the list */}
      <div className="space-y-2">
        {rest.map((entry) => (
          <div
            key={entry.rank}
            className={`card flex items-center gap-3 ${
              entry.is_current_user ? 'ring-2 ring-ramadan-blue bg-ramadan-blue/5' : ''
            }`}
          >
            <div className="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
              <span className="text-sm font-bold text-ramadan-slate/60 dark:text-gray-400">
                {entry.rank}
              </span>
            </div>
            <div className="flex-1 min-w-0">
              <p className={`font-semibold text-sm truncate ${
                entry.is_current_user ? 'text-ramadan-blue-dark' : 'text-ramadan-slate dark:text-white'
              }`}>
                {entry.name}
                {entry.is_current_user && (
                  <span className="text-xs ml-1 text-ramadan-blue">(Kamu)</span>
                )}
              </p>
            </div>
            <div className="text-right flex-shrink-0">
              <p className="font-bold text-sm text-ramadan-slate dark:text-white">{entry.total_points}</p>
              <p className="text-xs text-ramadan-slate/40 dark:text-gray-500">poin</p>
            </div>
          </div>
        ))}
      </div>

      {entries.length === 0 && (
        <div className="card text-center py-8">
          <p className="text-ramadan-slate/50 dark:text-gray-400">Belum ada data peringkat</p>
        </div>
      )}
    </div>
  )
}
