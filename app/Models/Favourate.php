<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourate extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'carId',
    ];

    public function user()
{
        return $this->belongsTo(User::class,'userId','id');
}

    public function car()
{
    return $this->belongsTo(Car::class,'carId','id');
}

}
