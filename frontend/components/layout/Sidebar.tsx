'use client'

import { useState } from 'react'
import Link from 'next/link'
import { usePathname } from 'next/navigation'
import { useAuth } from '@/hooks/useAuth'
import { useSidebar } from '@/contexts/SidebarContext'

interface MenuItem {
  name: string
  href: string
  icon: string
  children?: MenuItem[]
  roles?: string[]
}

export default function Sidebar() {
  const pathname = usePathname()
  // const pathname = '/dashboard'
  const { user } = useAuth()
  const { isCollapsed } = useSidebar()
  const [openMenus, setOpenMenus] = useState<string[]>([])

  const toggleMenu = (menuName: string) => {
    setOpenMenus((prev) =>
      prev.includes(menuName)
        ? prev.filter((m) => m !== menuName)
        : [...prev, menuName]
    )
  }

  const isActive = (href: string) => {
    return pathname === href
  }

  const isMenuOpen = (menuName: string) => {
    return openMenus.includes(menuName)
  }

  const canAccess = (roles?: string[]) => {
    if (!roles) return true
    if (!user) return false
    return roles.includes(user.level)
  }

  const menuItems: MenuItem[] = [
    {
      name: 'Dashboard',
      href: '/dashboard',
      icon: 'grid',
    },
    ...(user && (user.level === 'administrator' || user.email_verified_at)
      ? [
          {
            name: 'Buat Donation',
            href: '#',
            icon: 'smartphone',
          },
        ]
      : []),
  ]

  const manageItems: MenuItem[] = [
    ...(user && user.level !== 'volunteer'
      ? [
          {
            name: 'Campaign',
            href: '#',
            icon: 'tv',
            children: [
              { name: 'All Campaign', href: '/campaign', icon: '' },
              ...(user.level === 'administrator' || user.level === 'root'
                ? [
                    { name: 'Categories', href: '/campaign/categories', icon: '' },
                    { name: 'Add Campaign', href: '/campaign/create', icon: '' },
                  ]
                : []),
            ],
          },
        ]
      : []),
    {
      name: 'Donation',
      href: '#',
      icon: 'package',
      children: [
        { name: 'All Donation', href: '/donation', icon: '' },
        { name: 'Tranfered Donation', href: '/donation/transfer', icon: '' },
        { name: 'Donation History', href: '/donation/history', icon: '' },
        ...(user && user.level !== 'volunteer'
          ? [{ name: 'Mutation Donation', href: '/donation/mutation', icon: '' }]
          : []),
      ],
    },
    ...(user && user.level !== 'volunteer'
      ? [
          {
            name: 'Volunteers',
            href: '#',
            icon: 'users',
            children: [
              { name: 'All Volunteers', href: '/volunteer', icon: '' },
              ...(user.level === 'administrator' || user.level === 'root'
                ? [{ name: 'Group', href: '/volunteer/group', icon: '' }]
                : []),
              { name: 'Inactive Volunteer', href: '/volunteer/inactive', icon: '' },
            ],
          },
        ]
      : []),
    ...(user && (user.level === 'administrator' || user.level === 'root')
      ? [
          {
            name: 'Blog Post',
            href: '#',
            icon: 'file-text',
            children: [
              { name: 'Blog List', href: '/blog', icon: '' },
              { name: 'Blog Tags', href: '/blog/tags', icon: '' },
              { name: 'Categories', href: '/blog/categories', icon: '' },
            ],
          },
        ]
      : []),
  ]

  const settingsItems: MenuItem[] = [
    { name: 'Profil', href: '/user/profile', icon: 'user' },
    ...(user && (user.level === 'administrator' || user.level === 'root')
      ? [{ 
          name: 'Landing Page', 
          href: '#', 
          icon: 'settings',
          children: [
              { name: 'Profil Yayasan', href: '/settings/profile', icon: '' },
              { name: 'Layout', href: '/settings/layout', icon: '' },
            ],
        }]
      : []),
  ]

  const renderIcon = (iconName: string) => {
    const icons: Record<string, JSX.Element> = {
      grid: (
        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
        </svg>
      ),
      smartphone: (
        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
      ),
      tv: (
        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
      ),
      package: (
        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
      ),
      users: (
        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
      ),
      'file-text': (
        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
      ),
      user: (
        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      ),
      settings: (
        <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
      ),
    }
    return icons[iconName] || <div className="h-5 w-5" />
  }

  const renderMenuItem = (item: MenuItem, level = 0) => {
    if (!canAccess(item.roles)) return null

    if (item.children) {
      const isOpen = isMenuOpen(item.name)
      return (
        <li key={item.name}>
          <button
            onClick={(e) => {
              e.stopPropagation()
              toggleMenu(item.name)
            }}
            className={`w-full flex items-center ${isCollapsed ? 'justify-center px-2' : 'justify-between px-4'} py-2 text-sm font-medium rounded-md ${
              isOpen ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'
            }`}
            title={isCollapsed ? item.name : ''}
          >
            <div className={`flex items-center ${isCollapsed ? '' : 'space-x-3'}`}>
              {renderIcon(item.icon)}
              <span className={`transition-opacity duration-300 ${isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'}`}>{item.name}</span>
            </div>
            {!isCollapsed && (
              <svg
                className={`h-4 w-4 transition-transform ${isOpen ? 'transform rotate-180' : ''}`}
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
              </svg>
            )}
          </button>
          {isOpen && !isCollapsed && (
            <ul className="mt-1 space-y-1 pl-4">
              {item.children.map((child) => renderMenuItem(child, level + 1))}
            </ul>
          )}
        </li>
      )
    }

    return (
      <li key={item.name}>
        <Link
          href={item.href}
          className={`flex items-center ${isCollapsed ? 'justify-center px-2' : 'space-x-3 px-4'} py-2 text-sm font-medium rounded-md ${
            isActive(item.href)
              ? 'bg-primary-600 text-white'
              : 'text-gray-700 hover:bg-gray-100'
          }`}
          title={isCollapsed ? item.name : ''}
        >
          {renderIcon(item.icon)}
          <span className={`transition-opacity duration-300 ${isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'}`}>{item.name}</span>
        </Link>
      </li>
    )
  }

  return (
    <aside className={`bg-white border-r border-gray-200 fixed left-0 top-16 h-[calc(100vh-4rem)] overflow-y-auto transition-all duration-300 ${isCollapsed ? 'w-16' : 'w-64'}`}>
      <nav className="p-4 space-y-6">
        <div>
          <h6 className={`px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 transition-opacity duration-300 ${isCollapsed ? 'opacity-0 h-0 overflow-hidden' : 'opacity-100'}`}>
            Main
          </h6>
          <ul className="space-y-1">
            {menuItems.map((item) => renderMenuItem(item))}
          </ul>
        </div>

        {user && (user.level === 'administrator' || user.email_verified_at) && (
          <div>
            <h6 className={`px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 transition-opacity duration-300 ${isCollapsed ? 'opacity-0 h-0 overflow-hidden' : 'opacity-100'}`}>
              Manage
            </h6>
            <ul className="space-y-1">
              {manageItems.map((item) => renderMenuItem(item))}
            </ul>
          </div>
        )}

        <div>
          <h6 className={`px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 transition-opacity duration-300 ${isCollapsed ? 'opacity-0 h-0 overflow-hidden' : 'opacity-100'}`}>
            Settings
          </h6>
          <ul className="space-y-1">
            {settingsItems.map((item) => renderMenuItem(item))}
          </ul>
        </div>
      </nav>
    </aside>
  )
}

