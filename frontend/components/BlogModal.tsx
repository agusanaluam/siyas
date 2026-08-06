'use client'

import { useState } from 'react'
import RichTextEditor from './RichTextEditor'
import { compressImage } from '@/lib/imageCompression'

interface BlogModalProps {
  isOpen: boolean
  onClose: () => void
  onSubmit: (formData: FormData) => Promise<void>
  categories: Array<{ id: number; name: string }>
}

export default function BlogModal({ isOpen, onClose, onSubmit, categories }: BlogModalProps) {
  const [formData, setFormData] = useState({
    title: '',
    category: '',
    tags: '',
    content: '',
    status: true,
  })
  const [image, setImage] = useState<File | null>(null)
  const [imagePreview, setImagePreview] = useState<string>('')
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [submitting, setSubmitting] = useState(false)
  const [processingImage, setProcessingImage] = useState(false)

  const handleImageChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0]
      
      setProcessingImage(true)
      try {
        const compressed = await compressImage(file, 800, 0.8)
        setImage(compressed)
        setImagePreview(URL.createObjectURL(compressed))
      } catch (error) {
        console.error('Error compressing image:', error)
        setImage(file)
        setImagePreview(URL.createObjectURL(file))
      } finally {
        setProcessingImage(false)
      }
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setSubmitting(true)

    try {
      const formDataToSend = new FormData()
      formDataToSend.append('title', formData.title)
      formDataToSend.append('content', formData.content)
      formDataToSend.append('status', formData.status ? '1' : '0')
      
      if (image) {
        formDataToSend.append('featured_image', image)
      }

      await onSubmit(formDataToSend)
      
      // Reset form
      setFormData({
        title: '',
        category: '',
        tags: '',
        content: '',
        status: true,
      })
      setImage(null)
      setImagePreview('')
      onClose()
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const apiErrors: Record<string, string> = {}
        Object.keys(error.response.data.errors).forEach((key) => {
          apiErrors[key] = error.response.data.errors[key][0]
        })
        setErrors(apiErrors)
      } else {
        setErrors({ general: error.response?.data?.message || 'Gagal menyimpan blog' })
      }
    } finally {
      setSubmitting(false)
    }
  }

  if (!isOpen) return null

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
      <div className="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div className="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
          <h2 className="text-xl font-bold text-gray-900">Add Blog</h2>
          <button
            onClick={onClose}
            className="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form onSubmit={handleSubmit} className="p-6">
          {errors.general && (
            <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
              {errors.general}
            </div>
          )}

          {/* Image Upload */}
          <div className="mb-6">
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Add Image
            </label>
            <div className="flex items-start space-x-4">
              <div className="flex-shrink-0">
                {imagePreview ? (
                  <img src={imagePreview} alt="Preview" className="w-32 h-32 object-cover rounded-lg" />
                ) : (
                  <div className="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                    <svg className="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                )}
              </div>
              <div className="flex-1">
                <input
                  type="file"
                  accept="image/*"
                  onChange={handleImageChange}
                  className="hidden"
                  id="blog-image"
                />
                <label
                  htmlFor="blog-image"
                  className="btn btn-primary cursor-pointer inline-block"
                >
                  {processingImage ? 'Processing...' : 'Upload Image'}
                </label>
                <p className="mt-2 text-sm text-gray-500">
                  Upload an image below 2 MB. Accepted File format JPG, PNG
                </p>
                {errors.featured_image && <p className="mt-1 text-sm text-red-600">{errors.featured_image}</p>}
              </div>
            </div>
          </div>

          {/* Blog Title */}
          <div className="mb-6">
            <label className="label">Blog Title *</label>
            <input
              type="text"
              className="input"
              value={formData.title}
              onChange={(e) => setFormData({ ...formData, title: e.target.value })}
              required
            />
            {errors.title && <p className="mt-1 text-sm text-red-600">{errors.title}</p>}
          </div>

          <div className="grid grid-cols-2 gap-6 mb-6">
            {/* Category */}
            <div>
              <label className="label">Category *</label>
              <select
                className="input"
                value={formData.category}
                onChange={(e) => setFormData({ ...formData, category: e.target.value })}
                required
              >
                <option value="">Select</option>
                {categories.map((cat) => (
                  <option key={cat.id} value={cat.id}>
                    {cat.name}
                  </option>
                ))}
              </select>
              {errors.category && <p className="mt-1 text-sm text-red-600">{errors.category}</p>}
            </div>

            {/* Tags */}
            <div>
              <label className="label">Tags *</label>
              <input
                type="text"
                className="input"
                placeholder="Add new"
                value={formData.tags}
                onChange={(e) => setFormData({ ...formData, tags: e.target.value })}
              />
              {errors.tags && <p className="mt-1 text-sm text-red-600">{errors.tags}</p>}
            </div>
          </div>

          {/* Description */}
          <div className="mb-6">
            <label className="label">Description *</label>
            <RichTextEditor
              value={formData.content}
              onChange={(value) => setFormData({ ...formData, content: value })}
              placeholder="Enter blog description..."
            />
            {errors.content && <p className="mt-1 text-sm text-red-600">{errors.content}</p>}
          </div>

          {/* Status Toggle */}
          <div className="mb-6 flex items-center justify-between">
            <span className="text-sm font-medium text-gray-700">Status</span>
            <button
              type="button"
              onClick={() => setFormData({ ...formData, status: !formData.status })}
              className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors ${
                formData.status ? 'bg-green-500' : 'bg-gray-300'
              }`}
            >
              <span
                className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform ${
                  formData.status ? 'translate-x-6' : 'translate-x-1'
                }`}
              />
            </button>
          </div>

          {/* Actions */}
          <div className="flex justify-end space-x-4 pt-4 border-t border-gray-200">
            <button
              type="button"
              onClick={onClose}
              className="btn btn-secondary"
              disabled={submitting}
            >
              Cancel
            </button>
            <button
              type="submit"
              className="btn btn-primary"
              disabled={submitting || processingImage}
            >
              {submitting ? 'Adding...' : 'Add Blog'}
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}
