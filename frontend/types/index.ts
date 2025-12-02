export interface User {
  id: number
  name: string
  email: string
  level: 'volunteer' | 'leader' | 'administrator' | 'root'
  email_verified_at: string | null
  phone_number?: string
  volunteer_id?: number
  profile?: VolunteerProfile
}

export interface VolunteerProfile {
  id: number
  group_id?: number
  points?: number
  profile_picture?: string
}

export interface Campaign {
  id: number
  name: string
  description?: string
  category_id: number
  category?: CampaignCategory
  start_date: string
  end_date: string
  target_amount: number
  total_amount: number
  target_object?: number
  close_type: number
  status: number
  pic?: string
  image?: CampaignImage[]
}

export interface CampaignCategory {
  id: number
  name: string
  pic?: string
}

export interface CampaignImage {
  id: number
  program_id: number
  picture_path: string
}

export interface Donation {
  id: number
  liq_number: string
  donatur_name: string
  donatur_phone: string
  donatur_address?: string
  total_amount: number
  trans_date: string
  via_transfer: boolean
  reference_code?: string
  reference_picture?: string
  description?: string
  status: string
  volunteer_id: number
  detail?: DonationDetail[]
}

export interface DonationDetail {
  id: number
  donation_id: number
  program_id: number
  amount: number
  campaign?: Campaign
}

export interface BlogPost {
  id: number
  title: string
  content: string
  excerpt?: string
  featured_image?: string
  status: boolean
  published_at?: string
  created_at: string
  updated_at: string
  creator?: User
  category?: string
  image_url?: string
}

export interface ApiResponse<T> {
  data: T
  message?: string
}

export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

