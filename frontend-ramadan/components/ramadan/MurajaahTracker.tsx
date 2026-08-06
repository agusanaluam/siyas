'use client'

import { useState } from 'react'
import { Surah, Murajaah } from '@/types'
import toast from 'react-hot-toast'

interface MurajaahTrackerProps {
  surahList: Surah[]
  murajaah: Murajaah[]
  onSave: (data: { surah_number: number; surah_name: string; ayah_number: number }) => Promise<Murajaah>
  onDelete: (id: number) => Promise<void>
}

export default function MurajaahTracker({ surahList, murajaah, onSave, onDelete }: MurajaahTrackerProps) {
  const [selectedSurah, setSelectedSurah] = useState<Surah | null>(null)
  const [selectedAyah, setSelectedAyah] = useState<number>(0)
  const [saving, setSaving] = useState(false)
  const [expandedId, setExpandedId] = useState<number | null>(null)
  const [deletingId, setDeletingId] = useState<number | null>(null)

  const handleSurahChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    const nomor = parseInt(e.target.value)
    const surah = surahList.find((s) => s.nomor === nomor) || null
    setSelectedSurah(surah)
    setSelectedAyah(0)
  }

  const handleSave = async () => {
    if (!selectedSurah || !selectedAyah) return

    setSaving(true)
    try {
      await onSave({
        surah_number: selectedSurah.nomor,
        surah_name: selectedSurah.nama_latin,
        ayah_number: selectedAyah,
      })
      toast.success('Ayat berhasil disimpan!')
      setSelectedAyah(0)
    } catch {
      toast.error('Gagal menyimpan ayat')
    } finally {
      setSaving(false)
    }
  }

  const handleDelete = async (id: number) => {
    setDeletingId(id)
    try {
      await onDelete(id)
      toast.success('Ayat berhasil dihapus')
      if (expandedId === id) setExpandedId(null)
    } catch {
      toast.error('Gagal menghapus ayat')
    } finally {
      setDeletingId(null)
    }
  }

  const toggleExpand = (id: number) => {
    setExpandedId(expandedId === id ? null : id)
  }

  // Group murajaah by surah
  const groupedMurajaah = murajaah.reduce<Record<string, Murajaah[]>>((acc, item) => {
    const key = `${item.surah_number}-${item.surah_name}`
    if (!acc[key]) acc[key] = []
    acc[key].push(item)
    return acc
  }, {})

  return (
    <div className="space-y-3">
      <h2 className="font-bold text-ramadan-slate dark:text-white flex items-center gap-2">
        <span className="text-lg">📖</span> Muraja&apos;ah Hafalan
      </h2>

      {/* Add New Ayah Form */}
      <div className="card space-y-3">
        {/* Surah Dropdown */}
        <div>
          <label className="block text-xs font-medium text-ramadan-slate/60 dark:text-gray-400 mb-1">
            Pilih Surah
          </label>
          <select
            value={selectedSurah?.nomor || ''}
            onChange={handleSurahChange}
            className="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-ramadan-slate dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-ramadan-blue/50"
          >
            <option value="">-- Pilih Surah --</option>
            {surahList.map((surah) => (
              <option key={surah.nomor} value={surah.nomor}>
                {surah.nomor}. {surah.nama_latin} ({surah.jumlah_ayat} ayat)
              </option>
            ))}
          </select>
        </div>

        {/* Ayah Number Dropdown */}
        {selectedSurah && (
          <div>
            <label className="block text-xs font-medium text-ramadan-slate/60 dark:text-gray-400 mb-1">
              Pilih Ayat
            </label>
            <select
              value={selectedAyah || ''}
              onChange={(e) => setSelectedAyah(parseInt(e.target.value))}
              className="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-ramadan-slate dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-ramadan-blue/50"
            >
              <option value="">-- Pilih Ayat --</option>
              {Array.from({ length: selectedSurah.jumlah_ayat }, (_, i) => i + 1).map((num) => (
                <option key={num} value={num}>
                  Ayat {num}
                </option>
              ))}
            </select>
          </div>
        )}

        {/* Save Button */}
        <button
          onClick={handleSave}
          disabled={!selectedSurah || !selectedAyah || saving}
          className={`w-full py-2.5 rounded-xl font-semibold text-sm transition-all ${
            selectedSurah && selectedAyah && !saving
              ? 'bg-ramadan-blue text-white hover:bg-ramadan-blue-dark active:scale-[0.98]'
              : 'bg-gray-200 dark:bg-gray-600 text-gray-400 cursor-not-allowed'
          }`}
        >
          {saving ? 'Menyimpan...' : 'Simpan Ayat'}
        </button>
      </div>

      {/* Saved Murajaah List */}
      {Object.keys(groupedMurajaah).length > 0 && (
        <div className="space-y-2">
          <h3 className="text-sm font-semibold text-ramadan-slate/70 dark:text-gray-400">
            Ayat Tersimpan
          </h3>
          {Object.entries(groupedMurajaah).map(([key, items]) => (
            <div key={key} className="space-y-1.5">
              {items.map((item) => (
                <div key={item.id} className="card overflow-hidden !p-0">
                  {/* Collapsed Header */}
                  <button
                    onClick={() => toggleExpand(item.id)}
                    className="w-full flex items-center justify-between p-3 text-left"
                  >
                    <div className="flex items-center gap-2">
                      <span className="text-ramadan-blue font-medium text-sm">
                        {item.surah_name}
                      </span>
                      <span className="text-xs bg-ramadan-ice dark:bg-gray-600 text-ramadan-blue-dark dark:text-ramadan-blue px-2 py-0.5 rounded-full">
                        Ayat {item.ayah_number}
                      </span>
                    </div>
                    <div className="flex items-center gap-2">
                      <button
                        onClick={(e) => {
                          e.stopPropagation()
                          handleDelete(item.id)
                        }}
                        disabled={deletingId === item.id}
                        className="text-red-400 hover:text-red-600 p-1"
                      >
                        <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                          <path strokeLinecap="round" strokeLinejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                      <svg
                        className={`w-4 h-4 text-gray-400 transition-transform ${expandedId === item.id ? 'rotate-180' : ''}`}
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}
                      >
                        <path strokeLinecap="round" strokeLinejoin="round" d="M19 9l-7 7-7-7" />
                      </svg>
                    </div>
                  </button>

                  {/* Expanded Content */}
                  {expandedId === item.id && (
                    <div className="border-t border-gray-100 dark:border-gray-700 p-4 space-y-3">
                      {/* Arabic */}
                      <div>
                        <p className="text-xs font-medium text-ramadan-slate/50 dark:text-gray-500 mb-1">Arab</p>
                        <p className="text-right text-xl leading-loose font-arabic text-ramadan-slate dark:text-white" dir="rtl">
                          {item.ayah_ar}
                        </p>
                      </div>
                      {/* Transliteration */}
                      <div>
                        <p className="text-xs font-medium text-ramadan-slate/50 dark:text-gray-500 mb-1">Transliterasi</p>
                        <p className="text-sm text-ramadan-blue-dark dark:text-ramadan-blue italic">
                          {item.ayah_tr}
                        </p>
                      </div>
                      {/* Indonesian */}
                      <div>
                        <p className="text-xs font-medium text-ramadan-slate/50 dark:text-gray-500 mb-1">Terjemahan</p>
                        <p className="text-sm text-ramadan-slate/80 dark:text-gray-300">
                          {item.ayah_idn}
                        </p>
                      </div>
                    </div>
                  )}
                </div>
              ))}
            </div>
          ))}
        </div>
      )}

      {/* Empty State */}
      {murajaah.length === 0 && (
        <div className="card text-center py-6">
          <p className="text-sm text-ramadan-slate/40 dark:text-gray-500">
            Belum ada ayat yang disimpan. Pilih surah dan ayat di atas untuk mulai muraja&apos;ah.
          </p>
        </div>
      )}
    </div>
  )
}
