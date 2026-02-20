export interface User {
  id: number
  name: string
  email: string
  level: string
  email_verified_at: string | null
  phone_number?: string
}

export interface RamadanHabit {
  id: number
  name: string
  icon: string
    points: number
  sort_order: number
  is_completed: boolean
}

export interface HabitsSummary {
  completed: number
  total: number
  percentage: number
  points_today: number
}

export interface HabitsResponse {
  habits: RamadanHabit[]
  summary: HabitsSummary
  date: string
}

export interface LeaderboardEntry {
  rank: number
  name: string
  total_points: number
  total_completions: number
  is_current_user: boolean
}

export interface UserStats {
  total_points: number
  days_tracked: number
  total_completions: number
  habit_completion_rate: number
}

export interface AuthResponse {
  user: User
  token: string
  message?: string
}
