<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    protected $fillable = ["user_id", "title", "description", "color", "is_active", "frequency", "target_days"];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function habitLogs()
    {
        return $this->hasMany(HabitLog::class);
    }
    protected $appends = ['current_streak'];
    
    public function getCurrentStreakAttribute()
    {
        $logs = $this->habitLogs()->orderBy('completed_at', 'desc')->get();
        
        $streak = 0;
        $compareDate = date('Y-m-d'); 
    
        $hasLogToday = false;
        foreach ($logs as $log) {
            if ($log->completed_at->format('Y-m-d') == $compareDate) {
                $hasLogToday = true;
                break;
            }
        }
    
        if (!$hasLogToday) {
            $compareDate = date('Y-m-d', strtotime('-1 day'));
        }
    
        foreach ($logs as $log) {
            $logDate = $log->completed_at->format('Y-m-d');
    
            if ($logDate == $compareDate) {
                $streak++;
                $compareDate = date('Y-m-d', strtotime($compareDate . ' -1 day'));
            } 
        }
    
        return $streak;
    } 
}
