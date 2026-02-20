import { apiClient } from './client'
import { HabitsResponse, LeaderboardEntry, UserStats } from '@/types'

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
}
