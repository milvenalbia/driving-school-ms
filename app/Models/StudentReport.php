<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'schedule_id',
        'instructor_id',
        'theoritical_grade',
        'practical_grade',
        'hours',
        'remarks',
    ];

    public function schedule(){
        return $this->belongsTo(Schedules::class, 'schedule_id');
    }

    public function student(){
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function instructor(){
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function schedules(){
        return $this->hasMany(Schedules::class);
    }

    public function students(){
        return $this->hasMany(Students::class);
    }


}
