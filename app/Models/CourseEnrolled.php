<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrolled extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'schedule_id',
        'course_attendance',
        'user_id',
        'hours',
        'sessions',
    ];

    public function schedule(){
        return $this->belongsTo(Schedules::class, 'schedule_id');
    }

    public function student(){
        return $this->belongsTo(Students::class, 'student_id'); 
    }

    public function schedules(){
        return $this->hasMany(Schedules::class);
    }
}
