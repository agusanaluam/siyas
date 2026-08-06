const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'

export async function serverFetch<T>(endpoint: string, revalidate = 60): Promise<T> {
  const res = await fetch(`${API_URL}${endpoint}`, {
    next: { revalidate },
  })

  if (!res.ok) {
    throw new Error(`API error: ${res.status}`)
  }

  return res.json()
}
