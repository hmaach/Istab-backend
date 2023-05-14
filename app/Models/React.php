<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class React extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_poste',
        'unique_combination',
    ];
    public function rules()
    {
        return [
            'id_user' => 'required|exists:users,id|unique:reacts,id_user,NULL,id_poste,' . $this->id_poste,
            'id_poste' => 'required|exists:postes,id|unique:reacts,id_poste,NULL,id_user,' . $this->id_user,
        ];
    }
}
