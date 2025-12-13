
export const getImageUrl = (path: string | null | undefined): string => {
  if (!path) return ''
  if (path.startsWith('http') || path.startsWith('https')) return path
  
  const baseUrl = process.env.NEXT_PUBLIC_API_URL?.replace(/\/api\/?$/, '') || 'http://localhost:8000'
  const cleanPath = path.startsWith('/') ? path : `/${path}`
  
  return `${baseUrl}${cleanPath}`
}

export const getStorageUrl = (path: string | null | undefined): string => {
   // Legacy alias if needed, but getImageUrl is strictly better naming
   return getImageUrl(path)
}
