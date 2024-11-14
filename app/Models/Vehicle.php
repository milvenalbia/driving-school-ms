<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        "brand",
        "license_plate",
        "type",
        "transmission_type",
        "status",
        'start_date',
        'end_date'
    ];

    public function vehicleSchedules(){
        return $this->hasMany(VehicleScheduling::class);
    }
}
