<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

   protected $fillable = [
       'brandName',
       'modelName',
       'manufactureYear',
       'distance',
       'condition',
       'price',
       'ownerId',
       'description',
   ];

   public function user()
   {
       return $this->belongsTo(User::class,'ownerId','id');
   }

   public function dealership()
   {
       return $this->belongsTo(Dealership::class,'ownerId','id');
   }

}
