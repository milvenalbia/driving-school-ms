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
        'vehicle_id',
        'course_attendance',
        'user_id',
        'hours',
        'day1_status',
        'day2_status',
        'day3_status',
        'sessions',
        'start_date',
        'remarks',
        'grade',
        'course_type'
    ];

    public function schedule(){
        return $this->belongsTo(Schedules::class, 'schedule_id');
    }

    public function vehicle(){
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function student(){
        return $this->belongsTo(Students::class, 'student_id'); 
    }

    public function schedules(){
        return $this->hasMany(Schedules::class);
    }

    public function payments(){
        return $this->hasOne(Payment::class);
    }
}
