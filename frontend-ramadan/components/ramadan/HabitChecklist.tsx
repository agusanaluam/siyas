'use client'

import { RamadanHabit } from '@/types'
import HabitItem from './HabitItem'

interface HabitChecklistProps {
  habits: RamadanHabit[]
  onToggle: (id: number) => void
}

export default function HabitChecklist({ habits, onToggle }: HabitChecklistProps) {
  return (
    <div>
      <h2 className="font-bold text-ramadan-slate dark:text-white mb-3">
        Aktivitas Hari Ini
      </h2>
      <div className="space-y-2">
        {habits.map((habit) => (
          <HabitItem key={habit.id} habit={habit} onToggle={onToggle} />
        ))}
      </div>
    </div>
  )
}
