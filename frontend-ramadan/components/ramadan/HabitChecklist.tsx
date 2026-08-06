'use client'

import { RamadanHabit } from '@/types'
import HabitItem from './HabitItem'

interface HabitChecklistProps {
  habits: RamadanHabit[]
  onToggle: (id: number) => void
}

export default function HabitChecklist({ habits, onToggle }: HabitChecklistProps) {
  const positiveHabits = habits.filter((h) => h.type !== 'negative')
  const negativeHabits = habits.filter((h) => h.type === 'negative')

  return (
    <div className="space-y-4">
      {/* Positive Habits */}
      {positiveHabits.length > 0 && (
        <div>
          <h2 className="font-bold text-ramadan-slate dark:text-white mb-3 flex items-center gap-2">
            <span className="text-lg">✨</span> Amal Baik
          </h2>
          <div className="space-y-2">
            {positiveHabits.map((habit) => (
              <HabitItem key={habit.id} habit={habit} onToggle={onToggle} />
            ))}
          </div>
        </div>
      )}

      {/* Negative Habits */}
      {negativeHabits.length > 0 && (
        <div>
          <h2 className="font-bold text-red-600 dark:text-red-400 mb-3 flex items-center gap-2">
            <span className="text-lg">🚫</span> Amal yang Harus Dihindari
          </h2>
          <div className="space-y-2">
            {negativeHabits.map((habit) => (
              <HabitItem key={habit.id} habit={habit} onToggle={onToggle} />
            ))}
          </div>
        </div>
      )}
    </div>
  )
}
