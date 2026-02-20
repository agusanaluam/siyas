<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\RamadanHabit;
use App\Models\RamadanHabitCompletion;

class RamadanHabitController extends Controller
{
    private const DEFAULT_HABITS = [
        ['name' => 'Puasa (Sahur & Buka tepat waktu)', 'icon' => 'moon', 'sort_order' => 1],
        ['name' => 'Shalat 5 Waktu', 'icon' => 'pray', 'sort_order' => 2],
        ['name' => 'Shalat Rawatib', 'icon' => 'pray', 'sort_order' => 3],
        ['name' => 'Shalat Tarawih & Witir', 'icon' => 'mosque', 'sort_order' => 4],
        ['name' => 'Tilawah Al-Quran / Murajaah Hafalan', 'icon' => 'book-open', 'sort_order' => 5],
        ['name' => 'Sedekah Harian', 'icon' => 'heart', 'sort_order' => 6],
        ['name' => 'Dzikir Setelah Shalat', 'icon' => 'sun', 'sort_order' => 7],
        ['name' => 'Istighfar Sebelum Tidur', 'icon' => 'star', 'sort_order' => 8],
        ['name' => 'Baca Buku', 'icon' => 'book', 'sort_order' => 9],
        ['name' => 'Olahraga Ringan', 'icon' => 'heart-pulse', 'sort_order' => 10],
    ];

    private const POINTS_PER_HABIT = 10;

    public function index(Request $request)
    {
        $user = Auth::user();
        $date = $request->query('date', now()->format('Y-m-d'));

        // Auto-seed default habits if user has none
        $habits = RamadanHabit::where('user_id', $user->id)->orderBy('sort_order')->get();

        if ($habits->isEmpty()) {
            $habits = $this->seedDefaultHabits($user->id);
        }

        // Get completions for the given date
        $completedHabitIds = RamadanHabitCompletion::where('user_id', $user->id)
            ->where('completed_date', $date)
            ->pluck('habit_id')
            ->toArray();

        $habitsWithStatus = $habits->map(function ($habit) use ($completedHabitIds) {
            return [
                'id' => $habit->id,
                'name' => $habit->name,
                'icon' => $habit->icon,
                'sort_order' => $habit->sort_order,
                'is_completed' => in_array($habit->id, $completedHabitIds),
            ];
        });

        $completedCount = count($completedHabitIds);
        $totalCount = $habits->count();
        $percentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;

        return response()->json([
            'habits' => $habitsWithStatus,
            'summary' => [
                'completed' => $completedCount,
                'total' => $totalCount,
                'percentage' => $percentage,
                'points_today' => $completedCount * self::POINTS_PER_HABIT,
            ],
            'date' => $date,
        ]);
    }

    public function saveProgress(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'completions' => 'required|array',
            'completions.*' => 'integer|exists:ramadan_habits,id',
        ]);

        $user = Auth::user();
        $date = $request->input('date');
        $completionIds = $request->input('completions', []);

        // Verify all habit IDs belong to the current user
        $userHabitIds = RamadanHabit::where('user_id', $user->id)->pluck('id')->toArray();
        $validIds = array_intersect($completionIds, $userHabitIds);

        DB::transaction(function () use ($user, $date, $validIds) {
            // Remove all completions for this user on this date
            RamadanHabitCompletion::where('user_id', $user->id)
                ->where('completed_date', $date)
                ->delete();

            // Insert new completions
            $records = array_map(function ($habitId) use ($user, $date) {
                return [
                    'habit_id' => $habitId,
                    'user_id' => $user->id,
                    'completed_date' => $date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $validIds);

            if (!empty($records)) {
                RamadanHabitCompletion::insert($records);
            }
        });

        return response()->json([
            'message' => 'Progress berhasil disimpan',
            'completed_count' => count($validIds),
            'points_earned' => count($validIds) * self::POINTS_PER_HABIT,
        ]);
    }

    public function leaderboard(Request $request)
    {
        $currentUser = Auth::user();

        // Get all users who have ramadan habits, along with their total completions
        $leaderboard = DB::table('ramadan_habit_completions')
            ->join('users', 'ramadan_habit_completions.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(ramadan_habit_completions.id) as total_completions'),
                DB::raw('COUNT(ramadan_habit_completions.id) * ' . self::POINTS_PER_HABIT . ' as total_points')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_points')
            ->get();

        $ranked = $leaderboard->values()->map(function ($entry, $index) use ($currentUser) {
            $isCurrentUser = $entry->id === $currentUser->id;
            return [
                'rank' => $index + 1,
                'name' => $isCurrentUser ? $entry->name : $this->maskName($entry->name),
                'total_points' => (int) $entry->total_points,
                'total_completions' => (int) $entry->total_completions,
                'is_current_user' => $isCurrentUser,
            ];
        });

        // If current user has no completions yet, add them at the bottom
        $currentUserInList = $ranked->firstWhere('is_current_user', true);
        if (!$currentUserInList) {
            $ranked->push([
                'rank' => $ranked->count() + 1,
                'name' => $currentUser->name,
                'total_points' => 0,
                'total_completions' => 0,
                'is_current_user' => true,
            ]);
        }

        return response()->json($ranked);
    }

    public function stats(Request $request)
    {
        $user = Auth::user();

        $totalCompletions = RamadanHabitCompletion::where('user_id', $user->id)->count();
        $totalPoints = $totalCompletions * self::POINTS_PER_HABIT;

        $daysTracked = RamadanHabitCompletion::where('user_id', $user->id)
            ->distinct('completed_date')
            ->count('completed_date');

        $totalHabits = RamadanHabit::where('user_id', $user->id)->count();

        $completionRate = ($daysTracked > 0 && $totalHabits > 0)
            ? round(($totalCompletions / ($daysTracked * $totalHabits)) * 100)
            : 0;

        return response()->json([
            'total_points' => $totalPoints,
            'days_tracked' => $daysTracked,
            'total_completions' => $totalCompletions,
            'habit_completion_rate' => $completionRate,
        ]);
    }

    private function seedDefaultHabits(int $userId)
    {
        $habits = [];
        foreach (self::DEFAULT_HABITS as $habit) {
            $habits[] = RamadanHabit::create([
                'user_id' => $userId,
                'name' => $habit['name'],
                'icon' => $habit['icon'],
                'sort_order' => $habit['sort_order'],
            ]);
        }

        return collect($habits);
    }

    private function maskName(string $name): string
    {
        return 'Hamba Allah';
    }
}
