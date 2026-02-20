<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\RamadanHabit;
use App\Models\RamadanHabitCompletion;
use App\Models\RamadanDefaultHabit;

class RamadanHabitController extends Controller
{
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
                'points' => $habit->points,
                'type' => $habit->type ?? 'positive',
                'sort_order' => $habit->sort_order,
                'is_completed' => in_array($habit->id, $completedHabitIds),
            ];
        });

        $completedHabits = $habits->whereIn('id', $completedHabitIds);

        // Calculate points: positive habits add, negative habits subtract
        $positivePoints = $completedHabits->where('type', 'positive')->sum('points');
        $negativePoints = $completedHabits->where('type', 'negative')->sum('points');
        $pointsToday = $positivePoints - $negativePoints;

        $completedCount = $completedHabits->count();
        $totalCount = $habits->count();
        $percentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;

        return response()->json([
            'habits' => $habitsWithStatus,
            'summary' => [
                'completed' => $completedCount,
                'total' => $totalCount,
                'percentage' => $percentage,
                'points_today' => $pointsToday,
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
        $userHabits = RamadanHabit::where('user_id', $user->id)->get();
        $userHabitIds = $userHabits->pluck('id')->toArray();
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

        // Calculate net points earned from completed habits
        $completedHabits = $userHabits->whereIn('id', $validIds);
        $positivePoints = $completedHabits->where('type', 'positive')->sum('points');
        $negativePoints = $completedHabits->where('type', 'negative')->sum('points');
        $pointsEarned = $positivePoints - $negativePoints;

        return response()->json([
            'message' => 'Progress berhasil disimpan',
            'completed_count' => count($validIds),
            'points_earned' => $pointsEarned,
        ]);
    }

    public function leaderboard(Request $request)
    {
        $currentUser = Auth::user();

        // Sum points: positive habits add, negative habits subtract
        $leaderboard = DB::table('ramadan_habit_completions')
            ->join('ramadan_habits', 'ramadan_habit_completions.habit_id', '=', 'ramadan_habits.id')
            ->join('users', 'ramadan_habit_completions.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(ramadan_habit_completions.id) as total_completions'),
                DB::raw("SUM(CASE WHEN ramadan_habits.type = 'negative' THEN -ramadan_habits.points ELSE ramadan_habits.points END) as total_points")
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

        // Calculate total points: positive habits add, negative habits subtract
        $totalPoints = DB::table('ramadan_habit_completions')
            ->join('ramadan_habits', 'ramadan_habit_completions.habit_id', '=', 'ramadan_habits.id')
            ->where('ramadan_habit_completions.user_id', $user->id)
            ->selectRaw("SUM(CASE WHEN ramadan_habits.type = 'negative' THEN -ramadan_habits.points ELSE ramadan_habits.points END) as net_points")
            ->value('net_points') ?? 0;

        $totalCompletions = RamadanHabitCompletion::where('user_id', $user->id)->count();

        $daysTracked = RamadanHabitCompletion::where('user_id', $user->id)
            ->distinct('completed_date')
            ->count('completed_date');

        $totalHabits = RamadanHabit::where('user_id', $user->id)->count();

        $completionRate = ($daysTracked > 0 && $totalHabits > 0)
            ? round(($totalCompletions / ($daysTracked * $totalHabits)) * 100)
            : 0;

        return response()->json([
            'total_points' => (int) $totalPoints,
            'days_tracked' => $daysTracked,
            'total_completions' => $totalCompletions,
            'habit_completion_rate' => $completionRate,
        ]);
    }

    private function seedDefaultHabits(int $userId)
    {
        $defaults = RamadanDefaultHabit::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Fallback if default habits table is empty (seeder hasn't run yet)
        if ($defaults->isEmpty()) {
            $defaults = collect([
                (object) ['name' => 'Puasa (Sahur & Buka tepat waktu)', 'icon' => 'moon', 'points' => 10, 'sort_order' => 1, 'type' => 'positive'],
                (object) ['name' => 'Shalat 5 Waktu', 'icon' => 'pray', 'points' => 10, 'sort_order' => 2, 'type' => 'positive'],
                (object) ['name' => 'Shalat Rawatib', 'icon' => 'pray', 'points' => 10, 'sort_order' => 3, 'type' => 'positive'],
                (object) ['name' => 'Shalat Tarawih & Witir', 'icon' => 'mosque', 'points' => 10, 'sort_order' => 4, 'type' => 'positive'],
                (object) ['name' => 'Tilawah Al-Quran / Murajaah Hafalan', 'icon' => 'book-open', 'points' => 10, 'sort_order' => 5, 'type' => 'positive'],
                (object) ['name' => 'Sedekah Harian', 'icon' => 'heart', 'points' => 10, 'sort_order' => 6, 'type' => 'positive'],
                (object) ['name' => 'Dzikir Setelah Shalat', 'icon' => 'sun', 'points' => 10, 'sort_order' => 7, 'type' => 'positive'],
                (object) ['name' => 'Istighfar Sebelum Tidur', 'icon' => 'star', 'points' => 10, 'sort_order' => 8, 'type' => 'positive'],
                (object) ['name' => 'Baca Buku', 'icon' => 'book', 'points' => 10, 'sort_order' => 9, 'type' => 'positive'],
                (object) ['name' => 'Olahraga Ringan', 'icon' => 'heart-pulse', 'points' => 10, 'sort_order' => 10, 'type' => 'positive'],
                (object) ['name' => 'Berkata Kotor / Menyakiti Lisan', 'icon' => 'warning', 'points' => 5, 'sort_order' => 11, 'type' => 'negative'],
                (object) ['name' => 'Marah Berlebihan', 'icon' => 'warning', 'points' => 5, 'sort_order' => 12, 'type' => 'negative'],
                (object) ['name' => 'Menunda Solat Tanpa Uzur', 'icon' => 'warning', 'points' => 5, 'sort_order' => 13, 'type' => 'negative'],
                (object) ['name' => 'Ghibah', 'icon' => 'warning', 'points' => 5, 'sort_order' => 14, 'type' => 'negative'],
                (object) ['name' => 'Lalai Menjaga Hati', 'icon' => 'warning', 'points' => 5, 'sort_order' => 15, 'type' => 'negative'],
            ]);
        }

        $habits = [];
        foreach ($defaults as $default) {
            $habits[] = RamadanHabit::create([
                'user_id' => $userId,
                'name' => $default->name,
                'icon' => $default->icon,
                'points' => $default->points,
                'type' => $default->type ?? 'positive',
                'sort_order' => $default->sort_order,
            ]);
        }

        return collect($habits);
    }

    private function maskName(string $name): string
    {
        return 'Hamba Allah';
    }
}
