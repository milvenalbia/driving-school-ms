<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleScheduling extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_enrolled_id',
        "vehicle_id",
        'start_date',
        'end_date',
        'use_status'
    ];

    public function vehicle(){
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function course_enrolled(){
        return $this->belongsTo(CourseEnrolled::class, 'course_enrolled_id');
    }
}
