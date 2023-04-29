<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Poste;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->input('query');
        $stagiaires = DB::table('users')
            ->join('groupes', 'users.id_groupe', '=', 'groupes.id')
            ->join('filieres', 'groupes.id_filiere', '=', 'filieres.id')
            ->select('users.id', 'users.nom', 'users.prenom', 'filieres.libelle')
            ->where('users.nom', 'like', '%' . $query . '%')
            ->orWhere('users.prenom', 'like', '%' . $query . '%')
            ->limit(2)
            ->get();

        $users = DB::table('users')
            ->select('users.id', 'users.nom', 'users.prenom')
            ->whereNull('id_groupe')
            ->where('users.nom', 'like', '%' . $query . '%')
            ->orWhere('users.prenom', 'like', '%' . $query . '%')
            ->limit(2)
            ->get();
        return [
            'stagiaires' => $stagiaires,
            'users' => $users
        ];
    }
}
