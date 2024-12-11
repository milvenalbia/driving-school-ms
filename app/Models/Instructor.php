<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'user_id',
        'firstname',
        'lastname',
        'middle',
        'gender',
        'email',
        'phone_number',
        'driving_experience',
        'birth_date',
        'address',
        'image_path',
        'deleted_at',
        'hasSchedule',
    ];

    public function students(){
        return $this->hasMany(Students::class);
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
