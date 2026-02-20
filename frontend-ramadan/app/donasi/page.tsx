'use client'

import RamadanHeader from '@/components/layout/RamadanHeader'
import BottomNav from '@/components/layout/BottomNav'

export default function DonasiPage() {
  return (
    <div className="min-h-screen bg-ramadan-snow dark:bg-gray-900 pb-20">
      <RamadanHeader />

      <main className="max-w-lg mx-auto px-4 py-6 space-y-6">
        {/* Header */}
        <div className="text-center space-y-2">
          <div className="w-16 h-16 bg-ramadan-ice dark:bg-gray-700 rounded-full flex items-center justify-center text-3xl mx-auto">
            🤲
          </div>
          <h1 className="text-xl font-bold text-ramadan-slate dark:text-white">
            Donasi
          </h1>
        </div>

        {/* Message */}
        <div className="card text-center">
          <p className="text-ramadan-slate dark:text-gray-200 leading-relaxed font-medium">
            Ayo titipkan segera zakat fitrah, infaq dan shodaqoh di Yayasan Cahaya Ayah Bunda
          </p>
        </div>

        {/* QRIS Section */}
        <div className="card">
          <h2 className="font-bold text-ramadan-slate dark:text-white mb-3 text-center">
            Scan QRIS untuk Donasi
          </h2>
          <div className="bg-gray-100 dark:bg-gray-700 rounded-2xl p-8 flex flex-col items-center justify-center min-h-[300px]">
            <div className="w-48 h-48 bg-white dark:bg-gray-600 rounded-xl flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-500">
              <div className="text-center">
                <svg className="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                  <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                  <path strokeLinecap="round" strokeLinejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                </svg>
                <p className="text-sm text-gray-400 font-medium">QRIS</p>
              </div>
            </div>
            <p className="text-xs text-gray-500 dark:text-gray-400 mt-3">
              Gambar QRIS akan ditampilkan di sini
            </p>
          </div>
        </div>

        {/* WhatsApp Contact */}
        <a
          href="https://wa.me/6285285924949"
          target="_blank"
          rel="noopener noreferrer"
          className="w-full flex items-center justify-center gap-3 py-3.5 rounded-2xl font-semibold text-white bg-green-500 hover:bg-green-600 active:scale-[0.98] transition-all shadow-lg"
        >
          <svg className="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
          Hubungi Admin via WhatsApp
        </a>
      </main>

      <BottomNav />
    </div>
  )
}
