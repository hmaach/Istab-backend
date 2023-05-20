<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDF extends Model
{
    use HasFactory;

    public function post()
    {
        $this->belongsTo(Poste::class);
    }

    public function class_pdf()
    {
        $this->belongsTo(Classe_PDF::class);
    }
}
