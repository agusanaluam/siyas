'use client'

import { RamadanHabit } from '@/types'

interface HabitItemProps {
  habit: RamadanHabit
  onToggle: (id: number) => void
}

const iconMap: Record<string, string> = {
  moon: '🌙',
  pray: '🤲',
  mosque: '🕌',
  'book-open': '📖',
  heart: '💝',
  sun: '☀️',
  star: '⭐',
  book: '📚',
}

export default function HabitItem({ habit, onToggle }: HabitItemProps) {
  return (
    <button
      onClick={() => onToggle(habit.id)}
      className={`w-full flex items-center gap-4 p-4 rounded-2xl transition-all ${
        habit.is_completed
          ? 'bg-ramadan-sage/10 dark:bg-ramadan-sage/20'
          : 'bg-white dark:bg-gray-800'
      }`}
    >
      {/* Icon */}
      <div className={`w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0 ${
        habit.is_completed ? 'bg-ramadan-sage/20' : 'bg-gray-100 dark:bg-gray-700'
      }`}>
        {iconMap[habit.icon] || '📋'}
      </div>

      {/* Name & Points */}
      <div className="flex-1 text-left">
        <p className={`font-medium text-sm ${
          habit.is_completed ? 'text-ramadan-sage-dark dark:text-ramadan-sage line-through' : 'text-ramadan-brown dark:text-white'
        }`}>
          {habit.name}
        </p>
        <p className="text-xs text-ramadan-brown/40 dark:text-gray-500 mt-0.5">+10 poin</p>
      </div>

      {/* Checkbox */}
      <div className={`w-7 h-7 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all ${
        habit.is_completed
          ? 'bg-ramadan-sage border-ramadan-sage'
          : 'border-gray-300 dark:border-gray-600'
      }`}>
        {habit.is_completed && (
          <svg className="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={3}>
            <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
          </svg>
        )}
      </div>
    </button>
  )
}
