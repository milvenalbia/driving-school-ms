<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'schedule_id',
        'instructor_id',
        'course_enrolled_id',
        'type',
        'grade',
        'remarks',
    ];

    public function schedule(){
        return $this->belongsTo(Schedules::class, 'schedule_id');
    }

    public function student(){
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function course(){
        return $this->belongsTo(CourseEnrolled::class, 'course_enrolled_id');
    }

    public function instructor(){
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }
}
