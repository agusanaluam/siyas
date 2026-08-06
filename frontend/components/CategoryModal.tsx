'use client'

import { useState, useEffect } from 'react'

interface CategoryModalProps {
  isOpen: boolean
  onClose: () => void
  onSubmit: (data: { name: string; status: boolean }) => Promise<void>
  editData?: { id: number; name: string; status: boolean } | null
}

export default function CategoryModal({ isOpen, onClose, onSubmit, editData }: CategoryModalProps) {
  const [name, setName] = useState('')
  const [status, setStatus] = useState(true)
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [submitting, setSubmitting] = useState(false)

  useEffect(() => {
    if (editData) {
      setName(editData.name)
      setStatus(editData.status)
    } else {
      setName('')
      setStatus(true)
    }
  }, [editData, isOpen])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setSubmitting(true)

    try {
      await onSubmit({ name, status })
      setName('')
      setStatus(true)
      onClose()
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Failed to save category' })
      }
    } finally {
      setSubmitting(false)
    }
  }

  if (!isOpen) return null

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
      <div className="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div className="flex justify-between items-center px-6 py-4 border-b border-gray-200">
          <h2 className="text-xl font-bold text-gray-900">
            {editData ? 'Edit Blog Category' : 'Add Blog Category'}
          </h2>
          <button
            onClick={onClose}
            className="text-red-600 hover:text-red-700 transition-colors"
          >
            <svg className="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
              <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
            </svg>
          </button>
        </div>

        <form onSubmit={handleSubmit} className="p-6">
          {errors.general && (
            <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
              {errors.general}
            </div>
          )}

          {/* Category Name */}
          <div className="mb-6">
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Category *
            </label>
            <input
              type="text"
              className="input w-full"
              value={name}
              onChange={(e) => setName(e.target.value)}
              required
            />
            {errors.name && <p className="mt-1 text-sm text-red-600">{errors.name}</p>}
          </div>

          {/* Status Toggle */}
          <div className="mb-6 flex items-center justify-between">
            <span className="text-sm font-medium text-gray-700">Status</span>
            <button
              type="button"
              onClick={() => setStatus(!status)}
              className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors ${
                status ? 'bg-green-500' : 'bg-gray-300'
              }`}
            >
              <span
                className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform ${
                  status ? 'translate-x-6' : 'translate-x-1'
                }`}
              />
            </button>
          </div>

          {/* Actions */}
          <div className="flex justify-end space-x-4 pt-4">
            <button
              type="button"
              onClick={onClose}
              className="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors"
              disabled={submitting}
            >
              Cancel
            </button>
            <button
              type="submit"
              className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
              disabled={submitting}
            >
              {submitting ? 'Saving...' : editData ? 'Update Category' : 'Add Category'}
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}
