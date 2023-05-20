<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;
    protected $searchable = [];


    public function notification()
    {
        return $this->hasOne(Notification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
