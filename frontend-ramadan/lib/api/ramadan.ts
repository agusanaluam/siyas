import { apiClient } from './client'
import { HabitsResponse, LeaderboardEntry, UserStats, Surah, SurahDetail, Murajaah } from '@/types'

export const ramadanService = {
  async getHabits(date: string): Promise<HabitsResponse> {
    const response = await apiClient.get<HabitsResponse>('/ramadan/habits', {
      params: { date },
    })
    return response.data
  },

  async saveProgress(date: string, completions: number[]): Promise<{ message: string; completed_count: number; points_earned: number }> {
    const response = await apiClient.post('/ramadan/habits/save-progress', {
      date,
      completions,
    })
    return response.data
  },

  async getLeaderboard(): Promise<LeaderboardEntry[]> {
    const response = await apiClient.get<LeaderboardEntry[]>('/ramadan/leaderboard')
    return response.data
  },

  async getStats(): Promise<UserStats> {
    const response = await apiClient.get<UserStats>('/ramadan/stats')
    return response.data
  },

  async getSurahList(): Promise<Surah[]> {
    const response = await apiClient.get<Surah[]>('/ramadan/surah-list')
    return response.data
  },

  async getSurahDetail(nomor: number): Promise<SurahDetail> {
    const response = await apiClient.get<SurahDetail>(`/ramadan/surah/${nomor}`)
    return response.data
  },

  async getMurajaah(): Promise<Murajaah[]> {
    const response = await apiClient.get<Murajaah[]>('/ramadan/murajaah')
    return response.data
  },

  async saveMurajaah(data: { surah_number: number; surah_name: string; ayah_number: number }): Promise<Murajaah> {
    const response = await apiClient.post<Murajaah>('/ramadan/murajaah', data)
    return response.data
  },

  async deleteMurajaah(id: number): Promise<void> {
    await apiClient.delete(`/ramadan/murajaah/${id}`)
  },
}
