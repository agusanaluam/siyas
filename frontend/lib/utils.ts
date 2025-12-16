
export const getImageUrl = (path: string | null | undefined): string => {
  if (!path) return ''
  if (path.startsWith('http') || path.startsWith('https')) return path

  const baseUrl = process.env.NEXT_PUBLIC_API_URL?.replace(/\/api\/?$/, '') || 'http://localhost:8000'

  // Jika path sudah dimulai dengan /storage/, gunakan langsung
  if (path.startsWith('/storage/')) {
    return `${baseUrl}${path}`
  }

  // Jika path tidak dimulai dengan /storage/, tambahkan /storage/
  const cleanPath = path.startsWith('/') ? path : `/${path}`
  const storagePath = cleanPath.startsWith('/storage/') ? cleanPath : `/storage${cleanPath}`

  return `${baseUrl}${storagePath}`
}

export const getStorageUrl = (path: string | null | undefined): string => {
   // Legacy alias if needed, but getImageUrl is strictly better naming
   return getImageUrl(path)
}
