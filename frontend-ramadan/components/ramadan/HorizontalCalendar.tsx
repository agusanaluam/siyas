'use client'

import { useRef, useEffect } from 'react'

interface HorizontalCalendarProps {
  selectedDate: string
  onSelectDate: (date: string) => void
}

const DAYS_ID = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']
const MONTHS_ID = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']

function formatDate(date: Date): string {
  const y = date.getFullYear()
  const m = String(date.getMonth() + 1).padStart(2, '0')
  const d = String(date.getDate()).padStart(2, '0')
  return `${y}-${m}-${d}`
}

function getDaysList(): Date[] {
  const today = new Date()
  const days: Date[] = []
  // Show 14 days back and 7 days forward
  for (let i = -14; i <= 7; i++) {
    const d = new Date(today)
    d.setDate(today.getDate() + i)
    days.push(d)
  }
  return days
}

function isToday(date: Date): boolean {
  const today = new Date()
  return date.toDateString() === today.toDateString()
}

export default function HorizontalCalendar({ selectedDate, onSelectDate }: HorizontalCalendarProps) {
  const scrollRef = useRef<HTMLDivElement>(null)
  const days = getDaysList()

  useEffect(() => {
    // Scroll to today on mount
    if (scrollRef.current) {
      const todayIndex = days.findIndex(d => isToday(d))
      const itemWidth = 72
      const containerWidth = scrollRef.current.clientWidth
      const scrollTo = (todayIndex * itemWidth) - (containerWidth / 2) + (itemWidth / 2)
      scrollRef.current.scrollLeft = Math.max(0, scrollTo)
    }
  }, [])

  return (
    <div className="card">
      <div
        ref={scrollRef}
        className="flex gap-2 overflow-x-auto scrollbar-hide pb-1 -mx-1 px-1"
        style={{ scrollBehavior: 'smooth' }}
      >
        {days.map((day) => {
          const dateStr = formatDate(day)
          const isSelected = dateStr === selectedDate
          const today = isToday(day)

          return (
            <button
              key={dateStr}
              onClick={() => onSelectDate(dateStr)}
              className={`flex-shrink-0 flex flex-col items-center py-2 px-3 rounded-xl min-w-[60px] transition-all ${
                isSelected
                  ? 'bg-ramadan-blue text-white shadow-md'
                  : today
                  ? 'bg-ramadan-ice dark:bg-gray-700 text-ramadan-slate dark:text-white'
                  : 'text-ramadan-slate/60 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'
              }`}
            >
              <span className="text-xs font-medium">{DAYS_ID[day.getDay()]}</span>
              <span className={`text-lg font-bold mt-0.5 ${isSelected ? 'text-white' : today ? 'text-ramadan-blue-dark' : ''}`}>
                {day.getDate()}
              </span>
              <span className="text-xs">{MONTHS_ID[day.getMonth()]}</span>
              {today && isSelected && (
                <div className="w-4 h-4 mt-1 bg-white rounded-full flex items-center justify-center">
                  <svg className="w-3 h-3 text-ramadan-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={3}>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              )}
            </button>
          )
        })}
      </div>
    </div>
  )
}
