<?php

namespace App\Http\Controllers;

use App\Http\Requests\HabitLogRequest;
use App\Models\Habit;
use Illuminate\Http\Request;

class HabitLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index(Request $request, $id) 
    {
        $habit = $request->user()->habits()->find($id);

        if (!$habit) {
            return response()->json([
                'success' => false,
                'message' => 'habit doesnt exist',
            ], 404); 
        }

        $logs = $habit->habitLogs()
            ->orderBy('completed_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logs,
            'message' => 'habit logs retrieved',
        ]);
    }

        /**
         * Store a newly created resource in storage.
         */
        public function store(HabitLogRequest $request, $id) 
    {
        $habit = $request->user()->habits()->find($id);

        if (!$habit) {
            return response()->json([
                'success' => false,
                'message' => 'Habit not found or unauthorized'
            ], 404);
        }

        $date = $request->completed_at ?? now()->toDateString();

        $log = $habit->habitLogs()->updateOrCreate(
            ['completed_at' => $date],
            ['note' => $request->note]
        );

        return response()->json([
            'success' => true,
            'data' => $log,
            'message' => 'Progress tracked successfully'
        ], 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    

    public function stats($id)
{
    $habit = Habit::with('habitLogs')->findOrFail($id);

    $totalCompletions = $habit->habitLogs()->count();

    $last30DaysLogs = $habit->habitLogs()
        ->where('completed_at', '>=', now()->subDays(30))
        ->count();
    
    $completionRate = ($last30DaysLogs / 30) * 100;

    return response()->json([
        'success' => true,
        'data' => [
            'habit_title' => $habit->title,
            'current_streak' => $habit->current_streak,
            'total_completions' => $totalCompletions,
            'completion_rate' => round($completionRate, 2) . '%'
        ]
    ]);
}
}
