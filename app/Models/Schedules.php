<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'schedule_code',
        'name',
        'date',
        'type',
        'instructor',
        'enrolled_student',
        'slots',
        'amount',
    ];

    public function instructorBy(){
        return $this->belongsTo(Instructor::class, 'instructor');
    }
}
