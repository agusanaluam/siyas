'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import { usePathname } from 'next/navigation'

interface Setting {
  name: string
  photo: string
}

interface User {
  name: string
  email: string
  level: string
}

interface LandingHeaderProps {
  forceScrolledStyle?: boolean
}

export default function LandingHeader({ forceScrolledStyle = false }: LandingHeaderProps) {
  const pathname = usePathname()
  const [isScrolled, setIsScrolled] = useState(forceScrolledStyle)
  const [setting, setSetting] = useState<Setting | null>(null)
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false)
  const [user, setUser] = useState<User | null>(null)
  const [isAuthenticated, setIsAuthenticated] = useState(false)

  useEffect(() => {
    const handleScroll = () => {
      // Only update scroll state if not forcing scrolled style
      if (!forceScrolledStyle) {
        setIsScrolled(window.scrollY > 10)
      }
    }
    window.addEventListener('scroll', handleScroll)
    
    fetchSettings()
    checkAuth()

    return () => window.removeEventListener('scroll', handleScroll)
  }, [forceScrolledStyle])


  const checkAuth = async () => {
    try {
      // Only run on client side
      if (typeof window === 'undefined') {
        console.log('Auth check - Running on server, skipping')
        return
      }
      
      console.log('Auth check - Starting...')
      console.log('Auth check - All cookies:', document.cookie)
      
      // Get token from cookie manually to avoid SSR issues
      const cookies = document.cookie.split(';')
      const authCookie = cookies.find(c => c.trim().startsWith('auth_token='))
      const token = authCookie?.split('=')[1]?.trim()
      
      console.log('Auth check - Token found:', !!token)
      console.log('Auth check - Token value:', token ? token.substring(0, 20) + '...' : 'none')
      
      if (token) {
        const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
        console.log('Auth check - API URL:', apiUrl)
        
        const response = await fetch(`${apiUrl}/auth/user`, {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
          },
        })
        console.log('Auth check - Response status:', response.status)
        
        if (response.ok) {
          const data = await response.json()
          console.log('Auth check - User data received:', data)
          // The API returns {user: {...}}, so we need to extract the user object
          const userData = data.user || data
          console.log('Auth check - User name:', userData.name)
          setUser(userData)
          setIsAuthenticated(true)
          console.log('Auth check - State updated: isAuthenticated = true')
        } else {
          console.log('Auth check - Response not OK, status:', response.status)
          const errorText = await response.text()
          console.log('Auth check - Error response:', errorText)
        }
      } else {
        console.log('Auth check - No token found, user not authenticated')
      }
    } catch (error) {
      console.error('Error checking auth:', error)
    }
  }

  const fetchSettings = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
      const response = await fetch(`${apiUrl}/settings/profile`)
      const data = await response.json()
      setSetting(data)
    } catch (error) {
      console.error('Error fetching settings:', error)
    }
  }

  const logoUrl = setting?.photo 
    ? `${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}/storage/${setting.photo}`
    : null

  // Dynamic classes based on scroll state
  const navLinkClass = isScrolled 
    ? "text-gray-600 font-medium hover:text-brand-600 transition-colors"
    : "text-white font-medium hover:text-brand-200 transition-colors"
  
  const activeNavLinkClass = isScrolled
    ? "text-brand-600 font-medium hover:text-brand-700"
    : "text-white font-bold hover:text-brand-200"

  const logoTextClass = isScrolled
    ? "text-brand-600"
    : "text-white"

  const mobileMenuButtonClass = isScrolled
    ? "text-gray-600"
    : "text-white"

  return (
    <header 
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        isScrolled ? 'bg-white shadow-md py-2' : 'bg-transparent py-4'
      }`}
    >
      <div className="container mx-auto px-4 md:px-6">
        <div className="flex items-center justify-between">
          {/* Logo */}
          <Link href="/" className="flex items-center gap-2">
            {logoUrl ? (
              <img 
                src={logoUrl} 
                alt={setting?.name || 'Logo'} 
                className="h-10 md:h-12 object-contain"
              />
            ) : (
              <div className={`text-2xl font-bold ${logoTextClass}`}>
                {setting?.name || 'SIYAS'}
              </div>
            )}
            {setting?.name && !logoUrl && (
               <span className={`font-bold text-lg hidden md:block ${isScrolled ? 'text-gray-800' : 'text-white'}`}>{setting.name}</span>
            )}
          </Link>

          {/* Desktop Menu */}
          <nav className="hidden md:flex items-center space-x-8">
            <Link href="/" className={pathname === '/' ? activeNavLinkClass : navLinkClass}>
              Beranda
            </Link>
            <Link href="/about" className={pathname === '/about' ? activeNavLinkClass : navLinkClass}>
              Tentang Kami
            </Link>
            <div className="relative group">
              <button className={`flex items-center ${navLinkClass}`}>
                Program
                <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              {/* Dropdown Menu */}
              <div className="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <div className="py-2">
                  <Link 
                    href="/program" 
                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-600 transition-colors"
                  >
                    Donasi
                  </Link>
                  <Link 
                    href="#program" 
                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-600 transition-colors"
                  >
                    Event
                  </Link>
                </div>
              </div>
            </div>
            <Link href="#donation" className={navLinkClass}>
              Rekening Donasi
            </Link>
            <Link href="#news" className={navLinkClass}>
              Berita & Kegiatan
            </Link>
            <Link href="#contact" className={navLinkClass}>
              Kontak Kami
            </Link>
          </nav>

          {/* CTA Button / User Icon */}
          <div className="hidden md:block">
            {isAuthenticated && user && user.name ? (
              <Link 
                href="/dashboard" 
                className="flex items-center space-x-2 hover:opacity-80 transition-opacity"
                title={user.name}
              >
                <div className={`h-10 w-10 rounded-full flex items-center justify-center text-white font-medium ${
                  isScrolled ? 'bg-brand-600' : 'bg-white/20 backdrop-blur-sm'
                }`}>
                  {user.name.charAt(0).toUpperCase()}
                </div>
              </Link>
            ) : (
              <Link 
                href="/login" 
                className="bg-brand-600 text-white px-6 py-2.5 rounded-full font-medium hover:bg-brand-700 transition-colors shadow-lg shadow-brand-200"
              >
                Jadi Relawan
              </Link>
            )}
          </div>

          {/* Mobile Menu Button */}
          <button 
            className={`md:hidden ${mobileMenuButtonClass}`}
            onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
          >
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              {isMobileMenuOpen ? (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
              ) : (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
              )}
            </svg>
          </button>
        </div>

        {/* Mobile Menu */}
        {isMobileMenuOpen && (
          <div className="md:hidden mt-4 bg-white rounded-lg shadow-xl p-4 absolute left-4 right-4 top-16">
            <nav className="flex flex-col space-y-4">
              <Link href="/" className={pathname === '/' ? 'text-brand-600 font-medium' : 'text-gray-600 font-medium'}>Beranda</Link>
              <Link href="/about" className={pathname === '/about' ? 'text-brand-600 font-medium' : 'text-gray-600 font-medium'}>Tentang Kami</Link>
              <Link href="#program" className="text-gray-600 font-medium">Program</Link>
              <Link href="#donation" className="text-gray-600 font-medium">Rekening Donasi</Link>
              <Link href="#news" className="text-gray-600 font-medium">Berita & Kegiatan</Link>
              <Link href="#contact" className="text-gray-600 font-medium">Kontak Kami</Link>
              {isAuthenticated && user && user.name ? (
                <Link 
                  href="/dashboard" 
                  className="bg-brand-600 text-white px-6 py-2 rounded-full font-medium text-center"
                >
                  Profil
                </Link>
              ) : (
                <Link 
                  href="/login" 
                  className="bg-brand-600 text-white px-6 py-2 rounded-full font-medium text-center"
                >
                  Jadi Relawan
                </Link>
              )}
            </nav>
          </div>
        )}
      </div>
    </header>
  )
}
