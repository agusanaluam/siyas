'use client'

import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import DashboardLayout from '@/components/layout/DashboardLayout'
import { useAuth } from '@/hooks/useAuth'
import { blogService } from '@/lib/api/blog'
import { blogCategoryService, BlogCategory } from '@/lib/api/blogMeta'
import { toast } from 'react-hot-toast'

export default function GenerateBlogPage() {
  const router = useRouter()
  const { user, loading: authLoading } = useAuth()
  const [topic, setTopic] = useState('')
  const [additionalInstructions, setAdditionalInstructions] = useState('')
  const [categoryId, setCategoryId] = useState<number | undefined>(undefined)
  const [autoPublish, setAutoPublish] = useState(false)
  const [categories, setCategories] = useState<BlogCategory[]>([])
  const [generating, setGenerating] = useState(false)

  useEffect(() => {
    fetchCategories()
  }, [])

  const fetchCategories = async () => {
    try {
      const data = await blogCategoryService.getAll()
      const items = Array.isArray(data) ? data : []
      setCategories(items)
    } catch {
      // Categories are optional
    }
  }

  const handleGenerate = async (e: React.FormEvent) => {
    e.preventDefault()

    if (!topic.trim()) {
      toast.error('Topik artikel harus diisi')
      return
    }

    setGenerating(true)

    try {
      const result = await blogService.generate({
        topic: topic.trim(),
        additional_instructions: additionalInstructions.trim() || undefined,
        auto_publish: autoPublish,
        category_id: categoryId,
      })

      toast.success(result.message || 'Artikel berhasil digenerate!')
      router.push(`/blog/${result.data.id}/edit`)
    } catch (error: unknown) {
      const err = error as { response?: { data?: { message?: string; error?: string } } }
      const message = err.response?.data?.error || err.response?.data?.message || 'Gagal mengenerate artikel'
      toast.error(message)
    } finally {
      setGenerating(false)
    }
  }

  if (authLoading) {
    return (
      <DashboardLayout>
        <div className="flex items-center justify-center min-h-[60vh]">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-500"></div>
        </div>
      </DashboardLayout>
    )
  }

  if (!user || !['administrator', 'root'].includes(user.level)) {
    return (
      <DashboardLayout>
        <div className="text-center py-20">
          <p className="text-gray-500">Anda tidak memiliki akses ke halaman ini.</p>
        </div>
      </DashboardLayout>
    )
  }

  return (
    <DashboardLayout>
      <div className="max-w-2xl mx-auto">
        {/* Header */}
        <div className="flex items-center justify-between mb-6">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Generate Artikel AI</h1>
            <p className="text-sm text-gray-500 mt-1">
              Buat artikel otomatis dengan AI yang SEO-friendly untuk Yayasan Cahaya Ayah Bunda
            </p>
          </div>
          <Link
            href="/blog"
            className="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1"
          >
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
          </Link>
        </div>

        {/* Form */}
        <form onSubmit={handleGenerate} className="space-y-6">
          <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
            {/* Topic */}
            <div>
              <label htmlFor="topic" className="block text-sm font-medium text-gray-700 mb-1">
                Topik Artikel <span className="text-red-500">*</span>
              </label>
              <input
                id="topic"
                type="text"
                value={topic}
                onChange={(e) => setTopic(e.target.value)}
                placeholder="Contoh: Program pendidikan anak di daerah terpencil"
                className="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
                disabled={generating}
              />
            </div>

            {/* Additional Instructions */}
            <div>
              <label htmlFor="instructions" className="block text-sm font-medium text-gray-700 mb-1">
                Instruksi Tambahan <span className="text-gray-400">(opsional)</span>
              </label>
              <textarea
                id="instructions"
                value={additionalInstructions}
                onChange={(e) => setAdditionalInstructions(e.target.value)}
                placeholder="Contoh: Fokuskan pada dampak program terhadap kehidupan masyarakat. Sertakan data statistik."
                rows={3}
                className="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none resize-none"
                disabled={generating}
              />
            </div>

            {/* Category */}
            <div>
              <label htmlFor="category" className="block text-sm font-medium text-gray-700 mb-1">
                Kategori <span className="text-gray-400">(opsional)</span>
              </label>
              <select
                id="category"
                value={categoryId || ''}
                onChange={(e) => setCategoryId(e.target.value ? Number(e.target.value) : undefined)}
                className="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none bg-white"
                disabled={generating}
              >
                <option value="">Pilih kategori</option>
                {categories.map((cat) => (
                  <option key={cat.id} value={cat.id}>
                    {cat.name}
                  </option>
                ))}
              </select>
            </div>

            {/* Auto Publish Toggle */}
            <div className="flex items-center gap-3">
              <input
                id="autoPublish"
                type="checkbox"
                checked={autoPublish}
                onChange={(e) => setAutoPublish(e.target.checked)}
                className="w-4 h-4 text-brand-500 border-gray-300 rounded focus:ring-brand-500"
                disabled={generating}
              />
              <label htmlFor="autoPublish" className="text-sm text-gray-700">
                Langsung publish setelah generate
              </label>
            </div>
          </div>

          {/* Info Box */}
          <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div className="flex gap-3">
              <svg className="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div className="text-sm text-blue-700">
                <p className="font-medium mb-1">Informasi</p>
                <ul className="list-disc list-inside space-y-1 text-blue-600">
                  <li>AI akan menghasilkan artikel dalam Bahasa Indonesia</li>
                  <li>Keyword YCAB dan Cahaya Ayah Bunda akan dimasukkan secara natural</li>
                  <li>Artikel akan dibuat sebagai draft (kecuali auto-publish diaktifkan)</li>
                  <li>Anda bisa mengedit artikel setelah di-generate</li>
                  <li>Proses generate membutuhkan waktu sekitar 30-60 detik</li>
                </ul>
              </div>
            </div>
          </div>

          {/* Submit Button */}
          <button
            type="submit"
            disabled={generating || !topic.trim()}
            className="w-full bg-brand-500 hover:bg-brand-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center gap-2"
          >
            {generating ? (
              <>
                <div className="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent"></div>
                Sedang mengenerate artikel...
              </>
            ) : (
              <>
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Generate Artikel
              </>
            )}
          </button>
        </form>
      </div>
    </DashboardLayout>
  )
}
