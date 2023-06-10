<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EpinRequest extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'epin',
        'email',
        'status',
        'allotted_to_user_id',
        'approved_at'
    ];
     public function users()
    {
      return  $this->hasMany(User::class);
    }
}
