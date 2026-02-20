'use client'

import { useState, useEffect } from 'react'
import { useAuth } from '@/contexts/AuthContext'
import { useRamadan } from '@/hooks/useRamadan'
import RamadanHeader from '@/components/layout/RamadanHeader'
import BottomNav from '@/components/layout/BottomNav'
import HorizontalCalendar from '@/components/ramadan/HorizontalCalendar'
import HabitChecklist from '@/components/ramadan/HabitChecklist'
import DailyStats from '@/components/ramadan/DailyStats'
import toast from 'react-hot-toast'

function formatToday(): string {
  const d = new Date()
  const y = d.getFullYear()
  const m = String(d.getMonth() + 1).padStart(2, '0')
  const day = String(d.getDate()).padStart(2, '0')
  return `${y}-${m}-${day}`
}

function formatDateIndonesian(dateStr: string): string {
  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
  const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
  const d = new Date(dateStr + 'T00:00:00')
  return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`
}

export default function DashboardPage() {
  const { loading: authLoading } = useAuth()
  const { habits, summary, stats, loading, fetchHabits, saveProgress, fetchStats } = useRamadan()
  const [selectedDate, setSelectedDate] = useState(formatToday())
  const [localHabits, setLocalHabits] = useState(habits)
  const [hasChanges, setHasChanges] = useState(false)
  const [saving, setSaving] = useState(false)

  useEffect(() => {
    if (!authLoading) {
      fetchHabits(selectedDate)
      fetchStats()
    }
  }, [selectedDate, authLoading])

  useEffect(() => {
    setLocalHabits(habits)
    setHasChanges(false)
  }, [habits])

  const handleToggle = (habitId: number) => {
    setLocalHabits((prev) =>
      prev.map((h) =>
        h.id === habitId ? { ...h, is_completed: !h.is_completed } : h
      )
    )
    setHasChanges(true)
  }

  const handleSaveProgress = async () => {
    setSaving(true)
    try {
      const completedIds = localHabits.filter((h) => h.is_completed).map((h) => h.id)
      await saveProgress(selectedDate, completedIds)
      toast.success('Progress berhasil disimpan!')
      setHasChanges(false)
      // Refresh data
      await Promise.all([fetchHabits(selectedDate), fetchStats()])
    } catch {
      toast.error('Gagal menyimpan progress')
    } finally {
      setSaving(false)
    }
  }

  const handleDateSelect = (date: string) => {
    if (hasChanges) {
      const confirm = window.confirm('Ada perubahan yang belum disimpan. Lanjutkan pindah tanggal?')
      if (!confirm) return
    }
    setSelectedDate(date)
  }

  const localCompleted = localHabits.filter((h) => h.is_completed).length
  const localTotal = localHabits.length
  const localPercentage = localTotal > 0 ? Math.round((localCompleted / localTotal) * 100) : 0

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

      <main className="max-w-lg mx-auto px-4 py-4 space-y-4">
        {/* Date Display */}
        <div className="flex items-center gap-2 text-ramadan-slate/70 dark:text-gray-400">
          <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
            <path strokeLinecap="round" strokeLinejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span className="text-sm">{formatDateIndonesian(selectedDate)}</span>
        </div>

        {/* Stats Cards */}
        <DailyStats
          summary={{ ...summary, points_today: localCompleted * 10 }}
          stats={stats}
        />

        {/* Horizontal Calendar */}
        <HorizontalCalendar
          selectedDate={selectedDate}
          onSelectDate={handleDateSelect}
        />

        {/* Habit Checklist */}
        {loading && localHabits.length === 0 ? (
          <div className="card text-center py-8">
            <div className="animate-pulse text-ramadan-slate/50">Memuat aktivitas...</div>
          </div>
        ) : (
          <HabitChecklist habits={localHabits} onToggle={handleToggle} />
        )}

        {/* Save Button */}
        {localHabits.length > 0 && (
          <div className="sticky bottom-16 pt-2 pb-2">
            <button
              onClick={handleSaveProgress}
              disabled={saving || !hasChanges}
              className={`w-full py-3.5 rounded-2xl font-semibold text-white transition-all shadow-lg ${
                hasChanges
                  ? 'bg-ramadan-blue hover:bg-ramadan-blue-dark active:scale-[0.98]'
                  : 'bg-gray-300 dark:bg-gray-600 cursor-not-allowed'
              }`}
            >
              {saving ? 'Menyimpan...' : hasChanges ? `Simpan Progress (${localPercentage}%)` : 'Progress Tersimpan'}
            </button>
          </div>
        )}
      </main>

      <BottomNav />
    </div>
  )
}
