<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'user_id',
        'firstname',
        'lastname',
        'middle',
        'email',
        'gender',
        'phone_number',
        'birth_date',
        'street',
        'barangay',
        'district',
        'municipality',
        'province',
        'region',
        'civil_status',
        'birth_palce',
        'image_path',
        'assigned_instructor',
        'course_attendance',
        'course_completed',
        'theoretical_test',
        'enroll_status',
        'practical_test',
    ];

    public function assignedInstructor(){
        return $this->belongsTo(Instructor::class, 'assigned_instructor');
    }

    public function course()
    {
        return $this->hasMany(CourseEnrolled::class);
    }

}
