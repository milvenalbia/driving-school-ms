<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_code',
        'student_id',
        'schedule_id',
        'paid_amount',
        'balance',
        'status',
    ];

    public function schedule(){
        return $this->belongsTo(Schedules::class, 'schedule_id');
    }

    public function student(){
        return $this->belongsTo(Students::class, 'student_id'); 
    }
}
