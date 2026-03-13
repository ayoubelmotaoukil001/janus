<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class HabitLog extends Model
{
    protected $fillable = ["habit_id", "note" , "completed_at"];
    protected $casts = [
        'completed_at' => 'date',
    ];

    public function habit() 
    {
        return $this->belongsTo(Habit::class);
    }
}